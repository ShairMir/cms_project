<?php include "db.php"; ?>
<?php session_start(); ?>

<?php 
	
// Getting the data from the login form submit button
if (isset($_POST['login'])) {

	$username = $_POST['username'];
	$password = $_POST['password'];

	$username = mysqli_real_escape_string($connection, $username);
	$password = mysqli_real_escape_string($connection, $password);
	// Make a query based on the username from the login form submit button
	$query = "SELECT * FROM users WHERE username = '{$username}' ";
	$select_user_query = mysqli_query($connection, $query); // assigning the Query-results to a variable

	if (!$select_user_query) {
		die("Query Failed" . mysqli_error($connection));
	}

	// Loop through the query result and assign variables to the row values inside DB.
	while ($row = mysqli_fetch_assoc($select_user_query)) {
		$db_user_id = $row['user_id'];
		$db_username = $row['username'];
		$db_user_password = $row['user_password'];
		$db_user_firstname = $row['user_firstname'];
		$db_user_lastname = $row['user_lastname'];
		$db_user_role = $row['user_role'];
	}

	$password = crypt($password, $db_user_password);

	// Check if the username and password match the username and password inside DB
	if ($username === $db_username && $password === $db_user_password) {
		// if a match, assign sessions to that user
		$_SESSION['username'] = $db_username;
		$_SESSION['firstname'] = $db_user_firstname;
		$_SESSION['lastname'] = $db_user_lastname;
		$_SESSION['user_role'] = $db_user_role;

	} else { // else redirect back to index.php
		header("Location: ../index.php");
	}

	// Only give admins access to admin area
	if($db_user_role === 'admin') {
		header("Location: ../admin/index.php");
	} else {
		header("Location: ../index.php");
	}
}
?>