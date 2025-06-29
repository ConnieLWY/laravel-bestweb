<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use SoftDeletes;
    use HasFactory;
    protected $fillable = [
        'name',
        'category_id',
        'description',
        'price',
        'stock',
        'enabled',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
