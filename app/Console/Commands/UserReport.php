<?php

namespace App\Console\Commands;

use App\Models\Answer;
use App\Models\Assignment;
use App\Models\Report;
use App\Service\AI\OpenAiService;
use http\Client\Curl\User;
use Illuminate\Console\Command;

class UserReport extends Command
{
    private OpenAiService $openAiService;

    public function __construct(
        OpenAiService $openAiService,
    )
    {
        $this->openAiService = $openAiService;
        parent::__construct();
    }

    protected $signature = 'app:user-report';

    public function handle(): void
    {
        $users = \App\Models\User::all();

        foreach ($users as $user) {
            $reports = Report::query()
                ->where('user_id', '=', $user->id)
                ->get();

            $result = "";
            foreach ($reports as $report) {
                $result .= $report->text . PHP_EOL;
            }

            $response = $this->openAiService->test('На основе общих прошлых отчетов, создай общую характеристику для человека. ' . $result);
            $user->update([
                'general_report' => $response,
            ]);
        }
    }
}
