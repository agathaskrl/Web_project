$(function() {
     $("#autocomplete").autocomplete({
         source: function(request, response) {
             $.ajax({
                 url: "ajax.php",
                 type: 'post',
                 dataType: "json",
                 data: {
                     search: request.term
                 },
                 success: function(data) {
                     response(data);
                 }
             });
         },
         select: function(event, ui) {
             $('#autocomplete').val(ui.item.label);
             return false;
         },
         focus: function(event, ui) {
             $("#autocomplete").val(ui.item.label);
             return false;
         }
     });
 
// Display different sections for products and categories
$("#autocomplete").data("ui-autocomplete")._renderItem = function(ul, item) {
     return $("<li>")
         .append("<div class='" + item.type.toLowerCase() + "-item'>" + item.label + "</div>")
         .appendTo(ul);
 };
});