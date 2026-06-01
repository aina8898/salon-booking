@extends('layouts.app')

@section('content')

<div class="appointment-page">
    <h2 class="appointment-page__title">管理画面（予約編集）</h2>

    @if ($errors->any())
        <div class="alert-error">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('admin.appointments.update', $appointment->id) }}" class="appointment-form">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="customer_id">顧客</label>
            <select id="customer_id" name="customer_id">
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}"
                        {{ old('customer_id', $appointment->customer_id) == $customer->id ? 'selected' : '' }}>
                        {{ $customer->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="staff_id">スタッフ</label>
            <select id="staff_id" name="staff_id">
                @foreach($staffs as $staff)
                    <option value="{{ $staff->id }}"
                        {{ old('staff_id', $appointment->staff_id) == $staff->id ? 'selected' : '' }}>
                        {{ $staff->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="appointment_start">予約日時</label>
            <input
                id="appointment_start"
                type="datetime-local"
                name="appointment_start"
                value="{{ old('appointment_start', $appointment->appointment_start->format('Y-m-d\TH:i')) }}"
            >
        </div>

        <div class="form-group">
            <label>メニュー</label>

            <div class="service-options">
                @foreach($menus as $menu)
                    @php
                        $selectedService = collect(old('services', $appointment->services_json ?? []))
                            ->firstWhere('id', $menu->id);

                        $checked = !empty($selectedService);
                    @endphp

                    <div class="service-option">
                        <label class="service-option__label">
                            <input
                                type="checkbox"
                                name="services[{{ $loop->index }}][id]"
                                value="{{ $menu->id }}"
                                {{ $checked ? 'checked' : '' }}
                            >
                            <span>
                                {{ $menu->service_name }}（{{ $menu->duration_minutes }}分・{{ number_format($menu->price) }}円）
                            </span>
                        </label>

                        <input
                            name="services[{{ $loop->index }}][quantity]"
                            type="hidden"
                            value="1"
                        >
                    </div>
                @endforeach
            </div>
        </div>

        <div class="form-group">
            <label for="note">メモ</label>
            <textarea id="note" name="note">{{ old('note', $appointment->note) }}</textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">更新</button>
            <a href="{{ route('admin.appointments.index') }}" class="btn-cancel">戻る</a>
        </div>
    </form>
</div>

@endsection
