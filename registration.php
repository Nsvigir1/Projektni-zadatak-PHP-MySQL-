<?php

    require_once "func_gen.php";
    
    print '
    <!DOCTYPE html>
    <html lang="en">
      <head>
        <meta charset="UTF-8" />
        <meta
          name="viewport"
          content="width=device-width, initial-scale=1.0; maximum-scale=1.0"
        />
        <meta http-equiv="content-type" content="text/html" />
        <meta name="author" content="Nikola Švigir " />
        <title>NTPWS projekt</title>
        <link rel="shortcut icon" type="image/x-icon" href="img/favicon.png" />
        <link rel="stylesheet" href="style.css" />
      </head>
    <h1>User Registration</h1>';
   
    if ($_POST['_action_'] == FALSE) {
        
        print '
        <form action="" id="inf_form" name="inf_form" method="POST">
        <input type="hidden" id="_action_" name="_action_" value="TRUE">

        <label for="firstname">First Name*</label>
        <input
            type="text"
            id="firstname"
            name="firstname"
            placeholder="Your name..."
            required
        />

        <label for="lastname">Last name*</label>
        <input
            type="text"
            id="lastname"
            name="lastname"
            placeholder="Your last name..."
            required
        />

        <label for="email">Email*</label>
        <input
            type="email"
            id="email"
            name="email"
            placeholder="Your email..."
            required
        />
        <label for="username">Username*</label>
        <input
            type="text"
            id="username"
            name="username"
            placeholder="Your Username..."
            required
        />
        <label for="password">Password*</label>
        <input
            type="password"
            id="password"
            name="password"
            placeholder="Your Password..."
            required
        />            
        <label for="country">Select country*</label>
        <select type="select" name="country" id="country" required>
            <option value="">Select country</option>';
            $query  = "SELECT * FROM countries";
            $result = @mysqli_query($MySQL, $query);
            while($row = @mysqli_fetch_array($result)) {
                print '<option value="' . $row['country_code'] . '">' 
                . $row['country_name'] . '</option>';
            }
            print'
            </select>
        <label for="city">City*</label>
        <input
            type="text"
            id="city"
            name="city"
            placeholder="Your city name..."
            required
        />
        <label for="street">Street*</label>
        <input
            type="text"
            id="street"
            name="street"
            placeholder="Your street name..."
            required
        />
        <label for="dob">Date of birth*</label>
        <input
            type="date"
            id="dob"
            name="dob"
            placeholder="Enter Date of birth..."
            required
        />
        <input type="submit" value="Submit"/>
        <br></br><br>
        </form>';
        print'
        <form method="post">
        <label for="password_gen">Generate Password and Username</label>
        <input type="text" name="username_gen" placeholder="Enter First and Last name...">
        <input type="submit" name="password_gen" value="Generate"/>'; 
        if(isset($_POST['password_gen'])) {
            if($_POST['password_gen'] == "Generate") {
                print " ". randomPassword();
                if(!$_POST['username_gen'] == ""){
                    print " ". generate_username($_POST['username_gen']);
                }
            }
        }
        print'
        </form>';
    }
    else if ($_POST['_action_'] == TRUE) {
        require_once "func_gen.php";
        $query  = "SELECT * FROM users";
        $query .= " WHERE email='" .  $_POST['email'] . "'";
		$query .= " OR username='" .  $_POST['username'] . "'";
        $result = @mysqli_query($MySQL, $query);
        $row = @mysqli_fetch_array($result);
        #$con=mysqli_connect("localhost", "root","ajeto","pwa");
        $pass_hash = password_hash($_POST['password'], PASSWORD_DEFAULT, ['cost' => 12]);

        if (!isset($row['email']) || !isset($row['username'])) {
            $query  = "INSERT INTO users (firstname, lastname, email, username, password, country, city, street, dob)";
            $query .= " VALUES ('" . $_POST['firstname'] . "', '" . $_POST['lastname'] . "', '" . $_POST['email'] . "', '" . $_POST['username'] . "', '" . $pass_hash . "'
            ,'" . $_POST['country'] . "','" . $_POST['city'] . "','" . $_POST['street'] . "','" . $_POST['dob'] . "')";
            $result = @mysqli_query($MySQL, $query);
            if($result){
                #$msg = "Registered successfully";
                echo 'Registered Successfully';
            }
            else{
                #$msg = "Error Registering";
                echo'Error Registering';
            }
        }
        else{
            print '<h3> This username or email is alredy taken! </h3> ';
        }
        
    }
?>