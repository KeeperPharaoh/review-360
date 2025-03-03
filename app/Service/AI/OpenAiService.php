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

    public function generateGeneralReport(string $text)
    {

    }
}
