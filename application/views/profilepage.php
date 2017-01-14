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
                <div class="col-border col-xs-9 well">

                    <div class="panel panel-default">
                        <div class="panel-body">

                            <ul class="nav nav-tabs">
                                <li class="active"><a data-toggle="tab" href="#tab1">Posts</a>
                                </li>
                                <li><a data-toggle="tab" href="#tab2">Comments</a>
                                </li>
                            </ul>

                            <div id="tab-content" class="tab-content">
                                <div id="tab1" class="tab-pane fade in active">
                                    <div class="row">
                                        <div class="col-sm-12">
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
                                    </div>
                                </div>
                                <div id="tab2" class="tab-pane fade">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <?php
                                            for ($index = 0; $index < count($comments); $index++) {
                                                echo '<div class="panel panel-default">';
                                                echo '<div class="panel-heading">';
                                                echo '<h4>' . $parent_posts[$index]->title . '</h4>';
                                                echo '</div>';
                                                $rating = $comments[$index]->upvotes - $comments[$index]->downvotes;
                                                echo '<div class="panel-body"><strong>' . $comments[$index]->text . '</strong><br>Community Rating: ' . $rating . ' | Submitted on ' . $posts[$index][0]->creation . '</div>';
                                                echo '</div>';

                                                $total_rating += $rating;
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

                <div class="col-border col-xs-3 well">
                    <div class="card" style="width: 20rem;">
                        <img class="card-img-top" src="<?php echo asset_url() ?>imgs/avatar.png" alt="<?php echo (isset($this->session->username) ? $this->session->username : ""); ?>">
                        <div class="card-block">
                            <h4 class="card-title"><?php echo (isset($this->session->username) ? $this->session->username : ""); ?></h4>
                            <p class="card-text">User Rating: <?php echo $total_rating; ?></p>
                        </div>
                    </div>
                </div>
            </div>
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
