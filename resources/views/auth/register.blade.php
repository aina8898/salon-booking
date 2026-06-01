@extends('layouts.app')

@section('title', '新規登録')

@section('content')
<div class="auth-page">
    <section class="auth-card">
        <h2 class="auth-title">新規登録</h2>

        <form method="POST" action="{{ route('register') }}" class="auth-form">
            @csrf

            <div class="auth-field">
                <label for="name" class="auth-label">名前</label>
                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    autofocus
                    class="auth-input @error('name') is-error @enderror"
                >
                @error('name')
                    <p class="auth-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="auth-field">
                <label for="age" class="auth-label">年齢</label>
                <input
                    id="age"
                    type="number"
                    name="age"
                    value="{{ old('age') }}"
                    required
                    min="1"
                    max="150"
                    class="auth-input @error('age') is-error @enderror"
                >
                @error('age')
                    <p class="auth-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="auth-field">
                <label for="email" class="auth-label">メールアドレス</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    class="auth-input @error('email') is-error @enderror"
                >
                @error('email')
                    <p class="auth-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="auth-field">
                <label for="password" class="auth-label">パスワード</label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    required
                    class="auth-input @error('password') is-error @enderror"
                >
                @error('password')
                    <p class="auth-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="auth-field">
                <label for="password_confirmation" class="auth-label">パスワード（確認）</label>
                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    required
                    class="auth-input"
                >
            </div>

            <button type="submit" class="auth-submit">
                登録
            </button>
        </form>

        <p class="auth-switch">
            <a href="{{ route('login') }}">すでにアカウントをお持ちの方はこちら</a>
        </p>
    </section>
</div>
@endsection
