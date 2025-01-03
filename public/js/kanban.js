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
        const allForms = document.querySelectorAll('.task-menu, .delete-confirm, .tag-form');
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
// Toggle task form
function toggleTaskForm(status) {
    const forms = document.querySelectorAll('.task-form');
    const form = document.getElementById(status + '-form');
    console.log(forms);  
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
    if (!event.target.closest('.btn-more') && !event.target.closest('.tag-form') && !event.target.closest('.delete-confirm')) {               
        const allForms = document.querySelectorAll('.task-menu, .delete-confirm, .tag-form');
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

const contributorForm = document.getElementById('contributorForm');
const contributorButton = document.querySelector('.btn-manage-contributers');

contributorButton.addEventListener('click', function(e) {
    contributorForm.classList.toggle('hidden');
});
document.addEventListener('click', function(e) {
    if(!e.target.closest('#contributorForm') && !e.target.matches('.btn-manage-contributers'))
    contributorForm.classList.add('hidden');
});

