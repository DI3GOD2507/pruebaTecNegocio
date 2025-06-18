<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'Customers';
    protected $primaryKey = 'Id';
    public $timestamps = true;

    protected $fillable = [
        'PersonId',
        'Status'
    ];

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'PersonId', 'Id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'CustomerId', 'Id');
    }
}
