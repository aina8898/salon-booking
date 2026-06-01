@extends('layouts.app')

@section('content')

<div class="admin-page">
    <div class="admin-page__header">
        <div>
            <h2 class="admin-page__title">管理画面（予約一覧）</h2>
            <p class="admin-page__lead">予約の確認、絞り込み、編集、削除ができます。</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form method="GET" class="admin-filter">
        <div class="admin-filter__field">
            <label for="date">日付</label>
            <input id="date" type="date" name="date" value="{{ request('date') }}">
        </div>

        <div class="admin-filter__field">
            <label for="staff_id">スタッフ</label>
            <select id="staff_id" name="staff_id">
                <option value="">すべて</option>
                @foreach($staffs as $staff)
                    <option value="{{ $staff->id }}"
                        {{ request('staff_id') == $staff->id ? 'selected' : '' }}>
                        {{ $staff->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="admin-filter__button">検索</button>
    </form>

    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>日時</th>
                    <th>顧客</th>
                    <th>スタッフ</th>
                    <th>メニュー</th>
                    <th>操作</th>
                </tr>
            </thead>

            <tbody>
                @forelse($appointments as $appt)
                    <tr>
                        <td class="admin-table__id">#{{ $appt->id }}</td>
                        <td>{{ $appt->appointment_start->format('Y-m-d H:i') }}</td>
                        <td>{{ $appt->customer->name ?? '-' }}</td>
                        <td>{{ $appt->staff->name ?? '-' }}</td>
                        <td>
                            @foreach($appt->services_json as $service)
                                @php
                                    $menu = $menus->get($service['id'] ?? null);
                                @endphp

                                <span class="admin-menu-name">{{ $menu->service_name ?? '不明なメニュー' }}</span>
                            @endforeach
                        </td>
                        <td>
                            <div class="admin-actions">
                                <a href="{{ route('admin.appointments.edit', $appt->id) }}" class="admin-action admin-action--edit">
                                    編集
                                </a>

                                <form method="POST" action="{{ route('admin.appointments.destroy', $appt->id) }}">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                        class="admin-action admin-action--delete"
                                        onclick='return confirm(@json(config("message.confirm_delete")))'>
                                        削除
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="admin-empty">予約はありません。</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="admin-pagination">
        {{ $appointments->links() }}
    </div>
</div>

@endsection
