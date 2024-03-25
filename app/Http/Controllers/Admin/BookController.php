<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use App\Models\Book;
use App\Models\Author;
use App\Models\Category;
use App\Http\Requests\BookPostRequest;

class BookController extends Controller
{
    public function index(): Response
    {
        $books = Book::with('category')
            ->orderBy('category_id')
            ->orderBy('title')
            ->get();

        return response()
            ->view('admin/book/index', ['books' => $books])
            ->header('Content-Type', 'text/html')
            ->header('Content-Encoding', 'UTF-8');
    }

    public function show(Book $book): View
    {
        return view('admin/book/show', compact('book'));
    }

    public function create(): View
    {
        $categories = Category::all();

        $authors = Author::all();

        return view('admin/book/create', compact('categories', 'authors'));
    }

    public function store(BookPostRequest $request): RedirectResponse
    {
        $book = new Book();

        $book->category_id = $request->category_id;
        $book->title = $request->title;
        $book->price = $request->price;

        DB::transaction(function () use ($book, $request) {

            $book->save();

            $book->authors()->attach($request->author_id);

        });

        return redirect(route('book.index'))
            ->with('message', $book->title . "を追加しました。");
    }

    public function edit(Book $book): View
    {
        $categories = Category::all();
        $authors = Author::all();

        $authorIds = $book->authors()->pluck('id')->all();

        return view('admin/book/edit', compact('book', 'categories', 'authors', 'authorIds'));
    }
}
