<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Customer;
use App\Models\Staff;
use App\Models\Service;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::withBasics();

        if ($request->filled('date')) {
            $query->whereDate('appointment_start', $request->date);
        }

        if ($request->filled('staff_id')) {
            $query->where('staff_id', $request->staff_id);
        }

        $appointments = $query
            ->orderBy('appointment_start', 'desc')
            ->paginate(10);

        $staffs = Staff::all();
        $menus = Service::all()->keyBy('id');

        return view('admin.appointments.index', compact('appointments', 'staffs', 'menus'));
    }

    public function edit($id)
    {
        $appointment = Appointment::findOrFail($id);
        $customers = Customer::all();
        $staffs = Staff::all();
        $menus = Service::all();

        return view('admin.appointments.edit', compact(
            'appointment',
            'customers',
            'staffs',
            'menus'
        ));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'staff_id' => ['required', 'exists:staff,id'],
            'appointment_start' => ['required', 'date'],
            'services' => ['required', 'array', 'min:1'],
            'services.*.id' => ['required', 'exists:services,id'],
            'services.*.quantity' => ['nullable', 'integer', 'min:1'],
            'note' => ['nullable', 'string'],
        ]);

        try {
            Appointment::updateByAdmin($id, $data);

            return redirect()
                ->route('admin.appointments.index')
                ->with('success', config('message.appointment_updated'));
        } catch (\RuntimeException $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        Appointment::removeByAdmin($id);

        return redirect()
            ->route('admin.appointments.index')
            ->with('success', config('message.appointment_deleted'));
    }
}
