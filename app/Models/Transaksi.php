<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';
    protected $primaryKey = 'id_transaksi';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_transaksi',
        'id_pengguna',
        'id_pelanggan',
        'tanggal',
        'total',
        'status',
        'catatan',
        'diskon',
        'pajak',
        'biaya_pengiriman',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'total' => 'decimal:2',
        'diskon' => 'decimal:2',
        'pajak' => 'decimal:2',
    ];
    public function pengguna()
    {
        return $this->belongsTo(User::class, 'id_pengguna', 'id');
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function details()
    {
        return $this->hasMany(Detail::class, 'id_transaksi', 'id_transaksi');
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'id_transaksi', 'id_transaksi');
    }

    /**
     * Auto-generate transaksi ID: INV-{YYYY}-{MM}-{SEQ}-{ID_PELANGGAN}
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->id_transaksi)) {
                $date = $model->tanggal instanceof Carbon
                    ? $model->tanggal
                    : Carbon::parse($model->tanggal);
                $year = $date->format('Y');
                $month = $date->format('m');
                $prefix = "INV-{$year}-{$month}";
                $lastSeq = DB::table('transaksi')
                    ->whereYear('tanggal', $year)
                    ->whereMonth('tanggal', $month)
                    ->select(DB::raw("MAX(CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(id_transaksi, '-', 4), '-', -1) AS UNSIGNED)) as max"))
                    ->first()->max ?? 0;
                $seq = str_pad($lastSeq + 1, 7, '0', STR_PAD_LEFT);
                $model->id_transaksi = "{$prefix}-{$seq}-{$model->id_pelanggan}";
            }
        });
    }
}
