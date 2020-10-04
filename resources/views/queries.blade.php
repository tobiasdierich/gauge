@extends('gauge::layouts.default')

@section('body')
    <div class="bg-white rounded-lg shadow-lg">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead>
            <tr>
                <th class="px-6 py-3 text-left leading-4 font-medium text-gray-600 uppercase tracking-wider">
                    SQL
                </th>
                <th class="px-6 py-3 text-left leading-4 font-medium text-gray-600 uppercase tracking-wider">
                    # Queries
                </th>
                <th class="px-6 py-3 text-left leading-4 font-medium text-gray-600 uppercase tracking-wider">
                    Average Time
                </th>
                <th class="px-6 py-3 text-left leading-4 font-medium text-gray-600 uppercase tracking-wider">
                    Total Time
                </th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
            @foreach($queries as $query)
                <tr>
                    <td class="px-6 py-4 whitespace-no-wrap">
                        {{ \Illuminate\Support\Str::limit($query->content['sql'], 60) }}
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap">
                        {{ number_format($query->count) }}
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap">
                        @formatNanoseconds($query->duration_average)
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap">
                        @formatNanoseconds($query->duration_total)
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        @empty($queries->items())
            <div class="py-16 flex justify-center items-center">
                <p class="text-gray-600">No entries</p>
            </div>
        @endempty
    </div>

    <div class="mt-4">
        {{ $queries->links('gauge::partials.pagination') }}
    </div>
@endsection
