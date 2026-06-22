<?php

namespace Tests\Feature;

use App\Exceptions\UnauthorizedException;
use App\Exceptions\NotFoundException;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class CustomExceptionsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Define temporary test routes for testing the exceptions directly
        Route::get('/_test/unauthorized-exception', function () {
            throw new UnauthorizedException();
        });

        Route::get('/_test/not-found-exception', function () {
            throw new NotFoundException();
        });
    }

    public function test_unauthorized_exception_redirects_for_web_request(): void
    {
        $response = $this->get('/_test/unauthorized-exception');

        $response->assertRedirect(route('login'));
    }

    public function test_unauthorized_exception_returns_json_for_api_or_json_request(): void
    {
        $response = $this->getJson('/_test/unauthorized-exception');

        $response->assertStatus(401);
        $response->assertJson([
            'success' => false,
            'message' => 'غير مصرح بهذا الإجراء',
        ]);
    }

    public function test_not_found_exception_aborts_with_404_for_web_request(): void
    {
        $response = $this->get('/_test/not-found-exception');

        $response->assertStatus(404);
    }

    public function test_not_found_exception_returns_json_for_api_or_json_request(): void
    {
        $response = $this->getJson('/_test/not-found-exception');

        $response->assertStatus(404);
        $response->assertJson([
            'success' => false,
            'message' => 'العنصر المطلوب غير موجود',
        ]);
    }
}
