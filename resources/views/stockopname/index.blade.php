@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Riwayat Stock Opname</h1>
            <p class="text-gray-500 text-sm mt-1">Catatan hasil audit fisik berkala untuk seluruh aset.</p>
        </div>
        <a href="{{ route('admin.stock-opname.create') }}" class="bg-pili-primary text-white px-5 py-2.5 rounded-xl font-bold hover:bg-pili-dark shadow-md transition flex items-center gap-2">
            <span>+</span> Lakukan Audit Baru
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 font-medium">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow-soft rounded-2xl overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-200">
                        <th class="p-4 font-bold">Tanggal & Lokasi</th>
                        <th class="p-4 font-bold">Tim Pemeriksa</th>
                        <th class="p-4 font-bold text-center">Tercatat di Sistem</th>
                        <th class="p-4 font-bold text-center">Ditemukan Fisik</th>
                        <th class="p-4 font-bold text-center">Selisih/Hilang</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($opnames as $row)
                        <tr class="hover:bg-gray-50/80 transition">
                            <td class="p-4">
                                <div class="font-bold text-gray-900">{{ $row->tanggal->format('d M Y') }}</div>
                                <div class="text-sm text-gray-500">{{ $row->lokasi }}</div>
                            </td>
                            <td class="p-4 text-sm font-semibold text-gray-700">{{ $row->tim_pemeriksa }}</td>
                            <td class="p-4 text-center font-bold text-blue-600 bg-blue-50/30">
                                {{ $row->jumlah_terdaftar }} item
                            </td>
                            <td class="p-4 text-center font-bold text-green-600 bg-green-50/30">
                                {{ $row->jumlah_ditemukan }} item
                            </td>
                            <td class="p-4 text-center font-bold {{ $row->jumlah_hilang > 0 ? 'text-red-600 bg-red-50/50' : 'text-gray-400' }}">
                                {{ $row->jumlah_hilang }} item
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-12 text-center text-gray-500 font-medium">Belum ada riwayat stock opname yang tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection