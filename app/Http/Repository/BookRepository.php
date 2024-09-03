<?php
namespace App\Http\Repository;
use app\Models\Book;
/**
 * Summary of BookRepository
 * In this repo i had added some filters i was working on taking the input from the service that takes from the controller
 */
Class BookRepository{
    public function getByAuthor($author){
        return Book::where('author',$author)->get();
    }
}