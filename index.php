<?php include "includes/header.php"; ?>
    
    <!-- Navigation -->
    <?php include "includes/navigation.php"; ?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Blog Entries Column -->

            <div class="col-md-8">

                <h1 class="page-header">
                    Blog Posts
                </h1>
                
                <?php 
                // PAGINATION FUNCTIONALITY

                $per_page = 3; // amount of posts per page
                $page = 'default';

                // GET request for page number
                if(isset($_GET['page'])) { 
                    $page = $_GET['page'];
                } else {
                    $page = "";
                }

                if($page == "" || $page == 1) { // if its the first page or the GET request is for page 1..
                    $page_1 = 0; // start LIMIT query with 0 when showing all posts..
                } else {
                    $page_1 = ($page * $per_page) - $per_page; // else show posts corresponding to the page number e.g. page 3 = LIMIT 10, 5
                }

                // Query to count the number of published posts and converting it into an integer
                $post_query_count = "SELECT * FROM posts WHERE post_status = 'published' ";
                $find_count = mysqli_query($connection, $post_query_count);

                $count = mysqli_num_rows($find_count); // count rows in posts table
              
                $count = ceil($count / $per_page); // convert float into integer for the count
               

                // SHOW ALL POSTS BASED ON:
                // most recent published post and limited to 5 posts for the pagination
                $query = "SELECT * FROM posts WHERE post_status = 'published' ORDER BY post_id DESC LIMIT $page_1, $per_page  "; // e.g. 10, 5 = show posts 11-15 
                $select_all_posts_query = mysqli_query($connection, $query);

                while ($row = mysqli_fetch_assoc($select_all_posts_query)) {
                    $post_id = $row['post_id'];
                    $post_title = $row['post_title'];
                    $post_user = $row['post_user'];
                    $post_date = $row['post_date'];
                    $post_image = $row['post_image'];
                    $post_content = substr($row['post_content'], 0, 150) . "...";
                    $post_tags = $row['post_tags'];
                    $post_status = $row['post_status'];

                    // show post if status is published
                    if ($post_status == 'published') {
                                  
                        ?> 

                        <!-- All Blog Posts -->
                        <h2>
                            <a href="post.php?p_id=<?php echo $post_id; ?>"><?php echo $post_title ?></a>
                        </h2>
                        <p class="lead">
                            by <a href="author_posts.php?user=<?php echo $post_user ?>&p_id=<?php echo $post_id ?>"><?php echo $post_user ?></a>
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
                    }
                }
                ?>

            </div>

            <!-- Blog Sidebar Widgets Column -->
            <?php include "includes/sidebar.php"; ?>

        </div>
        <!-- /.row -->
        

        <ul class="pager">

        <?php // SHOWING PAGINATION NUMBERS BASED ON THE ROWS COUNT OF PUBLISHED POSTS

        for($i = 1; $i <= $count; $i++) {

            if($i == $page) { // styles the clicked page by adding a class
                echo "<li><a class='active_link' href='index.php?page={$i}'>{$i}</a></li>";
            } elseif ($i == 1 && $page == "") { // styles the first page link by default
                echo "<li><a class='first_link' href='index.php?page={$i}'>{$i}</a></li>";
            } else { 
                echo "<li><a href='index.php?page={$i}'>{$i}</a></li>"; 
            }

        }

        ?>

        </ul>

        <hr>
        <?php include "includes/footer.php"; ?>