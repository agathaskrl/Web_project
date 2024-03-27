function toggleCategories() {
  var categoryContainer = document.getElementById("category-container");
  categoryContainer.style.display =
    categoryContainer.style.display === "none" ? "block" : "none";
}
document.getElementById("filterbtn").addEventListener("click", function () {
  applyFilter();
});

// Function to collect selected categories and send them to the server
function applyFilter() {
  var selectedCategories = [];
  var categoryCheckboxes = document.getElementsByName("category[]");

  // Loop through checkboxes to find selected categories
  for (var i = 0; i < categoryCheckboxes.length; i++) {
    if (categoryCheckboxes[i].checked) {
      selectedCategories.push(categoryCheckboxes[i].value);
    }
  }

  // Send selected categories to the server via AJAX
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "filter_products.php", true);
  xhr.setRequestHeader("Content-Type", "application/json");

  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
      // Update the products table with the filtered products
      var productsTable = document.getElementById("products_table");
      productsTable.innerHTML = xhr.responseText;
    }
  };

  xhr.send(JSON.stringify({ categories: selectedCategories }));
}
