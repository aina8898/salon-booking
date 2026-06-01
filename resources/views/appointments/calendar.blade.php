@extends('layouts.app')

@section('title', '空き状況カレンダー')

@section('content')

<div class="calendar-page">
    <div class="calendar-page__header">
        <div>
            <h2 class="calendar-page__title">空き状況カレンダー</h2>
        </div>
    </div>

    <div class="calendar-current">
        <strong class="calendar-current__date">
            {{ $date->format('Y年n月j日') }}
        </strong>
        <span class="calendar-current__weekday">
            {{ ['日', '月', '火', '水', '木', '金', '土'][$date->dayOfWeek] }}曜日
        </span>
    </div>

    <div class="calendar-nav">
        <div class="calendar-nav__days">
            <a href="{{ route('appointments.index', ['date' => $date->copy()->subDay()->format('Y-m-d'), 'service_id' => $selectedServiceId]) }}">
                前日
            </a>

            <a href="{{ route('appointments.index', ['service_id' => $selectedServiceId]) }}" class="is-today">
                今日
            </a>

            <a href="{{ route('appointments.index', ['date' => $date->copy()->addDay()->format('Y-m-d'), 'service_id' => $selectedServiceId]) }}">
                翌日
            </a>
        </div>

        <div class="calendar-nav__actions">
            <form method="GET" action="{{ route('appointments.calendar') }}" class="calendar-date-form">
                <label for="date">日付を指定</label>
                <input id="date" name="date" type="date" value="{{ $date->format('Y-m-d') }}">

                @if(!empty($selectedServiceId))
                    <input type="hidden" name="service_id" value="{{ $selectedServiceId }}">
                @endif

                <button type="submit">
                    表示
                </button>
            </form>

            <a href="{{ route('appointments.create') }}" class="calendar-new-btn">
                新規予約
            </a>
        </div>
    </div>

    <div class="calendar-legend">
        <div>
            <span class="availability-calendar__symbol availability-calendar__symbol--free">○</span>
            空きあり
        </div>

        <div>
            <span class="availability-calendar__symbol availability-calendar__symbol--busy">×</span>
            予約あり
        </div>
    </div>

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
                        <td class="availability-calendar__time-cell">
                            {{ $timeLabel }}
                        </td>

                        @foreach ($staffs as $staff)
                            @php
                                $cell = $grid[$staff->id][$timeLabel] ?? ['busy' => false, 'can_book' => false];
                            @endphp

                            <td class="availability-calendar__cell">
                                @if($cell['busy'])
                                    <span class="availability-calendar__symbol availability-calendar__symbol--busy">
                                        ×
                                    </span>
                                @elseif($cell['can_book'])
                                    <a href="{{ route('appointments.create', [
                                            'date' => $date->format('Y-m-d'),
                                            'time' => $timeLabel,
                                            'staff_id' => $staff->id,
                                            'service_id' => $selectedServiceId ?? '',
                                        ]) }}"
                                        class="availability-calendar__symbol availability-calendar__symbol--free">
                                        ○
                                    </a>
                                @else
                                    <span class="availability-calendar__symbol availability-calendar__symbol--free">
                                        ○
                                    </span>
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
