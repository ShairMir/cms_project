<?php 

// Get request user id and database data extraction
if (isset($_GET['edit_user']) && ($_SESSION['user_role'] == 'admin')) {

	$the_user_id = escape($_GET['edit_user']);

	$query = "SELECT * FROM users WHERE user_id = $the_user_id";
	$select_users_query = mysqli_query($connection, $query);

	while ($row = mysqli_fetch_assoc($select_users_query)) {

		$user_id 		= $row['user_id'];
	    $username 		= $row['username'];
	    $user_password 	= $row['user_password'];
	    $user_firstname = $row['user_firstname'];
	    $user_lastname 	= $row['user_lastname'];
	    $user_email 	= $row['user_email'];
	    $user_image 	= $row['user_image'];
	    $user_role 		= $row['user_role'];
	}
?>

<?php 
	// Post request to update user
	if (isset($_POST['edit_user'])) { 
		
		$user_firstname = escape($_POST['user_firstname']);
		$user_lastname 	= escape($_POST['user_lastname']);
		$user_role 		= escape($_POST['user_role']);
		$username 		= escape($_POST['username']);
		$user_email 	= escape($_POST['user_email']);
		$user_password 	= escape($_POST['user_password']);
		$user_date 		= escape(date('d-m-y'));

		if (!empty($user_password)) {

			$query_password = "SELECT user_password FROM users WHERE user_id = $the_user_id";
			$get_user_query = mysqli_query($connection, $query_password);
			confirmQuery($get_user_query);

			$row = mysqli_fetch_array($get_user_query);
			$db_user_password = $row['user_password'];

			//if ($db_user_password != $user_password) {
			$hashed_password = password_hash($user_password, PASSWORD_BCRYPT, array('cost' => 12) );
			//}	

			// Update DB
			$query = "UPDATE users SET ";
			$query .= "user_firstname = '{$user_firstname}', ";
			$query .= "user_lastname = '{$user_lastname}', ";
			$query .= "user_role = '{$user_role}', ";
			$query .= "username = '{$username}', ";
			$query .= "user_email = '{$user_email}', ";
			$query .= "user_password = '{$hashed_password}' ";
			$query .= "WHERE user_id = {$the_user_id}";

			$edit_user_query = mysqli_query($connection, $query);

			confirmQuery($edit_user_query);

			echo "<p class='bg-success'>User Updated. <a class='text-danger' href='../admin/users.php'>View All Users?</a></p>";
		} 
	} 
} else { // If the user id is not present in the URL, redirect to the home page
	header("Location: index.php");
}

?>

<form action="" method="post" enctype="multipart/form-data">
	
	<div class="form-group">
		<label for="title">First Name</label>
		<input type="text" value="<?php echo $user_firstname; ?>" class="form-control" name="user_firstname">
	</div>

	<div class="form-group">
		<label for="title">Last Name</label>
		<input type="text" value="<?php echo $user_lastname; ?>" class="form-control" name="user_lastname">
	</div>
	
	<div class="form-group">
		<select name="user_role" id="">
			
			<option value="<?php echo $user_role; ?>"><?php echo $user_role; ?></option>
			<?php 

			if ($user_role == 'admin') {
				echo "<option value='subscriber'>subscriber</option>";
			} else {
				echo "<option value='admin'>admin</option>";
			}

			?>
			
		</select>
	</div>

	<!-- <div class="form-group">
		<label for="post_image">Post Image</label>
		<input type="file" name="image">
	</div> -->
	
	<div class="form-group">
		<label for="post_tags">Username</label>
		<input type="text" value="<?php echo $username; ?>" class="form-control" name="username">
	</div>

	<div class="form-group">
		<label for="post_tags">Email</label>
		<input type="email" value="<?php echo $user_email; ?>" class="form-control" name="user_email">
	</div>	

	<div class="form-group">
		<label for="post_tags">Password</label>
		<input type="password" value="<?php echo $user_password; ?>" class="form-control" name="user_password">
	</div>	

	<div class="form-group">
		<input type="submit" class="btn btn-primary" name="edit_user" value="Update User">
	</div>


</form>