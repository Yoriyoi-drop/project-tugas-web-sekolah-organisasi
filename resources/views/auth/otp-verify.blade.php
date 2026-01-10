<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-2xl font-bold mb-4">Verify Your Email</h2>

                    <form method="POST" action="{{ route('otp.verify') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="otp" class="block text-gray-700 text-sm font-bold mb-2">
                                Enter OTP Code
                            </label>
                            <input type="text" name="otp" id="otp"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                required maxlength="6">
                            @error('otp')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Verify
                            </button>
                            
                            <a href="#" onclick="event.preventDefault(); document.getElementById('resend-form').submit();" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                                Resend OTP
                            </a>
                        </div>
                    </form>
                    
                    <form id="resend-form" action="{{ route('otp.resend') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
