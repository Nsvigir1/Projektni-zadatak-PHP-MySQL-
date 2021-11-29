<?php 
	print '
	<h1>Sign In </h1>
	<div id="signin">';
	
	if ($_POST['action'] == FALSE) {
		print '
		<form action="" name="myForm" id="myForm" method="POST">
			<input type="hidden" id="action" name="action" value="TRUE">

			<label for="username">Username:</label>
			<input type="text" id="username" name="username" value=""  required>
									
			<label for="password">Password:</label>
			<input type="password" id="password" name="password" value=""  required>
									
			<input type="submit" value="Submit">
		</form>';
	}
	else if ($_POST['action'] == TRUE) {
		$query  = "SELECT * FROM users";
		$query .= " WHERE username='" .  $_POST['username'] . "'";
		$result = @mysqli_query($db, $query);
		$row = @mysqli_fetch_array($result, MYSQLI_ASSOC);

		if (password_verify($_POST['password'], $row['password'])) {
			$_SESSION['user']['valid'] = 'true';
			$_SESSION['user']['id'] = $row['id'];
			$_SESSION['user']['firstname'] = $row['firstname'];
			$_SESSION['user']['lastname'] = $row['lastname'];
			$_SESSION['user']['access'] = $row['access'];
			$_SESSION['message'] = '<p>Welcome, ' . $_SESSION['user']['firstname'] . ' ' . $_SESSION['user']['lastname'] . '</p>';
			header("Location: index.php?menu=1");
		}
		
		# Wrong username or password
		else {
			unset($_SESSION['user']);
			$_SESSION['message'] = '<p>You entered wrong email or password!!!!</p> ';
			header("Location: index.php?menu=7");
		}
	}
	print '
	</div>';
?>
