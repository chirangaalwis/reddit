<?php
//include 'test.php';
//$favicon = asset_url() . 'imgs/reddit.png';
//echo $favicon;

//  start a session
session_start();

?>

<!DOCTYPE html>
<html>
    <title>Reddit</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
<!--    <link rel="icon" href="<?php echo $favicon; ?>" type="image/gif" sizes="16x16">-->
    <link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
    <style>
        .w3-navbar li a {
            padding-top: 12px;
            padding-left: 100px;
            padding-right: 100px;
            padding-bottom: 12px;
        }
    </style>
    
    <body>
        <div class="w3-container">
            <ul class="w3-navbar w3-black">
                <li><img src="">Logo</li>
                <li><a href="#">Front Page</a></li>
                <li><a href="#">About</a></li>
                
<?php if(!empty($_SESSION['loggedIn']) && !empty($_SESSION['username'])): ?>  
                <!-- handle a logged in user -->
                <li class="w3-dropdown-hover">
                    <a href='#'><?php $username ?></a>
                    <div class="w3-dropdown-content w3-white w3-card-4">
                        <a href="#">Create Post</a>
                        <a href="#">Edit Profile</a>
                        <a href="#">My Posts</a>
                        <a href="#">Logout</a>
                    </div>
                </li>
<?php elseif (!empty($_POST['username']) && !empty($_POST['password'])): {
    //  validate the user credentials
    $inputUsername = $_POST['username'];
    $inputPassword = $_POST['password'];
    
    //  TODO: to be modified
    if($inputUsername === 'chiranga') {
        $_SESSION['loggedIn'] = 1;
        $_SESSION['username'] = $inputUsername;
        
        echo "<li class='w3-dropdown-hover'>";
        echo "<a href='#'>$inputUsername</a>";
        echo "<div class='w3-dropdown-content w3-white w3-card-4'>";
        echo "<a href='#'>Create Post</a>";
        echo "<a href='#'>Edit Profile</a>";
        echo "<a href='#'>My Posts</a>";
        echo "<a href='#'>Logout</a>";
        echo "</div>";
        echo "</li>";
    }
}
?>
<?php else: ?>
                <li><a href="javascript:displayResult();">Sign-Up/LogIn</a></li>
<?php endif; ?>
            </ul>
        </div>
        
        <!-- modal to handle the sign-up/login -->
        <div id="modal" class="w3-modal">
            <div class="w3-modal-content w3-card-8 w3-animate-zoom" style="width:100%">
                <header class="w3-container w3-blue">
                    <span onclick="document.getElementById('id01').style.display='none'"
                          class="w3-closebtn w3-padding-top">&times;</span>
                    <h2>Welcome to IIT-Reddit</h2>
                </header>
                <ul class="w3-pagination w3-white w3-border-bottom" style="width:100%;">
                    <li><a href="#" class="tablink" onclick="openForm(event, 'signup')">Sign-Up</a></li>
                    <li><a href="#" class="tablink" onclick="openForm(event, 'login')">Login</a></li>
                </ul>
            </div>
            <div id="signup" class="w3-container form">
                <form method="post" action="frontpage" onsubmit="return validatePasswords()">
                    <p>
                        <input name="email" class="w3-input" type="email" required>
                        <label>Email Address</label></p>
                    <p>
                        <input name="username" class="w3-input" type="text" required>
                        <label>Username</label></p>
                    <p>
                        <input name="password" class="w3-input" type="password" required>
                        <label>Password</label></p>
                    <p>
                        <input name="confirm" class="w3-input" type="password" required>
                        <label>Confirm Password</label></p>
                    <p><input type="submit" value="Sign-Up"></p>
                </form>
            </div>
            <div id="login" class="w3-container form">
                <form method="post" action="frontpage">
                    <p>
                        <input name="username" class="w3-input" type="text" required>
                        <label>Username</label></p>
                    <p>
                        <input name="password" class="w3-input" type="password" required>
                        <label>Password</label></p>
                    <p><input type="submit" value="Login"></p>
                </form>
            </div>
        </div>
        
        <script>
            function displayResult() {
                document.getElementById('modal').style.display='block';
            }
            
            document.getElementsByClassName("tablink")[0].click();
            function openForm(event, formName) {
                document.getElementsByClassName("tablink")[0].click();
                var i, x, tablinks;
                x = document.getElementsByClassName("form");
                for (i = 0; i < x.length; i++) {
                    x[i].style.display = "none";
                }
                tablinks = document.getElementsByClassName("tablink");
                for (i = 0; i < x.length; i++) {
                    tablinks[i].classList.remove("w3-light-grey");
                }
                document.getElementById(formName).style.display = "block";
                event.currentTarget.classList.add("w3-light-grey");
            }
            
            function validatePasswords() {
                var password = document.getElementById("password").value;
                var confirmation = document.getElementById("confirm").value;
                var valid = true;
                if (password !== confirmation) {
                    alert("The password confirmation has failed. Please try again.");
                    valid = false;
                }
                return valid;
            }
        </script>
    </body>
</html>
