<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    //
    use SoftDeletes;
    protected $fillable = [
        'name',
        'about',
        'thumbnail',
        'price',
        'category_id',
        'is_popular'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_popular' => 'boolean',
        ];
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function merchants(){
        return $this->belongsToMany(Merchant::class, 'merchant_products')
        ->withPivot('stock')
        ->withTimestamps();
    }

    public function warehouses(){
        return $this->belongsToMany(Warehouse::class,'warehouse_products')
        ->withPivot('stock')
        ->withTimestamps();
    }

    public function transaction(){
        return $this->hasMany(TransactionProduct::class);
    }
}
