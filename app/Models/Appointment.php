<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Appointment extends Model
{
    protected $fillable = [
        'customer_id','staff_id','appointment_start',
        'total_price','total_minutes','note','services_json',
    ];

    protected $casts = [
        'appointment_start' => 'datetime',
        'services_json'     => 'array',   // ← JSONを配列で扱える
    ];

    public function customer(){ return $this->belongsTo(Customer::class); }
    public function staff(){ return $this->belongsTo(Staff::class); }

    public function scopeWithBasics($q)
    {
        return $q->with(['customer','staff']);
    }

    public function scopeOverlapForStaff($q, int $staffId, Carbon $start, Carbon $end)
    {
        return $q->where('staff_id', $staffId)
            ->whereBetween('appointment_start', [$start, $end->copy()->subSecond()]);
    }

    public static function computeTotals(array $services): array
    {
        $ids = collect($services)->pluck('id');
        $qty = collect($services)->keyBy('id')->map(fn($v)=> $v['quantity'] ?? 1);
        $svs = Service::whereIn('id', $ids)->get();

        $totalPrice = 0; $totalMin = 0; 
        foreach ($svs as $s) {
            $q = $qty[$s->id];
            $totalPrice += $s->price * $q;
            $totalMin   += $s->duration_minutes * $q;
        }
        return [$totalPrice, $totalMin];
    }

    public static function book(array $data): self
    {
        [$totalPrice, $totalMin] = self::computeTotals($data['services']);

        $start = Carbon::parse($data['appointment_start']);
        $end   = (clone $start)->addMinutes($totalMin);

        $busy = self::where('staff_id', $data['staff_id'])
            ->whereBetween('appointment_start', [$start, $end->copy()->subSecond()])
            ->exists();
            
        if ($busy){
            throw new \RuntimeException('この時間帯は空きがありません。');
        }

        return DB::transaction(function () use ($data, $totalPrice, $totalMin) {
            return self::create([
                'customer_id'       => $data['customer_id'],
                'staff_id'          => $data['staff_id'],
                'appointment_start' => $data['appointment_start'],
                'total_price'       => $totalPrice,
                'total_minutes'     => $totalMin,
                'services_json'     => json_encode($data['services']),
                'note'              => $data['note'] ?? null,
            ]);
        });
    }

}
