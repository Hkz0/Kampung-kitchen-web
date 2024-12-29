import { BASE_URL } from "config.js";
document.addEventListener('DOMContentLoaded', () => {
    loadNavbar().then(() => {
        checkUserLoginStatus();
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

async function checkUserLoginStatus() {
    try {
        const response = await fetch(`${BASE_URL}check_session.php`);
        const data = await response.json();
        
        if (data.logged_in) {
            document.getElementById('upload-recipe-link').style.display = 'block';
            document.getElementById('my-account-link').style.display = 'block';
            document.getElementById('login-link').style.display = 'none';
            document.getElementById('register-link').style.display = 'none';
        } else {
            document.getElementById('upload-recipe-link').style.display = 'none';
            document.getElementById('my-account-link').style.display = 'none';
            document.getElementById('login-link').style.display = 'block';
            document.getElementById('register-link').style.display = 'block';
        }
    } catch (error) {
        console.error('Error checking user login status:', error);
    }
}

