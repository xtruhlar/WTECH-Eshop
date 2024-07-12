<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manufacturer extends Model
{
    protected $table = "manufacturers";
    protected $fillable = ['name', 'slug', 'image'];
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';


    public function products()
    {
        return $this->hasMany(Product::class);
    }
    use HasFactory;


    protected $casts = [
        'id' => 'string',
    ];
}
