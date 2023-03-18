<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
 

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
   


</head>
<body>
    <div id="app">
    <nav class="bg-gray-800 text-white">
    <div class="container mx-auto px-4 py-2 flex justify-between items-center">
      <a href="#" class="text-xl font-bold">
      <span class="bg-gradient-to-r from-indigo-600 to-pink-500 text-white px-2 box-decoration-clone hover:box-decoration-slice ">
  Social<br>
  Services
</span></a>
      
     <!-- <button class="px-4 py-2 bg-gray-600 hover:bg-gray-700 rounded-lg">Logout</button>-->
     @auth 
     <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button class="px-4 py-2 bg-gray-600 hover:bg-gray-700 rounded-lg" type="submit">Logout</button>
        </form>
        @endauth
    </div>
  </nav>
  
  <!-- Main content -->
  <main class="container mx-auto px-4 py-8">
    <!-- Your page content goes here -->
    @yield('content')
  </main>
  
  <!-- Footer -->
  <footer class="bg-gray-200 text-gray-600 text-center py-4">
    <p>Copyright &copy; 2023 - Mouradi</p>
  </footer>
    </div>
</body>

</html>
