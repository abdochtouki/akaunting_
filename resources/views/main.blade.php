<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Page</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="flex justify-center items-center h-screen bg-gray-100">
<div class="text-center">
    <h1 class="text-2xl font-bold mb-4">Welcome to Laravel App</h1>
    <a href="{{ route('login') }}" class="px-4 py-2 bg-blue-500 text-white rounded mr-2">Login</a>
    <a href="{{ route('register') }}" class="px-4 py-2 bg-green-500 text-white rounded">Register</a>
</div>
</body>
</html>
