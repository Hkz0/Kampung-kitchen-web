document.addEventListener('DOMContentLoaded', () => {
    loadNavbar();
    // We need to wait for the navbar to load before setting up the logout handler
    loadNavbar().then(() => {
        setupLogoutHandler();
    });
});

function loadNavbar() {
    return fetch('navbar.html')
        .then(response => response.text())
        .then(data => {
            const navbarContainer = document.getElementById('navbar-container');
            navbarContainer.innerHTML = data;
        })
        .catch(error => {
            console.error('Error loading the navbar:', error);
        });
}

function setupLogoutHandler() {
    console.log('Setting up logout handler');
    const logoutBtn = document.getElementById('logout-btn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function(event) {
            console.log('Logout button clicked');
            event.preventDefault();
            handleLogout();
        });
    } else {
        console.error('Logout button not found');
    }
}

async function handleLogout() {
    try {
        console.log('Attempting logout...');
        const response = await fetch('../api/logout.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            // Add credentials to ensure cookies are sent
            credentials: 'include'
        });

        const data = await response.json();
        console.log('Logout response:', data);
        
        if (data.success) {
            window.location.href = 'login.html';
        } else {
            alert('Logout failed: ' + (data.message || 'Unknown error'));
        }
    } catch (error) {
        console.error('Error during logout:', error);
        alert('Error during logout. Please try again.');
    }
}
