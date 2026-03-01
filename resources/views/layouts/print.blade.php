<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    <style>
        @media print {
            .no-print { display: none !important; }
        }
    </style>
</head>
<body class="bg-slate-100 antialiased" style="font-family: 'Courier Prime', monospace !important;">
    <div class="no-print fixed top-5 right-5 z-50">
        <button onclick="window.print()" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg font-bold shadow-lg transition-all flex items-center gap-2">
            <span>🖨️</span> Print Kartu
        </button>
    </div>
    @yield('content')
</body>
</html>
