<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes; // If you're using soft deletes

class Product extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "products";
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;


    public function category()
    {
        return $this->belongsTo(Category::class, 'categoryId', 'id');
    }

    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class, 'manufacturerId', 'id');
    }
    public function galleryImages()
    {
        return $this->hasMany(GalleryImage::class, 'productId', 'id');
    }

    public function carts()
    {
        return $this->belongsToMany(Cart::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class);
    }

    protected $fillable = [
        'featuredImage',
        'title',
        'shortDescription',
        'longDescription',
        'price',
        'availability'
    ];

    protected $casts = [
        'id' => 'string',
    ];

    // The model will use 'created_at' and 'updated_at' by default for timestamps
}
