<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Appointment extends Model
{
    protected $fillable = [
        'customer_id',
        'staff_id',
        'appointment_start',
        'total_price',
        'total_minutes',
        'note',
        'services_json',
    ];

    protected $casts = [
        'appointment_start' => 'datetime',
        'services_json' => 'array',
    ];

    public function getServicesJsonAttribute($value): array
    {
        $services = $value;

        while (is_string($services)) {
            $decoded = json_decode($services, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return [];
            }

            $services = $decoded;
        }

        return is_array($services) ? $services : [];
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function ensureOwnedByCurrentUser(): void
    {
        if ($this->customer_id !== auth()->id()) {
            abort(403);
        }
    }

    public function scopeWithBasics($q)
    {
        return $q->with(['customer', 'staff']);
    }

    public function scopeOverlapForStaff($q, int $staffId, Carbon $start, Carbon $end, ?int $excludeId = null)
    {
        $q->where('staff_id', $staffId)
            ->where('appointment_start', '<', $end)
            ->whereRaw(
                'DATE_ADD(appointment_start, INTERVAL total_minutes MINUTE) > ?',
                [$start->format('Y-m-d H:i:s')]
            );

        if ($excludeId !== null) {
            $q->where('id', '!=', $excludeId);
        }

        return $q;
    }

    public static function computeTotals(array $services): array
    {
        $ids = collect($services)->pluck('id');
        $qty = collect($services)->keyBy('id')->map(fn($v) => $v['quantity'] ?? 1);
        $svs = Service::whereIn('id', $ids)->get();

        $totalPrice = 0;
        $totalMin = 0;

        foreach ($svs as $s) {
            $q = $qty[$s->id];
            $totalPrice += $s->price * $q;
            $totalMin += $s->duration_minutes * $q;
        }

        return [$totalPrice, $totalMin];
    }

    public static function book(array $data): self
    {
        [$totalPrice, $totalMin] = self::computeTotals($data['services']);

        $start = Carbon::parse($data['appointment_start']);
        $end = (clone $start)->addMinutes($totalMin);

        return DB::transaction(function () use ($data, $totalPrice, $totalMin, $start, $end) {
            $busy = self::overlapForStaff($data['staff_id'], $start, $end)->exists();

            if ($busy) {
                throw new \RuntimeException(config('message.appointment_conflict'));
            }

            return self::create([
                'customer_id' => $data['customer_id'],
                'staff_id' => $data['staff_id'],
                'appointment_start' => $data['appointment_start'],
                'total_price' => $totalPrice,
                'total_minutes' => $totalMin,
                'services_json' => $data['services'],
                'note' => $data['note'] ?? null,
            ]);
        });
    }

    // 🔥 更新（追加）
    public static function updateAppointment(int $id, array $data): self
    {
        return DB::transaction(function () use ($id, $data) {

            $appt = self::findOrFail($id);

            $appt->ensureOwnedByCurrentUser();

            [$totalPrice, $totalMin] = self::computeTotals($data['services']);

            $start = Carbon::parse($data['appointment_start']);
            $end = (clone $start)->addMinutes($totalMin);

            $busy = self::overlapForStaff($data['staff_id'], $start, $end, $id)->exists();

            if ($busy) {
                throw new \RuntimeException(config('message.appointment_conflict'));
            }

            $appt->update([
                'customer_id' => $data['customer_id'],
                'staff_id' => $data['staff_id'],
                'appointment_start' => $data['appointment_start'],
                'total_price' => $totalPrice,
                'total_minutes' => $totalMin,
                'services_json' => $data['services'],
                'note' => $data['note'] ?? null,
            ]);

            return $appt;
        });
    }

    // 🔥 削除（追加）
    public static function remove(int $id): void
    {
        DB::transaction(function () use ($id) {
            $appt = self::findOrFail($id);
            $appt->ensureOwnedByCurrentUser();
            $appt->delete();
        });
    }

    public static function updateByAdmin(int $id, array $data): self
    {
        return DB::transaction(function () use ($id, $data) {

            $appt = self::findOrFail($id);

            [$totalPrice, $totalMin] = self::computeTotals($data['services']);

            $start = Carbon::parse($data['appointment_start']);
            $end = (clone $start)->addMinutes($totalMin);

            $busy = self::overlapForStaff($data['staff_id'], $start, $end, $id)->exists();

            if ($busy) {
                throw new \RuntimeException(config('message.appointment_conflict'));
            }

            $appt->update([
                'customer_id' => $data['customer_id'],
                'staff_id' => $data['staff_id'],
                'appointment_start' => $data['appointment_start'],
                'total_price' => $totalPrice,
                'total_minutes' => $totalMin,
                'services_json' => $data['services'],
                'note' => $data['note'] ?? null,
            ]);

            return $appt;
        });
    }

    public static function removeByAdmin(int $id): void
    {
        DB::transaction(function () use ($id) {
            $appt = self::findOrFail($id);
            $appt->delete();
        });
    }

}
