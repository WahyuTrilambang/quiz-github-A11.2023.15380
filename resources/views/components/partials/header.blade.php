<header class="bg-base-100 border-b border-base-300 h-16 flex items-center px-6 gap-4 sticky top-0 z-30 shadow-sm">

    {{-- Mobile Hamburger --}}
    <button onclick="toggleSidebar()" class="btn btn-square btn-ghost lg:hidden">
        <i data-lucide="menu" class="w-5 h-5"></i>
    </button>

    {{-- Breadcrumb --}}
    <div class="flex-1">
        <div class="flex items-center gap-2 text-sm">
            <span class="text-base-content/50">Poliklinik</span>
            <i data-lucide="chevron-right" class="w-4 h-4 text-base-content/30"></i>
            <span class="font-semibold text-base-content">
                {{ $title ?? 'Dashboard' }}
            </span>
        </div>
    </div>

    {{-- Fullscreen --}}
    <button onclick="toggleFullscreen()" class="btn btn-square btn-ghost">
        <i id="fsIcon" class="fas fa-expand w-5 h-5"></i>
    </button>

    {{-- Dropdown Notifikasi --}}
    <div class="dropdown dropdown-end">
        <label tabindex="0" role="button" class="btn btn-ghost btn-circle relative">
            <div class="indicator">
                <i class="fas fa-bell text-xl text-slate-600"></i>
                @if(auth()->check() && auth()->user()->unreadNotifications->count() > 0)
                    <span class="badge badge-sm badge-error indicator-item text-white font-bold animate-pulse">
                        {{ auth()->user()->unreadNotifications->count() }}
                    </span>
                @endif
            </div>
        </label>

        <div tabindex="0" class="mt-3 z-[1] card card-compact dropdown-content w-80 bg-base-100 shadow-xl border border-slate-200">
            <div class="card-body p-4">
                <div class="flex items-center justify-between border-b pb-2">
                    <h3 class="font-bold text-sm text-slate-800">Notifikasi</h3>
                    @if(auth()->check() && auth()->user()->unreadNotifications->count() > 0)
                        <form action="{{ route('notifications.markAsRead') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-xs text-indigo-600 hover:underline font-semibold">
                                Tandai Semua Dibaca
                            </button>
                        </form>
                    @endif
                </div>

                <div class="max-h-64 overflow-y-auto space-y-2 py-2">
                    @if(auth()->check())
                        @forelse(auth()->user()->notifications->take(10) as $notification)
                            <form action="{{ route('notifications.readSingle', $notification->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full text-left p-2.5 rounded-xl transition flex gap-3 items-start {{ $notification->unread() ? 'bg-indigo-50/70 border-l-4 border-indigo-500' : 'hover:bg-slate-50 opacity-75' }}">
                                    <div class="mt-0.5">
                                        <i class="{{ $notification->data['icon'] ?? 'fas fa-info-circle text-indigo-500' }}"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-xs font-bold text-slate-800">{{ $notification->data['judul'] ?? 'Notifikasi' }}</p>
                                        <p class="text-xs text-slate-600 mt-0.5 leading-snug">{{ $notification->data['pesan'] ?? '' }}</p>
                                        <span class="text-[10px] text-slate-400 mt-1 block">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                </button>
                            </form>
                        @empty
                            <div class="text-center py-6 text-slate-400 text-xs">
                                <i class="fas fa-bell-slash text-2xl mb-1 block"></i>
                                Belum ada notifikasi
                            </div>
                        @endforelse
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- User Info Dropdown --}}
    <div class="dropdown dropdown-end">
        <div tabindex="0" role="button" class="flex items-center gap-3 p-1.5 rounded-xl hover:bg-slate-100 transition cursor-pointer">
            <div class="text-right hidden sm:block">
                <div class="text-sm font-semibold leading-tight text-slate-800">
                    {{ auth()->user()->nama ?? auth()->user()->name ?? 'Pengguna' }}
                </div>
                <div class="text-xs text-slate-500 leading-tight capitalize">
                    {{ auth()->user()->role ?? 'User' }}
                </div>
            </div>

            <div class="avatar">
                <div class="w-10 h-10 rounded-full bg-primary text-primary-content flex items-center justify-center font-bold shadow-sm">
                    <span>
                        {{ strtoupper(substr(auth()->user()->nama ?? auth()->user()->name ?? 'U', 0, 1)) }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Menu Dropdown --}}
        <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow-lg bg-base-100 rounded-2xl w-52 mt-2 border border-slate-100">
            <li class="menu-title text-xs text-slate-400 uppercase px-3 py-1 font-bold">Akun Saya</li>
            
            <li>
                <a href="{{ route('profile.index') }}" class="flex items-center gap-2 py-2 text-slate-700 hover:bg-slate-50 rounded-xl">
                    <i class="fas fa-user-gear text-slate-400 w-4"></i>
                    Pengaturan Profile
                </a>
            </li>

            <div class="divider my-1"></div>

            <li>
                <form method="POST" action="{{ route('logout') }}" class="p-0">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2 py-2 text-rose-600 hover:bg-rose-50 rounded-xl font-semibold">
                        <i class="fas fa-right-from-bracket text-rose-500 w-4"></i>
                        Keluar / Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>
</header>

<script>
    function toggleFullscreen() {
        const icon = document.getElementById('fsIcon');

        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen();
            icon.classList.remove('fa-expand');
            icon.classList.add('fa-compress');
        } else {
            document.exitFullscreen();
            icon.classList.remove('fa-compress');
            icon.classList.add('fa-expand');
        }
    }
</script>