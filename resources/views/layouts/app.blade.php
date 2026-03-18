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
    <header class="site-header">
        <div class="header-container">

            <!-- Left -->
            <div class="header-left">

                <h1 class="site-title">
                    💇‍♀️ Salon Booking
                </h1>

                @auth
                    <nav class="main-nav">
                        <a href="{{ route('appointments.index') }}">
                            空き状況
                        </a>

                        <a href="{{ route('staff.index') }}">
                            スタッフ
                        </a>

                        <a href="{{ route('services.index') }}">
                            メニュー
                        </a>
                    </nav>
                @endauth

            </div>


            <!-- Right -->
            <div class="user-area">

                @auth
                    <span class="user-name">
                        {{ auth()->user()->name }} さん
                    </span>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="logout-btn">
                            ログアウト
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="login-link">
                        ログイン
                    </a>

                    <a href="{{ route('register') }}" class="register-link">
                        新規登録
                    </a>
                @endauth

            </div>

        </div>
    </header>

    <!-- Main -->
    <main class="flex-1 max-w-5xl mx-auto w-full px-6 py-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="text-center text-xs text-gray-400 py-6 border-t bg-white">
        &copy; {{ date('Y') }} Salon Booking System
    </footer>

</body>

</html>