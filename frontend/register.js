async function register() {
    const username = document.getElementById('username').value;
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    // MAKE SURE TO CHANGE THE URL TO THE CORRECT API ENDPOINT
    const response = await fetch('http://localhost/Kampung-kitchen-web/api/register.php', { //
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ username, email, password })
    });

    const result = await response.json();

    if (result.success) {
        document.getElementById('message').innerText = 'Registration successful!';
        // Redirect or handle successful registration
    } else {
        document.getElementById('message').innerText = 'Registration failed: ' + result.message;
    }
}
