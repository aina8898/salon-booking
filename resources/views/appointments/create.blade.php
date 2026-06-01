@extends('layouts.app')

@section('title', '新規予約')

@section('content')

<div class="appointment-page">
    <h2 class="appointment-page__title">
        新規予約
    </h2>

    @if ($errors->any())
        <div class="alert-error">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>

        <script>
            alert(@json($errors->first()));
        </script>
    @endif

    <form method="POST" action="{{ route('appointments.store') }}" class="appointment-form">
        @csrf

        <div class="form-group">
            <label for="date">日付</label>
            <input id="date" type="date" name="date" value="{{ old('date', $defaultDate ?? '') }}" required>
        </div>

        <div class="form-group">
            <label for="time">時間</label>
            <select id="time" name="time" required>
                @foreach($times as $time)
                    <option value="{{ $time }}" @if(old('time', $defaultTime ?? '') == $time) selected @endif>
                        {{ $time }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="staff_id">スタッフ</label>
            <select id="staff_id" name="staff_id" required>
                <option value="">選択してください</option>

                @foreach($staffs as $staff)
                    <option value="{{ $staff->id }}" @if(old('staff_id', $defaultStaffId ?? null) == $staff->id) selected @endif>
                        {{ $staff->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="service_id">メニュー</label>
            <select id="service_id" name="service_id" required>
                <option value="">選択してください</option>

                @foreach($menus as $menu)
                    <option value="{{ $menu->id }}" @if(old('service_id', $defaultServiceId ?? null) == $menu->id) selected @endif>
                        {{ $menu->service_name }}（{{ $menu->duration_minutes }}分・{{ number_format($menu->price) }}円）
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="note">備考（任意）</label>
            <textarea id="note" name="note" rows="3">{{ old('note') }}</textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">
                予約する
            </button>

            <a href="{{ route('appointments.index') }}" class="btn-cancel">
                キャンセル
            </a>
        </div>
    </form>
</div>

@endsection
