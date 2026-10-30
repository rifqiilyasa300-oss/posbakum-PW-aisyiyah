<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-white leading-tight uppercase tracking-tighter flex items-center gap-3">
            <span class="w-2 h-8 bg-blue-400 rounded-full"></span>
            Edit Data Personel
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-8">
                <a href="{{ route('admin.staff.index') }}" class="group text-slate-400 hover:text-slate-800 text-[10px] font-black uppercase tracking-[0.2em] flex items-center transition">
                    <svg class="w-4 h-4 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Daftar
                </a>
            </div>

            <div class="bg-white shadow-[0_20px_50px_rgba(0,0,0,0.05)] rounded-[2.5rem] border border-slate-100 overflow-hidden">
                <div class="p-8 md:p-12">
                    <div class="mb-10">
                        <h3 class="text-3xl font-black text-slate-900 uppercase tracking-tighter mb-2">Update Profil Staff</h3>
                        <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">Ubah informasi akun atau reset password personel di bawah ini.</p>
                    </div>

                    <form action="{{ route('admin.staff.update', $staff->id) }}" method="POST" class="space-y-8">
                        @csrf
                        @method('PATCH')

                        <div class="grid grid-cols-1 gap-6">
                            {{-- Nama & Email --}}
                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2.5">Nama Lengkap</label>
                                    <input type="text" name="name" value="{{ old('name', $staff->name) }}" class="w-full bg-slate-50 border-0 rounded-2xl px-5 py-4 focus:ring-2 focus:ring-blue-500 font-bold text-slate-700 shadow-inner @error('name') border-red-500 @enderror" required>
                                    @error('name') <span class="text-red-500 text-[10px] font-bold mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2.5">Email Resmi</label>
                                    <input type="email" name="email" value="{{ old('email', $staff->email) }}" class="w-full bg-slate-50 border-0 rounded-2xl px-5 py-4 focus:ring-2 focus:ring-blue-500 font-bold text-slate-700 shadow-inner @error('email') border-red-500 @enderror" required>
                                    @error('email') <span class="text-red-500 text-[10px] font-bold mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            {{-- Role, Keahlian, Phone --}}
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2.5">Otoritas / Role</label>
                                    <select name="role" class="w-full bg-slate-50 border-0 rounded-2xl px-5 py-4 focus:ring-2 focus:ring-blue-500 font-bold text-slate-700 shadow-inner" required>
                                        <option value="admin" {{ old('role', $staff->role) == 'admin' ? 'selected' : '' }}>ADMINISTRATOR</option>
                                        <option value="advokat" {{ old('role', $staff->role) == 'advokat' ? 'selected' : '' }}>ADVOKAT</option>
                                    </select>
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2.5">Keahlian / Spesialisasi Advokat</label>
                                    <select name="keahlian" class="w-full bg-slate-50 border-0 rounded-2xl px-5 py-4 focus:ring-2 focus:ring-blue-500 font-bold text-slate-700 shadow-inner">
                                        <option value="Umum" {{ old('keahlian', $staff->keahlian) == 'Umum' ? 'selected' : '' }}>UMUM</option>
                                        <option value="Pidana" {{ old('keahlian', $staff->keahlian) == 'Pidana' ? 'selected' : '' }}>PIDANA</option>
                                        <option value="Perdata" {{ old('keahlian', $staff->keahlian) == 'Perdata' ? 'selected' : '' }}>PERDATA</option>
                                        <option value="TUN" {{ old('keahlian', $staff->keahlian) == 'TUN' ? 'selected' : '' }}>HUKUM TATA USAHA NEGARA</option>
                                        <option value="Ketenagakerjaan" {{ old('keahlian', $staff->keahlian) == 'Ketenagakerjaan' ? 'selected' : '' }}>KETENAGAKERJAAN</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2.5">Nomor WhatsApp</label>
                                <input type="text" name="phone" value="{{ old('phone', $staff->phone) }}" class="w-full bg-slate-50 border-0 rounded-2xl px-5 py-4 focus:ring-2 focus:ring-blue-500 font-bold text-slate-700 font-mono shadow-inner" placeholder="62812xxx">
                                @error('phone') <span class="text-red-500 text-[10px] font-bold mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            {{-- Password Section --}}
                            <div class="bg-slate-900 p-8 rounded-3xl relative overflow-hidden">
                                <div class="relative z-10">
                                    <h4 class="text-blue-400 text-[10px] font-black uppercase tracking-widest mb-6 flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                        Ganti Password (Opsional)
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <input type="password" name="password" placeholder="Password Baru" class="w-full bg-slate-800 border-0 rounded-2xl px-5 py-4 text-white placeholder-slate-600 focus:ring-2 focus:ring-blue-500 font-bold text-sm @error('password') border-red-500 @enderror">
                                        <input type="password" name="password_confirmation" placeholder="Konfirmasi Password" class="w-full bg-slate-800 border-0 rounded-2xl px-5 py-4 text-white placeholder-slate-600 focus:ring-2 focus:ring-blue-500 font-bold text-sm">
                                    </div>
                                    @error('password') <span class="text-red-400 text-[10px] font-bold mt-2 block">{{ $message }}</span> @enderror
                                    <p class="text-[9px] text-slate-500 mt-4 font-medium italic">* Kosongkan jika tidak ingin mengganti password.</p>
                                </div>
                            </div>

                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-5 rounded-2xl shadow-xl transition-all active:scale-[0.98] uppercase tracking-[0.2em] text-xs">
                                Simpan Perubahan Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
