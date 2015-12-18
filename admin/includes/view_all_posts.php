<?php include "delete_modal.php" ?>
<?php 
// Looping through the checBoxArray and executing code based on the value chosen in bulk_options form
if (isset($_POST['checkBoxArray']) && ($_SESSION['user_role'] == 'admin')) {
    foreach($_POST['checkBoxArray'] as $postValueId) {
        $bulk_options = $_POST['bulk_options'];


        switch($bulk_options) {
            case 'published':
                
                $query = "UPDATE posts SET post_status = '{$bulk_options}' WHERE post_id = {$postValueId} ";
                $update_to_published_status = mysqli_query($connection, $query);
                confirmQuery($update_to_published_status);
                break;

            case 'draft':
                
                $query = "UPDATE posts SET post_status = '{$bulk_options}' WHERE post_id = {$postValueId} ";
                $update_to_draft_status = mysqli_query($connection, $query);
                confirmQuery($update_to_draft_status);
                break;

            case 'delete':

                $query = "DELETE FROM posts where post_id = {$postValueId}";
                $delete_posts = mysqli_query($connection, $query);
                confirmQuery($delete_posts);
                break;

            case 'clone':

                $query = "SELECT * FROM posts WHERE post_id = '{$postValueId}' ";
                $select_post_query = mysqli_query($connection, $query);

                while($row = mysqli_fetch_assoc($select_post_query)) {
                    $post_title       = $row['post_title'];
                    $post_category_id = $row['post_category_id'];
                    $post_date        = $row['post_date'];
                    $post_author      = $row['post_author'];
                    $post_status      = $row['post_status'];
                    $post_image       = $row['post_image'];
                    $post_tags        = $row['post_tags'];
                    $post_content     = $row['post_content'];
                }

                $query = "INSERT INTO posts(post_category_id, post_title, post_author, post_date, post_status, post_image, post_tags, post_content) ";
                $query .= "VALUES({$post_category_id}, '{$post_title}', '{$post_author}', now(), '{$post_status}', '{$post_image}', '{$post_tags}', '{$post_content}') ";

                $copy_query = mysqli_query($connection, $query);
                if(!$copy_query) {
                    die("Query Failed" . mysqli_error($connection));
                }

                break;             
        }
    }
}

?>

<h1 class="page-header">
    All Posts
</h1>
                        

<form action="" method="post">
    <table class="table table-bordered table-hover">

        <div id="bulkOptionContainer" class="col-xs-4">
            <select class="form-control" name="bulk_options" id="">
                <option value="">Select Options</option>
                <option value="published">Publish</option>
                <option value="draft">Draft</option>
                <option value="delete">Delete</option>
                <option value="clone">Clone</option>
            </select>
            <br>
        </div>

        <div class="col-xs-4">
            <input type="submit" name="submit" class="btn btn-success" value="Apply">
            <a class="btn btn-primary" href="posts.php?source=add_post">Add New</a>
        </div>


        <thead>
            <tr>
                <th><input id="selectAllBoxes" type="checkbox"></th>
                <th>Id</th>
                <th>User</th>
                <th>Title</th>
                <th>Category</th>
                <th>Status</th>
                <th>Image</th>
                <th>Tags</th>
                <th>Comments</th>
                <th>Date</th>
                <th>Views</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            
            <?php // SHOWING ALL POSTS INSIDE A TABLE

            if (isset($connection)) {
                
                $query = "SELECT * FROM posts ORDER BY post_id DESC ";
                $select_all_posts = mysqli_query($connection, $query);

                // $user_query = "SELECT * FROM users";
                // $select_users = mysqli_query($user_query);

                while ($row = mysqli_fetch_assoc($select_all_posts)) {
                    $post_id            = $row['post_id'];
                    $post_author        = $row['post_author'];
                    $post_user          = $row['post_user'];
                    $post_title         = $row['post_title'];
                    $post_category_id   = $row['post_category_id'];
                    $post_status        = $row['post_status'];
                    $post_image         = $row['post_image'];
                    $post_tags          = $row['post_tags'];
                    $post_comment_count = $row['post_comment_count'];
                    $post_date          = $row['post_date'];
                    $post_views_count   = $row['post_views_count'];

                    echo "<tr>";

                    ?>
                    
                    <!-- checkboxes for each post_id  -->
                    <td><input class='checkBoxes' type='checkbox' name='checkBoxArray[]' value='<?php echo $post_id; ?>'></td>
                    
                    <?php

                    echo "<td>{$post_id}</td>";


                    if (!empty($post_user)) {
                        echo "<td>{$post_user}</td>";
                    } else {
                        echo "<td>{$post_author}</td>";
                    }

                    echo "<td><a href='../post.php?p_id={$post_id}'>{$post_title}</a></td>";

                    // Query for showing the post categories dynamically
                    $query = "SELECT * FROM categories WHERE cat_id = $post_category_id ";
                    $select_categories_id = mysqli_query($connection, $query);

                    while ($row = mysqli_fetch_assoc($select_categories_id)) {
                        $cat_id = $row['cat_id'];
                        $cat_title = $row['cat_title'];

                        echo "<td>{$cat_title}</td>";
                    }

                    echo "<td>{$post_status}</td>";
                    echo "<td><img src='../images/{$post_image}' width='100' alt = 'image'></td>";
                    echo "<td>{$post_tags}</td>";

                    $query = "SELECT * FROM comments WHERE comment_post_id = $post_id AND comment_status = 'approved' ";
                    $send_comment_query = mysqli_query($connection, $query);

                    $row = mysqli_fetch_array($send_comment_query);
                    $comment_id = $row['comment_id'];
                    $count_comments = mysqli_num_rows($send_comment_query);

                    echo "<td><a href='post_comments.php?id=$post_id'>{$count_comments}</a></td>";

                    echo "<td>{$post_date}</td>";
                    echo "<td><a onClick=\"javascript: return confirm('Are you sure you want to reset the view count?')\" href='posts.php?reset=$post_id' >$post_views_count</a></td>";
                    echo "<td><a href='posts.php?source=edit_post&p_id={$post_id}'><i class='fa fa-pencil-square-o fa-lg'></i></a></td>";
                    echo "<td><a rel='$post_id' href='javascript:void(0)' class='delete_link'><i class='fa fa-times fa-lg'></i></a></td>";
                    // echo "<td><a onClick=\"javascript: return confirm('Are you sure you want to delete this post?')\" href='posts.php?delete=$post_id'><i class='fa fa-times fa-lg'></i></a></td>";
                    echo "</tr>";
                }        
            } 

            ?>

        </tbody>
     </table>
 </form>


 <?php 

if ($_SESSION['user_role'] == 'admin') {
   
    // DELETING POSTS BASED ON THE POST_ID RECEIVED FROM GET REQUEST
    if(isset($_GET['delete'])) {
        $the_post_id = escape($_GET['delete']);

        $query = "DELETE FROM posts WHERE post_id = $the_post_id";
        $delete_query = mysqli_query($connection, $query);
        
        header("Location: posts.php");
    }

    // RESETTING POSTS VIEW COUNT BASED ON THE POST_ID RECEIVED FROM GET REQUEST
    if(isset($_GET['reset'])) {
        $the_post_id = escape($_GET['reset']);

        $query = "UPDATE posts SET post_views_count = 0 WHERE post_id = {$the_post_id}";
        $reset_view_count_query = mysqli_query($connection, $query);
        
        header("Location: posts.php");
    }
}

?>

<script type="text/javascript">
    
    $(document).ready(function() {
        

        $(".delete_link").on('click', function(){
            
            var id = $(this).attr("rel");
            var delete_url = "posts.php?delete="+ id +" ";

            $(".model_delete_link").attr("href", delete_url);

            $("#myModal").modal('show');


        });


    });

</script>

