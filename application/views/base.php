<head>
    <style>
        body { padding-bottom: 70px; }
        
        body { background: rgba(3, 169, 244, 0.1) !important; }

        .navbar-custom {
            background-color: rgba(3, 169, 244, 0.4);
            color:#fff;
            border-radius:0;
        }

        .navbar-custom .navbar-nav > li > a {
            color:#fff;
            background-color: transparent;
        }
        .navbar-custom .navbar-nav > .active > a, .navbar-nav > .active > a:hover, .navbar-nav > .active > a:focus {
            color: #fff;
            background-color:transparent;
            
        }
        .navbar-custom .navbar-brand {
            color:#fff;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-custom">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand text-muted" href="#">Reddit</a>
            </div>
            <ul class="nav navbar-nav">
                <li class="active"><a href="http://<?php echo gethostname(); ?>/reddit/index.php/welcome/">Frontpage</a></li>
                <?php
                if ((isset($this->session->loggedin)) && ($this->session->loggedin == 1)) {
                    echo '<li class="dropdown">';
                    echo '<a class="dropdown-toggle" data-toggle="dropdown" href="#">';
                    if (isset($this->session->username)) {
                        echo $this->session->username;
                    }
                    echo '<span class="caret"></span>';
                    echo '<ul class="dropdown-menu">';
                    echo '<li><a href="http://' . gethostname() . '/reddit/index.php/welcome/logout">Logout</a></li>';
                    echo '</ul>';
                    echo '</a>';
                    echo '</li>';
                }
                ?>
            </ul>
        </div>
    </nav>
    <nav class="navbar navbar-default navbar-fixed-bottom">
        <div class="container">
            &copy; Copyrights Reserved | Reddit
        </div>
    </nav>
</body>
</html>

