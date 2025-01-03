document.addEventListener('DOMContentLoaded', () => {
    // Fetch the recipes data from the PHP script
    fetchRecipes();

    // Search functionality
    document.getElementById('search-form').addEventListener('submit', async function(event) {
        event.preventDefault();
        const searchTerm = document.getElementById('search-input').value;
        await searchRecipes(searchTerm);
    });
});

import { BASE_URL } from "./config.js";

async function fetchRecipes() {
    const response = await fetch(`${BASE_URL}recipe_card.php`);
    const data = await response.json();
    displayRecipes(data);
}

async function searchRecipes(searchTerm) {
    const response = await fetch(`${BASE_URL}search_recipes.php?search=${encodeURIComponent(searchTerm)}`);
    const data = await response.json();
    displayRecipes(data);
}

function displayRecipes(data) {
    const container = document.getElementById('recipe-container');
    container.innerHTML = ''; // Clear existing content

    data.forEach(recipe => {
        const recipeDiv = document.createElement('div');
        recipeDiv.classList.add('col-md-3', 'mb-4');
        recipeDiv.innerHTML = `
            <div class='recipe-card'>
                <a href='recipe.html?recipe_id=${encodeURIComponent(recipe.recipe_id)}' class="recipe-link">
                    <div class="recipe-image-container">
                        <img src='${recipe.image_url}' alt='${recipe.name}'>
                    </div>
                    <div class='recipe-name'>
                        <h5>${recipe.name}</h5>
                    </div>
                </a>
            </div>
        `;
        container.appendChild(recipeDiv);
    });
}
