$(document).ready(function () {
  $(".offerbutton").click(function () {
    var row = $(this).closest("tr");
    var item = row.find("td:eq(0)").text();
    var quantity = row.find("td:eq(1)").text();
    var subm_date = row.find("td:eq(2)").text();
    var ann_id = $(this).data("ann-id");

    // Retrieve user information from session
    var name = "<?php echo addslashes($_SESSION['name']); ?>";
    var surname = "<?php echo addslashes($_SESSION['surname']); ?>";
    var cit_username = "<?php echo addslashes($_SESSION['username']); ?>";
    var phone = "<?php echo addslashes($_SESSION['phone']); ?>";

    $.ajax({
      url: "saveoffer.php",
      method: "POST",
      data: {
        ann_id: ann_id,
        item: item,
        quantity: quantity,
        subm_date: subm_date,
        name: name,
        surname: surname,
        cit_username: cit_username,
        phone: phone,
      },
      success: function (response) {
        alert(response);
        row.find(".offerbutton").replaceWith("OFFER ALREADY MADE");
      },
      error: function (xhr, status, error) {
        console.error(xhr.responseText);
      },
    });
  });
});
