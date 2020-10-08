@extends('gauge::layouts.default')

@section('body')
    <div class="bg-white py-3 rounded-lg shadow-lg">
        <h2 class="px-4 md:px-6 md:text-lg text-gray-600 tracking-wider">Request Details</h2>

        <div class="py-2 w-full border-b border-gray-200"></div>

        <div class="mx-4 md:mx-6 mt-6">
            <div>
                <span class="block font-semibold">URI:</span>
                <span>
                    @include('gauge::components.method-badge')

                    <span class="ml-4">{{ $request->content['route'] }}</span>
                </span>
            </div>
            <div class="mt-4">
                <span class="block font-semibold">Controller Action:</span>
                <span>{{ $request->content['controller_action'] }}</span>
            </div>
            <div class="mt-4">
                <span class="block font-semibold"># Requests:</span>
                <span>{{ number_format($request->count) }}</span>
            </div>
            <div class="mt-4">
                <span class="block font-semibold">Average Runtime:</span>
                <span>@formatNanoseconds($request->duration_average)</span>
            </div>
            <div class="mt-4">
                <span class="block font-semibold">Total Runtime:</span>
                <span>@formatNanoseconds($request->duration_total)</span>
            </div>
            <div class="mt-4">
                <span class="block font-semibold">Last Seen:</span>
                <span>{{ $request->last_seen }}</span>
            </div>
        </div>
    </div>

    <div class="mt-8 bg-white rounded-lg shadow-lg overflow-scroll">
        <table class="min-w-full divide-y divide-gray-200 text-xs md:text-sm">
            <thead>
            <tr>
                <th class="px-4 md:px-6 py-2 md:py-3 text-left leading-4 font-medium text-gray-600 uppercase tracking-wider">
                    HTTP Status Code
                </th>
                <th class="px-2 md:px-6 py-2 md:py-3 text-left leading-4 font-medium text-gray-600 uppercase tracking-wider">
                    Runtime
                </th>
                <th class="px-4 md:px-6 py-2 md:py-3 text-left leading-4 font-medium text-gray-600 uppercase tracking-wider">
                    Time
                </th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
            @foreach($requests as $request)
                <tr>
                    <td class="px-4 md:px-6 py-2 md:py-4 whitespace-no-wrap">
                        @include('gauge::components.status-badge')
                    </td>
                    <td class="px-2 md:px-6 py-2 md:py-4 whitespace-no-wrap">
                        @formatNanoseconds($request->duration)
                    </td>
                    <td class="px-4 md:px-6 py-2 md:py-4 whitespace-no-wrap">
                        {{ $request->created_at }}
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
    </div>

    <div class="mt-4">
        {{ $requests->links('gauge::partials.pagination') }}
    </div>
@endsection
