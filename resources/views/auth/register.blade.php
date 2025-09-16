<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Rejestracja</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-black flex items-center justify-center min-h-screen px-2">
    <div class="w-full max-w-md bg-gray-900 rounded-lg shadow-2xl p-8 border border-amber-700/50">
        <h1 class="text-3xl font-bold mb-6 text-center text-amber-400 drop-shadow-lg">Rejestracja</h1>

        @if($errors->any())
            <div class="mb-4 bg-amber-800/20 text-amber-300 border border-amber-700/50 rounded p-3 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('register.post') }}" class="space-y-5">
            @csrf
            <div>
                <label class="block text-amber-300 mb-1 text-sm" for="name">Imię i nazwisko</label>
                <input type="text" name="name" id="name" required
                    class="w-full px-4 py-2 bg-gray-800 text-gray-100 border border-amber-700/50 rounded 
                    focus:outline-none focus:ring-2 focus:ring-amber-500/50 placeholder:text-gray-400 text-sm"
                    value="{{ old('name') }}" />
            </div>

            <div>
                <label class="block text-amber-300 mb-1 text-sm" for="email">Email</label>
                <input type="email" name="email" id="email" required
                    class="w-full px-4 py-2 bg-gray-800 text-gray-100 border border-amber-700/50 rounded 
                    focus:outline-none focus:ring-2 focus:ring-amber-500/50 placeholder:text-gray-400 text-sm"
                    value="{{ old('email') }}" />
            </div>

            <div>
                <label class="block text-amber-300 mb-1 text-sm" for="rola">Rola</label>
                <select name="rola" id="rola" required
                    class="w-full px-4 py-2 bg-gray-800 text-gray-100 border border-amber-700/50 rounded 
                    focus:outline-none focus:ring-2 focus:ring-amber-500/50 text-sm">
                    @foreach($roles as $role)
                        <option value="{{ $role }}" @if(old('rola') == $role) selected @endif>
                            {{ ucfirst($role) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-amber-300 mb-1 text-sm" for="password">Hasło</label>
                <input type="password" name="password" id="password" required
                    class="w-full px-4 py-2 bg-gray-800 text-gray-100 border border-amber-700/50 rounded 
                    focus:outline-none focus:ring-2 focus:ring-amber-500/50 placeholder:text-gray-400 text-sm" />
            </div>

            <div>
                <label class="block text-amber-300 mb-1 text-sm" for="password_confirmation">Powtórz hasło</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required
                    class="w-full px-4 py-2 bg-gray-800 text-gray-100 border border-amber-700/50 rounded 
                    focus:outline-none focus:ring-2 focus:ring-amber-500/50 placeholder:text-gray-400 text-sm" />
            </div>

            <button type="submit"
                class="w-full bg-amber-600 hover:bg-amber-500 text-gray-900 py-2 rounded transition font-semibold text-sm shadow-md">
                Zarejestruj
            </button>

            <div class="text-center mt-4">
                <a href="{{ route('login') }}" class="text-amber-400 hover:text-amber-300 text-sm">
                    Masz już konto? Zaloguj się
                </a>
            </div>
        </form>
    </div>
</body>
</html>
