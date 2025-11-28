<x-guest-layout>
    <div class="flex flex-col gap-6">
        <div class="space-y-2 text-center">
            <p class="text-sm uppercase tracking-[0.25em] text-indigo-200">Selamat datang kembali</p>
            <h1 class="text-3xl font-semibold text-white">Masuk ke akun Anda</h1>
            <p class="text-sm text-indigo-100/80">Akses dan kelola file Anda dengan tampilan baru yang lebih rapi.</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <div class="space-y-2">
                <div class="flex items-center justify-between text-sm text-indigo-100/80">
                    <x-input-label for="email" :value="__('Email')" class="text-indigo-100" />
                    <span class="text-indigo-200/70">Gunakan email yang terdaftar</span>
                </div>
                <x-text-input id="email" class="block w-full rounded-xl border-0 bg-white/10 text-white placeholder:text-indigo-100/60 focus:ring-2 focus:ring-indigo-400 focus:bg-white/20" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="nama@contoh.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="space-y-2">
                <div class="flex items-center justify-between text-sm text-indigo-100/80">
                    <x-input-label for="password" :value="__('Password')" class="text-indigo-100" />
                    @if (Route::has('password.request'))
                        <a class="text-indigo-200 hover:text-white transition" href="{{ route('password.request') }}">
                            {{ __('Lupa password?') }}
                        </a>
                    @endif
                </div>
                <x-text-input id="password" class="block w-full rounded-xl border-0 bg-white/10 text-white placeholder:text-indigo-100/60 focus:ring-2 focus:ring-indigo-400 focus:bg-white/20" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="flex items-center justify-between text-indigo-100/80 text-sm">
                <label for="remember_me" class="inline-flex items-center gap-3">
                    <input id="remember_me" type="checkbox" class="rounded border-indigo-300 bg-white/10 text-indigo-400 shadow-sm focus:ring-indigo-500" name="remember">
                    <span>{{ __('Ingat saya') }}</span>
                </label>
                <a href="{{ route('register') }}" class="text-indigo-200 hover:text-white transition">Buat akun baru</a>
            </div>

            <x-primary-button class="w-full justify-center rounded-xl bg-indigo-500 hover:bg-indigo-400 text-white text-sm uppercase tracking-wide py-3 shadow-lg shadow-indigo-900/40 transition">
                {{ __('Masuk') }}
            </x-primary-button>
        </form>
    </div>
</x-guest-layout>
