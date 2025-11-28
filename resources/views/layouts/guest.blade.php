<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="relative min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 overflow-hidden">
            <div class="absolute inset-0 opacity-30" aria-hidden="true">
                <div class="absolute -left-32 -top-32 h-96 w-96 rounded-full bg-purple-500 blur-[120px]"></div>
                <div class="absolute -right-24 top-16 h-80 w-80 rounded-full bg-indigo-500 blur-[120px]"></div>
                <div class="absolute left-1/2 bottom-0 h-64 w-64 -translate-x-1/2 rounded-full bg-cyan-400 blur-[120px]"></div>
            </div>

            <div class="relative min-h-screen flex flex-col sm:justify-center items-center px-4 py-10">
                <div class="flex items-center gap-3 text-white/80">
                    <a href="/" class="flex items-center gap-3">
                        <x-application-logo class="w-14 h-14 fill-current text-white" />
                        <div>
                            <p class="text-sm uppercase tracking-[0.3em]">Cloud Storage</p>
                            <p class="text-2xl font-semibold">{{ config('app.name', 'Laravel') }}</p>
                        </div>
                    </a>
                </div>

                <div class="w-full sm:max-w-md mt-10">
                    <div class="bg-white/10 backdrop-blur-md border border-white/10 shadow-2xl shadow-indigo-900/40 rounded-2xl p-8 sm:p-10 text-white">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
