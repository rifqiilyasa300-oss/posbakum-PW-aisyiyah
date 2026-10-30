<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-black text-white tracking-tight uppercase">{{ __('Laporan Pemasukan') }}</h2>
                <p class="text-sm text-green-100 mt-1 font-medium">Rekapitulasi keuangan dan performa pendapatan.</p>
            </div>

            {{-- QUICK STATS --}}
            <div class="flex gap-3">
                <div class="bg-white/10 backdrop-blur-md border border-white/20 px-5 py-3 rounded-2xl shadow-lg text-right">
                    <p class="text-[10px] uppercase tracking-[0.3em] text-white/70 font-black">Total Pendapatan</p>
                    <h3 class="text-2xl font-black text-white leading-none mt-1">
                        Rp {{ number_format($totalPemasukan, 0, ',', '.') }}
                    </h3>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-slate-100 via-gray-100 to-green-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- FILTER SECTION --}}
            <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 mb-8 flex flex-col md:flex-row gap-4 items-center justify-between">
                <form action="{{ route('admin.incomes.index') }}" method="GET" class="flex flex-wrap gap-3 w-full">
                    <div class="flex-1 min-w-[200px]">
                        <label class="text-[10px] font-black uppercase text-gray-400">Dari Tanggal</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full mt-1 rounded-xl border-gray-200 text-sm">
                    </div>
                    <div class="flex-1 min-w-[200px]">
                        <label class="text-[10px] font-black uppercase text-gray-400">Sampai Tanggal</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full mt-1 rounded-xl border-gray-200 text-sm">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="bg-green-700 text-white px-6 py-2.5 rounded-xl font-black text-xs uppercase hover:bg-green-800 transition">Filter Data</button>
                    </div>
                </form>
            </div>

            {{-- TABLE SECTION --}}
            <div class="bg-white/90 backdrop-blur-xl border border-white shadow-[0_10px_40px_rgba(0,0,0,0.06)] rounded-[2rem] overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-black text-gray-800 uppercase">Detail Transaksi</h3>
                    <button onclick="window.print()" class="text-[10px] font-black text-green-700 hover:text-green-900 uppercase tracking-widest">
                        Cetak Laporan (PDF)
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-8 py-5 text-left text-[11px] font-black uppercase text-gray-500">Tanggal</th>
                                <th class="px-8 py-5 text-left text-[11px] font-black uppercase text-gray-500">Keterangan</th>
                                <th class="px-8 py-5 text-left text-[11px] font-black uppercase text-gray-500">Kategori</th>
                                <th class="px-8 py-5 text-right text-[11px] font-black uppercase text-gray-500">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($incomes as $income)
                            <tr class="hover:bg-green-50/40 transition">
                                <td class="px-8 py-5 text-sm text-gray-600">{{ $income->created_at->format('d M Y') }}</td>
                                <td class="px-8 py-5 font-bold text-gray-900">{{ $income->description }}</td>
                                <td class="px-8 py-5">
                                    <span class="px-3 py-1 rounded-lg bg-gray-100 text-[10px] font-black text-gray-600 uppercase">{{ $income->category ?? 'Umum' }}</span>
                                </td>
                                <td class="px-8 py-5 text-right font-black text-green-700">
                                    + Rp {{ number_format($income->amount, 0, ',', '.') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-20 text-center text-gray-400">Tidak ada data transaksi ditemukan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="3" class="px-8 py-4 text-right font-black uppercase text-xs">Total Keseluruhan</td>
                                <td class="px-8 py-4 text-right font-black text-green-800 text-lg">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
