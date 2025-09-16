<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Aplikacja Inwentaryzacyjna' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-black text-gray-200 font-sans flex flex-col min-h-screen">

    <!-- Header -->
    <header class="bg-black text-amber-400 p-4 shadow-lg flex items-center justify-between border-b-2 border-amber-700">
        <h1 class="text-2xl font-bold tracking-wider">{{ $title ?? 'Stocktaking App' }}</h1>
        <div class="flex items-center space-x-4">
            @auth
                <span class="text-amber-400 font-medium">Hello, {{ auth()->user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-red-900 hover:bg-red-600 text-white px-3 py-1 rounded shadow-sm transition">
                        Logout
                    </button>
                </form>
            @endauth
        </div>
    </header>

    <div class="flex flex-1">

        <!-- Sidebar -->
        <aside class="bg-black w-64 p-6 border-r-2 border-amber-700 hidden md:block shadow-inner">
            <nav class="space-y-3">
                <a href="{{ route('welcome') }}" class="block p-3 rounded hover:bg-amber-700 hover:text-gray-950 transition font-medium">
                    Dashboard
                </a>
                <a href="{{ route('stocktakings.index') }}" class="block p-3 rounded hover:bg-amber-700 hover:text-gray-950 transition font-medium">
                    Stocktakings
                </a>
                <a href="{{ route('products.index') }}" class="block p-3 rounded hover:bg-amber-700 hover:text-gray-950 transition font-medium">
                    Products
                </a>
                <a href="{{ route('regions.index') }}" class="block p-3 rounded hover:bg-amber-700 hover:text-gray-950 transition font-medium">
                    Regions
                </a>
                <a href="{{ route('logs.index') }}" class="block p-3 rounded hover:bg-amber-700 hover:text-gray-950 transition font-medium">
                    Logs
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-8 bg-black">
            @if(!($noWrapper ?? false))
                <div class="bg-gray-900 rounded-xl shadow-lg p-6">
                    {{ $slot }}
                </div>
            @else
                {{ $slot }}
            @endif
        </main>


    </div>

</body>
</html>
