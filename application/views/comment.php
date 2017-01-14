<?php include_once 'base.php'; ?>
<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8">
        <title>Reddit | Comment</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    </head>

    <body>
        <br>
        <br>
        <div class="container">
            <h2>Reddit|Comment</h2>
            <form action="http://<?php echo gethostname(); ?>/reddit/index.php/welcome/add_comment" method="post">

                <div class="form-group">
                    <label for="comment">Your comment: </label>
                    <input type="text" class="form-control" id="comment" name="comment" placeholder="Enter comment" required>
                    <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                    <input type="hidden" name="parent_comment" value="<?php echo $parent_comment; ?>">
                </div>

                <button type="submit" class="btn btn-default">Comment</button>
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


