<div class="card">
    <div class="card-body">
        <form id="colorForm" method="POST" action="consultancyQuestionaire.php" onsubmit="return validateForm()" enctype="multipart/form-data">
            <h3 class="mb-2 pt-4"><b>Stage 1: Assessment</b></h3>
            <hr>
            <h3><b>Consultancy Questionnaire</b></h3>
            <h6><b>Note: Answer only if you want to undergo Product Development</b></h6>
            <h6><b>Please provide the following information.</b></h6>
            <h6>User Information</h6>
            <input type="hidden" class="form-control" name="userid" id="userid" value="<?php echo $Id ?>" required />
            <div class="row mb-3">
                <div class="col-md-6">
                    <input type="text" class="form-control" name="name" id="name" placeholder="Name" required />
                </div>
                <div class="col-md-6">
                    <input type="text" class="form-control" name="address" id="address" placeholder="Address" required />
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <input type="text" class="form-control" name="municipality" id="municipality" placeholder="Municipality" required />
                </div>
                <div class="col-md-6">
                    <input type="text" class="form-control" name="phoneNumber" id="phoneNumber" placeholder="Phone Number" required />
                </div>
            </div>
            <h6>Product Information</h6>
            <div class="row mb-3">
                <div class="col-md-4">
                    <select style="color:black;" class="form-select" name="product" id="product" required>
                        <option value="">Select a product</option>
                        <?php foreach ($products as $product): ?>
                            <option value="<?= $product['product_id']; ?>" data-name="<?= $product['productName']; ?>"><?= $product['productName']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" name="labelingformat" id="labelingformat" placeholder="Labeling Format: (stick on label, header, etc)" required />
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" name="brandName" id="brandName" placeholder="Brand Name: (ex. Coca-cola, Milo, Mt.Dew)" required />
                </div>
            </div>
            <input type="hidden" name="product_name" id="product_name" />
            <script>
                document.getElementById('product').addEventListener('change', function() {
                    var selectedOption = this.options[this.selectedIndex];
                    document.getElementById('product_name').value = selectedOption.getAttribute('data-name');
                });
            </script>
            <h6>Product Identity Name: True Name/Nature of the Food (ex. salted Peanut, Dried Mango)</h6>
            <input type="text" class="form-control mb-3" name="productIdentity" id="productIdentity" required />
            <h6>If 1 label with 2 or more product selection: Name of Product</h6>
            <div class="row mb-3">
                <div class="col-md-6">
                    <input type="text" class="form-control" name="label1" id="label1" placeholder="Label 1" required />
                </div>
                <div class="col-md-6">
                    <input type="text" class="form-control" name="label2" id="label2" placeholder="Label 2" required />
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <input type="text" class="form-control" name="label3" id="label3" placeholder="Label 3" required />
                </div>
                <div class="col-md-6">
                    <input type="text" class="form-control" name="label4" id="label4" placeholder="Label 4" required />
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <input type="text" class="form-control" name="tagline" id="tagline" placeholder="Tagline (optional)" required />
                </div>
                <div class="col-md-6">
                    <input type="text" class="form-control" name="netContent" id="netContent" placeholder="Net. Content (kg, g, ml,etc.):" required />
                </div>
            </div>

            <h6 class="">Ingredients (from most to least quantity):</h6>
            <textarea class="form-control mb-3" name="ingredients" rows="7" required></textarea>
            <h6 class="">Direction of the Product:</h6>
            <textarea class="form-control mb-3" name="ProductDirect" rows="7" required></textarea>
            <h6 class="">Concept of Design</h6>
            <textarea class="form-control mb-3" name="ConceptDesign" rows="7" required></textarea>

            <div class="row">
                <div class="col-md-6">
                    <h4 class="">Size of the Product</h4>
                    <div class="form-check form-check-inline" style="margin-left:30px;">
                        <input class="form-check-input" type="radio" name="size" value="small" required>
                        <label style="font-size: 15px;" class="form-check-label">Small</label>
                    </div>
                    <div class="form-check form-check-inline" style="margin-left:30px;">
                        <input class="form-check-input" type="radio" name="size" value="medium" required>
                        <label style="font-size: 15px;" class="form-check-label">Medium</label>
                    </div>
                    <div class="form-check form-check-inline" style="margin-left:30px;">
                        <input class="form-check-input" type="radio" name="size" value="large" required>
                        <label style="font-size: 15px;" class="form-check-label">Large</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <h6 class="">Expiry Date of the Product</h6>
                    <input class="form-control mb-3" type="date" name="expiryDate" required />
                </div>
            </div>

            <h6 class="">Notes or Other Comments</h6>
            <textarea class="form-control mb-3" name="Comment" rows="7" required></textarea>

            <h6 class="">Dominant Color to be used</h6>
            <input class="form-control mb-3" type="text" name="DominantColor" id="DominantColor" placeholder="Dominant Color to be used" required />
            <input type="hidden" id="pickedColors" name="SelectedColor" value="" required>

            <div class="form-group">
                <h6 for="displayColors">Color used in the design</h6>
                <input type="text" id="displayColors" class="form-control" readonly required>
            </div>
            <div class="form-group">
                <label for="colorDropdown">Select Colors:</label>
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle text-white" type="button" id="colorDropdownBtn" data-bs-toggle="dropdown" aria-expanded="false">
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
            <h6>Upload Draft Design (Optional):</h6>
            <input type="file" name="draft_img" class="form-control mb-3">
            <div class="text-end">
                <button type="submit" class="btn btn-primary text-white">Submit</button>
            </div>
        </form>
    </div>
</div>