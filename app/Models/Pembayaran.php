<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';
    protected $primaryKey = 'id_pembayaran';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_pembayaran',
        'id_transaksi',
        'metode',
        'jumlah',
        'keterangan',
        'tanggal',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah' => 'decimal:2',
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'id_transaksi', 'id_transaksi');
    }

    /**
     * Auto-generate pembayaran ID: PAY-{YYYYMMDD}-{SEQ}
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->id_pembayaran)) {
                $date = $model->tanggal instanceof Carbon
                    ? $model->tanggal
                    : Carbon::parse($model->tanggal);
                $prefix = 'PAY-' . $date->format('Ymd');
                $lastNum = DB::table('pembayaran')
                    ->whereDate('tanggal', $date->toDateString())
                    ->select(DB::raw("MAX(CAST(SUBSTRING_INDEX(id_pembayaran, '-', -1) AS UNSIGNED)) as max"))
                    ->first()->max ?? 0;
                $seq = str_pad($lastNum + 1, 7, '0', STR_PAD_LEFT);
                $model->id_pembayaran = "{$prefix}-{$seq}";
            }
        });
    }
}
