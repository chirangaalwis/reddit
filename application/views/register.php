<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8">
        <title>...</title>
    </head>

    <body>
        <form action="http://<?php echo gethostname(); ?>/reddit/index.php/welcome/register" method="post">
            <p>
                <label for="username">username: </label>
                <input type="text" id="username" name="username" required><br />

                <label for="password">password: </label>
                <input type="password" id="password" name="password" required><br />

                <label for="email">email-address: </label>
                <input type="email" id="email" name="email" required><br />

                <input type="submit" value="send"> <input type="reset">
            </p>
        </form>
    </body>
</html>


