$(document).ready(function () {
  $("#uploadForm").submit(function (event) {
    event.preventDefault();
    console.log("Form submitted");

    var fileInput = $("#file-selector")[0].files[0]; 
    if (!fileInput) {
      //if no file is selected alert the user
      alert("Please select a file to upload");
      return;
    }

    var formData = new FormData(this); //cretae data from the object

    $.ajax({
      type: "POST",
      url: "updatewarehouse.php",
      data: formData, //pass the data from the form to the server
      processData: false,
      contentType: false,
      success: function () {
        alert("Insertion successful!");
        location.reload(); 
      },
      error: function (xhr) {
        console.error(xhr.responseText); 
      },
    });
  });
});

$(document).ready(function () {
  $("#deleteelementsbtn").click(function () {
    //user must confirm 
    if (confirm("Are you sure you want to delete all elements?")) {
      $.ajax({
        type: "POST",
        url: "updatewarehouse.php",
        data: { truncate_elements: true },
        success: function () {
          alert("Elements deleted successful!");
          location.reload();
        },
        error: function (xhr) {
          console.error(xhr.responseText);
        },
      });
    }
  });
});
