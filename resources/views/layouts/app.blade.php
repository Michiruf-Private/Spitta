<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 font-sans text-gray-900 antialiased">
    <header class="border-b border-gray-200 bg-white">
        <div class="mx-auto flex w-full max-w-[600px] items-center gap-3 px-4 py-4">
            <span class="flex size-9 items-center justify-center rounded-lg bg-blue-700 font-semibold text-white">M</span>
            <div>
                <p class="text-sm font-semibold leading-tight">Medicus Fachverlag</p>
                <p class="text-xs leading-tight text-gray-500">Fachseminare für Arzt- &amp; Zahnarztpraxen</p>
            </div>
        </div>
    </header>

    <main class="mx-auto w-full max-w-[600px] px-4 py-8">
        {{ $slot }}
    </main>
</body>
</html>
