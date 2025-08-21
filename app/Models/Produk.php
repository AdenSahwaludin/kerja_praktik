<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';
    protected $primaryKey = 'id_produk';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_produk',
        'id_kategori',
        'nama',
        'gambar',
        'nomor_bpom',
        'harga',
        'biaya_produk',
        'stok',
        'batas_stok',
    ];


    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }
    /**
     * Auto-generate EAN-13 id_produk
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->id_produk)) {
                // get max numeric prefix (first 12 digits)
                $last = DB::table('produk')
                    ->select(DB::raw('MAX(CAST(SUBSTRING(id_produk, 1, 12) AS UNSIGNED)) as max'))
                    ->first()->max ?? 0;
                $prefix = str_pad($last + 1, 12, '0', STR_PAD_LEFT);
                $model->id_produk = $prefix . static::calculateCheckDigit($prefix);
            }
        });
    }

    /**
     * Calculate EAN-13 check digit
     */
    protected static function calculateCheckDigit(string $digits): string
    {
        $sum = 0;
        $len = strlen($digits);
        for ($i = 0; $i < $len; $i++) {
            $num = (int)$digits[$i];
            // from rightmost, odd positions + even positions*3
            $pos = $len - $i;
            $sum += ($pos % 2 === 0) ? $num * 3 : $num;
        }
        return (string)((10 - ($sum % 10)) % 10);
    }
}
