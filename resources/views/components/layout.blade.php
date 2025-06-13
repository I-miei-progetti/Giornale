<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite(['resources/css/app.css','resources/js/app.js'])
    <title>Al Volante</title>
    <link rel="icon" href="/image/logo.png" class="logo" type="image/x-icon">
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />


</head>
<body>
    <x-navbar/>
     {{-- <x-carousel/> --}}
    <div class="min-vh-100">
        {{$slot}}
    </div>
   
    <x-footer/>
</body>
</html>