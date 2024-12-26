document.addEventListener('DOMContentLoaded', () => {
    loadNavbar();
});

function loadNavbar() {
    fetch('navbar.html')
        .then(response => response.text())
        .then(data => {
            const navbarContainer = document.getElementById('navbar-container');
            navbarContainer.innerHTML = `
                <nav class="navbar navbar-expand-sm navbar-dark" style="background-color: lightblue;">
                    ${data}
                </nav>
            `;
        })
        .catch(error => {
            console.error('Error loading the navbar:', error);
        });
}
