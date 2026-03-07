@extends('layouts.app')

@section('title', '新規登録')

@section('content')
<div class="max-w-md mx-auto bg-white shadow rounded p-6">
    <h2 class="text-xl font-bold mb-4">新規登録</h2>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-semibold mb-1">名前</label>
            <input 
                type="text" 
                name="name" 
                value="{{ old('name') }}" 
                required 
                autofocus
                class="border rounded px-3 py-2 w-full @error('name') border-red-500 @enderror"
            >
            @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold mb-1">年齢</label>
            <input 
                type="number" 
                name="age" 
                value="{{ old('age') }}" 
                required 
                min="1" 
                max="150"
                class="border rounded px-3 py-2 w-full @error('age') border-red-500 @enderror"
            >
            @error('age')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold mb-1">メールアドレス</label>
            <input 
                type="email" 
                name="email" 
                value="{{ old('email') }}" 
                required
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

        <div>
            <label class="block text-sm font-semibold mb-1">パスワード（確認）</label>
            <input 
                type="password" 
                name="password_confirmation" 
                required
                class="border rounded px-3 py-2 w-full"
            >
        </div>

        <div class="flex gap-3">
            <button type="submit" class="bg-pink-500 text-white px-6 py-2 rounded flex-1">
                登録
            </button>
        </div>
    </form>

    <div class="mt-4 text-center">
        <a href="{{ route('login') }}" class="text-sm text-pink-600 hover:underline">
            すでにアカウントをお持ちの方はこちら
        </a>
    </div>
</div>
@endsection
