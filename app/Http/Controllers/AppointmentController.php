<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Customer;
use App\Models\Staff;
use App\Models\Service;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    // 一覧画面は「スタッフ別・日別の空き状況カレンダー」を表示する
    public function index(Request $request)
    {
        // calendar アクションのロジックをそのまま使う
        return $this->calendar($request);
    }

    public function store(Request $r)
    {
        // ログイン中の顧客IDを取得
        $customerId = auth()->id();

        // 日付と時間が別々に送られてきた場合は結合
        if ($r->has('date') && $r->has('time')) {
            $appointmentStart = Carbon::parse($r->input('date') . ' ' . $r->input('time'))->format('Y-m-d H:i:s');
            $r->merge(['appointment_start' => $appointmentStart]);
        }

        // service_id が送られてきた場合は services 配列に変換
        if ($r->has('service_id') && !$r->has('services')) {
            $serviceId = $r->input('service_id');
            $r->merge(['services' => [['id' => $serviceId, 'quantity' => 1]]]);
        }

        // customer_id をログイン中の顧客に設定
        $r->merge(['customer_id' => $customerId]);

        $data = $r->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'staff_id' => ['required', 'exists:staff,id'],
            'appointment_start' => ['required', 'date', 'after:now'],
            'services' => ['required', 'array', 'min:1'],
            'services.*.id' => ['required', 'exists:services,id'],
            'services.*.quantity' => ['nullable', 'integer', 'min:1'],
            'note' => ['nullable', 'string'],
        ]);

        try {
            $appt = Appointment::book($data);
            return redirect()->route('appointments.index')
                ->with('success', '予約が完了しました。');
        } catch (\RuntimeException $e) {
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function create(Request $request)
    {
        $staffs = Staff::all();
        $menus = Service::all();

        // URLパラメータから初期値を取得
        $defaultDate = $request->input('date', Carbon::today()->format('Y-m-d'));
        $defaultTime = $request->input('time', '09:00');
        $defaultStaffId = $request->input('staff_id');
        $defaultServiceId = $request->input('service_id');

        $times = [];
        $time = Carbon::createFromTime(9, 0);
        $end = Carbon::createFromTime(18, 0);

        while ($time <= $end) {
            $times[] = $time->format('H:i');
            $time->addMinutes(30);
        }

        return view('appointments.create', compact(
            'staffs',
            'menus',
            'times',
            'defaultDate',
            'defaultTime',
            'defaultStaffId',
            'defaultServiceId'
        ));
    }

    public function update(Request $r, $id)
    {
        $data = $r->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'staff_id' => ['required', 'exists:staff,id'],
            'appointment_start' => ['required', 'date', 'after:now'],
            'services' => ['required', 'array', 'min:1'],
            'services.*.id' => ['required', 'exists:services,id'],
            'services.*.quantity' => ['nullable', 'integer', 'min:1'],
            'note' => ['nullable', 'string'],
        ]);

        try {
            $appt = Appointment::findOrFail($id);
            [$totalPrice, $totalMin] = Appointment::computeTotals($data['services']);

            $appt->update([
                'customer_id' => $data['customer_id'],
                'staff_id' => $data['staff_id'],
                'appointment_start' => $data['appointment_start'],
                'total_price' => $totalPrice,
                'total_minutes' => $totalMin,
                'services_json' => json_encode($data['services']),
                'note' => $data['note'] ?? null,
            ]);

            return response()->json($appt, 200);
        } catch (\RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function destroy($id)
    {
        $appt = Appointment::findOrFail($id);
        $appt->delete();
        return response()->json(['message' => '予約を削除しました'], 200);
    }

    public function edit($id)
    {
        $appointment = Appointment::findOrFail($id);
        $customers = Customer::all();
        $staffs = Staff::all();
        $menus = Service::all();
        return view('appointments.edit', compact('appointment', 'customers', 'staffs', 'menus'));
    }

    public function calendar(Request $request)
    {
        // ① 日付取得（未指定なら今日）
        $date = $request->input('date');
        $date = $date ? Carbon::parse($date) : Carbon::today();

        // ② メニュー（1つのみ選択）
        $serviceId = $request->input('service_id');
        $selectedServiceIds = $serviceId ? [(int) $serviceId] : [];
        $totalMinutes = 0;
        if (!empty($selectedServiceIds)) {
            $selectedServices = Service::whereIn('id', $selectedServiceIds)->get();
            $totalMinutes = $selectedServices->sum('duration_minutes');
        }

        $staffs = Staff::all();

        // ④ 1日の時間スロットを生成（例：9:00〜18:00 を30分刻み）
        $businessStart = $date->copy()->setTime(9, 0);
        $businessEnd = $date->copy()->setTime(18, 0);
        $slotMinutes = 30;

        $timeSlots = [];
        for ($t = $businessStart->copy(); $t < $businessEnd; $t->addMinutes($slotMinutes)) {
            $timeSlots[] = $t->copy();
        }

        // ⑤ 指定日の予約をまとめて取得＆スタッフごとにグルーピング
        $appointments = Appointment::whereDate('appointment_start', $date)
            ->with('staff', 'customer')
            ->get()
            ->groupBy('staff_id');

        $grid = []; // $grid[staff_id][H:i] = ['busy' => bool, 'can_book' => bool]

        foreach ($staffs as $staff) {
            $staffAppointments = $appointments->get($staff->id, collect());

            foreach ($timeSlots as $slot) {
                // スロットの開始・終了（予約判定に使う）
                $slotStart = $slot->copy();
                // メニューを選んでいないときは「1スロット分」で判定
                $duration = $totalMinutes > 0 ? $totalMinutes : $slotMinutes;
                $slotEnd = $slot->copy()->addMinutes($duration);

                // このスタッフのこの時間帯が既存予約と重なっているかチェック
                $isBusy = $staffAppointments->contains(function ($appt) use ($slotStart, $slotEnd) {
                    $apptStart = $appt->appointment_start;
                    $apptEnd = $appt->appointment_start->copy()->addMinutes($appt->total_minutes);

                    // 区間 [slotStart, slotEnd) と [apptStart, apptEnd) が重なっているか
                    return $slotStart < $apptEnd && $slotEnd > $apptStart;
                });

                $grid[$staff->id][$slotStart->format('H:i')] = [
                    'busy' => $isBusy,
                    // メニューが選択されていて、かつ埋まっていなければ「予約候補」とみなす
                    'can_book' => !$isBusy && $totalMinutes > 0,
                ];
            }
        }

        $menus = Service::all();

        return view('appointments.calendar', [
            'date' => $date,
            'staffs' => $staffs,
            'timeSlots' => $timeSlots,
            'grid' => $grid,
            'menus' => $menus,
            'selectedServiceId' => $selectedServiceIds[0] ?? null,
            'totalMinutes' => $totalMinutes,
        ]);
    }
}
