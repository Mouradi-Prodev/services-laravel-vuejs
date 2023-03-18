@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
        <div>
            
            <h2 class="mt-6 text-center text-3xl leading-9 font-extrabold text-gray-900">
                {{ __('Verify Your Email Address') }}
            </h2>
        </div>
        <div class="mt-8">
            @if (session('resent'))
                <div class="text-green-700 mb-6" role="alert">
                    {{ __('A fresh verification link has been sent to your email address.') }}
                </div>
            @endif

            <p class="text-lg leading-7 text-gray-600">{{ __('Before proceeding, please check your email for a verification link.') }}</p>
            <p class="mt-1 text-lg leading-7 text-gray-600">{{ __('If you did not receive the email') }},</p>

            <form class="mt-6" method="POST" action="{{ route('verification.resend') }}">
                @csrf
                <div class="rounded-md shadow-sm">
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent text-lg font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 transition duration-150 ease-in-out">
                        {{ __('click here to request another') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
