<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config( 'app.name', 'Laravel' ) }} - Installer</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    @livewireStyles
</head>
<body>
    <div class="min-h-screen bg-gray-50 justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full space-y-8">
            <livewire:installer-wizard />
        </div>
    </div>
    @livewireScripts
</body>
</html>
