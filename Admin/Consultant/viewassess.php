<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("location: ../../index.php");
    exit();
}
include '../../session.php';

function getLoggedInUser($conn, $user_id)
{
    $sql = "SELECT * FROM users WHERE userid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$user_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

$loggedInUser = getLoggedInUser($conn, $_SESSION['id']);

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $query = "SELECT * FROM consultancyquestionnaire WHERE id = :id";
    $statement = $conn->prepare($query);
    $statement->bindParam(':id', $id, PDO::PARAM_INT);
    $statement->execute();
    $Data = $statement->fetch(PDO::FETCH_ASSOC);

    // Debugging statement to check if data is retrieved
    if ($Data) {
        echo "<script>console.log('Data retrieved successfully');</script>";
    } else {
        echo "<script>console.log('Failed to retrieve data');</script>";
    }
}

$selectedSize = isset($Data['Size']) ? $Data['Size'] : '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>LFPMS</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="shortcut icon" href="assets/img/bb.png" />
    <style>
        .img-container {
            text-align: center;
        }

        .img-container img {
            max-width: 100%;
            height: auto;
            max-height: 500px;
            /* Adjust this value as needed */
        }
    </style>
</head>

<body class="with-welcome-text">
    <div class="container-scroller">
        <?php include 'importantinclude/topbar.php'; ?>
        <div class="container-fluid page-body-wrapper">
            <?php include 'importantinclude/sidebar.php'; ?>
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row">
                        <div class="col-lg-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h1 class="mb-4 text-center">Consultancy Questionnaire</h1>
                                    <hr>
                                    <form id="colorForm" method="POST" action="consultancyQuestionaire.php" onsubmit="return validateForm()" enctype="multipart/form-data">
                                        <div class="form-row">
                                            <input type="hidden" name="userid" id="userid" value="<?php echo $Id ?>" required />
                                        </div>

                                        <h3 class="mt-4 mb-4">MSME's Personal Information.</h3>

                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label for="name">Name</label>
                                                <input type="text" class="form-control" name="name" id="name" value="<?php echo isset($Data['name']) ? $Data['name'] : ''; ?>" required readonly />
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="address">Address</label>
                                                <input type="text" class="form-control" name="address" id="address" value="<?php echo isset($Data['address']) ? $Data['address'] : ''; ?>" required readonly />
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label for="municipality">Municipality</label>
                                                <input type="text" class="form-control" name="municipality" id="municipality" value="<?php echo isset($Data['municipality']) ? $Data['municipality'] : ''; ?>" required readonly />
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="phoneNumber">Phone Number</label>
                                                <input type="text" class="form-control" name="phoneNumber" id="phoneNumber" value="<?php echo isset($Data['phoneNumber']) ? $Data['phoneNumber'] : ''; ?>" required readonly />
                                            </div>
                                        </div>

                                        <h3 class="mt-4 mb-4">MSME's Product Information.</h3>

                                        <div class="row">
                                            <div class="form-group col-md-4">
                                                <label for="product">Name of Product</label>
                                                <input type="text" class="form-control" name="product" id="product" value="<?php echo isset($Data['ProductName']) ? $Data['ProductName'] : ''; ?>" required readonly />
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="labelingformat">Labeling Format (stick on label, header, etc)</label>
                                                <input type="text" class="form-control" name="labelingformat" id="labelingformat" value="<?php echo isset($Data['labelingFormat']) ? $Data['labelingFormat'] : ''; ?>" required readonly />
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="brandName">Brand Name (ex. Coca-cola, Milo, Mt.Dew)</label>
                                                <input type="text" class="form-control" name="brandName" id="brandName" value="<?php echo isset($Data['brandName']) ? $Data['brandName'] : ''; ?>" required readonly />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="productIdentity">Product Identity Name: True Name/Nature of the Food (ex. salted Peanut, Dried Mango)</label>
                                            <input type="text" class="form-control" name="productIdentity" id="productIdentity" value="<?php echo isset($Data['productIdentity']) ? $Data['productIdentity'] : ''; ?>" required readonly />
                                        </div>

                                        <div class="row">
                                            <h6 class="mt-4 mb-4">Name of Product's (If 1 label with 2 or more product selection).</h6>
                                            <div class="form-group col-md-6">
                                                <label for="label1">Product 1</label>
                                                <input type="text" class="form-control" name="label1" id="label1" value="<?php echo isset($Data['label1']) ? $Data['label1'] : ''; ?>" required readonly />
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="label2">Product 2</label>
                                                <input type="text" class="form-control" name="label2" id="label2" value="<?php echo isset($Data['label2']) ? $Data['label2'] : ''; ?>" required readonly />
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label for="label3">Product 3</label>
                                                <input type="text" class="form-control" name="label3" id="label3" value="<?php echo isset($Data['label3']) ? $Data['label3'] : ''; ?>" required readonly />
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="label4">Product 4</label>
                                                <input type="text" class="form-control" name="label4" id="label4" value="<?php echo isset($Data['label4']) ? $Data['label4'] : ''; ?>" required readonly />
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-3"></div>
                                            <div class="form-group col-md-3">
                                                <label for="tagline">Tagline (optional)</label>
                                                <input type="text" class="form-control" name="tagline" id="tagline" value="<?php echo isset($Data['tagline']) ? $Data['tagline'] : ''; ?>" required readonly />
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="netContent">Net Content (kg, g, ml, etc.)</label>
                                                <input type="text" class="form-control" name="netContent" id="netContent" value="<?php echo isset($Data['netContent']) ? $Data['netContent'] : ''; ?>" required readonly />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="ingredients">Ingredients (from most to least quantity)</label>
                                            <textarea name="ingredients" class="form-control" rows="3" disabled><?php echo isset($Data['ingredients']) ? $Data['ingredients'] : ''; ?></textarea>
                                        </div>

                                        <div class="form-group">
                                            <label for="expiryDate">Expiry Date of the Product</label>
                                            <input type="date" class="form-control" name="expiryDate" id="expiryDate" value="<?php echo isset($Data['expiryDate']) ? $Data['expiryDate'] : ''; ?>" required readonly />
                                        </div>

                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label for="ProductDirect">Direction of the Product</label>
                                                <textarea name="ProductDirect" class="form-control" rows="3" disabled><?php echo isset($Data['DirectProduct']) ? $Data['DirectProduct'] : ''; ?></textarea>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="ConceptDesign">Concept of Design</label>
                                                <textarea name="ConceptDesign" class="form-control" rows="3" disabled><?php echo isset($Data['ConceptDesign']) ? $Data['ConceptDesign'] : ''; ?></textarea>
                                            </div>
                                        </div>

                                        <h4>Size of the Product</h4>
                                        <div class="form-group" style="margin-left: 30px;">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="size" value="small" id="sizeSmall" <?php if ($selectedSize == 'small') echo 'checked'; ?> disabled>
                                                <label class="form-check-label" for="sizeSmall">Small</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="size" value="medium" id="sizeMedium" <?php if ($selectedSize == 'medium') echo 'checked'; ?> disabled>
                                                <label class="form-check-label" for="sizeMedium">Medium</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="size" value="large" id="sizeLarge" <?php if ($selectedSize == 'large') echo 'checked'; ?> disabled>
                                                <label class="form-check-label" for="sizeLarge">Large</label>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="DominantColor">Dominant Color to be used</label>
                                            <input type="text" class="form-control" name="DominantColor" id="DominantColor" value="<?php echo isset($Data['DominantColor']) ? $Data['DominantColor'] : ''; ?>" placeholder="Dominant Color to be used" required readonly />
                                        </div>
                                        <div class="form-group">
                                            <label for="Comment">Notes or Other Comments</label>
                                            <textarea name="Comment" class="form-control" rows="3" disabled><?php echo isset($Data['Comment']) ? $Data['Comment'] : ''; ?></textarea>
                                        </div>

                                        <div class="form-group">
                                            <input type="hidden" id="pickedColors" name="SelectedColor">
                                            <label for="displayColors">Selected Colors</label>
                                            <input type="text" class="form-control" id="displayColors" value="<?php echo isset($Data['SelectedColor']) ? $Data['SelectedColor'] : ''; ?>" readonly>
                                        </div>

                                        <div class="form-group">
                                            <table id="colorTable">

                                            </table>
                                        </div>

                                        <div class="form-group img-container">
                                            <label style="font-size: 30px; font-weight: bold; margin-bottom:25px;" for="uploadedImage">Uploaded Draft Design</label>
                                            <?php if (!empty($Data['draft_img'])) { ?>
                                                <div>
                                                    <img src="../uploaded_img/<?php echo $Data['draft_img']; ?>" alt="Draft Image" class="img-fluid">
                                                </div>
                                            <?php } else { ?>
                                                <p>No image uploaded</p>
                                            <?php } ?>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php include 'importantinclude/footer.php'; ?>
            </div>
        </div>
    </div>
    <?php include 'script.php'; ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const colorToRGB = {
                "white": "rgb(255, 255, 255)",
                "champagne": "rgb(247, 231, 206)",
                "palepink": "rgb(250, 218, 221)",
                "paleblue": "rgb(175, 238, 238)",
                "pear": "rgb(209, 226, 49)",
                "apricot": "rgb(251, 206, 177)",
                "cerise": "rgb(222, 49, 99)",
                "azure": "rgb(0, 127, 255)",
                "lime": "rgb(0, 255, 0)",
                "orange": "rgb(255, 165, 0)",
                "ruby": "rgb(224, 17, 95)",
                "cerulean": "rgb(42, 82, 190)",
                "yellowgreen": "rgb(154, 205, 50)",
                "copper": "rgb(184, 115, 51)",
                "crimson": "rgb(220, 20, 60)",
                "steelblue": "rgb(70, 130, 180)",
                "beige": "rgb(245, 245, 220)",
                "salmon": "rgb(250, 128, 114)",
                "rosepink": "rgb(255, 102, 204)",
                "aqua": "rgb(0, 255, 255)",
                "khaki": "rgb(195, 176, 145)",
                "redorange": "rgb(255, 69, 0)",
                "magenta": "rgb(255, 0, 255)",
                "turquoise": "rgb(64, 224, 208)",
                "olive": "rgb(128, 128, 0)",
                "rust": "rgb(183, 65, 14)",
                "redviolet": "rgb(199, 21, 133)",
                "seagreen": "rgb(46, 139, 87)",
                "umber": "rgb(99, 81, 71)",
                "sienna": "rgb(160, 82, 45)",
                "plum": "rgb(221, 160, 221)",
                "aquamarine": "rgb(127, 255, 212)",
                "ivory": "rgb(255, 255, 240)",
                "seashell": "rgb(255, 245, 238)",
                "violet": "rgb(238, 130, 238)",
                "emerald": "rgb(80, 200, 120)",
                "lemon": "rgb(255, 247, 0)",
                "peach": "rgb(255, 229, 180)",
                "fuchsia": "rgb(255, 0, 255)",
                "jade": "rgb(0, 168, 107)",
                "yellow": "rgb(255, 255, 0)",
                "coral": "rgb(255, 127, 80)",
                "purple": "rgb(128, 0, 128)",
                "palegreen": "rgb(152, 251, 152)",
                "gold": "rgb(255, 215, 0)",
                "indianred": "rgb(205, 92, 92)",
                "eggplant": "rgb(97, 64, 81)",
                "applegreen": "rgb(141, 182, 0)",
                "cream": "rgb(255, 253, 208)",
                "pink": "rgb(255, 192, 203)",
                "lavender": "rgb(230, 230, 250)",
                "green": "rgb(0, 255, 0)",
                "amber": "rgb(255, 191, 0)",
                "red": "rgb(255, 0, 0)",
                "amethyst": "rgb(153, 102, 204)",
                "forestgreen": "rgb(34, 139, 34)",
                "goldenrod": "rgb(218, 165, 32)",
                "carmine": "rgb(150, 0, 24)",
                "blueviolet": "rgb(138, 43, 226)",
                "celadon": "rgb(172, 225, 175)",
                "ocher": "rgb(204, 119, 34)",
                "maroon": "rgb(128, 0, 0)",
                "indigo": "rgb(75, 0, 130)",
                "sage": "rgb(188, 184, 138)",
                "bisque": "rgb(255, 228, 196)",
                "mistyrose": "rgb(255, 228, 225)",
                "babyblue": "rgb(137, 207, 240)",
                "slate": "rgb(112, 128, 144)",
                "tan": "rgb(210, 180, 140)",
                "oldrose": "rgb(192, 128, 129)",
                "skyblue": "rgb(135, 206, 235)",
                "mauve": "rgb(224, 176, 255)",
                "bronze": "rgb(205, 127, 50)",
                "rosybrown": "rgb(188, 143, 143)",
                "blue": "rgb(0, 0, 255)",
                "taupe": "rgb(72, 60, 50)",
                "sepia": "rgb(112, 66, 20)",
                "rosewood": "rgb(101, 0, 11)",
                "ultramarine": "rgb(18, 10, 143)",
                "silver": "rgb(192, 192, 192)",
                "brown": "rgb(165, 42, 42)",
                "grey": "rgb(128, 128, 128)"
            };

            const table = document.getElementById("colorTable");
            const pickedColors = new Set(); // Use a set to store picked colors

            function updateDisplay() {
                const displayColors = document.getElementById("displayColors");
                displayColors.value = Array.from(pickedColors).join(", ");
                table.innerHTML = ''; // Clear the table

                pickedColors.forEach(color => {
                    const row = document.createElement("tr");
                    const cell = document.createElement("td");
                    cell.style.backgroundColor = colorToRGB[color];
                    cell.style.width = "50px"; // Adjust the width as needed
                    cell.style.height = "50px"; // Adjust the height as needed
                    row.appendChild(cell);
                    table.appendChild(row);
                });
            }

            // Initialize pickedColors with the existing selected colors
            const initialColors = document.getElementById("displayColors").value.split(", ");
            initialColors.forEach(color => {
                if (color) {
                    pickedColors.add(color.trim());
                }
            });
            console.log("Initial picked colors:", Array.from(pickedColors)); // Debugging statement
            updateDisplay(); // Initial display update
        });
    </script>
</body>

</html>