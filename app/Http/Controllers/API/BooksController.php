<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

class BooksController extends Controller
{
    public function index(Book $book)
    {
        return response()->json($book->all());
    }

    public function show($id)
    {
        return response()->json(Book::find($id));
    }

    public function store(Request $request)
    {

        $book = Book::create($request->all());
        return response()->json($book, 201);
    }
}
