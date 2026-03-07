<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Salon Booking')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 text-gray-800 min-h-screen flex flex-col">

    <!-- Header -->
    <header class="bg-white border-b">
        <div class="max-w-6xl mx-auto px-6 py-4 flex justify-between items-center">

            <!-- Left side -->
            <div>
                <h1 class="text-2xl font-bold text-pink-600">
                    💇‍♀️ Salon Booking
                </h1>

                @auth
                    <nav class="mt-2 flex gap-6 text-sm text-gray-600">
                        <a href="{{ route('appointments.index') }}"
                           class="hover:text-pink-600 transition">
                            空き状況
                        </a>

                        <a href="{{ route('staff.index') }}"
                           class="hover:text-pink-600 transition">
                            スタッフ
                        </a>

                        <a href="{{ route('services.index') }}"
                           class="hover:text-pink-600 transition">
                            メニュー
                        </a>
                    </nav>
                @endauth
            </div>

            <!-- Right side -->
            <div class="flex items-center gap-4 text-sm">
                @auth
                    <span class="text-gray-600">
                        {{ auth()->user()->name }} さん
                    </span>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="px-3 py-1 rounded-md bg-gray-800 text-white hover:opacity-80 transition">
                            ログアウト
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                       class="text-pink-600 hover:underline">
                        ログイン
                    </a>

                    <a href="{{ route('register') }}"
                       class="text-pink-600 hover:underline">
                        新規登録
                    </a>
                @endauth
            </div>

        </div>
    </header>

    <!-- Main -->
    <main class="flex-1 max-w-6xl mx-auto w-full px-6 py-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="text-center text-xs text-gray-400 py-6 border-t bg-white">
        &copy; {{ date('Y') }} Salon Booking System
    </footer>

</body>
</html>