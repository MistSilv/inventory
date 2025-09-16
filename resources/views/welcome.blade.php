<x-layout>
    <main class="container mx-auto mt-10 px-4">
        <h1 class="text-3xl font-bold text-amber-400 mb-8">Dashboard</h1>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">

            <!-- Stocktakings -->
            <a href="{{ route('stocktakings.index') }}"
               class="bg-gray-900 p-6 rounded-xl shadow-lg hover:shadow-2xl transition duration-300 transform hover:-translate-y-1 hover:scale-105 border border-amber-700">
                <h2 class="text-xl font-semibold mb-2 text-amber-400">Stocktakings</h2>
                <p class="text-gray-400">View and manage all stocktaking events.</p>
            </a>

            <!-- Products -->
            <a href="{{ route('products.index') }}"
               class="bg-gray-900 p-6 rounded-xl shadow-lg hover:shadow-2xl transition duration-300 transform hover:-translate-y-1 hover:scale-105 border border-amber-700">
                <h2 class="text-xl font-semibold mb-2 text-amber-400">Products</h2>
                <p class="text-gray-400">Browse and edit your product catalog.</p>
            </a>

            <!-- Regions -->
            <a href="{{ route('regions.index') }}"
               class="bg-gray-900 p-6 rounded-xl shadow-lg hover:shadow-2xl transition duration-300 transform hover:-translate-y-1 hover:scale-105 border border-amber-700">
                <h2 class="text-xl font-semibold mb-2 text-amber-400">Regions</h2>
                <p class="text-gray-400">Manage storage locations and regions.</p>
            </a>

            <!-- Logs -->
            <a href="{{ route('logs.index') }}"
               class="bg-gray-900 p-6 rounded-xl shadow-lg hover:shadow-2xl transition duration-300 transform hover:-translate-y-1 hover:scale-105 border border-amber-700">
                <h2 class="text-xl font-semibold mb-2 text-amber-400">Logs</h2>
                <p class="text-gray-400">Audit trail of user actions and changes.</p>
            </a>

            <!-- Test -->
            <a href="{{ route('test') }}"
               class="bg-gray-900 p-6 rounded-xl shadow-lg hover:shadow-2xl transition duration-300 transform hover:-translate-y-1 hover:scale-105 border border-amber-700">
                <h2 class="text-xl font-semibold mb-2 text-amber-400">Test</h2>
                <p class="text-gray-400">Test environment or features.</p>
            </a>

        </div>
    </main>
</x-layout>
