<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Personel extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'personel';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'adi_soyadi',
        'unvan_id',
        'birim_id',
        'notlar',
    ];

    /**
     * Personelin ünvanı.
     */
    public function unvan(): BelongsTo
    {
        return $this->belongsTo(Unvan::class, 'unvan_id');
    }

    /**
     * Personelin birimi.
     */
    public function birim(): BelongsTo
    {
        return $this->belongsTo(Birim::class, 'birim_id');
    }
}
