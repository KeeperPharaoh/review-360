<?php

namespace App\Http\Controllers;

use App\Models\UserMeta;
use App\Service\AI\OpenAiService;
use Illuminate\Http\Request;

class OneToOneController extends Controller
{
    private OpenAiService $openAiService;

    public function __construct(OpenAiService $openAiService)
    {
        $this->openAiService = $openAiService;
    }

    public function generateQuestions()
    {
        $userId = request()->input('user_id');

        return response()->json([
            'kek',
            'kek'
        ]);
    }

    public function createUserMeta()
    {
        $data = request()->all();
        UserMeta::query()->create([
            'user_id' => $data['user_id'],
            'text' => $data['text'],
        ]);

        return redirect()->back();
    }
}
