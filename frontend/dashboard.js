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

async function fetchRecipes() {
    const response = await fetch('http://192.168.0.251/api/recipe_card.php');
    const data = await response.json();
    displayRecipes(data);
}

async function searchRecipes(searchTerm) {
    const response = await fetch(`http://192.168.0.251/api/search_recipes.php?search=${encodeURIComponent(searchTerm)}`);
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
