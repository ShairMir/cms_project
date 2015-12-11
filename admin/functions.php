<?php 

function users_online() {

    if(isset($_GET['onlineusers'])) {

        global $connection;

        if (!$connection) {
            session_start();
            include("../includes/db.php");

            $session = session_id();
            $time = time();
            $time_out_in_seconds = 60;
            $time_out = $time - $time_out_in_seconds;

            $query = "SELECT * FROM users_online WHERE session = '$session'";
            $send_query = mysqli_query($connection, $query);
            $count = mysqli_num_rows($send_query);

            if ($count == NULL) {
                mysqli_query($connection, "INSERT INTO users_online(session, time) VALUES('$session', '$time')");
            } else {
                mysqli_query($connection, "UPDATE users_online SET time = '$time' WHERE session = '$session'");
            }

            $users_online_query = mysqli_query($connection, "SELECT * FROM users_online WHERE time > '$time_out' ");
            echo $count_user = mysqli_num_rows($users_online_query);
        }       
    } // get request
}

users_online();


function confirmQuery($result) {
    global $connection;
    if (!$result) {
        die("QUERY FAILED. ". mysqli_error($connection));
    }

}

function insert_categories() {
    // CREATE QUERY
    global $connection;

    if (isset($_POST['submit'])) { // input name='submit' from the form with post method
        $cat_title = trim($_POST['cat_title']); // removing spaces + refering to form with name = "cat_title"
        $cat_title = mysqli_real_escape_string($connection, $cat_title); // sanitizing

        if (empty(trim($cat_title))) { // dont allow spaces as entry
            echo "<h1>This field should not be empty</h1>";
        } else {
            $query = "INSERT INTO categories(cat_title) "; // insert into categories/cat_title db ..
            $query .= "VALUES ('{$cat_title}') "; // ..the value of string variable $cat_title

            $create_category_query = mysqli_query($connection, $query); // send the query to DB

            if(!$create_category_query) {
                die("QUERY FAILED" . mysqli_error($connection));
            }
        }
    header('Location: ' . $_SERVER['PHP_SELF']); // stop entries when refreshing with ctrl-r
    }
}


function findAllCategories() {
	global $connection;
	 // FIND ALL CATEGORIES QUERIES
    $query = "SELECT * FROM categories";
    $select_categories = mysqli_query($connection, $query);

    while($row = mysqli_fetch_assoc($select_categories)) {
        $cat_id = $row['cat_id'];
        $cat_title = $row['cat_title'];
        echo "<tr>";
        echo "<td>{$cat_id}</td>";
        echo "<td>{$cat_title}</td>";
        echo "<td><a href='categories.php?edit={$cat_id}'>Edit</a></td>";
        echo "<td><a onClick=\"javascript: return confirm('Are you sure you want to delete this category?')\" href='categories.php?delete={$cat_id}'>Delete</a></td>";
        echo "</tr>";
    }
}

function deleteCategories() {
	global $connection;
	// DELETE QUERY
    if (isset($_GET['delete'])) { // if the 'delete from line 74 is set'
        $the_cat_id = $_GET['delete'];

        $query = "DELETE FROM categories WHERE cat_id = {$the_cat_id} ";
        $delete_query = mysqli_query($connection, $query);
        header("Location: categories.php");
    }
}
















?>