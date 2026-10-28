<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StockOpname;
use App\Models\Barang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class StockOpnameController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(function ($request, $next) {
                $admin = Auth::guard('admin')->user();
                if ($admin && $admin->role !== 'pengelola') {
                    return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak: Fitur Stock Opname hanya dapat diakses oleh Admin Pengelola.');
                }
                return $next($request);
            })
        ];
    }
    public function index()
    {
        $opnames = StockOpname::orderBy('tanggal', 'desc')->get();
        $barangs = Barang::all()->keyBy('id');
        return view('admin.stockopname.index', compact('opnames', 'barangs'));
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

    public function update(Request $request, $id)
    {
        $opname = StockOpname::findOrFail($id);
        $request->validate([
            'rekomendasi' => 'nullable|string',
            'tim_pemeriksa' => 'nullable|string',
        ]);

        $opname->update($request->only(['rekomendasi', 'tim_pemeriksa']));

        return redirect()->route('admin.stock-opname.index')->with('success', 'Data Stock Opname berhasil diperbarui.');
    }
}