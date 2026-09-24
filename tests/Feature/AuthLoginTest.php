<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_invalid_login_returns_specific_error_message(): void
    {
        $response = $this->from('/login')->post('/login', [
            'email' => 'bad@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect('/login');
        $this->assertSame('Identifiants incorrects.', session('errors')->get('email')[0]);
    }
}
