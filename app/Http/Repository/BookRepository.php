<?php
namespace App\Http\Repository;
use app\Models\Book;
Class BookRepository{
    public function getByAuthor($author){
        return Book::where('author',$author)->get();
    }
}