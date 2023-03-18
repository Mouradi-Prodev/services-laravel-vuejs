@extends('layouts.app')

@section('content')
<div class="container mx-auto">
  <div class="flex justify-center">
    <div class="w-full md:w-8/12">
      <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="mb-4">
          <h1 class="text-center font-bold text-xl">{{ __('Register') }}</h1>
        </div>

        <form method="POST" action="{{ route('register') }}">
          @csrf

          <div class="mb-4">
            <label for="name" class="block text-gray-700 font-bold mb-2">{{ __('Name') }}</label>
            <input id="name" type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
            @error('name')
              <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
          </div>

          <div class="mb-4">
            <label for="email" class="block text-gray-700 font-bold mb-2">{{ __('Email Address') }}</label>
            <input id="email" type="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('email') border-red-500 @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
            @error('email')
              <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
          </div>

          <div class="mb-4">
            <label for="password" class="block text-gray-700 font-bold mb-2">{{ __('Password') }}</label>
            <input id="password" type="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline @error('password') border-red-500 @enderror" name="password" required autocomplete="new-password">
            @error('password')
              <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
          </div>

          <div class="mb-4">
            <label for="password-confirm" class="block text-gray-700 font-bold mb-2">{{ __('Confirm Password') }}</label>
            <input id="password-confirm" type="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" name="password_confirmation" required autocomplete="new-password">
          </div>

          <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
              {{ __('Register') }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection
