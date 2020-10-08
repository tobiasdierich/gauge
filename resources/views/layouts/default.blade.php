<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta Information -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="robots" content="noindex, nofollow">

    <title>Gauge{{ config('app.name') ? ' - ' . config('app.name') : '' }}</title>

    <!-- Style sheets-->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap" rel="stylesheet">
    <link href="{{ asset(mix('base.css', 'vendor/gauge')) }}" rel="stylesheet" type="text/css">
</head>
<body class="min-h-screen bg-gray-200 font-sans antialiased text-gray-700">
    <div class="container mx-auto min-h-screen flex flex-col">
        <div>
            @include('gauge::partials.header')
        </div>

        <div class="w-full flex md:space-x-12 py-8">
            <div class="hidden md:block w-64">
                @include('gauge::partials.sidebar')
            </div>

            <div class="max-w-full flex-grow px-4">
                @yield('body')
            </div>
        </div>
    </div>

    <script>
        window.Gauge = @json(\TobiasDierich\Gauge\Gauge::scriptVariables());
    </script>

    @yield('scripts')
</body>
</html>
