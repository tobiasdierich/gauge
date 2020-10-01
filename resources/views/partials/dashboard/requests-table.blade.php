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
    <tbody class="bg-white divide-y divide-gray-200">
    @foreach($requests as $request)
        <tr>
            <td class="px-4 py-2 whitespace-no-wrap">
                @switch($request->content['method'])
                    @case('GET')
                        <span class="px-2 leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                            {{ $request->content['method'] }}
                        </span>
                        @break

                    @case('POST')
                        <span class="px-2 leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            {{ $request->content['method'] }}
                        </span>
                        @break

                    @case('DELETE')
                        <span class="px-2 leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                            {{ $request->content['method'] }}
                        </span>
                        @break

                    @case('PATCH')
                    @case('UPDATE')
                        <span class="px-2 leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            {{ $request->content['method'] }}
                        </span>
                        @break

                    @default
                        <span class="px-2 leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                            {{ $request->content['method'] }}
                        </span>
                @endswitch

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
