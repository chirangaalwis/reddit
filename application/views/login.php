<?php include_once 'base.php'; ?>
<!DOCTYPE html>
<html>
    
    <head>
        <meta charset="utf-8">
        <title>Reddit | Sign-In</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    </head>
    
    <body>
        <br>
        <br>
        <div class="container">
            <h2>Reddit|Sign-In</h2>
            <form action="http://<?php echo gethostname(); ?>/reddit/index.php/welcome/login" method="post">
                
                <div class="form-group">
                    <label for="username">Username: </label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password: </label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
                </div>
                
                <button type="submit" class="btn btn-default">Submit</button>
                <button type="reset" class="btn btn-default">Reset</button>
            </form>
        </div>
        <br>
        <br>
        <div class="container">
            <p>
                Still haven't signed up? Come on <a href="register">sign-up</a>
            </p>
        </div>
    </body>
</html>
