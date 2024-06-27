$(function () {
  //function gia to autocomplete
  function initializeAutocomplete(element) {
    element.autocomplete({
      source: function (request, response) {
        console.log("Fetching data for:", request.term);

        // Fernei ta dedomena me texnikes AJAX
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
    
        element.val(ui.item.label);
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

      //Expand th forma me to koumpi + 
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

      //Metakinhs twn koumpiwn sto telos ths formas
      $(".button").appendTo("#form-ann");
      $("<br>").appendTo("#form-ann");
      $("#addann").appendTo("#form-ann");

      $("#hiddenInput").val(counter);
    });
  });
});
