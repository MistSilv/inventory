<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Aplikacja Inwentaryzacyjna' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="manifest" href="/manifest.webmanifest">
</head>
<body class="bg-gray-100 font-sans flex flex-col min-h-screen">

    <!-- Header -->
    <header class="bg-blue-600 text-white p-4 shadow-md flex items-center justify-between">
        <h1 class="text-2xl font-bold">{{ $title ?? 'Stocktaking App' }}</h1>
        <div>
            @auth
                <span class="mr-4">Hello, {{ auth()->user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="text-white hover:underline">Logout</button>
                </form>
            @endauth
        </div>
    </header>

    <div class="flex flex-1">

        <!-- Sidebar -->
        <aside class="bg-white w-64 p-4 border-r hidden md:block">
            <nav class="space-y-2">
                <a href="{{ route('dashboard') }}" class="block p-2 rounded hover:bg-blue-100">Dashboard</a>
                <a href="{{ route('stocktakings.index') }}" class="block p-2 rounded hover:bg-blue-100">Stocktakings</a>
                <a href="{{ route('products.index') }}" class="block p-2 rounded hover:bg-blue-100">Products</a>
                <a href="{{ route('regions.index') }}" class="block p-2 rounded hover:bg-blue-100">Regions</a>
                <a href="{{ route('logs.index') }}" class="block p-2 rounded hover:bg-blue-100">Logs</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            {{ $slot }}
        </main>

    </div>

    <!-- Footer -->
    <footer class="bg-gray-200 text-center text-gray-600 p-4 mt-auto">
        &copy; {{ date('Y') }} Stocktaking App. All rights reserved.
    </footer>

</body>
</html>
