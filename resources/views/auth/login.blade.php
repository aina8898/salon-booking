@extends('layouts.app')

@section('title', 'ログイン')

@section('content')
<div class="auth-page">
    <section class="auth-card">
        <h2 class="auth-title">ログイン</h2>

        <form method="POST" action="{{ route('login') }}" class="auth-form">
            @csrf

            <div class="auth-field">
                <label for="email" class="auth-label">メールアドレス</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
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

            <label class="auth-check">
                <input type="checkbox" name="remember" id="remember">
                <span>ログイン状態を保持する</span>
            </label>

            <button type="submit" class="auth-submit">
                ログイン
            </button>
        </form>

        <p class="auth-switch">
            <a href="{{ route('register') }}">新規登録はこちら</a>
        </p>
    </section>
</div>
@endsection
