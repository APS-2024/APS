<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'FMS') }}</title>
    <link rel="stylesheet" href="{{asset('public/assets-home/css/style2.css')}}">
    <link rel="stylesheet" href="{{asset('public/assets-home/css/responsive2.css')}}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

  
    <!-- aos animation  -->
    <!-- font awesome  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Alfa+Slab+One&family=Andika:ital,wght@0,400;0,700;1,400;1,700&family=Cabin:ital,wght@0,400..700;1,400..700&family=Inter:wght@100..900&family=Mukta:wght@200;300;400;500;600;700;800&family=Playwrite+AT:ital,wght@0,100..400;1,100..400&family=Playwrite+DK+Loopet:wght@100..400&family=Rubik:ital,wght@0,300..900;1,300..900&display=swap"
        rel="stylesheet">
    

       <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">




</head>
<style>
    
    a.item-link {
    text-decoration: none;
    padding-right: .5rem;
    padding-left: .5rem;
}
    
</style>
<body style="background-color:#1c202f">


<!-- loader  -->
    <div class="loader_bg">
        <div class="loader"><img src="{{asset('public/assets-home/images/loader2.gif')}}" alt="#" /></div>
    </div>
    
    
    
    
            @yield('content')


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
    <script src="{{asset('public/assets-home/js/script2.js')}}"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <!-- js file -->

    <script>
        AOS.init();
    </script>

   

    <script>
        AOS.init({
            duration: 1200,
            once: true
        });
        console.log('AOS Initialized');
    </script>

</body>
</html>
