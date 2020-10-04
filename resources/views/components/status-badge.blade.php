@switch(substr($request->content['response_status'], 0, 1))
    @case('1')
        <span class="px-2 leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
            {{ $request->content['response_status'] }}
        </span>
        @break

    @case('2')
        <span class="px-2 leading-5 font-semibold rounded-full bg-green-100 text-green-800">
            {{ $request->content['response_status'] }}
        </span>
        @break

    @case('3')
        <span class="px-2 leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
            {{ $request->content['response_status'] }}
        </span>
        @break

    @case('4')
        <span class="px-2 leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
            {{ $request->content['response_status'] }}
        </span>
        @break

    @default
        <span class="px-2 leading-5 font-semibold rounded-full bg-red-100 text-red-800">
            {{ $request->content['response_status'] }}
        </span>
@endswitch
