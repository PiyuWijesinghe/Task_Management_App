<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/bootstrap/app.php';

// Get users data
$users = App\Models\User::all(['id', 'name', 'email']);

echo "Users in database:\n";
echo "ID | Name | Email\n";
echo "---------------\n";

foreach ($users as $user) {
    echo "{$user->id} | {$user->name} | {$user->email}\n";
}

// Also check auth token data
$tokens = DB::table('personal_access_tokens')->get(['id', 'tokenable_id', 'name']);
echo "\nActive tokens:\n";
echo "Token ID | User ID | Name\n";
echo "---------------------\n";

foreach ($tokens as $token) {
    echo "{$token->id} | {$token->tokenable_id} | {$token->name}\n";
}