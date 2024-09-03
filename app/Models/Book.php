<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'title',
        'author',
        'description',
        'published_at',
        'available'
    ];
//defining the relation between borrowing table and books
    public function borrowingRecords()
    {
        return $this->hasMany(BorrowRecord::class);
    }
    public function category()
{
    return $this->belongsTo(Category::class);
}
    public function Users(){
        return $this->hasMany(User::class);
    }
}
