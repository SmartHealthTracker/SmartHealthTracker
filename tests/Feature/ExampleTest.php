<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function testHomeRedirectsToLogin()
    {
        $response = $this->get('/'); // accès sans être connecté

        $response->assertStatus(302); // Laravel redirige
        $response->assertRedirect('/user-pages/login');
    }
}
