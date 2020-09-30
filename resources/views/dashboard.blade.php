@extends('gauge::layouts.default')

@section('body')
    <div class="flex space-x-8">
        <div class="w-1/2">
            <div id="requestsChart">
                <chart :title="title" :endpoint="endpoint"></chart>
            </div>
        </div>

        <div class="w-1/2">
            <div id="queriesChart">
                <chart :title="title" :endpoint="endpoint"></chart>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{asset(mix('charts.js', 'vendor/gauge'))}}"></script>
@endsection
