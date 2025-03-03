<?php

namespace App\Console\Commands;

use App\Models\Answer;
use App\Models\Assignment;
use App\Models\User;
use App\Service\AI\OpenAiService;
use Illuminate\Console\Command;

class Test extends Command
{
    private OpenAiService $openAiService;

    public function __construct(
        OpenAiService $openAiService,
    )
    {
        $this->openAiService = $openAiService;
        parent::__construct();
    }

    protected $signature = 'app:test';

    protected $description = 'Command description';

    public function handle()
    {
        $notIn = Answer::query()
            ->whereNotNull('assignment_id')
            ->select('id')
            ->get()
            ->pluck('id')
            ->toArray();

        $assignments = Assignment::query()
            ->whereNotIn('id', $notIn)
            ->get();

        foreach ($assignments as $assignment) {
            $data = $this->openAiService->test(
                'Привет! Я использую тебя для интеграции, можешь все ответы отдавать в формате json. {"rating" : "твой ответ", "text" : "твой ответ"},
                      Мне нужно заполнить данные таблицы моковыми данными, Можешь дать в поле text ответ на вопрос "Дайте развернутую обратную связь по сотруднику без каких либо ограничений?"
                      тут сотрудники идут в контексте it компании, а в поле rating "Оцените от 1 до 5, насколько вы чувствуете сотрудника вовлеченным в деятельность компании?",
                       дай ответ такой чтобы я в своем php коде сделал просто json_decode. Можешь придумать разные отзывы, не бойся негативных дай оценку ' . rand(1, 5) . 'и напиши подходящий для нее отзыв. Можешь строго соблюдать данный формат'
            );
            $toUser = User::query()->where('id', '=', $assignment->to_user_id)->first();

            Answer::query()->create([
                'event_id' => 1,
                'question_id' => 11,
                'assignment_id' => $assignment->id,
                'user_id' => $assignment->from_user_id,
                'answer' => $data['rating'],
                'target_name' => $toUser->first_name . ' ' . $toUser->last_name,
            ]);

            Answer::query()->create([
                'event_id' => 1,
                'question_id' => 12,
                'assignment_id' => $assignment->id,
                'user_id' => $assignment->from_user_id,
                'answer' => $data['text'],
                'target_name' => $toUser->first_name . ' ' . $toUser->last_name,
            ]);
        }
    }
}
