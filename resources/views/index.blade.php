<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AIRO Quotation</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <h1>Quotation Calculator</h1>

    <div id="message" class="message hidden"></div>

    <main id="app">
        @include('partials.login-form')
        @include('partials.register-form')
        @include('partials.quotation-form')
    </main>
</body>
</html>