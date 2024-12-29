import { BASE_URL } from "config.js";
document.getElementById('register-form').addEventListener('submit', async function(event) {
    event.preventDefault();
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    const email = document.getElementById('email').value;

    // Basic validation
    if (!username || !password) {
        alert('Please fill in all fields');
        return;
    }

    try {
        const response = await fetch(`${BASE_URL}register.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ username, password, email })
        });

        const responseText = await response.text();
        console.log('Raw response:', responseText);

        let result;
        try {
            result = JSON.parse(responseText);
        } catch (e) {
            console.error('Failed to parse JSON:', responseText);
            throw new Error('Invalid JSON response from server');
        }

        if (result.success) {
            alert('Registration successful! Please login.');
            window.location.href = 'login.html';
        } else {
            alert(result.message || 'Registration failed');
        }
    } catch (error) {
        console.error('Error during registration:', error);
        alert('An error occurred during registration. Please try again.');
    }
});
