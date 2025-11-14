<?php
include "includes/dbcon.php"; // Database connection

try {
    // Determine the search criteria
    if (isset($_POST['search_query']) && !empty($_POST['search_query'])) {
        $search_query = $_POST['search_query'];
        // Search products by name
        $select_products = $conn->prepare("SELECT * FROM `products` INNER JOIN users ON users.userid = products.msme_id WHERE productName LIKE :search_query OR address LIKE :search_query");
        $select_products->bindValue(':search_query', '%' . $search_query . '%', PDO::PARAM_STR);
    } else if (isset($_POST['location']) && !empty($_POST['location'])) {
        $location = $_POST['location'];
        // Search products by location (address)
        $select_products = $conn->prepare("SELECT * FROM `products` INNER JOIN users ON users.userid = products.msme_id WHERE address LIKE :location");
        $select_products->bindValue(':location', '%' . $location . '%', PDO::PARAM_STR);
    } else {
        // No search criteria, fetch all products
        $select_products = $conn->prepare("SELECT * FROM `products` INNER JOIN users ON users.userid = products.msme_id");
    }

    $select_products->execute();

    // Check for products and display them
    if ($select_products->rowCount() > 0) {
        echo '<div class="container my-4">';
        echo '<div class="row" id="productResults">';

        while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) { 
            echo '<div class="col-md-5th mb-4">';
            echo '<a href="comment.php?id=' . htmlspecialchars($fetch_products['product_id']) . '" class="text-decoration-none">'; // Anchor tag wrapping the card
            echo '<div class="card product-card h-100">';
            echo '<img src="pages/uploaded_img/' . htmlspecialchars($fetch_products['productImage']) . '" class="card-img-top product-img" alt="Product Image">';
            echo '<div class="card-body">';
            echo '<h5 class="card-title">' . htmlspecialchars($fetch_products['productName']) . '</h5>';
            echo '<p class="product-price">&#8369;' . htmlspecialchars($fetch_products['productPrice']) . '</p>';
            // Display product location (address)
            echo '<p class="card-text">' . htmlspecialchars($fetch_products['address']) . '</p>';
            echo '<a href="comment.php?id=' . htmlspecialchars($fetch_products['product_id']) . '" class="btn btn-primary">View Details</a>';
            echo '</div></div></div>';
        }

        echo '</div>'; // End of row
        echo '</a>'; // End of Anchor
        echo '</div>'; // End of container
    } else {
        // No products found
        echo '<div class="container my-4">';
        echo '<div class="alert alert-warning text-center" role="alert">No products found.</div>';
        echo '</div>';
    }
} catch (PDOException $e) {
    // Error handling
    echo "Error: " . $e->getMessage();
}
?>
