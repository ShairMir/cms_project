<?php 

function confirm($result) {
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
        echo "<td><a href='categories.php?delete={$cat_id}'>Delete</a></td>";
        echo "<td><a href='categories.php?edit={$cat_id}'>Edit</a></td>";
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