<?php include_once 'base.php'; ?>
<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8">
        <title>Reddit | <?php echo (isset($this->session->username) ? $this->session->username : ""); ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <style>
            .well{
                background: #fff;
            }

            .submit-button { 
                background: none;
                border: none;
                color: #fff;
                text-decoration: none;
                cursor: pointer; 
            }

            .panel-default > .panel-heading {
                background-color: rgba(3, 169, 244, 0.4);
                color: white;
            }

            .panel-default > .panel-body {
                border-color: rgba(3, 169, 244, 0.4);
            }
        </style>
    </head>

    <body>
        <br>

        <div class="container">
            <div class="row">
                <div class="col-border col-xs-8 well">
                    <h4>POSTS:</h4>
                    
                    <?php
                    $total_rating = 0;
                    
                    for ($index = 0; $index < count($posts); $index++) {
                        echo '<div class="panel panel-default">';
                        echo '<div class="panel-heading">';
                        echo '<form method="post" action="http://' . gethostname() . '/reddit/index.php/welcome/display_post">';
                        echo '<input type="hidden" name="post" value="' . $posts[$index][0]->id . '">';
                        echo '<h4><input type="submit" class="submit-button" value="' . $posts[$index][0]->title . '"></h4>';
                        echo '</form>';
                        echo '</div>';
                        $rating = $posts[$index][0]->upvotes - $posts[$index][0]->downvotes;
                        echo '<div class="panel-body">Community Rating: ' . $rating . ' | Submitted on ' . $posts[$index][0]->creation . '</div>';
                        echo '</div>';
                        
                        $total_rating += $rating;
                    }
                    ?>
                </div>
                
                <div class="col-border col-xs-4 well">
                    <div>
                        <h2>
                            <?php echo (isset($this->session->username) ? $this->session->username : ""); ?>
                        </h2>
                    </div>
                    <div>
                        <p>User Rating: <?php echo $total_rating; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </body>

</html>
