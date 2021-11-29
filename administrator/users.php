<?php 
	echo'QQQQQ';
	# Update user profile
	if (isset($_POST['edit']) && $_POST['action'] == 'TRUE') {
		$query  = "UPDATE users SET firstName='" . $_POST['firstName'] . "', lastName='" . $_POST['lastName'] . "', email='" . $_POST['email'] . "', username='" . $_POST['username'] . "', country='" . $_POST['country'] . "' , access='" . $_POST['access'] . "'";
        $query .= " WHERE id=" . (int)$_POST['edit'];
        $query .= " LIMIT 1";
        $result = @mysqli_query($db, $query);
		# Close MySQL connection
		@mysqli_close($db);
		
		$_SESSION['message'] = '<p>You successfully changed user profile!</p>';
		
		# Redirect
		header("Location: index.php?menu=8&action=1");
	}
	# End update user profile
	
	# Delete user profile
	if (isset($_GET['delete']) && $_GET['delete'] != '') {
	
		$query  = "DELETE FROM users";
		$query .= " WHERE id=".(int)$_GET['delete'];
		$query .= " LIMIT 1";
		$result = @mysqli_query($db, $query);

		$_SESSION['message'] = '<p>You successfully deleted user profile!</p>';
		
		# Redirect
		header("Location: index.php?menu=8");
	}
	# End delete user profile
	
	
	#Show user info
	if (isset($_GET['id']) && $_GET['id'] != '') {
		$query  = "SELECT * FROM users";
		$query .= " WHERE id=".$_GET['id'];
		$result = @mysqli_query($db, $query);
		$row = @mysqli_fetch_array($result);
		print '
		<h2>User profile</h2>
		<p><b>First name:</b> ' . $row['firstname'] . '</p>
		<p><b>Last name:</b> ' . $row['lastname'] . '</p>
		<p><b>Username:</b> ' . $row['username'] . '</p>
        <p><b>Access:</b> ' . $row['access'] . '</p>';
  
		$_query  = "SELECT * FROM countries";
		$_query .= " WHERE country_code='" . $row['country'] . "'";
		$_result = @mysqli_query($db, $_query);
		$_row = @mysqli_fetch_array($_result);
		print '
		<p><b>Country:</b> ' .$_row['country_name'] . '</p>
		<p><a href="index.php?menu='.$menu.'&amp;action='.$action.'">Back</a></p>';
	}
	#Edit user profile
	else if (isset($_GET['edit']) && $_GET['edit'] != '') {
		$query  = "SELECT * FROM users";
		$query .= " WHERE id=".$_GET['edit'];
		$result = @mysqli_query($db, $query);
		$row = @mysqli_fetch_array($result);		
		print '
		<h2>Edit user profile</h2>
		<form action="" id="registration_form" name="registration_form" method="POST">
			<input type="hidden" id="action" name="action" value="TRUE">
			<input type="hidden" id="edit" name="edit" value="' . $_GET['edit'] . '">
			
			<label for="fname">First Name</label>
			<input type="text" id="fname" name="firstName" value="' . $row['firstname'] . '" placeholder="First name" required>
			<label for="lname">Last Name</label>
			<input type="text" id="lname" name="lastName" value="' . $row['lastname'] . '" placeholder="Last name" required>
				
			<label for="email">Your E-mail</label>
			<input type="email" id="email" name="email"  value="' . $row['email'] . '" placeholder="Your e-mail.." required>
			
			<label for="username">Username</label>
			<input type="text" id="username" name="username" value="' . $row['username'] . ' placeholder="Username.." required><br>

            <label for="username">Access</label>
            <select name="access" id="access">
			<option value="" selected disabled hidden>Select</option>
            <option value="administrator">Administrator</option>
            <option value="editor">Editor</option>
            <option value="user">User</option>
            </select>
            <br>
			
			<label for="country">Country</label>
			<select name="country" id="country">
				<option value="">Select</option>';
				$_query  = "SELECT * FROM countries";
				$_result = @mysqli_query($db, $_query);
				while($_row = @mysqli_fetch_array($_result)) {
					print '<option value="' . $_row['country_code'] . '"';
					if ($row['country'] == $_row['country_code']) { print ' selected'; }
					print '>' . $_row['country_name'] . '</option>';
				}
			print '
			</select>
			

			<hr>
			
			<input type="submit" value="Submit">
		</form>
		<p><a href="index.php?menu='.$menu.'&amp;action='.$action.'">Back</a></p>';
	}
	else if ($_SESSION['user']['access'] === 'administrator') {
		print '
		<h2 style="text-align:center">List of users</h2>';

        #if ($result -> num_rows !== 0 ) {
        print'
		<div id="users">
			<table  style="display:flex;flex-direction:column;align-items:center;margin-bottom:50px;">
				<thead>
					<tr>
                        <th width="35"></th>
						<th width="35"></th>
						<th width="150">First name</th>
						<th width="150">Last name</th>
						<th width="150">E mail</th>
						<th width="150">Country</th>
                        <th width="150">Access</th>
					</tr>
				</thead>
				<tbody style="border: 1px solid black">';
				$query  = "SELECT * FROM users";
				$result = @mysqli_query($db, $query);
				#$row = @mysqli_fetch_array($result);
				while($row = @mysqli_fetch_array($result)) {
					#print_r(array_keys($row));
					print '
					<tr style="text-align:left; border: 1px solid black";>
                        <td width="80; border: 1px solid black"><a href="index.php?menu='.$menu.'&amp;action='.$action.'&amp;edit=' .$row['id']. '">Edit</a></td>
                        <td width="120"><a href="index.php?menu='.$menu.'&amp;action='.$action.'&amp;delete=' .$row['id']. '">Delete</a></td>
						<td width="200;"><strong>' . $row['firstname'] . '</strong></td>
						<td width="200"> <strong>' . $row['lastname'] . '</strong></td>
						<td width="230">' . $row['email'] . '</td>
						<td width="200"">';
							$_query  = "SELECT * FROM countries";
							$_query .= " WHERE country_code='" . $row['country'] . "'";
							$_result = @mysqli_query($db, $_query);
							$_row = @mysqli_fetch_array($_result, MYSQLI_ASSOC);
							print $_row['country_name'] . '
						</td>
                        <td width="200"> '. $row['access'] .'</td>'
						 ;
						
						print '
						 </td>
					</tr>';
				}
			print '
				</tbody>
			</table>
		</div>';
    #} 
	/*else {
        print '<h3>There are no users yet!</h3>';
    }*/
	}
	
	# Close MySQL connection
	@mysqli_close($db);
?>