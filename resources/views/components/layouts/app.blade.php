<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name', 'Firing Scorer') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-gray-50 text-gray-900">
    {{-- Top Bar (Mobile + Desktop) --}}
    <header class="sticky top-0 z-40 border-b bg-white/90 backdrop-blur">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between gap-3">
                {{-- Brand --}}
                <div class="flex items-center gap-3">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl border bg-white">
                            🎯
                        </span>
                        <div class="leading-tight">
                            <div class="text-sm font-semibold">Firing Scorer</div>
                            <div class="text-xs text-gray-500">AIoT training dashboard</div>
                        </div>
                    </a>
                </div>

                {{-- Desktop Nav --}}
                <nav class="hidden md:flex items-center gap-1">
                    <a href="{{ route('dashboard') }}"
                       class="rounded-lg px-3 py-2 text-sm font-medium
                              {{ request()->routeIs('dashboard') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                        Dashboard
                    </a>

                    <a href="{{ route('dashboard') }}"
                       class="rounded-lg px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100">
                        Sessions
                    </a>

                    <a href="#"
                       class="rounded-lg px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100">
                        Reports
                    </a>
                </nav>

                {{-- Right side --}}
                <div class="flex items-center gap-2">
                    @auth
                        <div class="hidden sm:flex items-center gap-2">
                            <span class="text-sm text-gray-600">Hi,</span>
                            <span class="text-sm font-medium">{{ auth()->user()->name ?? 'User' }}</span>
                        </div>

                        {{-- Logout (optional if you have auth scaffolding) --}}
                        {{-- <form method="POST" action="{{ route('logout') }}" class="hidden sm:block">
                            @csrf
                            <button class="rounded-lg border px-3 py-2 text-sm hover:bg-gray-100">
                                Logout
                            </button>
                        </form> --}}
                    @endauth

                    {{-- Mobile nav shortcut --}}
                    <a href="{{ route('dashboard') }}"
                       class="md:hidden rounded-lg border px-3 py-2 text-sm hover:bg-gray-100">
                        Menu
                    </a>
                </div>
            </div>
        </div>
    </header>

    {{-- Page Shell --}}
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex gap-6 py-6">
            {{-- Sidebar (Desktop) --}}
            <aside class="hidden lg:block w-64 shrink-0">
                <div class="rounded-2xl border bg-white p-4">
                    <div class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Navigation
                    </div>

                    <div class="mt-3 space-y-1">
                        <a href="{{ route('dashboard') }}"
                           class="flex items-center gap-2 rounded-xl px-3 py-2 text-sm font-medium
                                  {{ request()->routeIs('dashboard') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                            <span>📊</span> <span>Dashboard</span>
                        </a>

                        <a href="{{ route('dashboard') }}"
                           class="flex items-center gap-2 rounded-xl px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100">
                            <span>🗂️</span> <span>Sessions</span>
                        </a>

                        <a href="#"
                           class="flex items-center gap-2 rounded-xl px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100">
                            <span>🧾</span> <span>Reports</span>
                        </a>
                    </div>

                    <div class="mt-6 rounded-xl bg-gray-50 p-3 text-sm text-gray-700">
                        <div class="font-medium">Tip</div>
                        <div class="mt-1 text-xs text-gray-600">
                            Start with a session to view shot clustering, IoT trends, and recommendations.
                        </div>
                    </div>
                </div>
            </aside>

            {{-- Main Content --}}
            <main class="min-w-0 flex-1">
                {{-- Flash / Alerts placeholder --}}
                @if(session('status'))
                    <div class="mb-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                        {{ session('status') }}
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>

    <footer class="border-t bg-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6 text-xs text-gray-500">
            © {{ date('Y') }} Firing Scorer • Built with Laravel + Livewire
        </div>
    </footer>

    @livewireScripts
</body>
</html>
