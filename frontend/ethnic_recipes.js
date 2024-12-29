import { BASE_URL } from "./config.js";

document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const ethnicId = urlParams.get('ethnic_id');


    if (ethnicId) {
        fetch(`${BASE_URL}ethnic_recipe_cards.php?ethnic_id=${ethnicId}`)
            .then(response => response.json())
            .then(data => {
                // Display ethnic name and description
                if (data.length > 0) {
                    document.getElementById('ethnic-name').textContent = data[0].ethnic_name;
                    document.getElementById('ethnic-description').textContent = data[0].ethnic_description;
                }
                displayRecipes(data);
            })
            .catch(error => {
                console.error('Error fetching ethnic recipes:', error);
            });
    }

    // Search functionality
    document.getElementById('search-form').addEventListener('submit', async function(event) {
        event.preventDefault();
        const searchTerm = document.getElementById('search-input').value;
        await searchRecipes(searchTerm);
    });
});

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
        recipeDiv.classList.add('col-md-4', 'mb-4');
        recipeDiv.innerHTML = `
            <div class='card'>
                <a href='recipe.html?recipe_id=${encodeURIComponent(recipe.recipe_id)}'>
                    <img src='${recipe.image_url}' class='card-img-top' alt='Recipe Image' style='height: 200px; object-fit: cover;'>
                </a>
                <div class='card-body'>
                    <h5 class='card-title'>${recipe.name}</h5>
                    <p class='card-text'>${recipe.description}</p>
                    <p class='small'>
                        Prep Time: ${recipe.prep_time} mins | Cook Time: ${recipe.cook_time} mins<br>
                        Servings: ${recipe.servings}
                    </p>
                </div>
            </div>
        `;
        container.appendChild(recipeDiv);
    });
}