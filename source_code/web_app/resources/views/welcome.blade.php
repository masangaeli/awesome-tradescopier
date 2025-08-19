<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Awesome Trades Copier</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-800">

    <div class="relative min-h-screen flex flex-col items-center justify-center">
        <div class="w-full max-w-6xl px-6">

            <!-- Header -->
            <header class="flex items-center justify-between py-6">
                <a href="{{ route('index') }}" class="flex items-center space-x-2">
                    <img src="{{ asset('pics/logo.png') }}" class="w-20 h-20" alt="Logo">
                    <span class="text-xl font-bold">Awesome Trades Copier</span>
                </a>

                @if (Route::has('login'))
                <nav class="flex items-center space-x-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" 
                           class="px-4 py-2 rounded-md bg-[#FF2D20] text-white font-medium shadow-md hover:bg-red-600 transition">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" 
                           class="px-4 py-2 rounded-md text-gray-700 hover:text-[#FF2D20] transition">
                            Log in
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" 
                               class="px-4 py-2 rounded-md border border-[#FF2D20] text-[#FF2D20] hover:bg-[#FF2D20] hover:text-white transition">
                                Register
                            </a>
                        @endif
                    @endauth
                </nav>
                @endif
            </header>

            <!-- Main Content -->
            <main class="mt-10">
                <div class="grid gap-8 lg:grid-cols-2">

                    <!-- Card -->
                    <div
                       class="flex flex-col items-start gap-4 rounded-xl bg-white p-8 shadow-lg hover:shadow-xl transition duration-300">
                        <h2 class="text-2xl font-semibold">Trades Copier</h2>
                        <hr class="w-1/3 border-[#FF2D20]">
                        <p class="text-gray-600">
                            Copy Trades from MT4/MT5 Master Accounts to Other MT4/MT5 Client Accounts with ease and reliability.
                            <br/>
                            <ul>
                                <li class="text-gray-600">Copy in EA Mode</li>
                                <li class="text-gray-600">Copy in Manual Mode</li>
                            </ul>
                        </p>
                    </div>

                    <!-- Platforms Card -->
                    <div class="flex flex-col items-center gap-6 rounded-xl bg-white p-8 shadow-lg hover:shadow-xl transition duration-300">
                        <h3 class="text-lg font-semibold">Supported Platforms</h3>
                        <div class="flex gap-6">
                            <img src="https://www.infinox-zh.com/img/trading-platforms/mt4/header.png" 
                                 class="w-24 h-24 object-contain" alt="MT4">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/2/27/MetaTrader_5.png" 
                                 class="w-24 h-24 object-contain" alt="MT5">
                        </div>
                    </div>

                </div>
            </main>

            <!-- Footer -->
            <footer class="py-10 text-center text-sm text-gray-500 mt-12">
                © {{ date('Y') }} Trades Copier - V1.0.0
            </footer>
        </div>
    </div>

</body>
</html>
