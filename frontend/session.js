async function checkSession() {
    try {
        const response = await fetch('../api/check_session.php');
        const data = await response.json();
        if (!data.logged_in) {
            window.location.href = 'login.html';
        }
    } catch (error) {
        console.error('Error checking session:', error);
    }
}

// Call checkSession on page load
document.addEventListener('DOMContentLoaded', checkSession);
