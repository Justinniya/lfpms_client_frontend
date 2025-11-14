<div class="container">
    <h3 class="mb-2 pt-1"><b>Stage 4: Implementation</b></h3>
    <p><b>Status:</b> Implementation of Design (Should do product update)</p>
    <p>And wait the Approval of the Admin(DTI) if Approved then the stage will be completed</p>
    <?php
    if ($consultationID) {
        try {
            // Prepare the SQL query
            $sql = "SELECT draft_img FROM consultation_report WHERE consultationID = :consultationID";
            $stmt = $conn->prepare($sql);

            // Bind the consultationID parameter
            $stmt->bindParam(':consultationID', $consultationID, PDO::PARAM_INT);

            // Execute the query
            $stmt->execute();

            // Fetch the result
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // Check if a result was found
            if ($result) {
                // Display the draft_img (assuming it's stored as a file path in the database)
                echo '<hr><center><b></b><p>Design Output</p></b><img src="../uploaded_img/' . htmlspecialchars($result['draft_img']) . '" alt="Draft Image" class="img-fluid" width="550px" height="auto" /></center>';
            } else {
                echo "No draft image found for the provided consultation ID.";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Consultation ID is not set.";
    }
    ?>
    <div class="text-end ">
    <a href="update_product.php?update=<?php echo $productId ?>" class="btn btn-primary text-white">Update Product</a>
    </div>
</div>