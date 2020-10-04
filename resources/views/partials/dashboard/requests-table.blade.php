<table class="min-w-full divide-y divide-gray-200 text-xs">
    <thead>
    <tr>
        <th class="px-4 py-1 text-left leading-4 font-medium text-gray-600 uppercase tracking-wider">
            Action
        </th>
        <th class="px-4 py-1 text-left leading-4 font-medium text-gray-600 uppercase tracking-wider">
            # Requests
        </th>
        <th class="px-4 py-1 text-left leading-4 font-medium text-gray-600 uppercase tracking-wider">
            Average Time
        </th>
    </tr>
    </thead>
    <tbody class="divide-y divide-gray-200">
    @foreach($requests as $request)
        <tr>
            <td class="px-4 py-2 whitespace-no-wrap">
                @include('gauge::components.method-badge')

                <span class="block pl-1">
                    {{ strrev(\Illuminate\Support\Str::limit(strrev($request->content['uri']), 30)) }}
                </span>
            </td>
            <td class="px-4 py-2 whitespace-no-wrap">
                {{ number_format($request->count) }}
            </td>
            <td class="px-4 py-2 whitespace-no-wrap">
                @formatNanoseconds($request->duration_average)
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
