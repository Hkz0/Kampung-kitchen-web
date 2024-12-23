<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kampung kitchen</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-sm navbar-dark" style="background-color: lightblue;">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">KAMPUNG KITCHEN</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Ethnic Recipes
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="melayu.php?ethnic_id=1">MELAYU</a></li>
                            <li><a class="dropdown-item" href="melayu.php?ethnic_id=2">CINA</a></li>
                            <li><a class="dropdown-item" href="melayu.php?ethnic_id=3">INDIA</a></li>
                            <li><a class="dropdown-item" href="melayu.php?ethnic_id=4">SABAH</a></li>
                            <li><a class="dropdown-item" href="melayu.php?ethnic_id=5">SARAWAK</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Homepage Heading -->
    <div class="container-fluid my-custom">
       <h1>KAMPUNG KITCHEN HOMEPAGE</h1>
   </div>

    <!-- Recipe Cards -->
    <div class="container mt-5">
        <div class="row">
            <?php
            // Establishing database connection
            $conn = new mysqli("localhost", "root", "", "kampung_kitchen");


            // Checking connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Query to fetch recipes
            $sql = "SELECT * FROM recipe";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "
                    <div class='col-md-4 mb-4'>
                        <div class='card'>
                            <a href='display_recipe.php?recipe_id=" . urlencode($row['recipe_id']) . "'>
                                <img src='" . htmlspecialchars($row['image_url']) . "' class='card-img-top' alt='Recipe Image' style='height: 200px; object-fit: cover;'>
                            </a>
                            <div class='card-body'>
                                <h5 class='card-title'>" . htmlspecialchars($row['name']) . "</h5>
                                <p class='card-text'>" . htmlspecialchars($row['description']) . "</p>
                                <p class='small'>
                                    Prep Time: " . $row['prep_time'] . " mins | Cook Time: " . $row['cook_time'] . " mins<br>
                                    Servings: " . $row['servings'] . "
                                </p>
                            </div>
                        </div>
                    </div>";
                }
            } else {
                echo "<p class='text-center'>No recipes available at the moment.</p>";
            }
            
            
            

            // Closing the connection
            $conn->close();
            ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>