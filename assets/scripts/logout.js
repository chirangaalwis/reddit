$(document).ready(function () {
    $("#logout").click(function (event) {
        event.preventDefault();
        $.ajax({
            method: "DELETE",
            url: "http://<?php echo gethostname(); ?>/reddit/index.php/welcome/sessions",
            success: function (data) {
                window.location.reload();
            }
        });
    });
});
