<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("location: ../../index.php");
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


if (isset($_GET['user_id']) && isset($_GET['user_id'])) {
    $user_id = htmlspecialchars($_GET['user_id']);
    $room_id = htmlspecialchars($_GET['room_id']);

    // Prepare a SQL query to get data from consultancy_questionnaire for this user_id
    $stmt = $conn->prepare(" 
    SELECT 
    id, 
    user_id, 
    name, 
    address, 
    municipality, 
    phoneNumber, 
    labelingFormat, 
    brandName, 
    ProductName, 
    productIdentity, 
    label1, 
    label2, 
    label3, 
    label4, 
    tagline, 
    netContent, 
    ingredients, 
    expiryDate, 
    DirectProduct, 
    ConceptDesign, 
    Size, 
    DominantColor, 
    Comment, 
    SelectedColor, 
    SubmittionDate, 
    draft_img, 
    status 
    FROM 
        consultancyquestionnaire 
    WHERE 
        user_id = :user_id
    ORDER BY 
        SubmittionDate DESC 
    LIMIT 1;
    ");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    // Fetch the result
    $consultancyData = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>LFPMS</title>
    <style>
        .img-container {
            text-align: center;
        }

        .img-container img {
            max-width: 100%;
            height: auto;
            max-height: 500px;
            /* Adjust as needed */
            display: inline-block;
            /* Ensures proper centering */
        }
    </style>
    <link rel="shortcut icon" href="assets/img/bb.png" />
</head>

<body class="with-welcome-text">
    <div class="container-scroller">
        <?php include 'importantinclude/topbar.php'; ?>
        <div class="container-fluid page-body-wrapper">
            <?php include 'importantinclude/sidebar.php'; ?>
            <!-- partial -->
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row">
                        <div class="container card" id="container">
                            <h1 class="text-center mt-4"><b>Consultation Report</b></h1>
                            <hr>
                            <?php if (!empty($consultancyData)): ?>
                                <h3><b>MSME Information</b></h3>
                                <form id="colorForm" method="POST" action="ConsultationReportProcess.php" onsubmit="return validateForm()" enctype="multipart/form-data">
                                    <input type="hidden" name="conID" value="<?php echo $_SESSION['id']; ?>">
                                    <input type="hidden" class="form-control" name="userid" id="userid" value="" placeholder="Name" required />
                                    <input type="hidden" class="form-control" name="room_id" id="room_id" value="<?php echo $room_id ?>" required />
                                    <div class="row col-md-6">
                                        <div class="form-group">
                                            <label for="name">Name</label>
                                            <input type="text" class="form-control" name="name" id="name" placeholder="Name" value="<?= htmlspecialchars($consultancyData['name']) ?>" readonly />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <h6 class="col-md-12"><b>Product Information</b></h6>
                                        <div class="form-group col-md-4">
                                            <label for="product">Name of Product</label>
                                            <input type="text" class="form-control" name="product" id="product" placeholder="Name of Product" value="<?= htmlspecialchars($consultancyData['ProductName']) ?>" readonly />
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="labelingformat">Labeling Format</label>
                                            <input type="text" class="form-control" name="labelingformat" id="labelingformat" value="<?= htmlspecialchars($consultancyData['labelingFormat']) ?>" readonly />
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="brandName">Brand Name</label>
                                            <input type="text" class="form-control" name="brandName" id="brandName" value="<?= htmlspecialchars($consultancyData['brandName']) ?>" readonly />
                                        </div>
                                    </div>

                                    <div class="row">
                                        <h6 class="text-center">Product Identity Name: True Name/Nature of the Food (ex. salted Peanut, Dried Mango)</h6>
                                        <div class="col-md-4"></div>
                                        <div class="form-group col-md-4">
                                            <input type="text" class="form-control" value="<?= htmlspecialchars($consultancyData['ProductName']) ?>" readonly />
                                        </div>
                                    </div>

                                    <hr>

                                    <h3 class="text-center mt-4 mb-4"><b>Product Design Information Suggestions as Consultant.</b></h3>
                                    <h6><b>Note:</b> Write a report for the final design output</h6>

                                    <div class="form-group">
                                        <label for="ConceptDesign">Concept of Design</label>
                                        <input value="<?= htmlspecialchars($consultancyData['id']) ?>" type="hidden" name="consultationID">
                                        <input value="<?php echo $room_id ?>" type="hidden" name="room_id">
                                        <textarea class="form-control" name="ConceptDesign" rows="7" required></textarea>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-2 mt-1 ml-4">
                                            <h6>Size of the Product</h6>
                                            <div class="form-group" style="margin-left: 30px;">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="size" id="sizeSmall" value="small" required>
                                                    <label style="margin-left:5px;" class="form-check-label" for="sizeSmall">Small</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="size" id="sizeMedium" value="medium" required>
                                                    <label style="margin-left:5px;" class="form-check-label" for="sizeMedium">Medium</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="size" id="sizeLarge" value="large" required>
                                                    <label style="margin-left:5px;" class="form-check-label" for="sizeLarge">Large</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-5">
                                            <label for="Comment">Notes or Other Comments:</label>
                                            <textarea class="form-control" name="Comment" rows="7" required></textarea>
                                        </div>
                                        <div class="form-group col-md-5">
                                            <label for="DominantColor">Dominant Color used in the design:</label>
                                            <input class="form-control" type="text" name="DominantColor" id="DominantColor" placeholder="Dominant Color to be used" required />
                                        </div>
                                    </div>

                                    <input type="hidden" id="pickedColors" name="SelectedColor" value="" required>
                                    <div class="form-group">
                                        <label for="displayColors">Color used in the design:</label>
                                        <input type="text" id="displayColors" class="form-control" readonly required>
                                    </div>
                                    <div class="form-group">
                                        <label for="colorDropdown">Select Colors:</label>
                                        <div class="dropdown">
                                            <button class="btn btn-secondary dropdown-toggle" type="button" id="colorDropdownBtn" data-bs-toggle="dropdown" aria-expanded="false">
                                                Select Colors
                                            </button>
                                            <div class="dropdown-menu" id="colorDropdownMenu" style="width: 100%; max-height: 300px; overflow-y: auto;">
                                                <input type="text" id="colorSearch" class="form-control" placeholder="Search colors...">
                                                <!-- Colors will be dynamically populated -->
                                            </div>
                                        </div>
                                    </div>

                                    <script>
                                        document.addEventListener("DOMContentLoaded", function() {
                                            const colors = {
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

                                            const dropdownMenu = document.getElementById("colorDropdownMenu");
                                            const displayColors = document.getElementById("displayColors");
                                            const pickedColors = document.getElementById("pickedColors");
                                            const selectedColors = new Set();

                                            // Populate dropdown with color options
                                            Object.keys(colors).forEach(color => {
                                                let listItem = document.createElement("div");
                                                listItem.classList.add("dropdown-item");
                                                listItem.style.display = "flex";
                                                listItem.style.alignItems = "center";
                                                listItem.style.width = "100%";

                                                let checkbox = document.createElement("input");
                                                checkbox.type = "checkbox";
                                                checkbox.value = color;
                                                checkbox.classList.add("color-option");
                                                checkbox.style.marginRight = "5px";

                                                let colorBox = document.createElement("span");
                                                colorBox.style.backgroundColor = colors[color];
                                                colorBox.style.width = "20px";
                                                colorBox.style.height = "20px";
                                                colorBox.style.display = "inline-block";
                                                colorBox.style.marginRight = "5px";

                                                let label = document.createElement("label");
                                                label.textContent = color;
                                                label.style.cursor = "pointer";
                                                label.style.display = "flex";
                                                label.style.alignItems = "center";

                                                listItem.appendChild(checkbox);
                                                listItem.appendChild(colorBox);
                                                listItem.appendChild(label);

                                                dropdownMenu.appendChild(listItem);

                                                // Handle color selection
                                                checkbox.addEventListener("change", function() {
                                                    if (this.checked) {
                                                        selectedColors.add(this.value);
                                                    } else {
                                                        selectedColors.delete(this.value);
                                                    }
                                                    updateSelectedColors();
                                                });
                                            });

                                            function updateSelectedColors() {
                                                let selectedArray = Array.from(selectedColors);
                                                displayColors.value = selectedArray.join(", ");
                                                pickedColors.value = selectedArray.join(",");
                                            }

                                            // Search functionality
                                            document.getElementById("colorSearch").addEventListener("input", function() {
                                                const searchValue = this.value.toLowerCase();
                                                const items = dropdownMenu.getElementsByClassName("dropdown-item");
                                                Array.from(items).forEach(item => {
                                                    const color = item.querySelector("label").textContent.toLowerCase();
                                                    if (color.includes(searchValue)) {
                                                        item.style.display = "flex";
                                                    } else {
                                                        item.style.display = "none";
                                                    }
                                                });
                                            });
                                        });
                                    </script>
                                    <br>
                                    <h6>Upload Final Design:</h6>
                                    <div class="text-center">
                                        <input type="file" name="draft_img" class="form-control" id="draft_img_input" onchange="previewImage(event)" required>
                                        <br>
                                        <div class="img-container">
                                            <img id="draft_img_preview" src="" alt="Draft Design Preview" class="img-thumbnail d-none">
                                        </div>
                                        <script>
                                            function previewImage(event) {
                                                const input = event.target;
                                                const preview = document.getElementById('draft_img_preview');

                                                // Check if there's a file
                                                if (input.files && input.files[0]) {
                                                    const reader = new FileReader();

                                                    // Load the image and set it to the preview
                                                    reader.onload = function(e) {
                                                        preview.src = e.target.result;
                                                        preview.classList.remove('d-none'); // Show the image
                                                    };

                                                    reader.readAsDataURL(input.files[0]); // Read the file
                                                } else {
                                                    // If no image is selected, hide the preview
                                                    preview.classList.add('d-none');
                                                }
                                            }
                                        </script>
                                    </div>
                                    <br>
                                    <div class="text-end">
                                        <button class="btn btn-primary" name="submit" id="submit" type="submit">Submit Suggestion</button>
                                    </div>
                                </form>
                            <?php else: ?>
                                <p>No consultation data found for this user.</p>
                            <?php endif; ?>
                            <br>
                        </div>
                    </div>
                    <?php include 'importantinclude/footer.php'; ?>
                </div>
            </div>
        </div>
        <?php include 'script.php'; ?>
    </div>

</body>

</html>