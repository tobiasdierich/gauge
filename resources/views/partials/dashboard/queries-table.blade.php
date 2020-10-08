<table class="min-w-full divide-y divide-gray-200 text-xs">
    <thead>
    <tr>
        <th class="px-4 py-1 text-left leading-4 font-medium text-gray-600 uppercase tracking-wider">
            SQL
        </th>
        <th class="px-2 md:px-4 py-1 text-left leading-4 font-medium text-gray-600 uppercase tracking-wider">
            # Queries
        </th>
        <th class="px-2 md:px-4 py-1 text-left leading-4 font-medium text-gray-600 uppercase tracking-wider">
            Average Time
        </th>
    </tr>
    </thead>
    <tbody class="divide-y divide-gray-200">
    @foreach($queries as $query)
        <tr>
            <td class="px-4 py-2 whitespace-no-wrap">
                {{ \Illuminate\Support\Str::limit($query->content['sql'], 40) }}
            </td>
            <td class="px-2 md:px-4 py-2 whitespace-no-wrap">
                {{ number_format($query->count) }}
            </td>
            <td class="px-2 md:px-4 py-2 whitespace-no-wrap">
                @formatNanoseconds($query->duration_average)
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

@empty($requests->items())
    <div class="py-16 flex justify-center items-center">
        <p class="text-gray-600">No entries</p>
    </div>
@endempty
