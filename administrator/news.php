<?php 

    # Add news
	if (isset($_POST['action']) && $_POST['action'] == 'new_news') {
		$_SESSION['message'] = '';
		$permision = 1; #Display news to all users
		if ($_SESSION['user']['access'] === 'user') {
			$permision = 0;
		}

		$query  = "INSERT INTO news (title, description, date, permision)";
		$query .= " VALUES ('" . $_POST['title'] . "', '" . $_POST['description'] . "', '" . date("Y-m-d G:i") . "', '".$permision."')";
		$result = @mysqli_query($db, $query);
        $ID = mysqli_insert_id($db);

        # picture
        if($_FILES['picture']['error'] == UPLOAD_ERR_OK && $_FILES['picture']['name'] != "") {
                
			$ext = strtolower(strrchr($_FILES['picture']['name'], "."));
			
            $_picture = $ID . '-' . rand(1,100) . $ext;
			copy($_FILES['picture']['tmp_name'], "news/".$_picture);
			
			if ($ext == '.jpg' || $ext == '.png' || $ext == '.gif') { 
				$_query  = "UPDATE news SET picture='" . $_picture . "'";
				$_query .= " WHERE id=" . $ID . " LIMIT 1";
				$_result = @mysqli_query($db, $_query);
				$_SESSION['message'] .= '<p>You successfully added picture.</p>';
			}
        }
		
		
		$_SESSION['message'] .= '<p>You successfully added news!</p>';
		header("Location: index.php?menu=8&action=2");

	}
	

    # Edit news
	if (isset($_POST['action']) && $_POST['action'] == 'edit_news' && ($_SESSION['user']['access'] === 'administrator' || $_SESSION['user']['access'] === 'editor')) {
		$query  = "UPDATE news SET title='" .$_POST['title']. "', description='" .$_POST['description']. "', permision='".$_POST['permision']."'";
        $query .= " WHERE id=" . (int)$_POST['edit'];
        $query .= " LIMIT 1";
        $result = @mysqli_query($db, $query);

		$ID = mysqli_insert_id($db);

        # picture
        if($_FILES['picture']['error'] == UPLOAD_ERR_OK && $_FILES['picture']['name'] != "") {
                
			# strtolower - Returns string with all alphabetic characters converted to lowercase. 
			# strrchr - Find the last occurrence of a character in a string
			$ext = strtolower(strrchr($_FILES['picture']['name'], "."));
            
			$_picture = (int)$_POST['edit'] . '-' . rand(1,100) . $ext;
			copy($_FILES['picture']['tmp_name'], "news/".$_picture);
			
			
			if ($ext == '.jpg' || $ext == '.png' || $ext == '.gif') { # test if format is picture
				$_query  = "UPDATE news SET picture='" . $_picture . "'";
				$_query .= " WHERE id=" . (int)$_POST['edit'] . " LIMIT 1";
				$_result = @mysqli_query($db, $_query);
				$_SESSION['message'] .= '<p>You successfully added picture.</p>';
			}
        }
		
		$_SESSION['message'] = '<p>You successfully changed news!</p>';
		header("Location: index.php?menu=8&action=2");

       
	}
	
    # Delete news
	if (isset($_GET['delete']) && $_GET['delete'] != '') {
		
        # Remove news
		$query  = "DELETE FROM news";
		$query .= " WHERE id=".(int)$_GET['delete'];
		$query .= " LIMIT 1";
		$result = @mysqli_query($db, $query);

        # Remove picture
        $query  = "SELECT picture FROM news";
        $query .= " WHERE id=".(int)$_GET['delete']." LIMIT 1";
        $result = @mysqli_query($db, $query);
        $row = @mysqli_fetch_array($result);
        @unlink("news/".$row['picture']); 

		$_SESSION['message'] = '<p>You successfully deleted news!</p>';
		header("Location: index.php?menu=8&action=2");

	}
	
	if (isset($_GET['id']) && $_GET['id'] != '') {
		$query  = "SELECT * FROM news";
		$query .= " WHERE id=".$_GET['id'];
		$query .= " ORDER BY date DESC";
		$result = @mysqli_query($db, $query);
		$row = @mysqli_fetch_array($result);
		print '
		<h2>News overview</h2>
		<div class="news">
			<img src="news/' . $row['picture'] . '" alt="' . $row['title'] . '" title="' . $row['title'] . '">
			<h2>' . $row['title'] . '</h2>
			' . $row['description'] . '
			<time datetime="' . $row['date'] . '">' . pickerDateToMysql($row['date']) . '</time>
			<hr>
		</div>
		<p><a href="index.php?menu='.$menu.'&amp;action='.$action.'">Back</a></p>';
	}
	
    # Add news
	else if (isset($_GET['add']) && $_GET['add'] != '') {
		print '
		<h2>Add news</h2>
		<form style="display:flex;gap:10px" action="" id="news_form" name="news_form" method="POST" enctype="multipart/form-data">
			<input type="hidden" id="action" name="action" value="new_news">
			
			<label for="title">Title</label>
			<input type="text" id="title" name="title" placeholder="News title" required>
			<label for="description">Description</label>
			<textarea rows="12" cols="80" id="description" name="description" placeholder="Description" required></textarea>
				
			<label for="picture">Picture</label>
			<input type="file" id="picture" name="picture">

			<label for="imageDesc">Image description</label>
			<input required placeholder="Image description" type="text" id="imageDesc" name="imageDesc">
			<hr>

			<input type="submit" value="Submit">
		</form>
		<p><a href="index.php?menu='.$menu.'&amp;action='.$action.'">Back</a></p>';
	}



	#Edit news
	else if (isset($_GET['edit']) && $_GET['edit'] != '' && ($_SESSION['user']['access'] === 'administrator' || $_SESSION['user']['access'] === 'editor')) {
		$query  = "SELECT * FROM news";
		$query .= " WHERE id=".$_GET['edit'];
		$result = @mysqli_query($db, $query);
		$row = @mysqli_fetch_array($result);

		print '
		<h2>Edit news</h2>
		<form action="" id="news_form_edit" name="news_form_edit" method="POST" enctype="multipart/form-data">
			<input type="hidden" id="action" name="action" value="edit_news">
			
			<input type="hidden" id="edit" name="edit" value="' . $row['id'] . '">

			<label for="title">Title</label>
			<input type="text" id="title" name="title" value="' . $row['title'] . '" placeholder="Title" required>

			<label for="description">Description *</label>
			<textarea id="description" name="description" placeholder="Description" required>' . $row['description'] . '</textarea>

			<label for="picture">Picture</label>
			<input type="file" id="picture" name="picture">

			<label for="permision">Permision</label>
            <select name="permision" id="permision">
			<option value="" selected disabled hidden>Select</option>
            <option value="1">True</option>
            <option value="0">False</option>
            </select>

			<hr>
			
			<input type="submit" value="Submit">
		</form>
		<p><a href="index.php?menu='.$menu.'&amp;action='.$action.'">Back</a></p>';
	}
	else {
		print '
		<h2 style="text-align:center">News</h2>
		<br>
		<div id="news">
			<table style="display:flex;flex-direction:column;align-items:center;">
				<thead>
					<tr style="text-align:center; border: 1px solid black";>
						<th width="50"></th>
						'; 
						if ($_SESSION['user']['access'] === 'administrator' || $_SESSION['user']['access'] === 'editor') {
							print '
							<th width="50"></th>
							<th width="50"></th>';
						}
						print '
						<th width="200">Title</th>
						<th width="500">Description</th>
						<th width="200">Date</th>
						<th width="300">Picture</th>
						<th width="200"></th>
					</tr>
				</thead>
				<tbody style="display: flex;flex-direction:column;row-gap:30px;">';
				$query  = "SELECT * FROM news";
				$query .= " ORDER BY date DESC";
				$result = @mysqli_query($db, $query);
				while($row = @mysqli_fetch_array($result)) {
					print '
					<tr style="text-align:center; border: 1px solid black";>
						<td width="50"><a href="index.php?menu='.$menu.'&amp;action='.$action.'&amp;id=' .$row['id']. '">Full Article</a></td>';

						if ($_SESSION['user']['access'] === 'administrator' || $_SESSION['user']['access'] === 'editor') {
							print '
							<td width="50"><a href="index.php?menu='.$menu.'&amp;action='.$action.'&amp;edit=' .$row['id']. '">Edit</a></td>
							<td width="50"><a href="index.php?menu='.$menu.'&amp;action='.$action.'&amp;delete=' .$row['id']. '">Delete</a></td>
							';
						} print '
						<td width="200">' . $row['title'] . '</td>
						<td width="500">';
						if(strlen($row['description']) > 160) {
                            echo substr(strip_tags($row['description']), 0, 100).'...';
                        } else {
                            echo strip_tags($row['description']);
                        }
						print '
						</td>
						<td width="200">' . $row['date'] . '</td>
						<td width="300">'; echo '<img src="news/'.( $row['picture'] ).'"/></td>';
						print'
						<td width="200">
						</td>
					</tr>';
				}
			print '
				</tbody>
			</table>
			
		</div>
		<a style="display: flex;justify-content: left;margin: 30px" href="index.php?menu=' . $menu . '&amp;action=' . $action . '&amp;add=true" class="AddLink">Add news</a>'; 
	}

	# Close MySQL connection
	@mysqli_close($db);
?>