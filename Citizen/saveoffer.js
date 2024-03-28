$(document).ready(function(){
    $(".offerbutton").click(function(){
        var item = $(this).closest("tr").find("td:eq(0)").text();
        var quantity = $(this).closest("tr").find("td:eq(1)").text();
        var subm_date = $(this).closest("tr").find("td:eq(2)").text(); // Assuming the submission date is in the third column
        
        // Retrieve user information from session
        var name = "<?php echo addslashes($_SESSION['name']); ?>";
        var surname = "<?php echo addslashes($_SESSION['surname']); ?>";
        var phone = "<?php echo addslashes($_SESSION['phone']); ?>";
        
        // Retrieve vehicle information from the user input
        var usernm_veh = $("#usernm_veh").val(); // Assuming you have an input field with id="usernm_veh"

        $.ajax({
            url: 'saveoffer.php',
            method: 'POST',
            data: {
                item: item,
                quantity: quantity,
                subm_date: subm_date,
                ret_date: '', //  retrieval date is not available yet
                name: name,
                surname: surname,
                phone: phone,
                usernm_veh: '' // Add the vehicle username
            },

            success: function(response) {
                alert(response);
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });
});