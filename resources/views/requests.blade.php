@extends('gauge::layouts.default')

@section('body')
    <div class="bg-white rounded-lg shadow-lg">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead>
            <tr>
                <th class="px-6 py-3 text-left leading-4 font-medium text-gray-600 uppercase tracking-wider">
                    Action
                </th>
                <th class="px-6 py-3 text-left leading-4 font-medium text-gray-600 uppercase tracking-wider">
                    # Requests
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
            @foreach($requests as $request)
                <tr>
                    <td class="px-6 py-4 whitespace-no-wrap">
                        @include('gauge::components.method-badge')

                        <span class="block pl-1">
                            {{ strrev(\Illuminate\Support\Str::limit(strrev($request->content['uri']), 60)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap">
                        {{ number_format($request->count) }}
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap">
                        @formatNanoseconds($request->duration_average)
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap">
                        @formatNanoseconds($request->duration_total)
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
