<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'name',
        'surname',
        'email',
        'street',
        'num',
        'city',
        'zip',
        'shipping_type_id',
        'payment_type',
        'price',
        'note',
    ];


    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}