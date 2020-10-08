@extends('gauge::layouts.default')

@section('body')
    <div class="flex flex-wrap space-y-8 md:flex-no-wrap md:space-y-0 md:space-x-8">
        <div class="w-full md:w-1/2">
            <div id="requestsChart">
                <chart :title="title" :endpoint="endpoint"></chart>
            </div>
        </div>

        <div class="w-full md:w-1/2">
            <div id="queriesChart">
                <chart :title="title" :endpoint="endpoint"></chart>
            </div>
        </div>
    </div>

    <div class="mt-16 flex flex-wrap space-y-8 md:flex-no-wrap md:space-y-0 md:space-x-8">
        <div class="w-full md:w-1/2 bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="p-4 flex justify-between items-baseline text-gray-600">
                <h2 class="font-semibold">Expensive Requests</h2>

                <a class="flex items-center text-sm hover:text-gray-700" href="{{ route('gauge.requests.index') }}">
                    View All

                    <svg class="ml-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </a>
            </div>

            @include('gauge::partials.dashboard.requests-table')
        </div>

        <div class="w-full md:w-1/2 bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="p-4 flex justify-between items-baseline text-gray-600">
                <h2 class="font-semibold">Expensive Queries</h2>

                <a class="flex items-center text-sm hover:text-gray-700" href="{{ route('gauge.queries.index') }}">
                    View All

                    <svg class="ml-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </a>
            </div>

            @include('gauge::partials.dashboard.queries-table')
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{asset(mix('charts.js', 'vendor/gauge'))}}"></script>
@endsection
