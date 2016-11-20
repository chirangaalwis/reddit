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
            .submit-button { 
                background: none;
                border: none;
                color: #0066ff;
                text-decoration: underline;
                cursor: pointer; 
            }
            form { display: inline; }
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
                            <?php echo $post->title . ' '; ?>
                            <form action="http://<?php echo gethostname(); ?>/reddit/index.php/welcome/upvote_post" method="post">
                                <?php
                                echo '<input type="hidden" name="post" value="' . $post->id . '">';
                                echo '<input type="hidden" name="upvotes" value="' . $post->upvotes . '">';
                                echo '<input type="submit" class="submit-button" value="upvote">';
                                echo '  ';
                                ?>
                            </form>
                            <form action="http://<?php echo gethostname(); ?>/reddit/index.php/welcome/downvote_post" method="post">
                                <?php
                                echo '<input type="hidden" name="post" value="' . $post->id . '">';
                                echo '<input type="hidden" name="downvotes" value="' . $post->downvotes . '">';
                                echo '<input type="submit" class="submit-button" value="downvote">';
                                echo '  ';
                                ?>
                            </form>
                            <form action="http://<?php echo gethostname(); ?>/reddit/index.php/welcome/delete_post" method="post">
                                <?php
                                echo '<input type="hidden" name="post" value="' . $post->id . '">';
                                echo '<input type="hidden" name="type" value="' . strtolower(get_class($post)) . '">';
                                echo '<input type="submit" class="submit-button" value="delete">';
                                ?>
                            </form>
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
                            Community Rating: <?php echo $post->upvotes - $post->downvotes; ?>
                            <br>
                            Submitted on <?php echo $post->creation; ?>
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
                                echo '</li>';
                            }
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
                                    echo '<input type="submit" class="submit-button" value="comment">';
                                    echo '</form>';

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
    </body>
</html>
