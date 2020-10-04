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
