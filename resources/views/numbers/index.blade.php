<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  @vite('resources/css/app.css')
  <title>RSH Dashbord</title>
</head>
<body>
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-4 text-center py-10">RSH Dashbord</h1>

    @if($todayNumber)
    <div class="bg-green-500 shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <h2 class="text-xl font-bold mb-2 text-white">Today's Number</h2>
        <p class="text-gray-700 text-base">
            Date: {{ $todayNumber->date->format('l, F d, Y') }}
        </p>
        <p class="text-gray-700 text-base">
            Number: {{ $todayNumber->number }}
        </p>
    </div>
    @endif

    <form action="{{ route('numbers.index') }}" method="GET" class="mb-4">
        <div class="flex flex-col space-y-4 md:flex-row md:space-y-0 md:space-x-4">
            <div class="flex-grow">
                <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search by date, day, number, or month" class="mt-1 py-3 px-6 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" class="mt-1 py-3 px-6 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>
            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" class="mt-1 py-3 px-6 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-blue-500 py-3 px-6 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Search
                </button>
            </div>
        </div>
    </form>
<div class="flex flex-col lg:flex-row items-center lg:items-start gap-10 ">
     <div class="mb-8 flex-1">
            <h2 class="text-2xl font-semibold mb-4">Daily Numbers</h2>
            <table class="w-full bg-white shadow-md rounded">
                <thead>
                    <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">Date</th>
                        <th class="py-3 px-6 text-left">Day</th>
                        <th class="py-3 px-6 text-right">Number</th>
                        <th class="py-3 px-6 text-right">Amount</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-light">
                    @foreach ($dailyNumbers as $daily)
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-6 text-left whitespace-nowrap">{{ $daily->date }}</td>
                        <td class="py-3 px-6 text-left">{{ $daily->day }}</td>
                        <td class="py-3 px-6 text-right">{{ $daily->number }}</td>
                        <td class="py-3 px-6 text-right">{{ number_format($daily->amount, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">
                {{-- {{ $dailyNumbers->links() }} --}}
                {{ $dailyNumbers->appends(request()->query())->links() }}
            </div>
        </div>

        <div class="flex-2">
            <h2 class="text-2xl font-semibold mb-4">Monthly Totals</h2>
            <table class="w-full bg-white shadow-md rounded">
                <thead>
                    <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">Month</th>
                        <th class="py-3 px-6 text-right">Total Number</th>
                        <th class="py-3 px-6 text-right">Total Amount</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-light">
                    @foreach ($monthlyTotals as $monthly)
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-6 text-left">{{ $monthly->month_name }}</td>
                        <td class="py-3 px-6 text-right">{{ $monthly->total_number }}</td>
                        <td class="py-3 px-6 text-right">{{ number_format($monthly->total_amount, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">
                {{-- {{ $monthlyTotals->links() }} --}}
                {{ $monthlyTotals->appends(request()->query())->links() }}
            </div>
        </div>
        </div>
</div>
</body>
</html>