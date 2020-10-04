@extends('gauge::layouts.default')

@section('body')
    <div class="bg-white py-3 rounded-lg shadow-lg">
        <h2 class="px-6 text-lg text-gray-600 tracking-wider">Request Details</h2>

        <div class="py-2 w-full border-b border-gray-200"></div>

        <table class="mx-6 mt-6">
            <tr>
                <td class="font-semibold">URI:</td>
                <td class="px-8 py-3">
                    @include('gauge::components.method-badge')

                    <span class="ml-4">{{ $request->content['route'] }}</span>
                </td>
            </tr>
            <tr>
                <td class="font-semibold">Controller Action:</td>
                <td class="px-8 py-3">{{ $request->content['controller_action'] }}</td>
            </tr>
            <tr>
                <td class="font-semibold"># Requests:</td>
                <td class="px-8 py-3">{{ number_format($request->count) }}</td>
            </tr>
            <tr>
                <td class="font-semibold">Average Runtime:</td>
                <td class="px-8 py-3">@formatNanoseconds($request->duration_average)</td>
            </tr>
            <tr>
                <td class="font-semibold">Total Runtime:</td>
                <td class="px-8 py-3">@formatNanoseconds($request->duration_total)</td>
            </tr>
            <tr>
                <td class="font-semibold">Last Seen:</td>
                <td class="px-8 py-3">{{ $request->last_seen }}</td>
            </tr>
        </table>
    </div>

    <div class="mt-8 bg-white rounded-lg shadow-lg">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead>
            <tr>
                <th class="px-6 py-3 text-left leading-4 font-medium text-gray-600 uppercase tracking-wider">
                    HTTP Status Code
                </th>
                <th class="px-6 py-3 text-left leading-4 font-medium text-gray-600 uppercase tracking-wider">
                    Runtime
                </th>
                <th class="px-6 py-3 text-left leading-4 font-medium text-gray-600 uppercase tracking-wider">
                    Time
                </th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
            @foreach($requests as $request)
                <tr>
                    <td class="px-6 py-4 whitespace-no-wrap">
                        @include('gauge::components.status-badge')
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap">
                        @formatNanoseconds($request->duration)
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap">
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
