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

