// Role navigation and display management
function showRole(roleName) {
    // Hide all role cards
    document.querySelectorAll('.role-card').forEach(card => {
        card.classList.add('hidden');
    });

    // Show selected role card
    const selectedCard = document.getElementById(`role-${roleName}`);
    if (selectedCard) {
        selectedCard.classList.remove('hidden');
    }

    // Update navigation active state
    document.querySelectorAll('.role-nav-item').forEach(item => {
        item.classList.remove('bg-blue-500', 'text-white');
        item.classList.add('text-gray-600', 'hover:bg-gray-100');
    });

    const activeNavItem = document.querySelector(`[data-role="${roleName}"]`);
    if (activeNavItem) {
        activeNavItem.classList.remove('text-gray-600', 'hover:bg-gray-100');
        activeNavItem.classList.add('bg-blue-500', 'text-white');
    }
}

// Toggle Add Permission Form
function toggleAddPermissionForm() {
    const form = document.getElementById('addPermissionForm');
    form.classList.toggle('hidden');
}

// Toggle Add Role Form
function toggleAddRoleForm() {
    const form = document.getElementById('addRoleForm');
    form.classList.toggle('hidden');
}

// Toggle Delete Role Confirmation
function toggleDeleteRoleConfirm(roleName) {
    const confirmForm = document.getElementById(`deleteRole-${roleName}`);
    confirmForm.classList.toggle('hidden');
}

// Initialize with the first role when the page loads
document.addEventListener('DOMContentLoaded', function() {
    const currRole = document.querySelector('.current-role').value;
    if(currRole !== ''){
        showRole(currRole);
        return;
    }

    const firstRoleNav = document.querySelector('.role-nav-item');

    if (firstRoleNav) {
        const firstRoleName = firstRoleNav.getAttribute('data-role');
        showRole(firstRoleName);
    }
});
