@extends('layouts.app')

@section('content')
<div class="container">
  <div class="flex justify-center">
    <div class="w-full md:w-1/2">
      <div class="border border-gray-200 rounded-md shadow-md">
        <div class="bg-gray-100 border-b border-gray-200 py-3 px-4">
          <h1 class="text-lg font-medium">{{ __('Confirm Password') }}</h1>
        </div>
        <div class="p-4">
          <p>{{ __('Please confirm your password before continuing.') }}</p>
          <form method="POST" action="{{ route('password.confirm') }}" class="mt-4">
            @csrf
            <div class="mb-4">
              <label for="password" class="block text-gray-700 font-medium mb-2">{{ __('Password') }}</label>
              <input id="password" type="password" class="form-input @error('password') border-red-500 @enderror" name="password" required autocomplete="current-password">
              @error('password')
              <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
              @enderror
            </div>
            <div class="flex items-center justify-between">
              <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium px-4 py-2 rounded-md">{{ __('Confirm Password') }}</button>
              @if (Route::has('password.request'))
              <a class="text-blue-500 hover:text-blue-600 font-medium" href="{{ route('password.request') }}">{{ __('Forgot Your Password?') }}</a>
              @endif
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
