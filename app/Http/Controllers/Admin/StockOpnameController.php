<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StockOpname;
use App\Models\Barang;
use Illuminate\Http\Request;

class StockOpnameController extends Controller
{
    public function index()
    {
        $opnames = StockOpname::orderBy('tanggal', 'desc')->get();
        return view('admin.stockopname.index', compact('opnames'));
    }

    public function create()
    {
        // Ambil data snapshot barang saat ini untuk diaudit
        $barangs = Barang::all();
        return view('admin.stockopname.create', compact('barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'lokasi' => 'required|string',
            'tim_pemeriksa' => 'required|string',
            'rekomendasi' => 'nullable|string',
            'audit' => 'required|array'
        ]);

        $detailHasil = [];
        $totalTerdaftar = 0; $totalDitemukan = 0; $totalRusak = 0; $totalHilang = 0;

        foreach ($request->audit as $barangId => $data) {
            $detailHasil[] = [
                'barang_id' => $barangId,
                'sistem' => $data['sistem'],
                'fisik' => $data['fisik'],
                'rusak' => $data['rusak'],
                'hilang' => $data['hilang'],
            ];
            
            $totalTerdaftar += $data['sistem'];
            $totalDitemukan += $data['fisik'];
            $totalRusak += $data['rusak'];
            $totalHilang += $data['hilang'];
        }

        StockOpname::create([
            'tanggal' => $request->tanggal,
            'lokasi' => $request->lokasi,
            'tim_pemeriksa' => $request->tim_pemeriksa,
            'rekomendasi' => $request->rekomendasi,
            'detail_hasil' => $detailHasil,
            'jumlah_terdaftar' => $totalTerdaftar,
            'jumlah_ditemukan' => $totalDitemukan,
            'jumlah_rusak' => $totalRusak,
            'jumlah_hilang' => $totalHilang,
        ]);

        return redirect()->route('admin.stock-opname.index')->with('success', 'Data Stock Opname berhasil disimpan.');
    }
}