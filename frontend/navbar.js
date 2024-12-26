document.addEventListener("DOMContentLoaded", function() {
    fetch('navbar.html')
        .then(response => response.text())
        .then(data => {
            document.getElementById('navbar-container').innerHTML = data;
            checkLoginStatus();
        });
});

function checkLoginStatus() {
    fetch('/api/get_user.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('login-nav').style.display = 'none';
                document.getElementById('register-nav').style.display = 'none';
                document.getElementById('account-nav').style.display = 'block';
                document.getElementById('logout-nav').style.display = 'block';
            } else {
                document.getElementById('login-nav').style.display = 'block';
                document.getElementById('register-nav').style.display = 'block';
                document.getElementById('account-nav').style.display = 'none';
                document.getElementById('logout-nav').style.display = 'none';
            }
        });
}
