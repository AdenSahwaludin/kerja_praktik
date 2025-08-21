<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = 'pelanggan';
    protected $primaryKey = 'id_pelanggan';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_pelanggan',
        'nama',
        'email',
        'telepon',
        'kota',
        'alamat',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->id_pelanggan)) {
                $last = DB::table('pelanggan')
                    ->select(DB::raw('MAX(CAST(SUBSTRING(id_pelanggan, 2) AS UNSIGNED)) as max'))
                    ->first()->max ?? 0;
                $model->id_pelanggan = 'P' . str_pad($last + 1, 6, '0', STR_PAD_LEFT);
            }
        });
    }

    /**
     * Relationships
     */
    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'id_pelanggan', 'id_pelanggan');
    }
}
