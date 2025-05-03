<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>White Usagi API</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="jumbotron">
            <h1 class="display-4">Welcome to White Usagi API!</h1>
            <p class="lead"> This is the backend of the APP to manage anime information, genres, studios and episodes.</p>
            <hr class="my-4">
            <p>
                Laravel Version: {{ Illuminate\Foundation\Application::VERSION }}<br>
                PHP Version: {{ PHP_VERSION }}
            </p>
            <p class="lead">
                The frontend of this app will be built separately.
            </p>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>