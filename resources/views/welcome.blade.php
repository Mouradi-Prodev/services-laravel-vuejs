<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="keywords" content="blogs, publishing, users">
        <meta property="og:title" content="Introducing the Website for Publishing Services and Blogs">
        <meta property="og:description" content="Check out our website where users can publish their blogs. and services">
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{url('/')}}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    </head>
    <body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-white font-sans antialiased">
        <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center selection:bg-red-500 selection:text-white">
            <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right">
                @if (Route::has('login'))
                    <div class="mb-2">
                        @auth
                            <a href="{{ url('/home') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Home</a>
                        @else
                            <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Log in</a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Register</a>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>

            <div class="max-w-5xl mx-auto p-4 sm:p-6 lg:p-8">
  <h1 class="text-4xl sm:text-5xl font-bold leading-tight mb-4">Welcome to Services and Blogs</h1>
  <p class="text-lg sm:text-xl leading-relaxed mb-8">Publish your own blog and explore thousands of other blogs created by people all around the world.</p>
  <a href="/Services" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded inline-block">Find more</a>
  
  <!-- New intro section -->
  <section class="py-12">
    <h2 class="text-2xl sm:text-3xl font-bold leading-tight mb-4">Latest Services</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <div class="bg-white rounded-lg shadow-lg p-6">
        <h3 class="text-xl font-bold mb-2">Web Development</h3>
        <p class="text-gray-700 leading-relaxed mb-4">We create custom websites that are tailored to your unique needs.</p>
        <a href="#" class="text-blue-500 hover:text-blue-600 font-semibold">Learn more</a>
      </div>
      <div class="bg-white rounded-lg shadow-lg p-6">
        <h3 class="text-xl font-bold mb-2">Graphic Design</h3>
        <p class="text-gray-700 leading-relaxed mb-4">Our team of talented designers can create stunning visuals for your brand.</p>
        <a href="#" class="text-blue-500 hover:text-blue-600 font-semibold">Learn more</a>
      </div>
      <div class="bg-white rounded-lg shadow-lg p-6">
        <h3 class="text-xl font-bold mb-2">Content Creation</h3>
        <p class="text-gray-700 leading-relaxed mb-4">We can help you create engaging content that resonates with your audience.</p>
        <a href="#" class="text-blue-500 hover:text-blue-600 font-semibold">Learn more</a>
      </div>
    </div>
  </section>
 
                            
            </div>
        </body>
    </html>