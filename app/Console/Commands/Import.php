<?php

namespace App\Console\Commands;

use App\Models\Answer;
use App\Models\Assignment;
use App\Models\Report;
use App\Models\User;
use App\Service\AI\OpenAiService;
use Illuminate\Console\Command;

class Import extends Command
{
    private OpenAiService $openAiService;

    public function __construct(
        OpenAiService $openAiService,
    )
    {
        $this->openAiService = $openAiService;
        parent::__construct();
    }

    protected $signature = 'app:import';

    protected $description = 'Command description';

    public function handle(): void
    {
        $users = User::all();
        $kek = 0;
        foreach ($users as $user) {
            $assignments = Assignment::query()
                ->where('to_user_id', $user->id)
                ->select('id')
                ->get()->pluck('id')->toArray();

            $answers = Answer::query()
                ->whereIn('assignment_id', $assignments)
                ->where('answer', '!=', 'Не взаимодействуем')
                ->where('question_id', '=', 1)
                ->get();
            $text = "Привет! Можешь сделать общую краткую характеристику о сотрудники, на основе ответов о сотрудники на следующие вопросы
                1. Оцените от 1 до 5, насколько, по вашему мнению, сотрудник вовлечен в деятельность компании и качественно выполняет свою работу?
                2. Дайте развернутую обратную связь по сотруднику без каких либо ограничений?
                Ответы: ";

            foreach ($answers as $answer) {
                $text .= PHP_EOL;
                $text .= '1. ' . $answer->answer;

                $twoAnswer = Answer::query()
                    ->where('assignment_id', $answer->assignment_id)
                    ->where('answer', '!=', 'Не взаимодействуем')
                    ->where('question_id', '=', 2)
                    ->first();
                if ($twoAnswer) {
                    $text .= PHP_EOL;
                    $text .= '2. ' . $twoAnswer->answer;
                }
            }

            $text .= 'Можешь сделать краткую характеристику и дать ответ только общий вывод о сотрудники';
            $response = $this->openAiService->test('На основе отчетов об компании, собери общую сводку об компании: ' . $text);
            Report::query()->create([
                'user_id' => $user->id,
                'event_id' => 1,
                'text' => $response,
            ]);
            echo $kek;
            $kek++;
        }
    }
}
