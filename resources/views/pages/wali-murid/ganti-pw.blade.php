@extends('component.layout')

@section('content')
    <div class="flex flex-col justify-center">
        <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-gray-900 md:text-5xl lg:text-6xl">
            Akun Anda
        </h1>
        <div class="flow-root">
            <dl class="my-3 divide-y divide-gray-100 text-sm">
                <div class="grid grid-cols-1 gap-1 py-3 even:bg-gray-50 sm:grid-cols-3 sm:gap-4">
                    <dt class="font-medium text-gray-900">Email Anda</dt>
                    <dd class="text-gray-700 sm:col-span-2">{{ $email }}</dd>
                </div>

                <!-- Password Update Form -->
                <form id="update-password-form" method="POST" action="{{ route('updatePw.wali') }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="email" value="{{ $email }}">

                    <div class="mb-4">
                        <label for="pw" class="block mb-2 text-sm font-medium text-gray-900">Password Baru</label>
                        <div class="relative">
                            <input type="password" id="pw" name="pw"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                required />
                            <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5">
                                <svg id="lockOpen" class="h-5 w-5 text-gray-500" fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 17a2 2 0 110-4 2 2 0 010 4zm4-7V7a4 4 0 10-8 0v3" />
                                    <rect width="20" height="12" x="2" y="11" rx="2" ry="2" />
                                </svg>
                                <svg id="lockClosed" class="h-5 w-5 text-gray-500 hidden" fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 17a2 2 0 110-4 2 2 0 010 4zm4-7V7a4 4 0 10-8 0v3" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 11h16v10a2 2 0 01-2 2H6a2 2 0 01-2-2V11z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-start mb-6">
                        <div class="flex items-center h-5">
                            <input id="corrected" type="checkbox" value=""
                                class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300"
                                required />
                        </div>
                        <label for="corrected" class="ms-2 text-sm font-medium text-gray-900">Pastikan anda sudah
                            memasukan data dengan benar!!</label>
                    </div>

                    <div class="flex flex-row justify-end py-3">
                        <button type="submit"
                            class="inline-block rounded border border-blue-700 mx-2 px-8 py-3 text-sm font-medium text-blue-700 transition-colors hover:bg-blue-800 hover:text-white focus:ring-4 focus:ring-blue-300">
                            Ganti Pw
                        </button>
                    </div>
                </form>
            </dl>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#pw');
        const lockOpen = document.querySelector('#lockOpen');
        const lockClosed = document.querySelector('#lockClosed');

        togglePassword.addEventListener('click', function () {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);

            // Toggle visibility of SVG icons
            lockOpen.classList.toggle('hidden');
            lockClosed.classList.toggle('hidden');
        });
    });
    </script>
@endsection

