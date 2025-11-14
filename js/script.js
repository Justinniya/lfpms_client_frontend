// Sign up button in index.php redirecting to signuo.php
// function redirectToSignUp() {
//   window.location.href = "signup.php"; 
// }

// MSME button in signup.php redirecting to msme.php
function redirectToMSME() {
  window.location.href = "msme.php";
}
function redirectToTerms() {
  window.location.href = "pages/Terms.php"; // Redirect to terms.php
}

// Register button in msme.php redirecting to home.php
function validatePhoneNumber(input) {
  input.value = input.value.replace(/\D/g, ''); 
}

function login() {
  alert("User registered!");
  window.location.href = 'home.php'; 
}
function redirectToCustomer() {
  window.location.href = "Customer.php"; // Redirect to customer.php
}


///toggle setting in every html file that is in the nav bar
document.getElementById("userIcon").addEventListener("click", function() {
  toggleMenu();
});

let subMenu = document.getElementById("subMenu");

// Function to toggle the submenu visibility by toggling the "open-menu" class
function toggleMenu() {
  subMenu.classList.toggle("open-menu");
}

document.addEventListener('DOMContentLoaded', function() {
  var navLinks = document.querySelectorAll('nav ul li a');
  navLinks.forEach(function(link) {
    link.addEventListener('click', function() {
      navLinks.forEach(function(navLink) {
        navLink.classList.remove('active');
      });
      link.classList.add('active');
    });
  });
});

// Function to post a comment
function postComment(button) {
  var input = button.previousElementSibling;
  var comment = input.value;
  // Assuming you'll handle the logic to post the comment
  console.log("Comment:", comment);
  // Clear input after posting
  input.value = "";
}

// Function to load images dynamically
function loadImages() {
  var galleryContainer = document.getElementById("galleryContainer");

  // Sample images data
  var imagesData = [
    "path_to_image_1.jpg",
    "path_to_image_2.jpg",
    // Add more image paths here
  ];

  // Loop through the images data and create gallery items
  imagesData.forEach(function(imageSrc) {
    var galleryItem = document.createElement("div");
    galleryItem.classList.add("gallery-item");

    var img = document.createElement("img");
    img.src = imageSrc;
    img.alt = "Image";

    var commentSection = document.createElement("div");
    commentSection.classList.add("comment-section");

    var commentInput = document.createElement("input");
    commentInput.type = "text";
    commentInput.placeholder = "Your comment";

    var postButton = document.createElement("button");
    postButton.textContent = "Post";
    postButton.onclick = function() {
      postComment(this);
    };

    var ratingDiv = document.createElement("div");
    ratingDiv.classList.add("rating");

    for (var i = 1; i <= 5; i++) {
      var radioInput = document.createElement("input");
      radioInput.type = "radio";
      radioInput.name = "rating";
      radioInput.value = i;
      ratingDiv.appendChild(radioInput);
      var label = document.createTextNode(i);
      ratingDiv.appendChild(label);
    }

    commentSection.appendChild(commentInput);
    commentSection.appendChild(postButton);
    commentSection.appendChild(ratingDiv);

    galleryItem.appendChild(img);
    galleryItem.appendChild(commentSection);

    galleryContainer.appendChild(galleryItem);
  });
}

// Call the function to load images when the page loads
window.onload = loadImages;

function rate(rating) {
  console.log("Rated:", rating);
  // Perform actions with the rating, such as submitting the review
}

// Function to show the popup
function showPopup(imageSrc, title, description, event) {
    event.preventDefault(); // Prevent default link behavior

    var popupOverlay = document.getElementById("popupOverlay");
    var popupContainer = document.getElementById("popupContainer");
    var popupTitle = document.getElementById("popupTitle");
    var popupDescription = document.getElementById("popupDescription");

    // Set the image source, title, and description
    popupImage.src = imageSrc;
    popupTitle.textContent = title;
    popupDescription.textContent = description;

    // Show the popup
    popupOverlay.style.display = "block";
    popupContainer.style.opacity = 1;

    // Display comments for the current picture
    displayComments(title);
}

// Function to hide the popup
function hidePopup(event) {
    var popupOverlay = document.getElementById("popupOverlay");
    var popupContainer = document.getElementById("popupContainer");

    if (event.target === popupOverlay || event.target.tagName === "BUTTON") {
        popupOverlay.style.display = "none";
        popupContainer.style.opacity = 0;
    }
}

// Function to submit a comment
function submitComment() {
    var commentBox = document.getElementById("commentBox");
    var pastComments = document.getElementById("commentSection");

    // Get the text from the comment box
    var commentText = commentBox.value.trim();

    // Check if the comment is not empty
    if (commentText !== "") {
        // Create a new comment element
        var commentElement = document.createElement("p");
        commentElement.textContent = commentText;

        // Append the comment to the past comments section
        pastComments.appendChild(commentElement);

        // Clear the comment box after submitting
        commentBox.value = "";
    }
}
