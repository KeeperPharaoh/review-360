<?php

namespace App\Http\Controllers;

use App\Models\Report;
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

        $reports = Report::query()
            ->where('user_id', '=', $userId)
            ->get();

        $metas = UserMeta::query()
            ->where('user_id', '=', $userId)
            ->get();

        $result = "";

        /** @var Report $report */
        foreach ($reports as $report) {
            $result .= "Отчет " . $report->event->name . PHP_EOL;
            $result .= $report->text . PHP_EOL;
        }

        /** @var UserMeta $meta */
        foreach ($metas as $meta) {
            $result .= "Заметка" . $meta->created_at . PHP_EOL;
            $result .= $meta->text . PHP_EOL;
        }

        $response = $this->openAiService->test(
            'На основе последних отчетов и заметок о сотрудники, можешь мне сгенирировать вопросы для встречи one-to-one. Вопросы должны быть индвидуальные и отражающий пользователя. Можешь ответ Дать в красивом формате, Просто список вопросов и между вопросами тег </br>'
            . $result);

        $response = str_replace('###', '', $response);

        return response()->json($response);
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
