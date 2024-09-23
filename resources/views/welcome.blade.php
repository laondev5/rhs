<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  @vite('resources/css/app.css')
</head>
<body>
 <div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-4">Daily Numbers and Monthly Totals</h1>

    <form action="{{ route('numbers.index') }}" method="GET" class="mb-4">
        <div class="flex space-x-4">
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>
            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Filter
                </button>
            </div>
        </div>
    </form>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <h2 class="text-xl font-bold mb-2">Daily Numbers</h2>
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Number</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dailyNumbers as $dailyNumber)
                    <tr>
                        <td class="py-2 px-4 border-b border-gray-200">{{ $dailyNumber->date->format('Y-m-d') }}</td>
                        <td class="py-2 px-4 border-b border-gray-200">{{ $dailyNumber->number }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">
                {{ $dailyNumbers->appends(request()->query())->links() }}
            </div>
        </div>

        <div>
            <h2 class="text-xl font-bold mb-2">Monthly Totals</h2>
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Month</th>
                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($monthlyTotals as $monthlyTotal)
                    <tr>
                        <td class="py-2 px-4 border-b border-gray-200">{{ $monthlyTotal->month->format('Y-m') }}</td>
                        <td class="py-2 px-4 border-b border-gray-200">{{ $monthlyTotal->total }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">
                {{ $monthlyTotals->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
</body>
</html>