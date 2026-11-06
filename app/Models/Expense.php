<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'title',
        'category',
        'amount',
        'expense_date',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'expense_date' => 'date',
        'amount'       => 'decimal:2',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function categoryLabel(string $cat): string
    {
        return [
            'operasional' => 'Operasional',
            'bahan'       => 'Bahan & Produk',
            'gaji'        => 'Gaji & Honor',
            'perawatan'   => 'Perawatan Alat',
            'lainnya'     => 'Lainnya',
        ][$cat] ?? ucfirst($cat);
    }

    public static function categories(): array
    {
        return ['operasional', 'bahan', 'gaji', 'perawatan', 'lainnya'];
    }
}
