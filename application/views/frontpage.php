<?php include_once 'base.php'; ?>
<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8">
        <title>Reddit | Frontpage</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/backbone.js/1.3.3/backbone-min.js"></script>  
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
        <div class="complete">
            <br>

            <div class="container">
                <div class="row">
                    <div class="col-border col-xs-8 well">
                        <?php
                        for ($index = 0; $index < count($posts); $index++) {
                            echo '<div class="panel panel-default">';
                            echo '<div class="panel-heading">';
                            echo '<form method="post" action="http://' . gethostname() . '/reddit/index.php/welcome/display_post">';
                            echo '<input type="hidden" name="post" value="' . $posts[$index][0]->id . '">';
                            echo '<h4><input type="submit" class="submit-button" value="' . $posts[$index][0]->title . '"></h4>';
                            echo '</form>';
                            echo '</div>';
                            echo '<div class="panel-body">Community Rating: ' . ($posts[$index][0]->upvotes - $posts[$index][0]->downvotes) . ' | Submitted on ' . $posts[$index][0]->creation . '</div>';
                            echo '</div>';
                        }
                        ?>
                    </div>


                    <div class="col-border col-xs-4 well">
                        <div>
                            <a class="btn btn-large btn-info btn-block" href="<?php echo ((isset($this->session->loggedin)) && ($this->session->loggedin == 1)) ? 'share_post?page=text' : 'sessions'; ?>">
                                Share text post
                            </a>
                        </div>

                        <br>

                        <div>
                            <a class="btn btn-large btn-info btn-block" href="<?php echo ((isset($this->session->loggedin)) && ($this->session->loggedin == 1)) ? 'share_post?page=link' : 'sessions'; ?>">
                                Share link post
                            </a>
                        </div>

                        <?php if (!((isset($this->session->loggedin)) && ($this->session->loggedin == 1))) : ?>
                            <div id="signin-form">
                                <div>
                                    <h2>Reddit|Sign-In</h2>
                                    <form action="http://<?php echo gethostname(); ?>/reddit/index.php/welcome/sessions" method="post">

                                        <div class="form-group">
                                            <label for="username">Username: </label>
                                            <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="password">Password: </label>
                                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
                                        </div>

                                        <button type="submit" class="btn btn-default" id="signin">Submit</button>
                                        <button type="reset" class="btn btn-default">Reset</button>
                                    </form>
                                </div>
                                <div class="container">
                                    <p>
                                        Still haven't signed up? Come on <a href="register">sign-up</a>
                                    </p>
                                </div>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
                <div class="container">
                    <?php echo $links; ?>
                </div>
            </div>

            <script>
//                $(document).ready(function () {
//                    $("#signin").click(function (event) {
//                        event.preventDefault();
//                        var username = $("input#username").val();
//                        var password = $("input#password").val();
//                        $.ajax({
//                            method: "POST",
//                            url: "http://<?php echo gethostname(); ?>/reddit/index.php/welcome/sessions",
//                            data: {username: username, password: password},
//                            success: function (data) {
//                                $("#base-nav").load(location.href + " #base-nav");
//                                $("#signin-form").load(location.href + " #signin-form");
//                            }
//                        });
//                    });
//                });

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

                $(document).ready(function () {

                    var Session = Backbone.Model.extend({
                        urlRoot: "http://<?php echo gethostname(); ?>/reddit/index.php/welcome/sessions",
                        defaults: {
                            username: null,
                            password: null
                        }
                    });

                    var model = new Session();

                    var DisplayView = Backbone.View.extend({
                        el: ".complete",
                        model: model,
                        initialize: function () {
                            this.listenTo(this.model, "sync change", this.updateInterface);
                        },
                        events: {
                            "click #signin": "captureData"
                        },
                        captureData: function (event) {
                            event.preventDefault();
                            var username = $("input#username").val();
                            var password = $("input#password").val();
                            this.model.set({username: username, password: password});
                            this.model.save(null, {type: 'POST'});
                        },
                        updateInterface: function () {
                            $("#base-nav").load(location.href + " #base-nav");
                            $("#signin-form").load(location.href + " #signin-form");
                        },
                    });

                    var displayView = new DisplayView();

                });
            </script>
    </body>

</html>
