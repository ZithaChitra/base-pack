<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>MSB | Auth</title>
    @livewireStyles
</head>
<body>
    @livewire('auth.authenticate')
    @livewireScripts 
    <script>
        console.log('got here')
    </script>
</body>
</html>