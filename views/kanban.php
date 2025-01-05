<?php
use app\helpers\Dump;
$contributersIds = array_map(function ($contributer) {
    return $contributer->id;
}, $project->contributers);
?>
    <div class="min-h-screen p-6 relative">
        <div class="max-w-7xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-3xl font-bold text-gray-900"><?= htmlspecialchars($project->title) ?></h1>
                        <div class="relative inline-block">
                            <?php if ($project->user_id === $_SESSION['user']['id']): ?>
                                <button type="button" onclick="document.getElementById('delete-project-form').classList.toggle('hidden')" class="text-red-500 hover:text-red-600 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                    </svg>
                                </button>
                            <?php endif; ?>
                            <div id="delete-project-form" class="hidden absolute left-0 top-full mt-2 bg-white shadow-lg rounded-lg border border-gray-200 z-50">
                                <div class="p-4 w-64">
                                    <p class="text-sm text-gray-600 mb-3">Are you sure you want to delete this project?</p>
                                    <form action="/delete-project" method="POST">
                                        <input type="hidden" name="project_id" value="<?=$project->id?>">
                                        <div class="flex space-x-2">
                                            <button type="submit" class="flex-1 px-3 py-1 text-sm text-white bg-red-600 rounded hover:bg-red-700">Delete</button>
                                            <button type="button" onclick="document.getElementById('delete-project-form').classList.add('hidden')" class="flex-1 px-3 py-1 text-sm text-gray-600 bg-gray-100 rounded hover:bg-gray-200">Cancel</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-600"><?= htmlspecialchars($project->description) ?></p>
                </div>
                <div>
                <a href="/" class="text-blue-600 hover:text-blue-800 block">← Back to Projects</a>
                <?php if ($project->user_id === $_SESSION['user']['id']): ?>
                    <button class="btn-manage-contributers text-gray-400 mt-2 hover:text-gray-700 transition-colors" >Manage contributers</button>
                <?php endif; ?>
                    <div id="contributorForm"  class="hidden absolute top-1/2 left-1/2 translate-x-[-50%] translate-y-[-50%] bg-white rounded-lg shadow p-10 z-50">
                    <form class=" bg-white rounded-lg shadow p-4" action="add-contribution" method="POST">
                        <input type="hidden" name="project_id" value="<?=$project->id?>">
                        <div class="flex items-center gap-20">
                        <select class="select-contributor form-select" name="user_id">
                                <option class="hidden">Select Contributor</option>
                                <?php foreach($allUsers as $user): ?>
                                    <?php
                                        if($user['id'] !== $project->user_id  && !in_array($user['id'],$contributersIds)) {
                                            echo "<option value='{$user['id']}'>{$user['firstname']}  {$user['lastname']}</option>";
                                        }
                                        ?>
                                <?php endforeach?>
                            </select>
                            <button class="btn-add-contributor  rounded-full flex justify-center items-center   bg-gray-100 w-8 h-8 text-lg hover:bg-gray-300 transition-colors opacity-0 invisible">+</button>
                        </div>
                    </form>
                    <ul class=" mt-5 space-y-2">
                        <?php foreach($project->contributers as $contributer): ?>
                        <li class="flex w-full justify-between">
                            <p><?=$contributer->firstname?> <?=$contributer->lastname?></p>
                            <form action="delete-contribution" method="POST">
                                <input type="hidden" name="user_id" value="<?=$contributer->id?>">
                                <input type="hidden" name="project_id" value="<?=$project->id?>">
                                <button type="submit" class="btn btn-success rounded-full flex justify-center items-center   bg-red-50 w-8 h-8 text-lg hover:bg-red-100 transition-colors">-</button>
                            </form>
                        </li>
                        <?php endforeach;?>
                    </ul>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-gray-200">
                <div class="grid grid-cols-4 gap-6 items-start min-w-[1200px] p-1">
                <!-- Todo Column -->
                <div class="bg-white rounded-lg shadow p-4">
                    <h2 class="font-semibold text-gray-900 mb-4">Todo</h2>
                    <div id="todo" class="space-y-3 min-h-10">
                        <?php
                        foreach ($tasks as $task): ?>
                            <?php if ($task->status === 'todo'): ?>
                                <div draggable="true"  class="task <?=$task->tag === 'bug' ? 'bg-red-50' : ($task->tag === 'feature' ? 'bg-green-50' :'bg-gray-50')?>  p-4 rounded shadow-sm relative" data-id="<?= $task->id ?>">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="font-medium"><?= htmlspecialchars($task->title) ?></h3>
                                            <p class="text-sm text-gray-600"><?= htmlspecialchars($task->description) ?></p>
                                            <?php if(!empty($task->assignees)): ?>
                                            <div class="flex flex-wrap gap-1 mt-2">
                                                <?php foreach($task->assignees as $assignee): ?>
                                                <span class="text-xs px-2 py-0.5 <?= $assignee->id === $_SESSION['user']['id'] ? 'bg-green-800 text-white' : 'bg-gray-100 text-gray-500' ?> rounded-full"><?=$assignee->firstname?></span>
                                                <?php endforeach; ?>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="relative">
                                            <button onclick="toggleMenu('<?= $task->id ?>')" class="btn-more p-1 hover:bg-gray-200 rounded">
                                                <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 24 24">
                                                    <circle cx="12" cy="6" r="2"/>
                                                    <circle cx="12" cy="12" r="2"/>
                                                    <circle cx="12" cy="18" r="2"/>
                                                </svg>
                                            </button>
                                            <div id="menu-<?= $task->id ?>" class="task-menu absolute right-0 top-8 bg-white shadow-lg rounded-md border border-gray-200 w-32 z-50 transform translate-y-2 opacity-0 invisible transition-all duration-200 ease-in-out">
                                                <div class="py-1">
                                                    <button onclick="showTagForm('<?= $task->id ?>')" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Change Tag</button>
                                                    <button onclick="showAssignForm('<?= $task->id ?>')" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Assign Task</button>
                                                    <button onclick="showDeleteForm('<?= $task->id ?>')" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Delete</button>
                                                </div>
                                            </div>
                                            <div id="delete-form-<?= $task->id ?>" class="delete-confirm absolute right-0 top-8 bg-white shadow-lg rounded-md border border-gray-200 z-50 transform translate-y-2 opacity-0 invisible transition-all duration-200 ease-in-out">
                                                <div class="p-3">
                                                    <p class="text-sm text-gray-600 mb-3">Are you sure?</p>
                                                    <form class="space-y-2" action="/delete-task" method="POST">
                                                        <input type="hidden" name="task_id" value="<?= $task->id ?>">
                                                        <div class="flex space-x-2">
                                                            <button type="submit" class="flex-1 px-3 py-1 text-sm text-white bg-red-600 rounded hover:bg-red-700">Confirm</button>
                                                            <button type="button" onclick="closeDeleteForm('<?= $task->id ?>')" class="flex-1 px-3 py-1 text-sm text-gray-600 bg-gray-100 rounded hover:bg-gray-200">Cancel</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <div id="tag-form-<?= $task->id ?>" class="tag-form absolute right-0 top-8 bg-white shadow-lg rounded-md border border-gray-200 z-50 transform translate-y-2 opacity-0 invisible transition-all duration-200 ease-in-out">
                                                <div class="p-3">
                                                    <p class="text-sm text-gray-600 mb-3">Select Tag</p>
                                                    <form class="space-y-2" action="/change-task-tag" method="POST">
                                                        <input type="hidden" name="task_id" value="<?= $task->id ?>">
                                                        <select name="tag" class="w-full mb-3 text-sm border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                            <option value="basic" <?= $task->tag === 'basic' ? 'selected' : '' ?>>Basic</option>
                                                            <option value="feature" <?= $task->tag === 'feature' ? 'selected' : '' ?>>Feature</option>
                                                            <option value="bug" <?= $task->tag === 'bug' ? 'selected' : '' ?>>Bug</option>
                                                        </select>
                                                        <div class="flex space-x-2">
                                                            <button type="submit" class="flex-1 px-3 py-1 text-sm text-white bg-blue-600 rounded hover:bg-blue-700">Save</button>
                                                            <button type="button" onclick="closeTagForm('<?= $task->id ?>')" class="flex-1 px-3 py-1 text-sm text-gray-600 bg-gray-100 rounded hover:bg-gray-200">Cancel</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <div id="assign-form-<?= $task->id ?>" class="assign-form absolute right-0 top-8 bg-white shadow-lg rounded-md border border-gray-200 z-50 transform translate-y-2 opacity-0 invisible transition-all duration-200 ease-in-out">
                                                <div class="p-3">
                                                    <div class="flex justify-between items-center mb-3">
                                                        <p class="text-sm text-gray-600">Assign To</p>
                                                        <button onclick="closeAssignForm('<?= $task->id ?>')" class="text-gray-400 hover:text-gray-600">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                    <form class="space-y-2" action="/assign-task" method="POST">
                                                        <input type="hidden" name="task_id" value="<?= $task->id ?>">
                                                        <div class="flex items-center gap-2">
                                                        <select class="form-select text-sm" name="user_id">
                                                                <option class="hidden">Select User</option>
                                                                <?php foreach($allUsers as $user): ?>
                                                                    <?php
                                                                        $assigneesIds = array_map(function ($assignee) {
                                                                            return $assignee->id;
                                                                        }, $task->assignees);
            
                                                                            if($user['id'] !== $project->user_id  && in_array($user['id'],$contributersIds) && !in_array($user['id'],$assigneesIds)) {
                                                                                echo "<option value='{$user['id']}'>{$user['firstname']}  {$user['lastname']}</option>";
                                                                            }
                                                                            ?>
                                                                <?php endforeach?>
                                                            </select>
                                                            <button type="submit" class="rounded-full flex justify-center items-center bg-gray-100 w-6 h-6 text-sm hover:bg-gray-300 transition-colors">+</button>
                                                        </div>
                                                    </form>
                                                    <div class="mt-3 space-y-2">
                                                        <?php foreach($task->assignees as $assignee): ?>
                                                        <div class="flex items-center justify-between text-sm">
                                                            <span><?=$assignee->firstname?> <?=$assignee->lastname?></span>
                                                            <form action="/unassign-task" method="POST">
                                                                <input type="hidden" name="user_id" value="<?=$assignee->id?>">
                                                                <input type="hidden" name="task_id" value="<?=$task->id?>">
                                                                <button class="rounded-full flex justify-center items-center bg-red-50 w-6 h-6 text-sm hover:bg-red-100 transition-colors">-</button>
                                                            </form>
                                                        </div>
                                                        <?php endforeach;?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <!-- Add Task Button and Form -->
                        
                    </div>
                    <?php if ($project->user_id === $_SESSION['user']['id']): ?>
                    <div class="task bg-gray-50 p-4 rounded shadow-sm hover:bg-gray-100 cursor-pointer mt-5" onclick="toggleTaskForm('todo')">
                            <div class="add-task-btn">
                                <div class="flex items-center justify-center">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                </div>
                            </div>
                            <form class="task-form hidden mt-3" id="todo-form" action="/add-task" method="POST" onclick="event.stopPropagation()">
                                <input type="hidden" name="project_id" value="<?= $project->id ?>">  
                                <input type="hidden" name="status" value="todo">
                                <div class="mb-2">
                                    <input type="text" name="title" id="title" placeholder="Task title" required
                                        class="w-full px-2 py-1 text-sm border rounded">
                                </div>
                                <div class="mb-2">
                                    <textarea name="description" id="description" placeholder="Description" rows="2"
                                        class="w-full px-2 py-1 text-sm border rounded"></textarea>
                                </div>
                                <div class="flex justify-end space-x-2">
                                    <button type="button" onclick="toggleTaskForm('todo')"
                                        class="px-2 py-1 text-xs text-gray-600 hover:text-gray-800">Cancel</button>
                                    <button type="submit"
                                        class="px-2 py-1 text-xs text-white bg-blue-500 rounded hover:bg-blue-600">Add</button>
                                </div>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Doing Column -->
                <div class="bg-white rounded-lg shadow p-4">
                    <h2 class="font-semibold text-gray-900 mb-4">Doing</h2>
                    <div id="doing" class="space-y-3 min-h-10">
                        <?php foreach ($tasks as $task): ?>
                            <?php if ($task->status === 'doing'): ?>
                                <div draggable="true" class="task <?=$task->tag === 'bug' ? 'bg-red-50' : ($task->tag === 'feature' ? 'bg-green-50' :'bg-gray-50')?> p-4 rounded shadow-sm relative" data-id="<?= $task->id ?>">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="font-medium"><?= htmlspecialchars($task->title) ?></h3>
                                            <p class="text-sm text-gray-600"><?= htmlspecialchars($task->description) ?></p>
                                            <?php if(!empty($task->assignees)): ?>
                                            <div class="flex flex-wrap gap-1 mt-2">
                                                <?php foreach($task->assignees as $assignee): ?>
                                                <span class="text-xs px-2 py-0.5 <?= $assignee->id === $_SESSION['user']['id'] ? 'bg-green-800 text-white' : 'bg-gray-100 text-gray-500' ?> rounded-full"><?=$assignee->firstname?></span>
                                                <?php endforeach; ?>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="relative">
                                            <button onclick="toggleMenu('<?= $task->id ?>')" class="btn-more p-1 hover:bg-gray-200 rounded">
                                                <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 24 24">
                                                    <circle cx="12" cy="6" r="2"/>
                                                    <circle cx="12" cy="12" r="2"/>
                                                    <circle cx="12" cy="18" r="2"/>
                                                </svg>
                                            </button>
                                            <div id="menu-<?= $task->id ?>" class="task-menu absolute right-0 top-8 bg-white shadow-lg rounded-md border border-gray-200 w-32 z-50 transform translate-y-2 opacity-0 invisible transition-all duration-200 ease-in-out">
                                                <div class="py-1">
                                                    <button onclick="showTagForm('<?= $task->id ?>')" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Change Tag</button>
                                                    <button onclick="showAssignForm('<?= $task->id ?>')" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Assign Task</button>
                                                    <button onclick="showDeleteForm('<?= $task->id ?>')" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Delete</button>
                                                </div>
                                            </div>
                                            <div id="delete-form-<?= $task->id ?>" class="delete-confirm absolute right-0 top-8 bg-white shadow-lg rounded-md border border-gray-200 z-50 transform translate-y-2 opacity-0 invisible transition-all duration-200 ease-in-out">
                                                <div class="p-3">
                                                    <p class="text-sm text-gray-600 mb-3">Are you sure?</p>
                                                    <form class="space-y-2" action="/delete-task" method="POST">
                                                        <input type="hidden" name="task_id" value="<?= $task->id ?>">
                                                        <div class="flex space-x-2">
                                                            <button type="submit" class="flex-1 px-3 py-1 text-sm text-white bg-red-600 rounded hover:bg-red-700">Confirm</button>
                                                            <button type="button" onclick="closeDeleteForm('<?= $task->id ?>')" class="flex-1 px-3 py-1 text-sm text-gray-600 bg-gray-100 rounded hover:bg-gray-200">Cancel</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <div id="tag-form-<?= $task->id ?>" class="tag-form absolute right-0 top-8 bg-white shadow-lg rounded-md border border-gray-200 z-50 transform translate-y-2 opacity-0 invisible transition-all duration-200 ease-in-out">
                                                <div class="p-3">
                                                    <p class="text-sm text-gray-600 mb-3">Select Tag</p>
                                                    <form class="space-y-2" action="/change-task-tag" method="POST">
                                                        <input type="hidden" name="task_id" value="<?= $task->id ?>">
                                                        <select name="tag" class="w-full mb-3 text-sm border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                            <option value="basic" <?= $task->tag === 'basic' ? 'selected' : '' ?>>Basic</option>
                                                            <option value="feature" <?= $task->tag === 'feature' ? 'selected' : '' ?>>Feature</option>
                                                            <option value="bug" <?= $task->tag === 'bug' ? 'selected' : '' ?>>Bug</option>
                                                        </select>
                                                        <div class="flex space-x-2">
                                                            <button type="submit" class="flex-1 px-3 py-1 text-sm text-white bg-blue-600 rounded hover:bg-blue-700">Save</button>
                                                            <button type="button" onclick="closeTagForm('<?= $task->id ?>')" class="flex-1 px-3 py-1 text-sm text-gray-600 bg-gray-100 rounded hover:bg-gray-200">Cancel</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <div id="assign-form-<?= $task->id ?>" class="assign-form absolute right-0 top-8 bg-white shadow-lg rounded-md border border-gray-200 z-50 transform translate-y-2 opacity-0 invisible transition-all duration-200 ease-in-out">
                                                <div class="p-3">
                                                    <div class="flex justify-between items-center mb-3">
                                                        <p class="text-sm text-gray-600">Assign To</p>
                                                        <button onclick="closeAssignForm('<?= $task->id ?>')" class="text-gray-400 hover:text-gray-600">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                    <form class="space-y-2" action="/assign-task" method="POST">
                                                    <input type="hidden" name="task_id" value="<?=$task->id?>">
                                                        <div class="flex items-center gap-2">
                                                            <select class="form-select text-sm" name="user_id">
                                                                <option class="hidden">Select User</option>
                                                                <?php foreach($allUsers as $user): ?>
                                                                    <?php
                                                                        $assigneesIds = array_map(function ($assignee) {
                                                                            return $assignee->id;
                                                                        }, $task->assignees);
            
                                                                            if($user['id'] !== $project->user_id  && in_array($user['id'],$contributersIds) && !in_array($user['id'],$assigneesIds)) {
                                                                                echo "<option value='{$user['id']}'>{$user['firstname']}  {$user['lastname']}</option>";
                                                                            }
                                                                            ?>
                                                                <?php endforeach?>
                                                            </select>
                                                            <button type="submit" class="rounded-full flex justify-center items-center bg-gray-100 w-6 h-6 text-sm hover:bg-gray-300 transition-colors">+</button>
                                                        </div>
                                                    </form>
                                                    <div class="mt-3 space-y-2">
                                                        <?php foreach($task->assignees as $assignee): ?>
                                                        <div class="flex items-center justify-between text-sm">
                                                            <span><?=$assignee->firstname?> <?=$assignee->lastname?></span>
                                                            <form action="/unassign-task" method="POST">
                                                                <input type="hidden" name="user_id" value="<?=$assignee->id?>">
                                                                <input type="hidden" name="task_id" value="<?=$task->id?>">
                                                                <button class="rounded-full flex justify-center items-center bg-red-50 w-6 h-6 text-sm hover:bg-red-100 transition-colors">-</button>
                                                            </form>
                                                        </div>
                                                        <?php endforeach;?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <!-- Add Task Button and Form -->
                        
                    </div>
                    <?php if ($project->user_id === $_SESSION['user']['id']): ?>
                    <div class="task bg-gray-50 p-4 rounded shadow-sm hover:bg-gray-100 cursor-pointer mt-5" onclick="toggleTaskForm('doing')">
                            <div class="add-task-btn">
                                <div class="flex items-center justify-center">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                </div>
                            </div>
                            <form class="task-form hidden mt-3" id="doing-form" action="/add-task" method="POST" onclick="event.stopPropagation()">
                                <input type="hidden" name="project_id" value="<?= $project->id ?>">
                            
                                <input type="hidden" name="status" value="doing">
                                <div class="mb-2">
                                    <input type="text" name="title" placeholder="Task title" required
                                        class="w-full px-2 py-1 text-sm border rounded">
                                </div>
                                <div class="mb-2">
                                    <textarea name="description" placeholder="Description" rows="2"
                                        class="w-full px-2 py-1 text-sm border rounded"></textarea>
                                </div>
                                <div class="flex justify-end space-x-2">
                                    <button type="button" onclick="toggleTaskForm('doing')"
                                        class="px-2 py-1 text-xs text-gray-600 hover:text-gray-800">Cancel</button>
                                    <button type="submit"
                                        class="px-2 py-1 text-xs text-white bg-blue-500 rounded hover:bg-blue-600">Add</button>
                                </div>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Review Column -->
                <div class="bg-white rounded-lg shadow p-4">
                    <h2 class="font-semibold text-gray-900 mb-4">Review</h2>
                    <div id="review" class="space-y-3 min-h-10">
                        <?php foreach ($tasks as $task): ?>
                            <?php if ($task->status === 'review'): ?>
                                <div draggable="true" class="task <?=$task->tag === 'bug' ? 'bg-red-50' : ($task->tag === 'feature' ? 'bg-green-50' :'bg-gray-50')?> p-4 rounded shadow-sm relative" data-id="<?= $task->id ?>">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="font-medium"><?= htmlspecialchars($task->title) ?></h3>
                                            <p class="text-sm text-gray-600"><?= htmlspecialchars($task->description) ?></p>
                                            <?php if(!empty($task->assignees)): ?>
                                            <div class="flex flex-wrap gap-1 mt-2">
                                                <?php foreach($task->assignees as $assignee): ?>
                                                <span class="text-xs px-2 py-0.5 <?= $assignee->id === $_SESSION['user']['id'] ? 'bg-green-800 text-white' : 'bg-gray-100 text-gray-500' ?> rounded-full"><?=$assignee->firstname?></span>
                                                <?php endforeach; ?>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="relative">
                                            <button onclick="toggleMenu('<?= $task->id ?>')" class="btn-more p-1 hover:bg-gray-200 rounded">
                                                <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 24 24">
                                                    <circle cx="12" cy="6" r="2"/>
                                                    <circle cx="12" cy="12" r="2"/>
                                                    <circle cx="12" cy="18" r="2"/>
                                                </svg>
                                            </button>
                                            <div id="menu-<?= $task->id ?>" class="task-menu absolute right-0 top-8 bg-white shadow-lg rounded-md border border-gray-200 w-32 z-50 transform translate-y-2 opacity-0 invisible transition-all duration-200 ease-in-out">
                                                <div class="py-1">
                                                    <button onclick="showTagForm('<?= $task->id ?>')" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Change Tag</button>
                                                    <button onclick="showAssignForm('<?= $task->id ?>')" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Assign Task</button>
                                                    <button onclick="showDeleteForm('<?= $task->id ?>')" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Delete</button>
                                                </div>
                                            </div>
                                            <div id="delete-form-<?= $task->id ?>" class="delete-confirm absolute right-0 top-8 bg-white shadow-lg rounded-md border border-gray-200 z-50 transform translate-y-2 opacity-0 invisible transition-all duration-200 ease-in-out">
                                                <div class="p-3">
                                                    <p class="text-sm text-gray-600 mb-3">Are you sure?</p>
                                                    <form class="space-y-2" action="/delete-task" method="POST">
                                                        <input type="hidden" name="task_id" value="<?= $task->id ?>">
                                                        <div class="flex space-x-2">
                                                            <button type="submit" class="flex-1 px-3 py-1 text-sm text-white bg-red-600 rounded hover:bg-red-700">Confirm</button>
                                                            <button type="button" onclick="closeDeleteForm('<?= $task->id ?>')" class="flex-1 px-3 py-1 text-sm text-gray-600 bg-gray-100 rounded hover:bg-gray-200">Cancel</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <div id="tag-form-<?= $task->id ?>" class="tag-form absolute right-0 top-8 bg-white shadow-lg rounded-md border border-gray-200 z-50 transform translate-y-2 opacity-0 invisible transition-all duration-200 ease-in-out">
                                                <div class="p-3">
                                                    <p class="text-sm text-gray-600 mb-3">Select Tag</p>
                                                    <form class="space-y-2" action="/change-task-tag" method="POST">
                                                        <input type="hidden" name="task_id" value="<?= $task->id ?>">
                                                        <select name="tag" class="w-full mb-3 text-sm border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                            <option value="basic" <?= $task->tag === 'basic' ? 'selected' : '' ?>>Basic</option>
                                                            <option value="feature" <?= $task->tag === 'feature' ? 'selected' : '' ?>>Feature</option>
                                                            <option value="bug" <?= $task->tag === 'bug' ? 'selected' : '' ?>>Bug</option>
                                                        </select>
                                                        <div class="flex space-x-2">
                                                            <button type="submit" class="flex-1 px-3 py-1 text-sm text-white bg-blue-600 rounded hover:bg-blue-700">Save</button>
                                                            <button type="button" onclick="closeTagForm('<?= $task->id ?>')" class="flex-1 px-3 py-1 text-sm text-gray-600 bg-gray-100 rounded hover:bg-gray-200">Cancel</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <div id="assign-form-<?= $task->id ?>" class="assign-form absolute right-0 top-8 bg-white shadow-lg rounded-md border border-gray-200 z-50 transform translate-y-2 opacity-0 invisible transition-all duration-200 ease-in-out">
                                                <div class="p-3">
                                                    <div class="flex justify-between items-center mb-3">
                                                        <p class="text-sm text-gray-600">Assign To</p>
                                                        <button onclick="closeAssignForm('<?= $task->id ?>')" class="text-gray-400 hover:text-gray-600">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                    <form class="space-y-2" action="/assign-task" method="POST">
                                                    <input type="hidden" name="task_id" value="<?=$task->id?>">
                                                        <div class="flex items-center gap-2">
                                                            <select class="form-select text-sm" name="user_id">
                                                                <option value="" class="hidden">Select User</option>
                                                                <?php foreach($allUsers as $user): ?>
                                                                    <?php
                                                                        $assigneesIds = array_map(function ($assignee) {
                                                                            return $assignee->id;
                                                                        }, $task->assignees);
            
                                                                            if($user['id'] !== $project->user_id  && in_array($user['id'],$contributersIds) && !in_array($user['id'],$assigneesIds)) {
                                                                                echo "<option value='{$user['id']}'>{$user['firstname']}  {$user['lastname']}</option>";
                                                                            }
                                                                            ?>
                                                                <?php endforeach?>
                                                            </select>
                                                            <button type="submit" class="rounded-full flex justify-center items-center bg-gray-100 w-6 h-6 text-sm hover:bg-gray-300 transition-colors">+</button>
                                                        </div>
                                                    </form>
                                                    <div class="mt-3 space-y-2">
                                                    <?php foreach($task->assignees as $assignee): ?>
                                                        <div class="flex items-center justify-between text-sm">
                                                            <span><?=$assignee->firstname?> <?=$assignee->lastname?></span>
                                                            <form action="/unassign-task" method="POST">
                                                                <input type="hidden" name="user_id" value="<?=$assignee->id?>">
                                                                <input type="hidden" name="task_id" value="<?=$task->id?>">
                                                                <button class="rounded-full flex justify-center items-center bg-red-50 w-6 h-6 text-sm hover:bg-red-100 transition-colors">-</button>
                                                            </form>
                                                        </div>
                                                        <?php endforeach;?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <!-- Add Task Button and Form -->
                        
                    </div>
                    <?php if ($project->user_id === $_SESSION['user']['id']): ?>
                    <div class="task bg-gray-50 p-4 rounded shadow-sm hover:bg-gray-100 cursor-pointer mt-5" onclick="toggleTaskForm('review')">
                            <div class="add-task-btn">
                                <div class="flex items-center justify-center">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                </div>
                            </div>
                            <form class="task-form hidden mt-3" id="review-form" action="/add-task" method="POST" onclick="event.stopPropagation()">
                                <input type="hidden" name="project_id" value="<?= $project->id ?>">
                   
                                <input type="hidden" name="status" value="review">
                                <div class="mb-2">
                                    <input type="text" name="title" placeholder="Task title" required
                                        class="w-full px-2 py-1 text-sm border rounded">
                                </div>
                                <div class="mb-2">
                                    <textarea name="description" placeholder="Description" rows="2"
                                        class="w-full px-2 py-1 text-sm border rounded"></textarea>
                                </div>
                                <div class="flex justify-end space-x-2">
                                    <button type="button" onclick="toggleTaskForm('review')"
                                        class="px-2 py-1 text-xs text-gray-600 hover:text-gray-800">Cancel</button>
                                    <button type="submit"
                                        class="px-2 py-1 text-xs text-white bg-blue-500 rounded hover:bg-blue-600">Add</button>
                                </div>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Done Column -->
                <div class="bg-white rounded-lg shadow p-4">
                    <h2 class="font-semibold text-gray-900 mb-4">Done</h2>
                    <div id="done" class="space-y-3 min-h-10">
                        <?php foreach ($tasks as $task): ?>
                            <?php if ($task->status === 'done'): ?>
                                <div draggable="true" class="task <?=$task->tag === 'bug' ? 'bg-red-50' : ($task->tag === 'feature' ? 'bg-green-50' :'bg-gray-50')?> p-4 rounded shadow-sm relative" data-id="<?= $task->id ?>">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="font-medium"><?= htmlspecialchars($task->title) ?></h3>
                                            <p class="text-sm text-gray-600"><?= htmlspecialchars($task->description) ?></p>
                                            <?php if(!empty($task->assignees)): ?>
                                            <div class="flex flex-wrap gap-1 mt-2">
                                                <?php foreach($task->assignees as $assignee): ?>
                                                <span class="text-xs px-2 py-0.5 <?= $assignee->id === $_SESSION['user']['id'] ? 'bg-green-800 text-white' : 'bg-gray-100 text-gray-500' ?> rounded-full"><?=$assignee->firstname?></span>
                                                <?php endforeach; ?>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="relative">
                                            <button onclick="toggleMenu('<?= $task->id ?>')" class="btn-more p-1 hover:bg-gray-200 rounded">
                                                <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 24 24">
                                                    <circle cx="12" cy="6" r="2"/>
                                                    <circle cx="12" cy="12" r="2"/>
                                                    <circle cx="12" cy="18" r="2"/>
                                                </svg>
                                            </button>
                                            <div id="menu-<?= $task->id ?>" class="task-menu absolute right-0 top-8 bg-white shadow-lg rounded-md border border-gray-200 w-32 z-50 transform translate-y-2 opacity-0 invisible transition-all duration-200 ease-in-out">
                                                <div class="py-1">
                                                    <button onclick="showTagForm('<?= $task->id ?>')" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Change Tag</button>
                                                    <button onclick="showAssignForm('<?= $task->id ?>')" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Assign Task</button>
                                                    <button onclick="showDeleteForm('<?= $task->id ?>')" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Delete</button>
                                                </div>
                                            </div>
                                            <div id="delete-form-<?= $task->id ?>" class="delete-confirm absolute right-0 top-8 bg-white shadow-lg rounded-md border border-gray-200 z-50 transform translate-y-2 opacity-0 invisible transition-all duration-200 ease-in-out">
                                                <div class="p-3">
                                                    <p class="text-sm text-gray-600 mb-3">Are you sure?</p>
                                                    <form class="space-y-2" action="/delete-task" method="POST">
                                                        <input type="hidden" name="task_id" value="<?= $task->id ?>">
                                                        <div class="flex space-x-2">
                                                            <button type="submit" class="flex-1 px-3 py-1 text-sm text-white bg-red-600 rounded hover:bg-red-700">Confirm</button>
                                                            <button type="button" onclick="closeDeleteForm('<?= $task->id ?>')" class="flex-1 px-3 py-1 text-sm text-gray-600 bg-gray-100 rounded hover:bg-gray-200">Cancel</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <div id="tag-form-<?= $task->id ?>" class="tag-form absolute right-0 top-8 bg-white shadow-lg rounded-md border border-gray-200 z-50 transform translate-y-2 opacity-0 invisible transition-all duration-200 ease-in-out">
                                                <div class="p-3">
                                                    <p class="text-sm text-gray-600 mb-3">Select Tag</p>
                                                    <form class="space-y-2" action="/change-task-tag" method="POST">
                                                        <input type="hidden" name="task_id" value="<?= $task->id ?>">
                                                        <select name="tag" class="w-full mb-3 text-sm border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                            <option value="basic" <?= $task->tag === 'basic' ? 'selected' : '' ?>>Basic</option>
                                                            <option value="feature" <?= $task->tag === 'feature' ? 'selected' : '' ?>>Feature</option>
                                                            <option value="bug" <?= $task->tag === 'bug' ? 'selected' : '' ?>>Bug</option>
                                                        </select>
                                                        <div class="flex space-x-2">
                                                            <button type="submit" class="flex-1 px-3 py-1 text-sm text-white bg-blue-600 rounded hover:bg-blue-700">Save</button>
                                                            <button type="button" onclick="closeTagForm('<?= $task->id ?>')" class="flex-1 px-3 py-1 text-sm text-gray-600 bg-gray-100 rounded hover:bg-gray-200">Cancel</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <div id="assign-form-<?= $task->id ?>" class="assign-form absolute right-0 top-8 bg-white shadow-lg rounded-md border border-gray-200 z-50 transform translate-y-2 opacity-0 invisible transition-all duration-200 ease-in-out">
                                                <div class="p-3">
                                                    <div class="flex justify-between items-center mb-3">
                                                        <p class="text-sm text-gray-600">Assign To</p>
                                                        <button onclick="closeAssignForm('<?= $task->id ?>')" class="text-gray-400 hover:text-gray-600">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                    <form class="space-y-2" action="/assign-task" method="POST">
                                                    <input type="hidden" name="task_id" value="<?=$task->id?>">
                                                        <div class="flex items-center gap-2">
                                                            <select class="form-select text-sm" name="user_id">
                                                                <option value="" class="hidden">Select User</option>
                                                                <?php foreach($allUsers as $user): ?>
                                                                    <?php
                                                                        $assigneesIds = array_map(function ($assignee) {
                                                                            return $assignee->id;
                                                                        }, $task->assignees);
            
                                                                            if($user['id'] !== $project->user_id  && in_array($user['id'],$contributersIds) && !in_array($user['id'],$assigneesIds)) {
                                                                                echo "<option value='{$user['id']}'>{$user['firstname']}  {$user['lastname']}</option>";
                                                                            }
                                                                            ?>
                                                                <?php endforeach?>
                                                            </select>
                                                            <button type="submit" class="rounded-full flex justify-center items-center bg-gray-100 w-6 h-6 text-sm hover:bg-gray-300 transition-colors">+</button>
                                                        </div>
                                                    </form>
                                                    <div class="mt-3 space-y-2">
                                                    <?php foreach($task->assignees as $assignee): ?>
                                                        <div class="flex items-center justify-between text-sm">
                                                            <span><?=$assignee->firstname?> <?=$assignee->lastname?></span>
                                                            <form action="/unassign-task" method="POST">
                                                                <input type="hidden" name="user_id" value="<?=$assignee->id?>">
                                                                <input type="hidden" name="task_id" value="<?=$task->id?>">
                                                                <button class="rounded-full flex justify-center items-center bg-red-50 w-6 h-6 text-sm hover:bg-red-100 transition-colors">-</button>
                                                            </form>
                                                        </div>
                                                        <?php endforeach;?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <!-- Add Task Button and Form -->
                        
                    </div>
                    <?php if ($project->user_id === $_SESSION['user']['id']): ?>
                    <div class="task bg-gray-50 p-4 rounded shadow-sm hover:bg-gray-100 cursor-pointer mt-5" onclick="toggleTaskForm('done')">
                            <div class="add-task-btn">
                                <div class="flex items-center justify-center">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                </div>
                            </div>
                            <form class="task-form hidden mt-3" id="done-form" action="/add-task" method="POST" onclick="event.stopPropagation()">
                                <input type="hidden" name="project_id" value="<?= $project->id ?>">
                         
                                <input type="hidden" name="status" value="done">
                                <div class="mb-2">
                                    <input type="text" name="title" placeholder="Task title" required
                                        class="w-full px-2 py-1 text-sm border rounded">
                                </div>
                                <div class="mb-2">
                                    <textarea name="description" placeholder="Description" rows="2"
                                        class="w-full px-2 py-1 text-sm border rounded"></textarea>
                                </div>
                                <div class="flex justify-end space-x-2">
                                    <button type="button" onclick="toggleTaskForm('done')"
                                        class="px-2 py-1 text-xs text-gray-600 hover:text-gray-800">Cancel</button>
                                    <button type="submit"
                                        class="px-2 py-1 text-xs text-white bg-blue-500 rounded hover:bg-blue-600">Add</button>
                                </div>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/dragula/3.7.3/dragula.min.js"></script>
    <script src="./js/kanban.js"></script>
