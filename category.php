<?php include "includes/header.php"; ?>
    
    <!-- Navigation -->
    <?php include "includes/navigation.php"; ?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Blog Entries Column -->

            <div class="col-md-8">
                
                <h1 class="text-center page-header">
                    All Category Posts of 
                    <?php echo $_GET['title']; ?>
                </h1>

                <?php 
                
                if (isset($_GET['category'])) {
                    $post_category_id = escape($_GET['category']);

                    if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin') {
                        $query = "SELECT * FROM posts WHERE post_category_id = $post_category_id";
                    } else {
                        $query = "SELECT * FROM posts WHERE post_category_id = $post_category_id AND post_status = 'published' ";
                    }
                    
                    $select_all_posts_query = mysqli_query($connection, $query);

                    if(mysqli_num_rows($select_all_posts_query) < 1) {
                        echo "<h1 class='text-center'>No posts available</h1>";
                    } else {

                        while ($row = mysqli_fetch_assoc($select_all_posts_query)) {
                            $post_id = $row['post_id'];
                            $post_title = $row['post_title'];
                            $post_user = $row['post_user'];
                            $post_date = $row['post_date'];
                            $post_image = $row['post_image'];
                            $post_content = substr($row['post_content'], 0, 150) . "...";
                            $post_tags = $row['post_tags'];
                        
                        ?>

                            <!-- All Blog Posts -->
                            <h2>
                                <a href="post.php?p_id=<?php echo $post_id; ?>"><?php echo $post_title ?></a>
                            </h2>
                            <p class="lead">
                                by <a href="index.php"><?php echo $post_user ?></a>
                            </p>
                            <p><span class="glyphicon glyphicon-time"></span> <?php echo $post_date ?></p>
                            <hr>
                            <a href="post.php?p_id=<?php echo $post_id; ?>">
                                <img class="img-responsive" src="images/<?php echo $post_image; ?>" alt="">
                            </a>
                            <hr>
                            <p><?php echo $post_content ?></p>
                            <a class="btn btn-primary" href="post.php?p_id=<?php echo $post_id?>">Read More <span class="glyphicon glyphicon-chevron-right"></span></a>

                            <hr>

                <?php
                        } // end while
                    } // end else
                } else {
                    header("Locations: index.php");
                } 

                ?>


                

            </div>

            <!-- Blog Sidebar Widgets Column -->
            <?php include "includes/sidebar.php"; ?>

        </div>
        <!-- /.row -->

        <hr>
        <?php include "includes/footer.php"; ?>