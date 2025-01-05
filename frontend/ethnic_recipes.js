import { BASE_URL } from "./config.js";

document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const ethnicId = urlParams.get('ethnic_id');
    
    // Clear previous content
    const ethnicNameElement = document.getElementById('ethnic-name');
    const ethnicDescriptionElement = document.getElementById('ethnic-description');
    const container = document.getElementById('recipe-container');
    
    if (!ethnicId) {
        // Handle case when no ethnic_id is provided
        if (ethnicNameElement) {
            ethnicNameElement.textContent = "Please select an ethnic cuisine";
        }
        if (ethnicDescriptionElement) {
            ethnicDescriptionElement.textContent = "Navigate to a specific ethnic cuisine to view its recipes";
        }
        return;
    }

    console.log('Fetching recipes for ethnic_id:', ethnicId); // Debug log

    fetch(`${BASE_URL}ethnic_recipe_cards.php?ethnic_id=${ethnicId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Received data:', data); // Debug log
            
            if (data.length > 0) {
                if (ethnicNameElement) {
                    ethnicNameElement.textContent = data[0].ethnic_name;
                }
                if (ethnicDescriptionElement) {
                    ethnicDescriptionElement.textContent = data[0].ethnic_description;
                }
                displayRecipes(data);
            } else {
                // Handle case when no recipes are found
                if (ethnicNameElement) {
                    ethnicNameElement.textContent = "No recipes found";
                }
                if (ethnicDescriptionElement) {
                    ethnicDescriptionElement.textContent = "There are currently no recipes in this category.";
                }
                if (container) {
                    container.innerHTML = '<div class="col-12 text-center"><p class="text-white">No recipes available for this cuisine yet.</p></div>';
                }
            }
        })
        .catch(error => {
            console.error('Error fetching ethnic recipes:', error);
            // Handle error state
            if (ethnicNameElement) {
                ethnicNameElement.textContent = "Error Loading Cuisine";
            }
            if (ethnicDescriptionElement) {
                ethnicDescriptionElement.textContent = "There was an error loading the cuisine information.";
            }
            if (container) {
                container.innerHTML = '<div class="col-12 text-center"><p class="text-danger">Error loading recipes. Please try again later.</p></div>';
            }
        });

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
                    <p class='card-text small'>${recipe.description}</p>
                    <p class='small text-muted'>
                        Prep Time: ${recipe.prep_time} mins | Cook Time: ${recipe.cook_time} mins<br>
                        Servings: ${recipe.servings}
                    </p>
                </div>
            </div>
        `;
        container.appendChild(recipeDiv);
    });
}