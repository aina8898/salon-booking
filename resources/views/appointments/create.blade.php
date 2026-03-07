@extends('layouts.app')

@section('title', '新規予約')

@section('content')
<h2 class="text-xl font-bold mb-4">新規予約</h2>

    <form method="POST" action="{{ route('appointments.store') }}" class="bg-white shadow rounded p-6 space-y-4">
        @csrf

    <div>
        <label class="block text-sm font-semibold mb-1">日付</label>
        <input 
            type="date" 
            name="date" 
            value="{{ $defaultDate ?? '' }}" 
            required
            class="border rounded px-3 py-2 w-full"
        >
    </div>

    <div>
        <label class="block text-sm font-semibold mb-1">時間</label>
        <input 
            type="time" 
            name="time" 
            value="{{ $defaultTime ?? '09:00' }}" 
            required
            class="border rounded px-3 py-2 w-full"
        >
    </div>

    <div>
        <label class="block text-sm font-semibold mb-1">スタッフ</label>
        <select name="staff_id" required class="border rounded px-3 py-2 w-full">
            <option value="">選択してください</option>
            @foreach($staffs as $staff)
                <option value="{{ $staff->id }}" @if(($defaultStaffId ?? null) == $staff->id) selected @endif>
                    {{ $staff->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-semibold mb-1">メニュー</label>
        <select name="service_id" required class="border rounded px-3 py-2 w-full">
            <option value="">選択してください</option>
            @foreach($menus as $menu)
                <option value="{{ $menu->id }}" @if(($defaultServiceId ?? null) == $menu->id) selected @endif>
                    {{ $menu->service_name }}（{{ $menu->duration_minutes }}分・{{ number_format($menu->price) }}円）
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-semibold mb-1">備考（任意）</label>
        <textarea name="note" rows="3" class="border rounded px-3 py-2 w-full"></textarea>
    </div>

    <div class="flex gap-3">
        <button type="submit" class="bg-pink-500 text-white px-6 py-2 rounded">予約する</button>
        <a href="{{ route('appointments.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded">キャンセル</a>
    </div>
</form>
@endsection
