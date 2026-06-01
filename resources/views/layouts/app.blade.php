<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SPPK SMP Pasirian')</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="bg-slate-50/50 text-slate-800 font-sans antialiased min-h-screen flex flex-col">

    <header class="bg-slate-900 border-b border-slate-800 text-slate-300 sticky top-0 z-40 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                <div class="flex items-center gap-3">
                    <div
                        class="w-9 h-9 rounded-xl bg-emerald-600/10 border border-emerald-500/20 flex items-center justify-center text-emerald-500">
                        <i data-lucide="school" class="w-5 h-5"></i>
                    </div>
                    <span class="text-white font-black text-sm tracking-tight uppercase font-sans">
                        SPPK SMP Pasirian
                    </span>
                </div>

                <nav class="hidden md:flex items-center gap-1 text-[11px] font-bold uppercase tracking-wider">

                    <a href="{{ route('smart.rekomendasi') }}"
                        class="px-3.5 py-2 rounded-lg flex items-center gap-2 transition-all duration-150 {{ request()->routeIs('smart.rekomendasi') ? 'bg-slate-800 text-emerald-400 font-extrabold border border-slate-700/50' : 'hover:text-white hover:bg-slate-800/40' }}">
                        <i data-lucide="layers" class="w-4 h-4"></i>
                        <span>Pencarian Sekolah</span>
                    </a>

                    @if (session()->has('admin_authenticated'))
                        <span class="text-slate-800 px-2 font-light">|</span>

                        <a href="{{ route('admin.sekolah.index') }}"
                            class="px-3.5 py-2 rounded-lg flex items-center gap-2 transition-all duration-150 {{ request()->routeIs('admin.sekolah.index') ? 'bg-slate-800 text-emerald-400 font-extrabold border border-slate-700/50' : 'hover:text-white hover:bg-slate-800/40' }}">
                            <i data-lucide="database" class="w-4 h-4"></i>
                            <span>Kelola Sekolah</span>
                        </a>

                        <a href="{{ route('admin.audit.ahp') }}"
                            class="px-3.5 py-2 rounded-lg flex items-center gap-2 transition-all duration-150 {{ request()->routeIs('admin.audit.ahp') ? 'bg-slate-800 text-emerald-400 font-extrabold border border-slate-700/50' : 'hover:text-white hover:bg-slate-800/40' }}">
                            <i data-lucide="sliders" class="w-4 h-4"></i>
                            <span>AHP Audit</span>
                        </a>

                        <a href="{{ route('admin.audit.smart') }}"
                            class="px-3.5 py-2 rounded-lg flex items-center gap-2 transition-all duration-150 {{ request()->routeIs('admin.audit.smart') ? 'bg-slate-800 text-emerald-400 font-extrabold border border-slate-700/50' : 'hover:text-white hover:bg-slate-800/40' }}">
                            <i data-lucide="file-text" class="w-4 h-4"></i>
                            <span>SMART Audit</span>
                        </a>
                    @endif
                </nav>

                <div class="flex items-center gap-4">
                    @if (session()->has('admin_authenticated'))
                        <div class="flex items-center gap-3">
                            <span
                                class="text-xs font-semibold text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2.5 py-1 rounded-md font-mono">
                                👤 {{ session('admin_username') }}
                            </span>
                            <form action="{{ route('admin.logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit"
                                    class="bg-red-600 hover:bg-red-700 text-white text-xs font-bold py-2 px-3.5 rounded-lg flex items-center gap-1.5 transition-all shadow-xs border border-red-500/35 cursor-pointer">
                                    <i data-lucide="log-out" class="w-4 h-4"></i>
                                    <span>Keluar Sesi</span>
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('admin.login') }}" id="btn-navbar-login-trigger"
                            class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold py-2 px-3.5 rounded-lg flex items-center gap-1.5 transition-all shadow-xs border border-emerald-500/30">
                            <i data-lucide="log-in" class="w-4 h-4"></i>
                            <span>Login Admin</span>
                        </a>
                    @endif
                </div>

            </div>
        </div>
    </header>

    <main class="flex-grow py-8 bg-slate-50/30">
        @yield('content')
    </main>

    <footer class="bg-slate-900 border-t border-slate-800 py-6 mt-auto text-slate-400 text-xs">
        <div
            class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div>
                &copy; 2026 SPPK SMP Pasirian Lumajang. Built using <strong class="font-semibold text-slate-300">AHP +
                    SMART Methodology</strong>.
            </div>
            <div class="flex items-center gap-4 text-xxs font-bold uppercase tracking-wider text-slate-500">
                <span class="text-emerald-500 flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    Monolith Engine Active
                </span>
            </div>
        </div>
    </footer>

    <script>
        lucide.createIcons();
    </script>
</body>

</html>
