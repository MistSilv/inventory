<x-layout>
    <main class="container mx-auto mt-10 px-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">

            <!-- Stocktakings -->
            <a href="{{ route('stocktakings.index') }}" class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition duration-200">
                <h2 class="text-xl font-semibold mb-2">Stocktakings</h2>
                <p class="text-gray-600">View and manage all stocktaking events.</p>
            </a>

            <!-- Products -->
            <a href="{{ route('products.index') }}" class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition duration-200">
                <h2 class="text-xl font-semibold mb-2">Products</h2>
                <p class="text-gray-600">Browse and edit your product catalog.</p>
            </a>

            <!-- Regions -->
            <a href="{{ route('regions.index') }}" class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition duration-200">
                <h2 class="text-xl font-semibold mb-2">Regions</h2>
                <p class="text-gray-600">Manage storage locations and regions.</p>
            </a>

            <!-- Logs -->
            <a href="{{ route('logs.index') }}" class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition duration-200">
                <h2 class="text-xl font-semibold mb-2">Logs</h2>
                <p class="text-gray-600">Audit trail of user actions and changes.</p>
            </a>
            <a href="{{ route('test') }}" class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition duration-200">
                <h2 class="text-xl font-semibold mb-2">Test</h2>
                <p class="text-gray-600">Test.</p>
            </a>

        </div>
    </main>
</x-layout>
