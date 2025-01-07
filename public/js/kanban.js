document.addEventListener('DOMContentLoaded', function() {
    const contributorForm = document.getElementById('contributorForm');

    // Get all containers
    const containers = [
        document.querySelector('#todo'),
        document.querySelector('#doing'),
        document.querySelector('#review'),
        document.querySelector('#done')
    ];

    // Initialize dragula with containers and a moves function
    var drake = dragula(containers, {
        moves: function(el, container, handle) {
            // Only allow dragging of task elements (those with data-id)
            return el.hasAttribute('data-id');
        }
    });
    drake.on('drag',()=>{
        contributorForm.classList.add('hidden');
        
        const allForms = document.querySelectorAll('.task-menu, .delete-confirm, .tag-form, .assign-form');
        allForms.forEach(menu => {
            menu.classList.add('opacity-0', 'invisible', 'translate-y-2');
        });
        
        
    })
    drake.on('drop', function(el, target, source) {
        const taskId = el.dataset.id;
        const newStatus = target.id;

        fetch('/update-task-status', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `task_id=${taskId}&status=${newStatus}`
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                drake.cancel(true);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            drake.cancel(true);
        });
    });
});

const allMenus = document.querySelectorAll('.task-menu');

// Function to hide all toggleable forms
function hideToggleableForms() {
    const forms = document.querySelectorAll('.task-menu, .delete-confirm, .tag-form, .assign-form');
    forms.forEach(form => {
        form.classList.add('opacity-0', 'invisible', 'translate-y-2');
    });
}


// Toggle task form
function toggleTaskForm(status) {
    const forms = document.querySelectorAll('.task-form');
    const form = document.getElementById(status + '-form');
    [...forms].filter(form=>form.id !== status + '-form').forEach(form => {
        form.classList.add('hidden');
    });
    form.classList.toggle('hidden');
}

// Toggle menu
function toggleMenu(taskId) {
    const menu = document.getElementById(`menu-${taskId}`);
    
    // Close all other menus
    allMenus.forEach(m => {
        if (m !== menu) {
            m.classList.add('opacity-0', 'invisible', 'translate-y-2');
        }
    });
    
    // Toggle current menu
    menu.classList.toggle('opacity-0');
    menu.classList.toggle('invisible');
    menu.classList.toggle('translate-y-2');
}

// Close menus when clicking outside
document.addEventListener('click', function(event) {
    if (!event.target.closest('.btn-more') && !event.target.closest('.tag-form') && !event.target.closest('.delete-confirm') && !event.target.closest('.assign-form')) {               
        const allForms = document.querySelectorAll('.task-menu, .delete-confirm, .tag-form, .assign-form');
        allForms.forEach(menu => {
            menu.classList.add('opacity-0', 'invisible', 'translate-y-2');
        });
    }
});

function showDeleteForm(taskId) {
    const menu = document.getElementById(`menu-${taskId}`);
    const deleteForm = document.getElementById(`delete-form-${taskId}`);
    
    // Hide menu
    menu.classList.add('opacity-0', 'invisible', 'translate-y-2');
    
    // Show delete form
    deleteForm.classList.remove('opacity-0', 'invisible', 'translate-y-2');
    
    // Prevent event bubbling
    event.stopPropagation();
}

function closeDeleteForm(taskId) {
    const deleteForm = document.getElementById(`delete-form-${taskId}`);
    deleteForm.classList.add('opacity-0', 'invisible', 'translate-y-2');
    event.stopPropagation();
}

function showTagForm(taskId) {
    const menu = document.getElementById(`menu-${taskId}`);
    const tagForm = document.getElementById(`tag-form-${taskId}`);
    
    // Hide menu
    menu.classList.add('opacity-0', 'invisible', 'translate-y-2');
    
    // Show tag form
    tagForm.classList.remove('opacity-0', 'invisible', 'translate-y-2');
    
    // Prevent event bubbling
    event.stopPropagation();
}

function closeTagForm(taskId) {
    const tagForm = document.getElementById(`tag-form-${taskId}`);
    tagForm.classList.add('opacity-0', 'invisible', 'translate-y-2');
    event.stopPropagation();
}

// Show/hide assign form
function showAssignForm(taskId) {
    const menu = document.getElementById(`menu-${taskId}`);
    const assignForm = document.getElementById(`assign-form-${taskId}`);
    
    menu.classList.add('opacity-0', 'invisible', 'translate-y-2');
    assignForm.classList.remove('opacity-0', 'invisible', 'translate-y-2');
    event.stopPropagation();
}

function closeAssignForm(taskId) {
    const assignForm = document.getElementById(`assign-form-${taskId}`);
    assignForm.classList.add('opacity-0', 'invisible', 'translate-y-2');
    event.stopPropagation();
}

const contributorForm = document.getElementById('contributorForm');
const contributorButton = document.querySelector('.btn-manage-contributors');

//////////////////////



contributorButton.addEventListener('click', function(e) {
    contributorForm.classList.toggle('hidden');
});




////////////////////

document.addEventListener('click', function(e) {
    if(!e.target.closest('#contributorForm') && !e.target.matches('.btn-manage-contributors'))
    contributorForm.classList.add('hidden');
});

const selectContributor = document.querySelector('.select-contributor');
const btnAddContributor = document.querySelector('.btn-add-contributor');
const selectAssignees = document.querySelectorAll('.assign-form select');
const btnAddAssignees = document.querySelectorAll('.assign-form button[type="submit"]');

selectContributor.addEventListener('change', function() {
    if(this.value !== '') 
        btnAddContributor.classList.remove('opacity-0', 'invisible');
    else 
        btnAddContributor.classList.add('opacity-0', 'invisible');
});

// Hide all assignment form add buttons initially
btnAddAssignees.forEach(btn => {
    btn.classList.add('opacity-0', 'invisible');
});

// Add change event listeners to all assignment selects
selectAssignees.forEach((select, index) => {
    select.addEventListener('change', function() {
        if(this.value !== '') 
            btnAddAssignees[index].classList.remove('opacity-0', 'invisible');
        else 
            btnAddAssignees[index].classList.add('opacity-0', 'invisible');
    });
});