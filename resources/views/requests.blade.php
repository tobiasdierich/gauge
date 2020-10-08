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
                <tr class="hover:bg-gray-100 cursor-pointer">
                    <td class="whitespace-no-wrap">
                        <a href="{{ route('gauge.requests.show', ['familyHash' => $request->familyHash]) }}">
                            <div class="px-6 py-4">
                                @include('gauge::components.method-badge')

                                <span class="block pl-1">
                                    {{ strrev(\Illuminate\Support\Str::limit(strrev($request->content['uri']), 60)) }}
                                </span>
                            </div>
                        </a>
                    </td>
                    <td class="whitespace-no-wrap">
                        <a href="{{ route('gauge.requests.show', ['familyHash' => $request->familyHash]) }}">
                            <div class="px-6 py-4">
                                {{ number_format($request->count) }}
                            </div>
                        </a>
                    </td>
                    <td class="whitespace-no-wrap">
                        <a href="{{ route('gauge.requests.show', ['familyHash' => $request->familyHash]) }}">
                            <div class="px-6 py-4">
                                @formatNanoseconds($request->duration_average)
                            </div>
                        </a>
                    </td>
                    <td class="whitespace-no-wrap">
                        <a href="{{ route('gauge.requests.show', ['familyHash' => $request->familyHash]) }}">
                            <div class="px-6 py-4">
                                @formatNanoseconds($request->duration_total)
                            </div>
                        </a>
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
