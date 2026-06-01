@extends('layouts.app')

@section('title', 'Login Administrator - SPPK Pasirian')

@section('content')
    <script src="https://unpkg.com/lucide@latest"></script>

    <div id="admin-login-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-xs"></div>

        <div
            class="relative bg-white w-full max-w-md rounded-2xl border border-slate-100 shadow-2xl overflow-hidden p-6 sm:p-8 space-y-6 animate-in fade-in zoom-in-95 duration-200">

            <div class="text-center space-y-2">
                <div
                    class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center mx-auto border border-emerald-100 shadow-2xs">
                    <i data-lucide="server" class="w-6 h-6"></i>
                </div>
                <h3 class="text-xl font-extrabold text-slate-900 tracking-tight font-sans">
                    Garda Autentikasi Sistem
                </h3>
                <p class="text-slate-400 text-xs max-w-xs mx-auto leading-normal">
                    Silakan masukkan kredensial administrator resmi untuk membuka gerbang hak akses konfigurasi SPPK.
                </p>
            </div>

            @if (session('error'))
                <div
                    class="bg-red-50 border border-red-200 text-red-700 p-3.5 rounded-xl text-xs font-medium flex items-start gap-2.5">
                    <i data-lucide="alert-triangle" class="w-4 h-4 shrink-0 mt-0.5 text-red-600"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <form action="{{ route('admin.login') }}" method="POST" class="space-y-4">
                @csrf

                <div class="space-y-1.5">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Username
                        Korporat</label>
                    <div class="relative">
                        <span class="absolute left-3 top-3 text-slate-400"><i data-lucide="user" class="w-4 h-4"></i></span>
                        <input name="username" type="text" value="{{ old('username', 'admin') }}"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2.5 pl-10 pr-3 text-sm font-semibold text-slate-800 focus:outline-none focus:ring-1 focus:ring-emerald-500/40"
                            placeholder="contoh: admin" required />
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Kata Sandi
                        Rahasia</label>
                    <div class="relative">
                        <span class="absolute left-3 top-3 text-slate-400"><i data-lucide="lock" class="w-4 h-4"></i></span>
                        <input name="password" type="password" value="admin123"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2.5 pl-10 pr-3 text-sm text-slate-800 focus:outline-none focus:ring-1 focus:ring-emerald-500/40"
                            placeholder="••••••••" required />
                    </div>
                </div>

                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 text-xs text-slate-400 cursor-pointer select-none font-medium">
                        <input type="checkbox"
                            class="rounded text-emerald-600 border-slate-300 focus:ring-emerald-500/30 w-4 h-4 cursor-pointer"
                            checked />
                        Ingat saya (Remember me)
                    </label>
                </div>

                <div class="flex gap-3 justify-end pt-4 border-t border-slate-100">
                    <a href="{{ route('smart.rekomendasi') }}"
                        class="bg-white hover:bg-slate-50 text-slate-600 border border-slate-200 font-bold text-xs rounded-lg px-4 py-2.5 transition-all flex items-center justify-center">
                        Batalkan
                    </a>
                    <button type="submit" id="btn-submit-login-admin"
                        class="bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs rounded-lg px-5 py-2.5 flex items-center gap-1.5 transition-all shadow-sm shadow-emerald-700/10 cursor-pointer">
                        <i data-lucide="log-in" class="w-4 h-4"></i>
                        <span>Masuk Aplikasi</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
        lucide.createIcons();
    </script>
@endsection
