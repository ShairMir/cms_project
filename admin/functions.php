<?php 

function insert_categories() {
    // CREATE QUERY
    global $connection;

    if (isset($_POST['submit'])) { // input name='submit' from the form with post method
        $cat_title = trim($_POST['cat_title']); // removing spaces + refering to form with name = "cat_title"
        $cat_title = mysqli_real_escape_string($connection, $cat_title); // sanitizing

        if (empty(trim($cat_title))) { // dont allow spaces as entry
            echo "<h1>This field should not be empty</h1>";
        } else {
            $query = "INSERT INTO categories(cat_title) "; // insert into db with table categories..
            $query .= "VALUES ('{$cat_title}') "; // ..the value of string variable $cat_title

            $create_category_query = mysqli_query($connection, $query); // send the query to DB

            if(!$create_category_query) {
                die("QUERY FAILED" . mysqli_error($connection));
            }
        }
    header('Location: ' . $_SERVER['PHP_SELF']); // stop entries when refreshing with ctrl-r
    }
}





















?>