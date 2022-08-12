<?php

namespace Tests\Feature\API;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Book;
use Illuminate\Testing\Fluent\AssertableJson;

class BooksControllerTest extends TestCase
{

    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_books_get_endpoint()
    {
        $books = Book::factory(3)->create();

        $response = $this->getJson('/api/books');
        $response->assertStatus(200);
        $response->assertJsonCount(3);
        $response->assertJson(function (AssertableJson $json) use ($books) {
            // $json->whereType('0.id', 'integer');
            // $json->whereType('0.title', 'string');
            // $json->whereType('0.isbn', 'string');

            $json->whereAllType([
                '0.id' => 'integer',
                '0.title' => 'string',
                '0.isbn' => 'string'
            ]);

            $json->hasAll(['0.id', '0.title', '0.isbn']);

            $book = $books->first();

            $json->whereAll([
                '0.id' => $book->id,
                '0.title' => $book->title,
                '0.isbn' => $book->isbn
            ]);
        });
    }

    public function test_get_single_book()
    {
        $book = Book::factory()->createOne();

        $response = $this->getJson('/api/books/' . $book->id);
        $response->assertStatus(200);
        $response->assertJsonCount(5);
        $response->assertJson(function (AssertableJson $json) use ($book) {

            $json->whereAllType([
                'id' => 'integer',
                'title' => 'string',
                'isbn' => 'string'
            ]);

            $json->hasAll(['id', 'title', 'isbn', 'created_at', 'updated_at']);

            $json->whereAll([
                'id' => $book->id,
                'title' => $book->title,
                'isbn' => $book->isbn
            ]);
        });
    }

    public function test_post_book_endpoint()
    {
        $book = Book::factory()->makeOne();
        $response = $this->postJson('/api/books', $book->toArray());

        $response->assertStatus(201);
        $response->assertJsonCount(5);

        $response->assertJson(function (AssertableJson $json) use ($book) {

            $json->whereAllType([
                'id' => 'integer',
                'title' => 'string',
                'isbn' => 'string'
            ]);

            $json->hasAll(['id', 'title', 'isbn', 'created_at', 'updated_at']);

            $json->whereAll([
                'title' => $book->title,
                'isbn' => $book->isbn
            ])->etc();
        });
    }


    public function test_put_book_endpoint()
    {

        Book::factory()->createOne();
        $book = [
            'title' => 'Ola mundo',
            'isbn' => 'fdgdfgdfgdfg'
        ];

        $response = $this->putJson('/api/books/1', $book);

        $response->assertStatus(200);
        $response->assertJsonCount(5);

        $response->assertJson(function (AssertableJson $json) use ($book) {

            $json->whereAllType([
                'id' => 'integer',
                'title' => 'string',
                'isbn' => 'string'
            ]);

            $json->hasAll(['id', 'title', 'isbn', 'created_at', 'updated_at']);

            $json->whereAll([
                'title' => $book['title'],
                'isbn' => $book['isbn']
            ])->etc();
        });
    }

    public function test_patch_book_endpoint()
    {

        Book::factory()->createOne();
        $book = [
            'title' => 'Ola mundo atualizado',
        ];

        $response = $this->putJson('/api/books/1', $book);

        $response->assertStatus(200);
        $response->assertJsonCount(5);

        $response->assertJson(function (AssertableJson $json) use ($book) {

            $json->whereAllType([
                'id' => 'integer',
                'title' => 'string',
                'isbn' => 'string'
            ]);

            $json->hasAll(['id', 'title', 'isbn', 'created_at', 'updated_at']);

            $json->where(
                'title',
                $book['title']
            );
        });
    }

    public function test_delete_book_endpoint()
    {
        Book::factory()->createOne();
        $response = $this->deleteJson('/api/books/1');
        $response->assertStatus(204);
    }
}
