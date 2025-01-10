<?php
/**
 * @var array $allRoles
 * @var array $allPermissions
 */
?>
<div class="min-h-screen p-6 bg-gray-50">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Admin Dashboard</h1>
        
        <!-- Roles and Permissions Management Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Roles Section -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold">Roles Management</h2>
                    <button onclick="toggleAddRoleForm()" class="flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Add Role
                    </button>
                </div>

                <!-- Add Role Form (Hidden by default) -->
                <div id="addRoleForm" class="hidden mb-6">
                    <form action="/add-role" method="POST" class="bg-gray-50 rounded-lg p-4 space-y-4">
                        <div>
                            <label for="role_name" class="block text-sm font-medium text-gray-700">Role Name</label>
                            <input type="text" name="role_name" id="role_name" required
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>
                        <div class="flex justify-end space-x-3">
                            <button type="button" onclick="toggleAddRoleForm()" 
                                    class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit" 
                                    class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                Add Role
                            </button>
                        </div>
                    </form>
                </div>
                
                <?php if (empty($allRoles)): ?>
                    <p class="text-gray-500 text-center py-4">No roles found</p>
                <?php else: ?>
                    <!-- Role Navigation -->

                    <div class="flex overflow-x-auto mb-6 bg-gray-50 rounded-lg p-2 space-x-2">
                        <input  type="hidden" class="hidden current-role" value = "<?=$_SESSION['current_role'] ?? ''?>">
                        <?php unset($_SESSION['current_role']);?>
                        <?php foreach ($allRoles as $role): ?>
                            <button 
                                onclick="showRole('<?= htmlspecialchars($role->name) ?>')"
                                data-role="<?= htmlspecialchars($role->name) ?>"
                                class="role-nav-item flex-shrink-0 px-4 py-2 rounded-md text-sm font-medium text-gray-600 hover:bg-gray-100 transition">
                                <?= htmlspecialchars($role->name) ?>
                            </button>
                        <?php endforeach; ?>
                    </div>

                    <!-- Roles Cards Container -->
                    <div class="space-y-6">
                        <?php foreach ($allRoles as $role): ?>
                            <div id="role-<?= htmlspecialchars($role->name) ?>" class="role-card hidden border rounded-lg p-4">
                                <!-- Role Header -->
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-medium text-gray-900"><?= htmlspecialchars($role->name) ?></h3>
                                    <div>
                                        <button onclick="toggleDeleteRoleConfirm('<?= htmlspecialchars($role->name) ?>')" 
                                                class="px-3 py-1 text-sm text-red-600 hover:text-red-800 transition">
                                            Delete Role
                                        </button>
                                    </div>
                                </div>

                                <!-- Delete Role Confirmation (Hidden by default) -->
                                <div id="deleteRole-<?= htmlspecialchars($role->name) ?>" class="hidden mb-4">
                                    <form action="/delete-role" method="POST" class="bg-red-50 rounded-lg p-4">
                                        <input type="hidden" name="role_name" value="<?= htmlspecialchars($role->name) ?>">
                                        <p class="text-sm text-red-600 mb-3">Are you sure you want to delete this role? This action cannot be undone.</p>
                                        <div class="flex justify-end space-x-3">
                                            <button type="button" 
                                                    onclick="toggleDeleteRoleConfirm('<?= htmlspecialchars($role->name) ?>')"
                                                    class="px-3 py-1 text-sm border border-gray-300 rounded text-gray-700 bg-white hover:bg-gray-50">
                                                Cancel
                                            </button>
                                            <button type="submit" 
                                                    class="px-3 py-1 text-sm bg-red-600 text-white rounded hover:bg-red-700">
                                                Confirm Delete
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                
                                <!-- Role Permissions -->
                                <div class="flex flex-col">
                                    <!-- Header -->
                                    <div class="flex bg-gray-50 p-3 rounded-t-lg">
                                        <div class="flex-1 font-medium text-gray-600">Permission Name</div>
                                        <div class="w-24 text-center font-medium text-gray-600">Status</div>
                                        <div class="w-24 text-center font-medium text-gray-600">Action</div>
                                    </div>
                                    
                                    <!-- Permission List -->
                                    <?php foreach ($allPermissions as $permission): ?>
                                        <div class="flex items-center border-t p-3">
                                            <div class="flex-1 text-gray-900">
                                                <?= htmlspecialchars($permission->name) ?>
                                            </div>
                                            <div class="w-24 text-center">
                                                <?php 
                                                $hasPermission = in_array($permission->name, $role->permissions);
                                                $statusClass = $hasPermission ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800';
                                                ?>
                                                <span class="px-2 py-1 rounded-full text-xs <?= $statusClass ?>">
                                                    <?= $hasPermission ? 'Granted' : 'Not Granted' ?>
                                                </span>
                                            </div>
                                            <div class="w-24 text-center">
                                                <?php if ($hasPermission): ?>
                                                    <form action="/remove-permission" method="POST" class="inline">
                                                        <input type="hidden" name="role_name" value="<?= htmlspecialchars($role->name) ?>">
                                                        <input type="hidden" name="permission_name" value="<?= htmlspecialchars($permission->name) ?>">
                                                        <button type="submit" class="text-sm text-indigo-600 hover:text-indigo-900">
                                                            Remove
                                                        </button>
                                                    </form>
                                                <?php else: ?>
                                                    <form action="/add-permission" method="POST" class="inline">
                                                        <input type="hidden" name="role_name" value="<?= htmlspecialchars($role->name) ?>">
                                                        <input type="hidden" name="permission_name" value="<?= htmlspecialchars($permission->name) ?>">
                                                        <button type="submit" class="text-sm text-indigo-600 hover:text-indigo-900">
                                                            Grant
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Permissions Section -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Permissions Management</h2>
                <div class="flex flex-col space-y-4">
                    <?php if (empty($allPermissions)): ?>
                        <p class="text-gray-500 text-center py-4">No permissions found</p>
                    <?php else: ?>
                        <!-- Permissions Header -->
                        <div class="flex bg-gray-50 p-3 rounded-lg">
                            <div class="flex-1 font-medium text-gray-600">Permission Name</div>
                            <div class="w-24 text-right font-medium text-gray-600">Action</div>
                        </div>
                        
                        <!-- Permissions List -->
                        <?php foreach ($allPermissions as $permission): ?>
                            <div class="flex items-center border-b last:border-b-0 p-3">
                                <div class="flex-1 text-gray-900">
                                    <?= htmlspecialchars($permission->name) ?>
                                </div>
                                <div class="w-24 text-right">
                                    <form action="/delete-permission" method="POST" class="inline">
                                        <input type="hidden" name="permission_name" value="<?= htmlspecialchars($permission->name) ?>">
                                        <button type="submit" class="text-sm text-red-600 hover:text-red-800">Delete</button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <!-- Add Permission Button -->
                        <div class="pt-4">
                            <button onclick="toggleAddPermissionForm()" class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                <span class="ml-2">Add Permission</span>
                            </button>
                        </div>

                        <!-- Add Permission Form (Hidden by default) -->
                        <div id="addPermissionForm" class="hidden pt-4">
                            <form action="/create-permission" method="POST" class="space-y-4">
                                <div>
                                    <label for="permissionName" class="block text-sm font-medium text-gray-700">Permission Name</label>
                                    <input type="text" name="permission_name" id="permission_name" required
                                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                </div>
                                <div class="flex justify-end space-x-3">
                                    <button type="button" onclick="toggleAddPermissionForm()" 
                                            class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                        Cancel
                                    </button>
                                    <button type="submit" 
                                            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                        Add Permission
                                    </button>
                                </div>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="./js/admin-dashboard.js"></script>
