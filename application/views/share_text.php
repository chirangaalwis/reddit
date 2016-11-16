<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8">
        <title>...</title>
    </head>

    <body>
        <form action="http://<?php echo gethostname(); ?>/reddit/index.php/welcome/share_post" method="post">
            <p>
                <label for="title">title: </label>
                <input type="text" id="title" name="title" required><br />

                <label for="text-box">text: </label>
                <input type="text" id="text-box" name="text" required><br />

                <input type="hidden" value="text" name="type" />

                <input type="submit" value="send"> <input type="reset">
            </p>
        </form>
    </body>
</html>
