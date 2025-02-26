
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'Accueil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: white;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px;
        }
        .image-container {
            flex: 1;
            display: flex;
            justify-content: flex-start;
            padding-right: 200px;
        }
        .image-container img {
            max-width: 300px;
            border-radius: 10px;
        }
        .container {
            flex: 1;
            background: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-right: 150px;
        }
        .btn-custom {
            background-color: #6EA152;
            color: white;
            border: none;
        }
        .btn-custom:hover {
            background-color: #333;
        }
    </style>
</head>
<body>
<div class="image-container">
    <img src="{{ asset('public/img/akaunting-logo-green.svg') }}" class="w-16" alt="Akaunting" />
</div>
<div class="container">
    <h1>Welcome to Akaunting</h1>
    <p>Please choose an action:</p>
    <div class="d-grid gap-3">
        <a href="{{ route('register') }}" class="btn btn-custom btn-lg">register</a>
        <a href="{{ route('login') }}" class="btn btn-custom btn-lg">login</a>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
