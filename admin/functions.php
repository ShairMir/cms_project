<?php 

function insert_categories() {
	// CREATE QUERY
	global $connection;
    if (isset($_POST['submit'])) {
        $cat_title = $_POST['cat_title'];
        $cat_title = mysqli_real_escape_string($connection, $cat_title);

        if ($cat_title == "" || empty($cat_title)) {
            echo "<h1>This field should not be empty</h1>";
        } else {
            $query = "INSERT INTO categories(cat_title) ";
            $query .= "VALUES ('{$cat_title}') ";

            $create_category_query = mysqli_query($connection, $query);

            if(!$create_category_query) {
                die("QUERY FAILED" . mysqli_error($connection));
            }
        }
    header('Location: '.$_SERVER['PHP_SELF']);
    }
}






















?>