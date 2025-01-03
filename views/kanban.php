<?php
/** @var array $project */
/** @var array $tasks */

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($project->title) ?> - Kanban Board</title>
    
    <link href="./css/output.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dragula/3.7.3/dragula.min.css">
</head>
<body class="bg-gray-50">
    <div class="min-h-screen p-6 relative">
        <div class="max-w-7xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900"><?= htmlspecialchars($project->title) ?></h1>
                    <p class="text-gray-600"><?= htmlspecialchars($project->description) ?></p>
                </div>
                <div>
                <a href="/projects" class="text-blue-600 hover:text-blue-800 block">← Back to Projects</a>
                <button class="btn-manage-contributers text-gray-400 mt-2 hover:text-gray-700 transition-colors" >Manage contributers</button>
                    <div id="contributorForm"  class="hidden absolute top-1/2 left-1/2 translate-x-[-50%] translate-y-[-50%] bg-white rounded-lg shadow p-10 z-50">
                    <form class=" bg-white rounded-lg shadow p-4" action="" method="POST">
                        <div class="flex items-center gap-20">
                            <select class="form-select">
                                <option value="">Select Contributor</option>
                                <option value="contributor1">Contributor 1</option>
                                <option value="contributor2">Contributor 2</option>
                                <option value="contributor3">Contributor 3</option>
                            </select>
                            <button class="btn btn-success rounded-full flex justify-center items-center   bg-gray-100 w-8 h-8 text-lg hover:bg-gray-300 transition-colors">+</button>
                        </div>
                    </form>
                    <ul class=" mt-5 space-y-2">
                        <li class="flex w-full justify-between">
                            <p>contributer 1</p>
                            <button class="btn btn-success rounded-full flex justify-center items-center   bg-red-50 w-8 h-8 text-lg hover:bg-red-100 transition-colors">-</button>
                        </li>
                        <li class="flex w-full justify-between">
                            <p>contributer 1</p>
                            <button class="btn btn-success rounded-full flex justify-center items-center   bg-red-50 w-8 h-8 text-lg hover:bg-red-100 transition-colors">-</button>
                        </li>
                        <li class="flex w-full justify-between">
                            <p>contributer 1</p>
                            <button class="btn btn-success rounded-full flex justify-center items-center   bg-red-50 w-8 h-8 text-lg hover:bg-red-100 transition-colors">-</button>
                        </li>
                    </ul>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 items-start">
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
                                                <ul class="py-1">
                                                    <li><a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Details</a></li>
                                                    <li><a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Assign</a></li>
                                                    <li><button onclick="showTagForm('<?= $task->id ?>')" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Tag</button></li>
                                                    <li><button onclick="showDeleteForm('<?= $task->id ?>')" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 hover:text-red-700">Delete</button></li>
                                                </ul>
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
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <!-- Add Task Button and Form -->
                        
                    </div>
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
                                                <ul class="py-1">
                                                    <li><a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Details</a></li>
                                                    <li><a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Assign</a></li>
                                                    <li><button onclick="showTagForm('<?= $task->id ?>')" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Tag</button></li>
                                                    <li><button onclick="showDeleteForm('<?= $task->id ?>')" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 hover:text-red-700">Delete</button></li>
                                                </ul>
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
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <!-- Add Task Button and Form -->
                        
                    </div>
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
                                                <ul class="py-1">
                                                    <li><a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Details</a></li>
                                                    <li><a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Assign</a></li>
                                                    <li><button onclick="showTagForm('<?= $task->id ?>')" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Tag</button></li>
                                                    <li><button onclick="showDeleteForm('<?= $task->id ?>')" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 hover:text-red-700">Delete</button></li>
                                                </ul>
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
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <!-- Add Task Button and Form -->
                        
                    </div>
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
                                                <ul class="py-1">
                                                    <li><a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Details</a></li>
                                                    <li><a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Assign</a></li>
                                                    <li><button onclick="showTagForm('<?= $task->id ?>')" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Tag</button></li>
                                                    <li><button onclick="showDeleteForm('<?= $task->id ?>')" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 hover:text-red-700">Delete</button></li>
                                                </ul>
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
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <!-- Add Task Button and Form -->
                        
                    </div>
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
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/dragula/3.7.3/dragula.min.js"></script>
    <script src="./js/kanban.js"></script>
</body>
</html>
