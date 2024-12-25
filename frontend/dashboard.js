document.addEventListener('DOMContentLoaded', () => {
    // Fetch the recipes data from the PHP script
    fetch('../api/recipe_card.php')
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('recipe-container');
            
            // Loop through the recipes and display them
            data.forEach(recipe => {
                const recipeDiv = document.createElement('div');
                recipeDiv.classList.add('col-md-4', 'mb-4');
                
                const recipeContent = `
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
                
                recipeDiv.innerHTML = recipeContent;
                container.appendChild(recipeDiv);
            });
        })
        .catch(error => {
            console.error('Error fetching the recipe data:', error);
        });
});
