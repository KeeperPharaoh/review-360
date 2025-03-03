<?php

namespace App\Service\AI;

use OpenAI;
use OpenAI\Client;

class OpenAiService
{
    private function getClient(): Client
    {
        return OpenAI::client(env('OPENAI_API_KEY'));
    }

    private function getModel(): string
    {
        return 'gpt-4o-2024-11-20';
    }

    public function generateGeneralReport(string $text)
    {

    }


    public function test(string $text)
    {
        $result = $this->getClient()->chat()->create([
            'model' => 'gpt-4o-2024-11-20',
            'messages' => [
                ['role' => 'user', 'content' => $text],
            ],
        ]);

        $result = $result->choices[0]->message->content;
        if (preg_match('/\{.*?\}/s', $result, $matches)) {
            $result = $matches[0];
        }

        $data = json_decode($result, true);

        return $data;
    }
}
