import { BASE_URL } from "./config.js";

document.addEventListener('DOMContentLoaded', () => {
    loadUserData();
    setupEventListeners();
});

async function loadUserData() {
    try {
        const response = await fetch(`${BASE_URL}user_data.php`);
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

async function loadUserRecipes() {
    try {
        const response = await fetch(`${BASE_URL}user_recipes.php`, {
            method: 'GET',
            credentials: 'include',
            headers: {
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();

        if (data.success) {
            displayUserRecipes(data.recipes);
        } else {
            throw new Error(data.message || 'Failed to load recipes');
        }
    } catch (error) {
        console.error('Error loading user recipes:', error);
        document.getElementById('user-recipes').innerHTML = 
            '<p class="alert alert-danger">Unable to load recipes. Please try again later.</p>';
    }
}

function displayUserRecipes(recipes) {
    const container = document.getElementById('user-recipes');
    container.innerHTML = '';

    if (!recipes || recipes.length === 0) {
        container.innerHTML = '<p class="text-center">No recipes posted yet.</p>';
        return;
    }

    // Create a row to hold the recipes
    const row = document.createElement('div');
    row.className = 'row row-cols-1 row-cols-md-3 g-4';
    container.appendChild(row);

    recipes.forEach(recipe => {
        const col = document.createElement('div');
        col.className = 'col';
        
        col.innerHTML = `
            <div class="card h-100">
                <img src="${recipe.image_url || 'placeholder.jpg'}" 
                     class="card-img-top" 
                     alt="${recipe.title}"
                     style="height: 200px; object-fit: cover;"
                     onerror="this.src='placeholder.jpg'">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">${recipe.title}</h5>
                    <p class="created-at text-muted">Created on: ${new Date(recipe.created_at).toLocaleDateString()}</p>
                    <div class="mt-auto">
                        <a href="recipe.html?recipe_id=${recipe.id}" class="btn btn-primary w-100 mb-2">View Recipe</a>
                        <button class="btn btn-warning w-100 mb-2 edit-recipe-btn" data-recipe-id="${recipe.id}">Edit</button>
                        <button class="btn btn-danger w-100 delete-recipe-btn" data-recipe-id="${recipe.id}">Delete</button>
                    </div>
                </div>
            </div>
        `;
        
        row.appendChild(col);
    });

    // Add event listeners for edit and delete buttons
    document.querySelectorAll('.edit-recipe-btn').forEach(button => {
        button.addEventListener('click', () => editRecipe(button.dataset.recipeId));
    });

    document.querySelectorAll('.delete-recipe-btn').forEach(button => {
        button.addEventListener('click', () => deleteRecipe(button.dataset.recipeId));
    });
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

    // Add logout handler
    const logoutBtn = document.getElementById('logout-btn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', async function(event) {
            event.preventDefault();
            try {
                const response = await fetch(`${BASE_URL}logout.php`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    credentials: 'include'
                });

                const data = await response.json();
                
                if (data.success) {
                    window.location.href = 'logout_success.html';
                } else {
                    alert('Logout failed: ' + (data.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error during logout:', error);
                alert('Error during logout. Please try again.');
            }
        });
    }

    // Add event listener for save recipe button
    document.getElementById('save-recipe-btn').addEventListener('click', saveRecipeChanges);
}

async function saveProfileChanges() {
    const username = document.getElementById('edit-username').value;
    const email = document.getElementById('edit-email').value;

    try {
        const response = await fetch(`${BASE_URL}update_profile.php`, {
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
        const response = await fetch(`${BASE_URL}change_password.php`, {
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

async function editRecipe(recipeId) {
    try {
        const response = await fetch(`${BASE_URL}recipe_details.php?recipe_id=${recipeId}`);
        const data = await response.json();
        
        document.getElementById('edit-recipe-id').value = data.recipe_id;
        document.getElementById('edit-name').value = data.name;
        document.getElementById('edit-description').value = data.description;
        document.getElementById('edit-instructions').value = data.instructions;
        document.getElementById('edit-ingredients').value = data.ingredients;
        document.getElementById('edit-prep-time').value = data.prep_time;
        document.getElementById('edit-cook-time').value = data.cook_time;
        document.getElementById('edit-servings').value = data.servings;
        document.getElementById('edit-image-url').value = data.image_url;
        document.getElementById('edit-ethnic-id').value = data.ethnic_id;

        const modal = new bootstrap.Modal(document.getElementById('editRecipeModal'));
        modal.show();
    } catch (error) {
        console.error('Error loading recipe details:', error);
        alert('Error loading recipe details. Please try again.');
    }
}

async function deleteRecipe(recipeId) {
    if (!confirm('Are you sure you want to delete this recipe?')) {
        return;
    }

    try {
        const response = await fetch(`${BASE_URL}delete_recipe.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ recipe_id: recipeId }),
            credentials: 'include'
        });

        const data = await response.json();

        if (data.success) {
            alert('Recipe deleted successfully');
            loadUserRecipes(); // Refresh the list of recipes
        } else {
            alert('Failed to delete recipe: ' + data.message);
        }
    } catch (error) {
        console.error('Error deleting recipe:', error);
        alert('Error deleting recipe. Please try again.');
    }
}

async function saveRecipeChanges() {
    const recipeId = document.getElementById('edit-recipe-id').value;
    const name = document.getElementById('edit-name').value;
    const description = document.getElementById('edit-description').value;
    const instructions = document.getElementById('edit-instructions').value;
    const ingredients = document.getElementById('edit-ingredients').value;
    const prepTime = document.getElementById('edit-prep-time').value;
    const cookTime = document.getElementById('edit-cook-time').value;
    const servings = document.getElementById('edit-servings').value;
    const imageUrl = document.getElementById('edit-image-url').value;
    const ethnicId = document.getElementById('edit-ethnic-id').value;

    try {
        const response = await fetch(`${BASE_URL}edit_recipe.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                recipe_id: recipeId,
                name,
                description,
                instructions,
                ingredients,
                prep_time: parseInt(prepTime),
                cook_time: parseInt(cookTime),
                servings: parseInt(servings),
                image_url: imageUrl,
                ethnic_id: ethnicId ? parseInt(ethnicId) : null
            }),
            credentials: 'include'
        });

        const data = await response.json();

        if (data.success) {
            alert('Recipe updated successfully');
            loadUserRecipes();
            bootstrap.Modal.getInstance(document.getElementById('editRecipeModal')).hide();
        } else {
            alert('Failed to update recipe: ' + data.message);
        }
    } catch (error) {
        console.error('Error updating recipe:', error);
        alert('Error updating recipe. Please try again.');
    }
} 