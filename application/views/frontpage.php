<?php $this->load->helper('url'); ?>
<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8">
        <title>...</title>
    </head>

    <body>
        <?php if ((isset($this->session->loggedin)) && ($this->session->loggedin == 1)) : ?>

            <?php
//            echo "<pre>";
//            print_r($key); // or var_dump($data);
//            echo "</pre>";
            ?>
        
            <p>
                <a href="share?page=text">Share text post</a>
            </p>
            <p>
                <a href="share?page=link">Share link post</a>
            </p>
            <p>
                <a href="logout">Logout</a>
            </p>
        <?php else: ?>
            <form action="http://<?php echo gethostname(); ?>/reddit/index.php/welcome/login" method="post">
                <p>
                    <label for="username">username: </label>
                    <input type="text" id="username" name="username" required><br />

                    <label for="password">password: </label>
                    <input type="password" id="password" name="password" required><br />

                    <input type="submit" value="Sign-In"> <input type="reset">
                </p>
            </form>
            <p>
                <a href="register">Sign-Up</a>
            </p>
        <?php endif; ?>
    </body>
</html>
