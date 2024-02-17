$(document).ready(function () {
  $("#uploadForm").submit(function (event) {
    event.preventDefault(); // Prevent the default form submission
    console.log("Form submitted"); // Check if form submission is triggered

    var formData = new FormData(this); // Create form data object

    $.ajax({
      type: "POST",
      url: "updatewarehouse.php", // Specify the URL of your PHP script
      data: formData, // Pass form data to the server
      processData: false,
      contentType: false,
      success: function (response) {
        alert(response); // Display the response from the server
      },
      error: function (xhr, status, error) {
        console.error(xhr.responseText); // Log any errors to the console
      },
    });
  });
});
