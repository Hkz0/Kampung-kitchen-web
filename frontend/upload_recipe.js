document.getElementById('upload-recipe-form').addEventListener('submit', async function(event) {
    event.preventDefault();

    const name = document.getElementById('name').value;
    const description = document.getElementById('description').value;
    const instructions = document.getElementById('instructions').value;
    const ingredients = document.getElementById('ingredients').value;
    const prepTime = document.getElementById('prep-time').value;
    const cookTime = document.getElementById('cook-time').value;
    const servings = document.getElementById('servings').value;
    const imageUrl = document.getElementById('image-url').value;
    const ethnicId = document.getElementById('ethnic-id').value;

    try {
        const response = await fetch('../api/upload_recipe.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
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
            alert('Recipe uploaded successfully');
            document.getElementById('upload-recipe-form').reset();
            document.getElementById('image-preview').style.display = 'none';
        } else {
            alert('Failed to upload recipe: ' + data.message);
        }
    } catch (error) {
        console.error('Error uploading recipe:', error);
        alert('Error uploading recipe. Please try again.');
    }
});

document.getElementById('image-url').addEventListener('input', function() {
    const imageUrl = this.value;
    const imagePreview = document.getElementById('image-preview');
    if (imageUrl) {
        imagePreview.src = imageUrl;
        imagePreview.style.display = 'block';
    } else {
        imagePreview.style.display = 'none';
    }
}); 