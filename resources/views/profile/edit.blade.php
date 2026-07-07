@extends('layouts.app')

@section('title', 'ユーザー情報編集')

@section('content')
<div class="profile-page">
    <section class="profile-card">
        <div class="profile-card__header">
            <h2 class="profile-title">ユーザー情報編集</h2>
            <p class="profile-lead">登録している情報を変更できます。</p>
        </div>

        <form method="POST" action="{{ route('profile.update') }}" class="profile-form">
            @csrf
            @method('PUT')

            <div class="profile-field">
                <label for="name" class="profile-label">名前</label>
                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name', $customer->name) }}"
                    autocomplete="name"
                    required
                    autofocus
                    class="profile-input @error('name') is-error @enderror"
                >
                @error('name')
                    <p class="profile-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="profile-field">
                <label for="name_kana" class="profile-label">フリガナ</label>
                <input
                    id="name_kana"
                    type="text"
                    name="name_kana"
                    value="{{ old('name_kana', $customer->name_kana) }}"
                    placeholder="ヤマダ ハナコ"
                    required
                    class="profile-input @error('name_kana') is-error @enderror"
                >
                @error('name_kana')
                    <p class="profile-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="profile-field">
                <label for="phone_number" class="profile-label">携帯番号</label>
                <input
                    id="phone_number"
                    type="tel"
                    name="phone_number"
                    value="{{ old('phone_number', $customer->phone_number) }}"
                    placeholder="090-1234-5678"
                    autocomplete="tel"
                    required
                    class="profile-input @error('phone_number') is-error @enderror"
                >
                @error('phone_number')
                    <p class="profile-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="profile-field">
                <label for="email" class="profile-label">メールアドレス</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email', $customer->email) }}"
                    autocomplete="email"
                    required
                    class="profile-input @error('email') is-error @enderror"
                >
                @error('email')
                    <p class="profile-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="profile-actions">
                <a href="{{ route('appointments.mypage') }}" class="profile-cancel">
                    戻る
                </a>
                <button type="submit" class="profile-submit">
                    更新する
                </button>
            </div>
        </form>
    </section>
</div>
@endsection
