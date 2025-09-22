<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Task Manager</title>
        
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-blue-400 text-gray-900">

        <!-- Full-screen flex container -->
        <div class="flex flex-col h-screen">

            <!-- Header -->
            <x-header />

            <!-- Centered Auth Card -->
            <main class="flex-grow flex justify-center items-center px-4 mt-10">
                <div class="w-full sm:max-w-md px-6 py-9 backdrop-blur-md bg-white/90 shadow-xl rounded-2xl border border-white/10">
                    {{ $slot }}
                </div>
            </main>

        </div>
    </body>

</html>
