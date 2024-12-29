import { BASE_URL } from "./config.js";
document.addEventListener('DOMContentLoaded', () => {
    loadRecipe();
});

// Load the recipe details
// format the recipe page
function loadRecipe() {
    const urlParams = new URLSearchParams(window.location.search);
    const recipeId = urlParams.get('recipe_id');

    fetch(`${BASE_URL}recipe_details.php?recipe_id=${recipeId}`)
        .then(response => response.json())
        .then(data => {
            document.querySelector('.recipe-title').textContent = data.name;
            document.querySelector('.recipe-image').src = data.image_url;
            document.getElementById('recipe-author').textContent = data.username;
            document.getElementById('prep-time').textContent = data.prep_time;
            document.getElementById('cook-time').textContent = data.cook_time;
            document.getElementById('servings').textContent = data.servings;
            document.getElementById('ingredients-list').textContent = data.ingredients;
            document.getElementById('instructions-container').textContent = data.instructions;
            document.getElementById('ethnic-name').textContent = data.ethnic_name || 'Not specified';
            const createdDate = new Date(data.created_at);
            document.getElementById('created-at').textContent = createdDate.toLocaleDateString();

            /* to be fixed
            const ingredientsList = document.getElementById('ingredients-list');
            data.ingredients.forEach(ingredient => {
                const li = document.createElement('li');
                li.textContent = ingredient;
                li.classList.add('list-group-item');
                ingredientsList.appendChild(li);
            });

            const instructionsContainer = document.getElementById('instructions-container');
            data.instructions.forEach(instruction => {
                const instructionParagraph = document.createElement('p');
                instructionParagraph.textContent = instruction;
                instructionsContainer.appendChild(instructionParagraph);
            });
            */
        })
        .catch(error => {
            console.error('Error loading the recipe:', error);
        });
}
