<?php include "includes/header.php"; ?>
    
    <!-- Navigation -->
    <?php include "includes/navigation.php"; ?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Blog Entries Column -->

            <div class="col-md-8">


                <?php 

                if (isset($_GET['p_id'])) {
                    $the_post_id = escape($_GET['p_id']);

                    $view_query = "UPDATE posts SET post_views_count = post_views_count + 1 WHERE post_id = $the_post_id ";
                    $send_query = mysqli_query($connection, $view_query);

                    if(!$send_query) {
                        die("Query Failed");
                    }

                    // SHOW DRAFT POSTS TO ADMIN AND EXCLUDE DRAFT POSTS IF NOT ADMIN

                    if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin') {
                        $query = "SELECT * FROM posts WHERE post_id = $the_post_id ";
                    } else {
                        $query = "SELECT * FROM posts WHERE post_id = $the_post_id AND post_status = 'published' ";
                    }

                    $select_all_posts_query = mysqli_query($connection,$query);

                    if(!$select_all_posts_query) {
                        die('query failed' . mysqli_error($connection));
                    }

                    if(mysqli_num_rows($select_all_posts_query) < 1) {
                        echo "<h1 class='text-center'>No posts available</h1>";
                    } else {

                        while ($row = mysqli_fetch_assoc($select_all_posts_query)) {
                            $post_title     = $row['post_title'];
                            $post_user      = $row['post_user'];
                            $post_date      = $row['post_date'];
                            $post_image     = $row['post_image'];
                            $post_content   = $row['post_content'];
                            $post_tags      = $row['post_tags'];
                        
                        ?>

                            <h1 class=" text-center page-header">
                                Post
                            </h1>

                            <!-- First Blog Post -->
                            <h2>
                                <a href="#"><?php echo $post_title ?></a>
                            </h2>
                            <p class="lead">
                                by <a href="index.php"><?php echo $post_user ?></a>
                            </p>
                            <p><span class="glyphicon glyphicon-time"></span> <?php echo $post_date ?></p>
                            <hr>
                            <img class="img-responsive" src="images/<?php echo $post_image; ?>" alt="">
                            <hr>
                            <p><?php echo $post_content ?></p>

                            <hr>

                        <?php

                        } // end while

                        ?>

                
                        <!-- Blog Comments -->
                    

                        <?php 

                        if (isset($_POST['create_comment'])) {                  

                            $the_post_id        = escape($_GET['p_id']);
                            $comment_author     = escape($_POST['comment_author']);
                            $comment_email      = escape($_POST['comment_email']);
                            $comment_content    = escape($_POST['comment_content']);


                            if(!empty($comment_author) && !empty($comment_email) && !empty($comment_content)) {

                                $query = "INSERT INTO comments (comment_post_id, comment_author, comment_email, comment_content, comment_status, comment_date) ";
                                $query .= "VALUES ($the_post_id, '{$comment_author}', '{$comment_email}', '{$comment_content}', 'Unapproved', now() ) ";

                                $create_comment_query = mysqli_query($connection, $query);
                                    
                                    if (!$create_comment_query) {
                                        die("QUERY FAILED" . mysqli_error($connection));
                                    }

                            } else {
                                echo "<script> alert('Fields cannot be empty') </script>";
                            }
                        } // end if isset

                        ?>

                        <!-- Comments Form -->
                        <div class="well">
                            <h4>Leave a Comment:</h4>
                            <form action="" method="post" role="form">

                                <div class="form-group">
                                    <label for="Author">Author</label>
                                    <input class="form-control" type="text" name="comment_author">
                                </div>

                                <div class="form-group">
                                    <label for="Email">Email</label>
                                    <input class="form-control" type="email" name="comment_email">
                                </div>

                                <div class="form-group">
                                    <label for="Comment">Your Comment</label>
                                    <textarea class="form-control" rows="3" name="comment_content"></textarea>
                                </div>


                                <button type="submit" class="btn btn-primary" name="create_comment">Submit</button>
                            </form>
                        </div>

                        <hr>

                        <!-- Posted Comments -->

                        <?php // POST COMMENTS BASED ON APPROVAL STATUS

                        $query = "SELECT * FROM comments WHERE comment_post_id = {$the_post_id} ";
                        $query .= "AND comment_status = 'approved' ";
                        $query .= "ORDER BY comment_id ASC ";

                        $select_comment_query = mysqli_query($connection, $query);

                        if(!$select_comment_query) {
                            die("QUERY FAILED" . mysqli_error($connection));
                        }

                        while ($row = mysqli_fetch_assoc($select_comment_query)) {
                            $comment_date       = $row['comment_date'];
                            $comment_content    = $row['comment_content'];
                            $comment_author     = $row['comment_author'];
                        
                        ?>
                        
                            <!-- Comment -->
                            <div class="media">
                                <a class="pull-left" href="#">
                                    <img class="media-object" src="http://placehold.it/64x64" alt="">
                                </a>
                                <div class="media-body">
                                    <h4 class="media-heading"><?php echo $comment_author; ?>
                                        <small><?php echo $comment_date; ?></small>
                                    </h4> 
                                    <?php echo $comment_content; ?>
                                </div>
                            </div>

                        <?php

                        } // end while
                    }               
                } else {
                   header("Location: index.php");
                }

                        ?>
               
            </div>

            <!-- Blog Sidebar Widgets Column -->
            <?php include "includes/sidebar.php"; ?>

        </div>
        <!-- /.row -->

        <hr>
        <?php include "includes/footer.php"; ?>