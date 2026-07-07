@extends('layouts.app')

@section('content')

<div class="mypage">
    <div class="mypage__header">
        <div>
            <h2 class="mypage__title">マイページ（予約一覧）</h2>
            <p class="mypage__lead">予約内容の確認、編集、キャンセルができます。</p>
        </div>
        <a href="{{ route('profile.edit') }}" class="mypage-profile-link">
            ユーザー情報を編集
        </a>
    </div>

    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="mypage-table-wrap">
        <table class="mypage-table">
            <thead>
                <tr>
                    <th>日時</th>
                    <th>スタッフ</th>
                    <th>メニュー</th>
                    <th>操作</th>
                </tr>
            </thead>

            <tbody>
                @forelse($appointments as $appt)
                    <tr>
                        <td>{{ $appt->appointment_start->format('Y-m-d H:i') }}</td>
                        <td>{{ $appt->staff->name ?? '-' }}</td>
                        <td>
                            @foreach($appt->services_json as $service)
                                @php
                                    $menu = $menus->get($service['id'] ?? null);
                                @endphp

                                <span class="mypage-menu-name">{{ $menu->service_name ?? '不明なメニュー' }}</span>
                            @endforeach
                        </td>
                        <td>
                            <div class="mypage-actions">
                                <form method="POST" action="{{ route('appointments.destroy', $appt->id) }}">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                        class="mypage-action mypage-action--delete"
                                        onclick='return confirm(@json(config("message.confirm_delete")))'>
                                        キャンセル
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="mypage-empty">予約はありません。</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mypage-pagination">
        {{ $appointments->links() }}
    </div>
</div>

@endsection
