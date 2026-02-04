<x-guest-layout>
  <div class="min-h-screen flex">
    <!-- Left Side - Blue Section with Image -->
    <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-blue-600 to-blue-800 relative overflow-hidden">
      <div class="absolute inset-0 opacity-10">
        <svg class="w-full h-full" viewBox="0 0 400 400">
          <circle cx="200" cy="200" r="150" fill="none" stroke="white" stroke-width="2" />
          <circle cx="200" cy="200" r="200" fill="none" stroke="white" stroke-width="2" />
          <circle cx="200" cy="200" r="250" fill="none" stroke="white" stroke-width="2" />
        </svg>
      </div>
      <div class="relative z-10 flex items-center justify-center w-full p-12">
        <img src="{{ asset('images/login.png') }}" alt="Student reading"
          class="max-w-md object-contain drop-shadow-2xl">
      </div>
    </div>

    <!-- Right Side - Login Form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-white">
      <div class="max-w-md w-full space-y-8">
        <!-- Logo -->
        <div class="flex items-center justify-center lg:justify-start">
          <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-8 w-8">
          <span class="ml-2 text-xl font-semibold text-gray-800">DRDI</span>
        </div>

        <!-- Login Header -->
        <div>
          <h2 class="text-3xl font-bold text-gray-900">Login</h2>
          <p class="mt-2 text-sm text-gray-600">Enter your credentials to login to your account</p>
        </div>

        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-6">
          @csrf

          <!-- Email Field -->
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
            <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}"
              class="appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror">
            @error('email')
              <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <!-- Password Field -->
          <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
            <input id="password" name="password" type="password" autocomplete="current-password" required
              class="appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-red-500 @enderror">
            @error('password')
              <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <!-- Remember Me & Forgot Password -->
          <div class="flex items-center justify-between">
            <div class="flex items-center">
              <input id="remember_me" name="remember" type="checkbox"
                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
              <label for="remember_me" class="ml-2 block text-sm text-gray-700">
                Remember me
              </label>
            </div>

            <div class="text-sm">
              <a href="#" class="font-medium text-blue-600 hover:text-blue-500">
                Forgot Password?
              </a>
            </div>
          </div>

          <!-- Sign In Button -->
          <div>
            <button type="submit"
              class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
              Sign In
            </button>
          </div>


        </form>
      </div>
    </div>
  </div>
</x-guest-layout>
