<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReminderLog extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relasi: Log ini untuk transaksi Peminjaman yang mana?
    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class);
    }

    // Relasi: Log ini memperingatkan Admin siapa?
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}