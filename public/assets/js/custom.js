function markNotificationAsRead(id) {
    fetch('/notification/mark-read', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ id: id })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateNotificationBadge();
        }
    });
}

function markAllNotificationsAsRead() {
    fetch('/notification/mark-all-read', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateNotificationBadge();
            document.querySelectorAll('.notification-item').forEach(item => {
                item.classList.remove('unread');
            });
        }
    });
}

function updateNotificationBadge() {
    fetch('/notification/unread-count')
    .then(response => response.json())
    .then(data => {
        const badge = document.querySelector('.navbar-badge');
        if (data.count > 0) {
            badge.textContent = data.count;
            badge.style.display = 'inline';
        } else {
            badge.style.display = 'none';
        }
    });
}