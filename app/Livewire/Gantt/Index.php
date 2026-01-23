<?php

namespace App\Livewire\Gantt;

use App\Models\Project;
use App\Models\Task;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

class Index extends Component
{
    #[Url]
    public string $view = 'projects';

    #[Url]
    public ?string $month = null;

    #[Layout('layouts.app')]
    public function render()
    {
        $projects = Project::query()
            ->with('tasks')
            ->orderBy('name')
            ->get();

        $projectItems = $projects->map(function (Project $project) {
            $start = ($project->start_date ?? $project->created_at)->copy()->startOfDay();
            $end = ($project->due_date ?? $start)->copy()->startOfDay();

            if ($end->lessThan($start)) {
                $end = $start->copy();
            }

            return [
                'label' => $project->name,
                'sub_label' => $project->status_label,
                'start' => $start,
                'end' => $end,
                'range_label' => $start->format('d/m/Y') . ' → ' . $end->format('d/m/Y'),
                'bar_style' => $project->status_badge_style,
            ];
        })->all();

        $projectsTimeline = $this->buildTimeline($projectItems);
        $projectsTimeline['months'] = $this->buildMonthSegments($projectsTimeline['start'], $projectsTimeline['end']);
        $projectsTimeline['month_breakdown'] = $this->buildMonthBreakdown($projectItems, $this->month);

        $projectTaskTimelines = $projects->map(function (Project $project) {
            $taskItems = $project->tasks->map(function (Task $task) {
                $start = $task->created_at->copy()->startOfDay();
                $end = ($task->due_date ?? $task->created_at)->copy()->startOfDay();

                if ($end->lessThan($start)) {
                    $end = $start->copy();
                }

                return [
                    'label' => $task->title,
                    'sub_label' => $task->status_label,
                    'start' => $start,
                    'end' => $end,
                    'range_label' => $start->format('d/m/Y') . ' → ' . $end->format('d/m/Y'),
                    'bar_style' => $task->status_badge_style,
                ];
            })->all();

            $timeline = $this->buildTimeline($taskItems);

            return [
                'project' => $project->name,
                'project_dates' => $this->formatProjectDates($project),
                'timeline' => $timeline,
                'months' => $this->buildMonthSegments($timeline['start'], $timeline['end']),
                'month_breakdown' => $this->buildMonthBreakdown($taskItems, $this->month),
            ];
        });

        $allTasksItems = Task::query()
            ->with('project:id,name')
            ->orderBy('created_at')
            ->get()
            ->map(function (Task $task) {
                $start = $task->created_at->copy()->startOfDay();
                $end = ($task->due_date ?? $task->created_at)->copy()->startOfDay();

                if ($end->lessThan($start)) {
                    $end = $start->copy();
                }

                return [
                    'label' => $task->title,
                    'sub_label' => $task->project?->name ? 'Proyecto: ' . $task->project->name : 'Sin proyecto',
                    'start' => $start,
                    'end' => $end,
                    'range_label' => $start->format('d/m/Y') . ' → ' . $end->format('d/m/Y'),
                    'bar_style' => $task->status_badge_style,
                ];
            })
            ->all();

        $allTasksTimeline = $this->buildTimeline($allTasksItems);
        $allTasksTimeline['months'] = $this->buildMonthSegments($allTasksTimeline['start'], $allTasksTimeline['end']);
        $allTasksTimeline['month_breakdown'] = $this->buildMonthBreakdown($allTasksItems, $this->month);

        $selectedMonthLabel = null;

        if ($this->month) {
            try {
                $selectedMonthLabel = Carbon::createFromFormat('Y-m', $this->month)->translatedFormat('F Y');
            } catch (\Throwable $e) {
                $selectedMonthLabel = null;
            }
        }

        return view('livewire.gantt.index', [
            'projectsTimeline' => $projectsTimeline,
            'projectTaskTimelines' => $projectTaskTimelines,
            'allTasksTimeline' => $allTasksTimeline,
            'selectedMonthLabel' => $selectedMonthLabel,
        ]);
    }

    private function buildTimeline(array $items): array
    {
        if ($items === []) {
            return [
                'items' => [],
                'start' => null,
                'end' => null,
                'total_days' => 0,
            ];
        }

        $collection = collect($items);
        $minTimestamp = $collection->min(fn ($item) => $item['start']->timestamp);
        $maxTimestamp = $collection->max(fn ($item) => $item['end']->timestamp);

        $minDate = Carbon::createFromTimestamp($minTimestamp)->startOfDay();
        $maxDate = Carbon::createFromTimestamp($maxTimestamp)->startOfDay();
        $totalDays = max(1, $minDate->diffInDays($maxDate) + 1);

        $items = $collection->map(function (array $item) use ($minDate, $totalDays) {
            $start = $item['start'];
            $end = $item['end'];
            $durationDays = max(1, $start->diffInDays($end) + 1);
            $offsetDays = $minDate->diffInDays($start);

            $item['offset_percent'] = ($offsetDays / $totalDays) * 100;
            $item['duration_percent'] = ($durationDays / $totalDays) * 100;

            return $item;
        })->all();

        return [
            'items' => $items,
            'start' => $minDate,
            'end' => $maxDate,
            'total_days' => $totalDays,
        ];
    }

    private function buildMonthSegments(?Carbon $start, ?Carbon $end): array
    {
        if (!$start || !$end) {
            return [];
        }

        $startDate = $start->copy()->startOfMonth();
        $endDate = $end->copy()->startOfMonth();
        $totalDays = max(1, $start->diffInDays($end) + 1);

        $months = [];
        $cursor = $startDate->copy();

        while ($cursor <= $endDate) {
            $segmentStart = $cursor->copy()->startOfMonth();
            $segmentEnd = $cursor->copy()->endOfMonth();

            if ($segmentStart->lessThan($start)) {
                $segmentStart = $start->copy();
            }

            if ($segmentEnd->greaterThan($end)) {
                $segmentEnd = $end->copy();
            }

            $segmentDays = max(1, $segmentStart->diffInDays($segmentEnd) + 1);
            $offsetDays = $start->diffInDays($segmentStart);

            $months[] = [
                'key' => $cursor->format('Y-m'),
                'label' => $cursor->translatedFormat('F Y'),
                'offset_percent' => ($offsetDays / $totalDays) * 100,
                'duration_percent' => ($segmentDays / $totalDays) * 100,
            ];

            $cursor->addMonthNoOverflow()->startOfMonth();
        }

        return $months;
    }

    private function buildMonthBreakdown(array $items, ?string $monthKey): array
    {
        if (!$monthKey) {
            return [
                'month' => null,
                'days' => [],
            ];
        }

        try {
            $month = Carbon::createFromFormat('Y-m', $monthKey)->startOfMonth();
        } catch (\Throwable $e) {
            return [
                'month' => null,
                'days' => [],
            ];
        }

        $daysInMonth = $month->daysInMonth;
        $days = [];

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = $month->copy()->day($day)->startOfDay();
            $tasks = collect($items)->filter(function (array $item) use ($date) {
                return $date->betweenIncluded($item['start'], $item['end']);
            })->map(fn (array $item) => [
                'label' => $item['label'],
                'style' => $item['bar_style'],
            ])->values()->all();

            $days[] = [
                'date' => $date,
                'tasks' => $tasks,
            ];
        }

        return [
            'month' => $month,
            'days' => $days,
        ];
    }

    private function formatProjectDates(Project $project): string
    {
        $start = $project->start_date ?? $project->created_at;
        $end = $project->due_date ?? $start;

        if ($end->lessThan($start)) {
            $end = $start;
        }

        return $start->format('d/m/Y') . ' → ' . $end->format('d/m/Y');
    }
}