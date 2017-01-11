<?php include_once 'base.php'; ?>
<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8">
        <title>Reddit | Read-Vote-Comment</title>
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

            .comment-submit-button { 
                background: none;
                border: none;
                color: #000;
                text-decoration: none;
                cursor: pointer; 
            }

            .panel-default > .panel-heading {
                background-color: rgba(3, 169, 244, 0.4);
                color: white;
            }

            form { display: inline; }

            .voting-wrapper {display:inline-block;margin-left}
            .voting-wrapper .down-button {background: url(<?php echo asset_url() ?>imgs/thumbs.png) no-repeat;float: left;height: 14px;width: 16px;cursor:pointer;margin-top: 3px;}
            .voting-wrapper .down-button:hover {background: url(<?php echo asset_url() ?>imgs/thumbs.png) no-repeat 0px -16px;}
            .voting-wrapper .up-button {background: url(<?php echo asset_url() ?>imgs/thumbs.png) no-repeat -16px 0px;float: left;height: 14px;width: 16px;cursor:pointer;}
            .voting-wrapper .up-button:hover{background: url(<?php echo asset_url() ?>imgs/thumbs.png) no-repeat -16px -16px;;}
            .voting-btn{float:left;margin-right:5px;}
            .voting-btn span{font-size: 11px;float: left;margin-left: 3px;}
            
            .comment-voting-wrapper {display:inline-block;margin-left}
            .comment-voting-wrapper .comment-down-button {background: url(<?php echo asset_url() ?>imgs/thumbs.png) no-repeat;float: left;height: 14px;width: 16px;cursor:pointer;margin-top: 3px;}
            .comment-voting-wrapper .comment-down-button:hover {background: url(<?php echo asset_url() ?>imgs/thumbs.png) no-repeat 0px -16px;}
            .comment-voting-wrapper .comment-up-button {background: url(<?php echo asset_url() ?>imgs/thumbs.png) no-repeat -16px 0px;float: left;height: 14px;width: 16px;cursor:pointer;}
            .comment-voting-wrapper .comment-up-button:hover{background: url(<?php echo asset_url() ?>imgs/thumbs.png) no-repeat -16px -16px;;}
            .comment-voting-btn{float:left;margin-right:5px;}
            .comment-voting-btn span{font-size: 11px;float: left;margin-left: 3px;}
        </style>
    </head>

    <body>
        <br>
        <br>
        <div class="container">
            <div class="row">
                <div class="col-border col-xs-12 well">
                    <?php
                    $post = $post_object;
                    ?>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <?php
                            echo $post->title . ' ';

                            if ((isset($this->session->loggedin)) && ($this->session->loggedin == 1)) {
                                echo '<form action="http://' . gethostname() . '/reddit/index.php/welcome/delete_post" method="post">';
                            } else {
                                echo '<form action="http://' . gethostname() . '/reddit/index.php/welcome/sessions" method="get">';
                            }
                            echo '<input type="hidden" name="post" value="' . $post->id . '">';
                            echo '<input type="hidden" name="type" value="' . strtolower(get_class($post)) . '">';
                            echo '<input type="submit" class="submit-button" value="delete">';
                            echo '</form>';
                            ?>

                        </div>
                        <div class="panel-body">
                            <?php
                            if (isset($post->text)) {
                                echo $post->text;
                            } elseif (isset($post->link)) {
                                echo "<a href='" . $post->link . "' target='blank'>" . $post->link . "</a>";
                            }
                            ?>
                            <br>
                            <br>
                            <div id="rating">
                                Community Rating: <?php echo $post->upvotes - $post->downvotes; ?>
                            </div>
                            <div>
                                Submitted on <?php echo $post->creation; ?>
                            </div>

                            <?php
                            if ((isset($this->session->loggedin)) && ($this->session->loggedin == 1)) {
                                ?>
                                <!-- voting markup -->
                                <div class="voting-wrapper" id="voting-machine">
                                    <div class="voting-btn">
                                        <div class="up-button">&nbsp;</div><span id="up-votes"><?php echo $post->upvotes; ?></span>
                                    </div>
                                    <div class="voting-btn">
                                        <div class="down-button">&nbsp;</div><span id="down-votes"><?php echo $post->downvotes; ?></span>
                                    </div>
                                </div>
                                <!-- voting markup end -->

                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-border col-xs-12 well">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4>Comments</h4>
                        </div>
                        <div class="panel-body">
                            <?php
                            echo '<ul class="media-list">';

                            $comments = $post->comments;

                            for ($index = 0; $index < count($comments); $index++) {
                                echo '<li class="media panel-group">';
                                $comments = reindex_array($comments);
                                print_comment($comments, $comments[$index], $post->id);
                            }

                            echo '<li class="media panel-group">';
                            echo '<form method="post" action="add_comment">';
                            echo '<input type="hidden" name="post_id" value="' . $post->id . '">';
                            echo '<input type="submit" class="comment-submit-button" value="comment">';
                            echo '</form>';
                            echo '</li>';

                            echo '</ul>';

                            function print_comment(&$comments, $parent_comment, $post_id) {
                                if (isset($comments)) {
                                    echo '<div class="media-left">';
                                    echo '</div>';
                                    echo '<div class="media-body panel panel-default">';
                                    echo $parent_comment->text . '  ';
                                    echo '<form method="post" action="add_comment">';
                                    echo '<input type="hidden" name="post_id" value="' . $post_id . '">';
                                    echo '<input type="hidden" name="parent_comment" value="' . $parent_comment->id . '">';
                                    echo '<input type="submit" class="comment-submit-button" value="reply">';
                                    echo '</form>';

                                    echo '<div class="comment-voting-wrapper">';
                                    echo '<div class="comment-voting-btn">';
                                    echo '<div class="comment-up-button" id="' . $parent_comment->id . '">&nbsp;</div><span id="comment-up-votes-' . $parent_comment->id . '">' . $parent_comment->upvotes . '</span>';
                                    echo '</div>';
                                    echo '<div class="comment-voting-btn">';
                                    echo '<div class="comment-down-button" id="' . $parent_comment->id . '">&nbsp;</div><span id="comment-down-votes-' . $parent_comment->id . '">' . $parent_comment->downvotes . '</span>';
                                    echo '</div>';
                                    echo '</div>';

                                    $sub_comments = get_sub_comments($comments, $parent_comment->id);
                                    if (count($sub_comments) > 0) {
                                        foreach ($sub_comments as $sub_comment) {
                                            print_comment($comments, $sub_comment, $post_id);
                                        }
                                    }

                                    echo '</div>';
                                    echo '<br>';
                                    return;
                                } else {
                                    return;
                                }
                            }

                            function get_sub_comments(&$comments, $parent_id) {
                                $sub_comments = array();
                                if (!isset($parent_id)) {
                                    return $sub_comments;
                                }

                                $index = -1;
                                foreach ($comments as $comment) {
                                    $index++;
                                    reindex_array($comments);
                                    if ($comment->parent_id === $parent_id) {
                                        $sub_comments[] = $comment;
                                        unset($comments[$index]);
                                    }
                                }
                                return $sub_comments;
                            }

                            function reindex_array($array) {
                                $re_indexed = array();

                                foreach ($array as $element) {
                                    $re_indexed[] = $element;
                                }

                                return $re_indexed;
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function () {
                $("#voting-machine").click(function (event) {
                    event.preventDefault();
                    var clicked_button = event.target.className;

                    if (clicked_button === 'down-button') //user disliked the content
                    {
                        $.ajax({
                            method: "POST",
                            url: "http://<?php echo gethostname(); ?>/reddit/index.php/welcome/post_votes",
                            data: {post: <?php echo $post->id; ?>, type: 'DOWNVOTE'},
                            dataType: 'json',
                            success: function (response) {
                                $('#down-votes').text(response.downvotes);
                                $('#up-votes').text(response.upvotes);
                                $('#rating').text('Community Rating: ' + (response.upvotes - response.downvotes));
                            }
                        });
                    } else if (clicked_button === 'up-button') {
                        $.ajax({
                            method: "POST",
                            url: "http://<?php echo gethostname(); ?>/reddit/index.php/welcome/post_votes",
                            data: {post: <?php echo $post->id; ?>, type: 'UPVOTE'},
                            dataType: 'json',
                            success: function (response) {
                                $('#down-votes').text(response.downvotes);
                                $('#up-votes').text(response.upvotes);
                                $('#rating').text('Community Rating: ' + (response.upvotes - response.downvotes));
                            }
                        });
                    }
                });
            });

            $(document).ready(function () {
                $(".comment-voting-wrapper").click(function (event) {
                    event.preventDefault();
                    var clicked_button = event.target.id;
                    var clicked_button_class = event.target.className;

                    if (clicked_button_class === 'comment-down-button')
                    {
                        $.ajax({
                            method: "POST",
                            url: "http://<?php echo gethostname(); ?>/reddit/index.php/welcome/comment_votes",
                            data: {comment: clicked_button, type: 'DOWNVOTE'},
                            dataType: 'json',
                            success: function (response) {
                                $('#comment-down-votes-' + clicked_button).text(response.downvotes);
                                $('#comment-up-votes-' + clicked_button).text(response.upvotes);
                            }
                        });
                    } else if (clicked_button_class === 'comment-up-button') {
                        $.ajax({
                            method: "POST",
                            url: "http://<?php echo gethostname(); ?>/reddit/index.php/welcome/comment_votes",
                            data: {comment: clicked_button, type: 'UPVOTE'},
                            dataType: 'json',
                            success: function (response) {
                                $('#comment-down-votes-' + clicked_button).text(response.downvotes);
                                $('#comment-up-votes-' + clicked_button).text(response.upvotes);
                            }
                        });
                    }
                });
            });
        </script>
    </body>
</html>
