<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $table = 'Orders';
    protected $primaryKey = 'Id';
    public $timestamps = true;

    protected $fillable = [
        'CustomerId',
        'OrderDate',
        'Status',
        'TotalAmount',
        'Notes'
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'CustomerId', 'Id');
    }
}
