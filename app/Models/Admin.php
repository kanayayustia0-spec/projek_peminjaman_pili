<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $table = 'admins'; // Menyesuaikan nama tabel di database

    protected $guarded = ['id'];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}