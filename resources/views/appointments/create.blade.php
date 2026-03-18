@extends('layouts.app')

@section('title', '新規予約')

@section('content')

    <div class="appointment-page">

        <h2 class="appointment-page__title">
            新規予約
        </h2>

        <form method="POST" action="{{ route('appointments.store') }}" class="appointment-form">
            @csrf

            <div class="form-group">
                <label>日付</label>
                <input type="date" name="date" value="{{ $defaultDate ?? '' }}" required>
            </div>

            <div class="form-group">
                <label>時間</label>

                <select name="time" required>

                    @foreach($times as $time)

                        <option value="{{ $time }}" @if(($defaultTime ?? '') == $time) selected @endif>
                            {{ $time }}
                        </option>

                    @endforeach

                </select>

            </div>

            <div class="form-group">
                <label>スタッフ</label>

                <select name="staff_id" required>

                    <option value="">選択してください</option>

                    @foreach($staffs as $staff)
                        <option value="{{ $staff->id }}" @if(($defaultStaffId ?? null) == $staff->id) selected @endif>
                            {{ $staff->name }}
                        </option>
                    @endforeach

                </select>
            </div>

            <div class="form-group">
                <label>メニュー</label>

                <select name="service_id" required>

                    <option value="">選択してください</option>

                    @foreach($menus as $menu)
                        <option value="{{ $menu->id }}" @if(($defaultServiceId ?? null) == $menu->id) selected @endif>
                            {{ $menu->service_name }}（{{ $menu->duration_minutes }}分・{{ number_format($menu->price) }}円）
                        </option>
                    @endforeach

                </select>

            </div>

            <div class="form-group">
                <label>備考（任意）</label>
                <textarea name="note" rows="3"></textarea>
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