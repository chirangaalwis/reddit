<?php include_once 'base.php'; ?>
<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8">
        <title>Reddit | Share Post</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    </head>

    <body>
        <br>
        <br>
        <div class="container">
            <h2>Reddit|Share Link</h2>
            <form action="http://<?php echo gethostname(); ?>/reddit/index.php/welcome/share_post" method="post">

                <div class="form-group">
                    <label for="title">Title: </label>
                    <input type="text" class="form-control" id="title" name="title" placeholder="Enter title" required>
                </div>
                <div class="form-group">
                    <label for="link-text">Link: </label>
                    <input type="url" class="form-control" id="link-text" name="link" placeholder="Enter url" required>
                </div>

                <input type="hidden" value="link" name="type" />

                <button type="submit" class="btn btn-default">Share</button>
                <button type="reset" class="btn btn-default">Reset</button>
            </form>
        </div>
        <script>
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
        </script>
    </body>
</html>

