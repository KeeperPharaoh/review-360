<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UserReport extends Command
{
    protected $signature = 'app:user-report';

    public function handle(): void
    {
        $result = $client->chat()->create([
            'model' => 'gpt-4o-2024-11-20',
            'messages' => [
                ['role' => 'user', 'content' => 'Hello!'],
            ],
        ]);
    }
}
