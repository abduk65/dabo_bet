<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Vue Integration</title>
    @vite('resources/css/app.css')
</head>

<body>
    <div id="app">
        <!-- Your Vue components can be added here -->
        <weather-app></weather-app>
    </div>
    @vite('resources/js/app.js')
</body>

</html>
