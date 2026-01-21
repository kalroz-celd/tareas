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

            return [
                'project' => $project->name,
                'project_dates' => $this->formatProjectDates($project),
                'timeline' => $this->buildTimeline($taskItems),
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

        return view('livewire.gantt.index', [
            'projectsTimeline' => $projectsTimeline,
            'projectTaskTimelines' => $projectTaskTimelines,
            'allTasksTimeline' => $allTasksTimeline,
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
