$(function () {
  //function for autocomplete
  function initializeAutocomplete(element) {
    element.autocomplete({
      source: function (request, response) {
        console.log("Fetching data for:", request.term);

        // Fetch data
        $.ajax({
          url: "adanajax.php",
          type: "post",
          dataType: "json",
          data: {
            search: request.term,
          },
          success: function (data) {
            response(data);
            console.log("Data received:", data);
          },
        });
      },
      select: function (event, ui) {
        console.log("Item selected:", ui.item.label);
        // Set selection
        element.val(ui.item.label); // display the selected text
        return false;
      },
      focus: function (event, ui) {
        console.log("Item focused:", ui.item.label);
        element.val(ui.item.label);
        return false;
      },
    });
  }

  initializeAutocomplete($("#autocomplete"));

  $(document).ready(function () {
    let counter = 1;

    $("#addann").on("click", function () {
      counter++;

      //Expand the form
      var newInputFields =
        '<div class="input-box">' +
        '<label for="autocomplete"> Need for item:</label>' +
        `<input type="text" name="product-input-${counter}"` +
        counter +
        '" placeholder="ex. water" class="product-input" required>' +
        "</div>" +
        '<div class="input-box">' +
        '<span class="details">Quantity</span>' +
        `<input type="number" name="quantity-input-${counter}"` +
        counter +
        '" placeholder="Quantity" class="quantity-input" required>' +
        "</div>";

      $("#form-ann").append(newInputFields);

      initializeAutocomplete($(".product-input").last());

      //Move the buttons to the end of the form
      $(".button").appendTo("#form-ann");
      $("<br>").appendTo("#form-ann");
      $("#addann").appendTo("#form-ann");

      $("#hiddenInput").val(counter);
    });
  });
});
