    @extends('layouts.app')

    @section('title', '空き状況カレンダー')

    @section('content')
    <div class="space-y-4">
        {{-- タイトルのみ --}}
        <div>
            <h2 class="text-lg font-semibold text-gray-800">空き状況</h2>
        </div>

        {{-- 日付・ナビゲーション --}}
        <div class="mb-2 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center flex-wrap gap-2">
                <a
                    href="{{ route('appointments.index', ['date' => $date->copy()->subDay()->format('Y-m-d'), 'service_id' => $selectedServiceId]) }}"
                    class="text-xs text-gray-600 hover:text-pink-600"
                >
                    前日
                </a>
                <a
                    href="{{ route('appointments.index', ['service_id' => $selectedServiceId]) }}"
                    class="text-xs text-pink-600 font-semibold hover:underline"
                >
                    今日
                </a>
                <a
                    href="{{ route('appointments.index', ['date' => $date->copy()->addDay()->format('Y-m-d'), 'service_id' => $selectedServiceId]) }}"
                    class="text-xs text-gray-600 hover:text-pink-600"
                >
                    翌日
                </a>
            </div>

            <div class="flex items-center gap-3">
                <form method="GET" action="{{ route('appointments.calendar') }}" class="flex items-center gap-2">
                    <label for="date" class="text-xs text-gray-600 whitespace-nowrap">日付</label>
                    <input
                        id="date"
                        name="date"
                        type="date"
                        value="{{ $date->format('Y-m-d') }}"
                        class="border rounded px-2 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-pink-400"
                    />
                    @if(!empty($selectedServiceId))
                        <input type="hidden" name="service_id" value="{{ $selectedServiceId }}" />
                    @endif
                    <button type="submit" class="bg-gray-800 text-white px-3 py-1 rounded text-xs hover:bg-gray-900">
                        表示
                    </button>
                </form>

                <a href="{{ route('appointments.create') }}" class="bg-pink-500 text-white px-4 py-1.5 rounded text-xs inline-block hover:bg-pink-600">
                    新規予約
                </a>
            </div>
        </div>

        <div class="flex items-center gap-4 text-[11px] text-gray-500">
            <div class="flex items-center gap-1">
                <span class="availability-calendar__symbol availability-calendar__symbol--free">○</span>
                <span>空き</span>
            </div>
            <div class="flex items-center gap-1">
                <span class="availability-calendar__symbol availability-calendar__symbol--busy">×</span>
                <span>予約あり</span>
            </div>
        </div>

        {{-- カレンダー表 --}}
        <div class="availability-calendar">
            <table class="availability-calendar__table">
                <thead class="availability-calendar__head">
                    <tr>
                        <th class="availability-calendar__head-th availability-calendar__head-th--time">
                            時間
                        </th>
                        @foreach ($staffs as $staff)
                            <th class="availability-calendar__head-th">
                                {{ $staff->name }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="availability-calendar__body">
                    @foreach ($timeSlots as $slot)
                        @php
                            $timeLabel = $slot->format('H:i');
                        @endphp
                        <tr class="availability-calendar__row">
                            {{-- 時間ラベル --}}
                            <td class="availability-calendar__time-cell">
                                {{ $timeLabel }}
                            </td>

                            {{-- 各スタッフのその時間の状態 --}}
                            @foreach ($staffs as $staff)
                                @php
                                    $cell = $grid[$staff->id][$timeLabel] ?? ['busy' => false, 'can_book' => false];
                                @endphp

                                <td class="availability-calendar__cell">
                                    @if($cell['busy'])
                                        <span class="availability-calendar__symbol availability-calendar__symbol--busy">
                                            ×
                                        </span>
                                    @else
                                        @if($cell['can_book'])
                                            <a
                                                href="{{ route('appointments.create', [
                                                    'date'      => $date->format('Y-m-d'),
                                                    'time'      => $timeLabel,
                                                    'staff_id'  => $staff->id,
                                                    'service_id' => $selectedServiceId ?? '',
                                                ]) }}"
                                                class="availability-calendar__symbol availability-calendar__symbol--free"
                                            >
                                                ○
                                            </a>
                                        @else
                                            {{-- 空きだがこの画面からは予約しない枠：見た目は同じ「○」 --}}
                                            <span class="availability-calendar__symbol availability-calendar__symbol--free">
                                                ○
                                            </span>
                                        @endif
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endsection