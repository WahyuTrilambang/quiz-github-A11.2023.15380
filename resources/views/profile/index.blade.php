<x-layouts.app title="Pengaturan Profil & Keamanan">

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-800">Pengaturan Profil</h2>
        <p class="text-sm text-slate-500">Kelola informasi akun dan keamanan password Anda.</p>
    </div>

    {{-- Alert Sukses --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl flex items-center gap-3">
            <i class="fas fa-check-circle text-emerald-600 text-lg"></i>
            <span class="text-sm font-semibold">{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Card Info User --}}
        <div class="card bg-base-100 shadow-md rounded-2xl border border-slate-200 p-6 h-fit">
            <div class="flex flex-col items-center text-center">
                <div class="w-20 h-20 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-3xl font-extrabold mb-3">
                    {{ strtoupper(substr($user->nama ?? $user->name ?? 'U', 0, 1)) }}
                </div>
                <h3 class="text-lg font-bold text-slate-800">{{ $user->nama ?? $user->name }}</h3>
                <span class="px-3 py-1 bg-slate-100 text-slate-600 text-xs font-bold uppercase rounded-full mt-1">
                    Role: {{ $user->role ?? 'User' }}
                </span>
            </div>

            <div class="mt-6 border-t pt-4 space-y-3 text-sm">
                <div>
                    <span class="text-xs text-slate-400 block font-semibold uppercase">Email / No HP</span>
                    <span class="text-slate-700 font-medium">{{ $user->email ?? $user->no_hp ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-xs text-slate-400 block font-semibold uppercase">Alamat / No RM</span>
                    <span class="text-slate-700 font-medium">{{ $user->alamat ?? $user->no_rm ?? '-' }}</span>
                </div>
            </div>
        </div>

        {{-- Form Ganti Password --}}
        <div class="lg:col-span-2 card bg-base-100 shadow-md rounded-2xl border border-slate-200 p-6">
            <h3 class="text-lg font-bold text-slate-800 border-b pb-3 mb-5">Ganti Password</h3>

            <form action="{{ route('profile.password.update') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                {{-- Password Lama --}}
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold text-slate-700">Password Saat Ini</span>
                    </label>
                    <input type="password" name="current_password" required
                           placeholder="Masukkan password lama"
                           class="input input-bordered w-full rounded-xl @error('current_password') input-error @enderror" />
                    @error('current_password')
                        <span class="text-rose-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Password Baru --}}
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold text-slate-700">Password Baru</span>
                    </label>
                    <input type="password" name="password" required
                           placeholder="Minimal 8 karakter"
                           class="input input-bordered w-full rounded-xl @error('password') input-error @enderror" />
                    @error('password')
                        <span class="text-rose-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Konfirmasi Password Baru --}}
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold text-slate-700">Konfirmasi Password Baru</span>
                    </label>
                    <input type="password" name="password_confirmation" required
                           placeholder="Ulangi password baru"
                           class="input input-bordered w-full rounded-xl" />
                </div>

                <div class="pt-3">
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm rounded-xl transition shadow-md shadow-indigo-200">
                        <i class="fas fa-key mr-1"></i> Simpan Password Baru
                    </button>
                </div>
            </form>
        </div>

    </div>

</x-layouts.app>