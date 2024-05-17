$(document).ready(function () {
  $(".offerbutton").click(function () {
    var item = $(this).closest("tr").find("td:eq(0)").text();
    var quantity = $(this).closest("tr").find("td:eq(1)").text();
    var subm_date = $(this).closest("tr").find("td:eq(2)").text();

    // Retrieve user information from session
    var name = "<?php echo addslashes($_SESSION['name']); ?>";
    var surname = "<?php echo addslashes($_SESSION['surname']); ?>";
    var phone = "<?php echo addslashes($_SESSION['phone']); ?>";

    // Retrieve vehicle information from the user input
    var usrnm_veh = $("#usrnm_veh").val();

    $.ajax({
      url: "saveoffer.php",
      method: "POST",
      data: {
        item: item,
        quantity: quantity,
        subm_date: subm_date,
        name: name,
        surname: surname,
        phone: phone,
        usrnm_veh: usrnm_veh,
      },
      success: function (response) {
        alert(response);
        window.location.href = "offers.php";
      },
      error: function (xhr, status, error) {
        console.error(xhr.responseText);
      },
    });
  });
});
