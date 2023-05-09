<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="Introducing the website where users can publish their blogs.">
        <meta name="keywords" content="blogs, publishing, users">
        <meta property="og:title" content="Introducing the Website for Publishing Blogs">
        <meta property="og:description" content="Check out our website where users can publish their blogs.">
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{url('/Services-and-Blogs')}}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/sass/app.scss', 'resources/js/app.js'])
        <style>
  body {
    padding-top: 64px; /* adjust the value based on the height of your navbar */
  }
</style>
    </head>
    <body class="bg-gray-100 font-sans leading-normal tracking-normal">

<!-- Navbar -->
<nav class="bg-gray-800 p-2 mt-0 fixed w-full z-10 top-0">
    <div class="container mx-auto flex flex-wrap items-center">
        <div class="flex w-full md:w-1/2 justify-center md:justify-start text-white font-extrabold">
            <a class="text-white no-underline hover:text-white hover:no-underline" href="/Services">
                <span class="text-2xl pl-2"><i class="em em-grinning"></i> Services</span>
            </a>
        </div>
        <div class="flex w-full pt-2 content-center justify-between md:w-1/2 md:justify-end">
            <ul class="list-reset flex justify-between flex-1 md:flex-none items-center">
                @guest
                    <li class="mr-3">
                        <a class="inline-block py-2 px-4 text-white no-underline" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="mr-3">
                        <a class="inline-block text-gray-400 no-underline hover:text-gray-200 hover:text-underline py-2 px-4" href="{{ route('register') }}">Register</a>
                    </li>
                @else
                <li class="mr-3">
                    <a class="inline-block text-gray-400 no-underline hover:text-gray-200 hover:text-underline py-2 px-4" href="/Settings">{{ Auth::user()->name }}</a>
                </li>
                    <li class="mr-3">
                        <a class="inline-block text-gray-400 no-underline hover:text-gray-200 hover:text-underline py-2 px-4" href="/home">home</a>
                    </li>
                    <li class="mr-3">
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="inline-block">
                            @csrf
                            <button type="submit" class="text-gray-400 no-underline hover:text-gray-200 hover:text-underline py-2 px-4">Logout</button>
                        </form>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

<!-- Page Content -->
<div class="container mx-auto pt-16">
    <div id="app1">
        <services></services>
    </div>
</div>
            
        </body>
    </html>