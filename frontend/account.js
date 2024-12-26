document.addEventListener('DOMContentLoaded', function() {
    fetch('/api/get_user.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('username').textContent = data.user.username;
                document.getElementById('email').textContent = data.user.email;
            } else {
                window.location.href = '/frontend/login.html';
            }
        });
});

function logout() {
    fetch('/api/logout.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '/frontend/login.html';
            }
        });
}
