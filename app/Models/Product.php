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
        'description',
        'photo',
        'price',
        'category_id'
    ];
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
