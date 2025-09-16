<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Logowanie</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-black flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md bg-gray-900 rounded-lg shadow-2xl p-8 border border-amber-700/50">
        <h1 class="text-3xl font-bold mb-6 text-center text-amber-400 drop-shadow-lg">Logowanie</h1>
        
        @if($errors->any())
            <div class="mb-4 bg-amber-800/20 text-amber-300 border border-amber-700/50 rounded p-3">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}" class="space-y-5">
            @csrf
            <div>
                <label class="block text-amber-300 mb-1" for="email">Email</label>
                <input type="email" name="email" id="email" required
                    class="w-full px-4 py-2 bg-gray-800 text-gray-100 border border-amber-700/50 rounded focus:outline-none focus:ring-2 focus:ring-amber-500/50 placeholder:text-gray-400" />
            </div>
            <div>
                <label class="block text-amber-300 mb-1" for="password">Hasło</label>
                <input type="password" name="password" id="password" required
                    class="w-full px-4 py-2 bg-gray-800 text-gray-100 border border-amber-700/50 rounded focus:outline-none focus:ring-2 focus:ring-amber-500/50 placeholder:text-gray-400" />
            </div>
            <button type="submit"
                class="w-full bg-amber-600 hover:bg-amber-500 text-gray-900 py-2 rounded transition font-semibold shadow-md">
                Zaloguj
            </button>
        </form>
    </div>
</body>
</html>
