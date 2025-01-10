<?php
// Calculate statistics
$totalTasks = count($tasks);
$todoCount = count(array_filter($tasks, fn($task) => $task->status === 'todo'));
$doingCount = count(array_filter($tasks, fn($task) => $task->status === 'doing'));
$reviewCount = count(array_filter($tasks, fn($task) => $task->status === 'review'));
$doneCount = count(array_filter($tasks, fn($task) => $task->status === 'done'));

// Get task creation dates and tags for trends
$taskDates = array_map(fn($task) => (new DateTime($task->created_at))->format('Y-m-d'), $tasks);
$taskTags = array_map(fn($task) => $task->tag, $tasks);

// Calculate project progress
$progressPercentage = $totalTasks > 0 ? round(($doneCount / $totalTasks) * 100) : 0;
?>

<!-- Hidden inputs for chart data -->
<input type="hidden" id="todo-count" value="<?= $todoCount ?>">
<input type="hidden" id="doing-count" value="<?= $doingCount ?>">
<input type="hidden" id="review-count" value="<?= $reviewCount ?>">
<input type="hidden" id="done-count" value="<?= $doneCount ?>">
<input type="hidden" id="task-dates" value='<?= json_encode($taskDates) ?>'>
<input type="hidden" id="task-tags" value='<?= json_encode($taskTags) ?>'>

<div class="container mx-auto px-4 py-8">
    <!-- Project Header -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold"><?= htmlspecialchars($project->title) ?></h1>
            <div class="text-sm text-gray-500">
                Created on <?= (new DateTime($project->created_at))->format('M d, Y') ?>
            </div>
        </div>
        <p class="text-gray-600 mb-4"><?= htmlspecialchars($project->description) ?></p>
        
        <!-- Project Progress Bar -->
        <div class="mb-4">
            <div class="flex justify-between mb-1">
                <span class="text-sm font-medium">Project Progress</span>
                <span class="text-sm font-medium"><?= $progressPercentage ?>%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2.5">
                <div class="bg-blue-600 h-2.5 rounded-full" style="width: <?= $progressPercentage ?>%"></div>
            </div>
        </div>
    </div>

    <!-- Statistics Grid -->
    <div class="max-w-4xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Task Status Distribution -->
            <div class="bg-white rounded-lg shadow-lg p-4">
                <div class="h-64">
                    <canvas id="taskStatusChart"></canvas>
                </div>
            </div>

            <!-- Tag Distribution -->
            <div class="bg-white rounded-lg shadow-lg p-4">
                <div class="h-64">
                    <canvas id="tagDistributionChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Team Members Section -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
        <h2 class="text-xl font-bold mb-4">Team Members</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php foreach ($project->contributors as $contributor): ?>
            <div class="flex items-center space-x-3 bg-gray-50 p-3 rounded">
                <div class="flex-1">
                    <p class="font-medium"><?= htmlspecialchars($contributor->firstname . ' ' . $contributor->lastname) ?></p>
                    <p class="text-sm text-gray-600"><?= htmlspecialchars($contributor->email) ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="./js/project-details.js"></script>