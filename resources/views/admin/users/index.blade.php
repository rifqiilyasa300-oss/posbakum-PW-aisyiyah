<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-xl text-white leading-tight uppercase tracking-tighter">
            Manajemen Akun Staff
        </h2>
    </x-slot>

    <div class="py-10 pt-24 bg-slate-50 min-h-screen px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">

            {{-- Judul & Tombol: Rapi di HP --}}
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-6">
                <h3 class="text-xl font-black text-slate-800 uppercase tracking-tighter border-l-4 border-green-600 pl-4">
                    Daftar Personel
                </h3>
                <a href="{{ route('admin.staff.create') }}" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl font-bold text-xs uppercase tracking-widest transition shadow-lg active:scale-95 text-center">
                    + Tambah Staff Baru
                </a>
            </div>

            {{-- Pesan Notifikasi --}}
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 font-bold text-sm rounded-r-xl shadow-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 font-bold text-sm rounded-r-xl shadow-sm">
                    {{ session('error') }}
                </div>
            @endif

            {{-- ✅ BUNGKUS GESER KHUSUS HP: Tabel tidak akan terpotong lagi --}}
            <div class="overflow-x-auto w-full">
                <div class="min-w-[700px] bg-white shadow-[0_20px_50px_rgba(0,0,0,0.05)] rounded-[2.5rem] border border-slate-100 overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Nama Personel</th>
                                <th class="px-4 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Email Resmi</th>
                                <th class="px-4 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Otoritas</th>
                                <th class="px-4 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Kontak</th>
                                <th class="px-4 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Keahlian</th>
                                <th class="px-4 py-5 text-center text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Kendali</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($staffs as $staff)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-5">
                                    <div class="text-sm font-black text-slate-900">{{ $staff->name }}</div>
                                </td>
                                <td class="px-4 py-5 text-sm font-bold text-slate-500">
                                    {{ $staff->email }}
                                </td>
                                <td class="px-4 py-5">
                                    <span class="px-3 py-1.5 text-[9px] font-black rounded-lg uppercase tracking-wider {{ $staff->role == 'admin' ? 'bg-purple-50 text-purple-600' : 'bg-orange-50 text-orange-600' }}">
                                        {{ $staff->role }}
                                    </span>
                                </td>
                                <td class="px-4 py-5 text-sm font-bold text-slate-600 font-mono">
                                    {{ $staff->phone ?? '-' }}
                                </td>
                                <td class="px-4 py-5">
                                    <span class="text-[10px] font-black text-slate-700 uppercase tracking-wider">
                                        {{ $staff->role == 'advokat' ? ($staff->keahlian ?? 'UMUM') : '-' }}
                                    </span>
                                </td>
                                <td class="px-4 py-5 text-center">
                                    <div class="flex items-center justify-center gap-4">
                                        <a href="{{ route('admin.staff.edit', $staff->id) }}" class="text-blue-600 hover:text-blue-800 font-black text-[10px] uppercase tracking-widest transition">
                                            Edit
                                        </a>
                                        @if($staff->id !== Auth::id())
                                            <form action="{{ route('admin.staff.destroy', $staff->id) }}" method="POST" onsubmit="return confirm('PERINGATAN: Menghapus akun ini akan mencabut semua akses. Lanjutkan?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 font-black text-[10px] uppercase tracking-widest transition">
                                                    Hapus
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-slate-300 italic text-[10px] font-bold uppercase tracking-widest cursor-not-allowed">
                                                ( Anda )
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-4 py-12 text-center text-slate-400 font-bold uppercase tracking-widest text-xs">
                                    Belum ada data personel yang terdaftar.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <p class="mt-8 text-center text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">
                Pusat Data Personel Posbakum PW Aisyiyah
            </p>
        </div>
    </div>
</x-app-layout>
