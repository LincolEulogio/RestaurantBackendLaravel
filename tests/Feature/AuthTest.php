<?php

use App\Models\User;
use function Pest\Laravel\post;
use function Pest\Laravel\get;
use function Pest\Laravel\actingAs;

test('users can login with valid credentials', function () {
    $user = User::factory()->create([
        'password' => bcrypt($password = 'i-love-laravel'),
    ]);

    $response = post('/login', [
        'email' => $user->email,
        'password' => $password,
    ]);

    $response->assertRedirect('/dashboard');
    $this->assertAuthenticatedAs($user);
});

test('users cannot login with invalid password', function () {
    $user = User::factory()->create([
        'password' => bcrypt('i-love-laravel'),
    ]);

    $response = post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $response->assertSessionHasErrors();
    $this->assertGuest();
});

test('unauthenticated users are redirected to login', function () {
    get('/dashboard')->assertRedirect('/login');
});

test('admin can access reports', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    actingAs($admin)
        ->get('/reports')
        ->assertOk();
});

test('waiter cannot access reports', function () {
    $waiter = User::factory()->create(['role' => 'mesero']); // 'mesero' is the slug for waiter

    actingAs($waiter)
        ->get('/reports')
        ->assertForbidden();
});
