document.addEventListener('DOMContentLoaded', () => {
    loadUserData();
    setupEventListeners();
});

async function loadUserData() {
    try {
        const response = await fetch('../api/user_data.php');
        const data = await response.json();

        if (data.success) {
            displayUserData(data.user);
            loadUserRecipes(data.user.id);
        } else {
            window.location.href = 'login.html';
        }
    } catch (error) {
        console.error('Error loading user data:', error);
        alert('Error loading user data. Please try again.');
    }
}

function displayUserData(user) {
    document.getElementById('username').textContent = user.username;
    document.getElementById('email').textContent = user.email;
    
    // Also populate the edit form
    document.getElementById('edit-username').value = user.username;
    document.getElementById('edit-email').value = user.email;
}

async function loadUserRecipes(userId) {
    try {
        const response = await fetch(`../api/user_recipes.php?user_id=${userId}`);
        const data = await response.json();

        if (data.success) {
            displayUserRecipes(data.recipes);
        }
    } catch (error) {
        console.error('Error loading user recipes:', error);
    }
}

function displayUserRecipes(recipes) {
    const container = document.getElementById('user-recipes');
    container.innerHTML = '';

    if (recipes.length === 0) {
        container.innerHTML = '<p>No recipes posted yet.</p>';
        return;
    }

    recipes.forEach(recipe => {
        const recipeCard = createRecipeCard(recipe);
        container.appendChild(recipeCard);
    });
}

function createRecipeCard(recipe) {
    const col = document.createElement('div');
    col.className = 'col-md-4 mb-3';
    col.innerHTML = `
        <div class="card">
            <img src="${recipe.image_url}" class="card-img-top" alt="${recipe.title}">
            <div class="card-body">
                <h5 class="card-title">${recipe.title}</h5>
                <p class="card-text">${recipe.description}</p>
                <a href="recipe.html?id=${recipe.id}" class="btn btn-primary">View Recipe</a>
            </div>
        </div>
    `;
    return col;
}

function setupEventListeners() {
    // Edit Profile Modal
    const editProfileBtn = document.getElementById('edit-profile-btn');
    const saveProfileBtn = document.getElementById('save-profile-btn');
    
    editProfileBtn.addEventListener('click', () => {
        const modal = new bootstrap.Modal(document.getElementById('editProfileModal'));
        modal.show();
    });

    saveProfileBtn.addEventListener('click', saveProfileChanges);

    // Change Password Modal
    const changePasswordBtn = document.getElementById('change-password-btn');
    const savePasswordBtn = document.getElementById('save-password-btn');

    changePasswordBtn.addEventListener('click', () => {
        const modal = new bootstrap.Modal(document.getElementById('changePasswordModal'));
        modal.show();
    });

    savePasswordBtn.addEventListener('click', changePassword);
}

async function saveProfileChanges() {
    const username = document.getElementById('edit-username').value;
    const email = document.getElementById('edit-email').value;

    try {
        const response = await fetch('../api/update_profile.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ username, email }),
            credentials: 'include'
        });

        const data = await response.json();

        if (data.success) {
            alert('Profile updated successfully');
            loadUserData();
            bootstrap.Modal.getInstance(document.getElementById('editProfileModal')).hide();
        } else {
            alert('Failed to update profile: ' + data.message);
        }
    } catch (error) {
        console.error('Error updating profile:', error);
        alert('Error updating profile. Please try again.');
    }
}

async function changePassword() {
    const currentPassword = document.getElementById('current-password').value;
    const newPassword = document.getElementById('new-password').value;
    const confirmPassword = document.getElementById('confirm-password').value;

    if (newPassword !== confirmPassword) {
        alert('New passwords do not match');
        return;
    }

    try {
        const response = await fetch('../api/change_password.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                current_password: currentPassword,
                new_password: newPassword
            }),
            credentials: 'include'
        });

        const data = await response.json();

        if (data.success) {
            alert('Password changed successfully');
            document.getElementById('change-password-form').reset();
            bootstrap.Modal.getInstance(document.getElementById('changePasswordModal')).hide();
        } else {
            alert('Failed to change password: ' + data.message);
        }
    } catch (error) {
        console.error('Error changing password:', error);
        alert('Error changing password. Please try again.');
    }
} 