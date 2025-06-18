<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Person extends Model
{
    use HasFactory;

    protected $table = 'Persons';
    protected $primaryKey = 'Id';
    public $timestamps = true;

    protected $fillable = [
        'FirstName',
        'LastName',
        'DocumentNumber',
        'Email',
        'Phone'
    ];

    public function customer(): HasOne
    {
        return $this->hasOne(Customer::class, 'PersonId', 'Id');
    }
}
