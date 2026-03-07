@extends('layouts.app')

@section('title', 'ログイン')

@section('content')
<div class="max-w-md mx-auto bg-white shadow rounded p-6">
    <h2 class="text-xl font-bold mb-4">ログイン</h2>

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-semibold mb-1">メールアドレス</label>
            <input 
                type="email" 
                name="email" 
                value="{{ old('email') }}" 
                required 
                autofocus
                class="border rounded px-3 py-2 w-full @error('email') border-red-500 @enderror"
            >
            @error('email')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold mb-1">パスワード</label>
            <input 
                type="password" 
                name="password" 
                required
                class="border rounded px-3 py-2 w-full @error('password') border-red-500 @enderror"
            >
            @error('password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center">
            <input 
                type="checkbox" 
                name="remember" 
                id="remember"
                class="mr-2"
            >
            <label for="remember" class="text-sm">ログイン状態を保持する</label>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="bg-pink-500 text-white px-6 py-2 rounded flex-1">
                ログイン
            </button>
        </div>
    </form>

    <div class="mt-4 text-center">
        <a href="{{ route('register') }}" class="text-sm text-pink-600 hover:underline">
            新規登録はこちら
        </a>
    </div>
</div>
@endsection
