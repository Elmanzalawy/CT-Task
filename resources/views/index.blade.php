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
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Possimus consequuntur dolorum reiciendis repudiandae nostrum, tempore incidunt quibusdam numquam ipsam sequi praesentium inventore perferendis consequatur ipsum assumenda aliquid eius facere voluptatum eum quam temporibus perspiciatis unde. Totam eius nisi est odit dolore quidem assumenda, recusandae quas. Corrupti, dolorum unde! Dolores, laboriosam!</p>
                <tasks-wrapper></tasks-wrapper>
            </div>
        </div>

        <script src="{{ asset("js/app.js") }}"></script>
    </body>
</html>
