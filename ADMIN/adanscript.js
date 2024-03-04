$(function() {
    $("#autocomplete").autocomplete({
        source: function(request, response) {
            console.log('Fetching data for:', request.term);

            // Fetch data
            $.ajax({
                url: "ajax.php",
                type: 'post',
                dataType: "json",
                data: {
                    search: request.term
                },
                success: function(data) {
                    response(data);
                    console.log('Data received:', data);
                }
            });
        },
        select: function(event, ui) {
            console.log('Item selected:', ui.item.label);
            // Set selection
            $('#autocomplete').val(ui.item.label); // display the selected text
            return false;
        },
        focus: function(event, ui) {
            console.log('Item focused:', ui.item.label);
            $("#autocomplete").val(ui.item.label);
            return false;
        }
    });
});