<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ env('APP_NAME') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->
        <style>
            body {
                font-family: 'Nunito', sans-serif;
            }
        </style>

        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    </head>
    <body class="antialiased">
        <div id="app">
            <div id="container">
                <h1>{{ env('APP_NAME') ?? "Task Manager"}}</h1>
                <p>Task Manager is an app where you can create, edit, delete tasks, and reorder them by priority. It is also possible to group tasks by projects!</p>
                <tasks-wrapper></tasks-wrapper>
            </div>
        </div>

        <script src="{{ asset("js/app.js") }}"></script>
    </body>
</html>
