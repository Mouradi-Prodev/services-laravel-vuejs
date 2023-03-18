@extends('layouts.app')

@section('content')
<div class="container mx-auto">
  <div class="flex justify-center">
    <div class="w-full md:w-1/2 lg:w-2/3">
      <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="mb-4">
          <h1 class="text-gray-800 font-bold text-2xl">{{ __('Login') }}</h1>
        </div>
        <form method="POST" action="{{ route('login') }}">
          @csrf
          <div class="mb-4">
            <label for="email" class="block text-gray-700 font-bold mb-2">{{ __('Email Address') }}</label>
            <input id="email" type="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('email') border-red-500 @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
            @error('email')
            <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
          </div>
          <div class="mb-6">
            <label for="password" class="block text-gray-700 font-bold mb-2">{{ __('Password') }}</label>
            <input id="password" type="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline @error('password') border-red-500 @enderror" name="password" required autocomplete="current-password">
            @error('password')
            <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
          </div>
          <div class="mb-6">
            <label class="flex items-center">
              <input type="checkbox" class="form-checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
              <span class="ml-2 text-sm text-gray-600">{{ __('Remember Me') }}</span>
            </label>
          </div>
          <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
              {{ __('Login') }}
            </button>
            @if (Route::has('password.request'))
            <a class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800" href="{{ route('password.request') }}">
              {{ __('Forgot Your Password?') }}
            </a>
            @endif
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection
