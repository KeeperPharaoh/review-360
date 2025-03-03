<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\Report;
use App\Service\AI\OpenAiService;
use Illuminate\Console\Command;

class GeneralReport extends Command
{
    private OpenAiService $openAiService;

    public function __construct(
        OpenAiService $openAiService,
    )
    {
        $this->openAiService = $openAiService;
        parent::__construct();
    }

    protected $signature = 'app:general-report';

    protected $description = 'Command description';

    public function handle()
    {
        $reports = Report::query()
            ->where('event_id', '=', 3)
            ->get();

        $result = "";

        foreach ($reports as $report) {
            $result .= $report->text . PHP_EOL;
        }

        $response = $this->openAiService->test('На основе отчетов об компании, собери общую сводку об компании: ' . $result);
        $event = Event::query()->where('id', '=', 3)->update([
            'report' => $response,
        ]);

        $response = $this->openAiService->test('На основе отчетов об компании, собери общую сводку об сотрудниках: ' . $result);

        $event = Event::query()->where('id', '=', 3)->update([
            'team_report' => $response,
        ]);
    }
}
