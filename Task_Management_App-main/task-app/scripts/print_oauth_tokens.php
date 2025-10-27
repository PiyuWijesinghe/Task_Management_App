<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$rows = \App\Models\OauthToken::all();
if ($rows->isEmpty()) {
    echo "(no rows)\n";
    exit(0);
}

foreach ($rows as $r) {
    $expires = $r->expires_at ? $r->expires_at->toDateTimeString() : 'NULL';
    $created = $r->created_at ? $r->created_at->toDateTimeString() : 'NULL';
    echo sprintf("%s|%s|%s|%s|%s\n", $r->id, $r->user_id, $r->provider, $expires, $created);
}
