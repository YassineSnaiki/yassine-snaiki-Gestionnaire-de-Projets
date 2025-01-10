document.addEventListener('DOMContentLoaded', function() {
    // Task Status Distribution Chart
    const statusCtx = document.getElementById('taskStatusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['To Do', 'Doing', 'Review', 'Done'],
            datasets: [{
                data: [
                    document.querySelector('#todo-count').value,
                    document.querySelector('#doing-count').value,
                    document.querySelector('#review-count').value,
                    document.querySelector('#done-count').value
                ],
                backgroundColor: [
                    '#EF4444', // red for todo
                    '#F59E0B', // yellow for doing
                    '#3B82F6', // blue for review
                    '#10B981'  // green for done
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 12,
                        padding: 15,
                        font: {
                            size: 11
                        }
                    }
                },
                title: {
                    display: true,
                    text: 'Task Status Distribution',
                    font: {
                        size: 14
                    },
                    padding: {
                        bottom: 10
                    }
                }
            }
        }
    });

    // Tag Distribution Chart
    const tagCtx = document.getElementById('tagDistributionChart').getContext('2d');
    const tags = JSON.parse(document.querySelector('#task-tags').value);
    const uniqueTags = [...new Set(tags)];
    
    const tagCounts = uniqueTags.map(tag => {
        return tags.filter(t => t === tag).length;
    });

    new Chart(tagCtx, {
        type: 'bar',
        data: {
            labels: uniqueTags,
            datasets: [{
                label: 'Tasks per Tag',
                data: tagCounts,
                backgroundColor: '#6366F1'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                title: {
                    display: true,
                    text: 'Task Distribution by Tag',
                    font: {
                        size: 14
                    },
                    padding: {
                        bottom: 10
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        font: {
                            size: 11
                        }
                    }
                },
                x: {
                    ticks: {
                        font: {
                            size: 11
                        }
                    }
                }
            }
        }
    });
});
