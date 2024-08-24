<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'travel_id',
        'ticket',
        'date',
        'price',
    ];

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function travels(): BelongsTo
    {
        return $this->belongsTo(Travel::class);
    }
}
