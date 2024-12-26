async function login() {
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;

    // MAKE SURE TO CHANGE THE URL TO THE CORRECT API ENDPOINT
    const response = await fetch('http://localhost/Kampung-kitchen-web/api/login.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ username, password })
    });

    const result = await response.json();

    if (result.success) {
        document.getElementById('message').innerText = 'Login successful!';
        // Redirect or handle successful login
    } else {
        document.getElementById('message').innerText = 'Invalid username or password';
    }
}
