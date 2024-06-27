//Function gia na eisagei ta dedomena on sumbit sto pinaka procuts & categories
$(document).ready(function () {
  $("#uploadForm").submit(function (event) {
    event.preventDefault();
    console.log("Form submitted");

    var fileInput = $("#file-selector")[0].files[0]; 
    if (!fileInput) {
      //An den exei peilxthei arxeio eidopoihse me mhnyma ton xrhsth
      alert("Please select a file to upload");
      return;
    }

    var formData = new FormData(this); //cretae data from the object

    $.ajax({
      type: "POST",
      url: "updatewarehouse.php",
      data: formData, //Pernaei ta dedomena ston server
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
//Function gia na diagrafei ta dedomena apo ton pinaka products kai categories
$(document).ready(function () {
  $("#deleteelementsbtn").click(function () {
    //O xrhsths epivevaiwnei oti thelei na diagracei ta arxeia
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
