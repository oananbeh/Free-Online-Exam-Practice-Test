<?php 

if ($_POST) { // the user clicked Register or Login
	session_start();
	
	$connection = mysqli_connect("localhost", "root", "", "toefl") or die("Connection Error ".mysqli_error($connection));
	
	if (isset($_POST['login'])) { // the user clicked Login
		$LoginName = strip_tags($_POST['LoginName']); // get login name entered by the user
		$Password = strip_tags($_POST['Password']); // get password entered by the user
		
		if (empty($LoginName)) { // check if empty
			$error[] = 'please fill the LoginName';
		}
        if (empty($Password) )
            {
                $error[] = 'please fill the Password';
            }
		if(empty($error)) {			
            // look for the user in the database
			$sql = "SELECT * FROM users WHERE username='$LoginName' AND password='$Password'";
			$result = mysqli_query($connection, $sql)or die(mysqli_error($connection));
				
			if (mysqli_num_rows($result) == 1) { // if found
				$row = mysqli_fetch_array($result);
				extract($row);
			
				$_SESSION['UserFullName'] = "$firstName $fatherName $gFatherName $lastName";
					
				header("location: welcome.php"); // redirect to the welcome page
			}
			else { // the user not found, or not registered yet
				$error[] = 'Invalid Credentials';
			}
		}
	}
	
	if (isset($_POST['register'])) { // the user clicked Register
		// get data entered by the user
        $FirstName = strip_tags($_POST['FirstName']);
		$FatherName = strip_tags($_POST['FatherName']);
		$GrandFatherName = strip_tags($_POST['GrandFatherName']);
		$LastName = strip_tags($_POST['LastName']);
		$Birthdate = strip_tags($_POST['Birthdate']);
		$Email = strip_tags($_POST['Email']);
		$LoginName = strip_tags($_POST['LoginName']);
		$Password = strip_tags($_POST['Password']);
		$Qualification = strip_tags($_POST['qualification']);
		
        // check for empty mandatory fields
        if (empty($FirstName) ){$error[] = 'please Enter FirstName';}
        if (empty($FatherName) ){$error[] = 'please Enter FatherName';}
        if (empty($GrandFatherName) ){$error[] = 'please Enter GrandFatherName';}
        if (empty($LastName) ){$error[] = 'please Enter LastName';}
        if (empty($Birthdate) ){$error[] = 'please Enter Birthdate';}
        if (empty($Email) ){$error[] = 'please Enter Email';}
        if (empty($LoginName) ){$error[] = 'please Enter LoginName';}
        if (empty($Password) ){$error[] = 'please Enter Password';}
        
        if ($Qualification == "Baccalaureate") {
            $error[] = "Sorry!. You are not qualified for TOEFL";
        }
        
		if (empty($error)) { // no error
            // check if the username is already taken by some one else
            // as the username should be unique for each user
			$sql = "SELECT * FROM users WHERE username='$LoginName'";
			$result =  mysqli_query($connection, $sql) or die(mysqli_error($connection));
			
			if (mysqli_num_rows($result) == 1) {
				$error[] = "Username '$LoginName' is already reserved.";
			}
			
			if (empty($error)) {
                // save new user data into the database
				$sql = "INSERT INTO users(firstName, fatherName, gFatherName, lastName, birthdate, email, username, password, qualification) 
						VALUES ('$FirstName','$FatherName','$GrandFatherName','$LastName','$Birthdate','$Email','$LoginName','$Password', '$Qualification')";
				$result = $connection->query($sql) or die(mysqli_error($connection));
			
				$_SESSION['UserFullName'] = "$FirstName $FatherName $GrandFatherName $LastName";
						
				header("location: welcome.php");
			}
		}
	}
}

?>

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Language" content="en" />
	
	<link rel="stylesheet" type="text/css" href="style.css" />	
	
	<title>Free Online  Practice Test</title>
</head>
<body>

<?php include_once('header.php'); ?>

<div id="content" class="content-width-wrapper">

	<?php if (!empty($error)) { // if there is any error, show it 
    foreach($error as $er)
    {
        
    ?>
		<div id="error"><?php echo $er; ?></div>
	<?php } }?>

	<div width="100%">
		<div id="login" class="lfloat" width="50%">
		    <h2>Login</h2>
		    		    
		    <form method="post" action="index.php">
		    <center>
			    <table>
			    	<tr>
				        <td><label>Username</label></td>
			            <td><input type="text" required name="LoginName" /></td>
			        </tr>
			        <tr>
			            <td><label>Password</label></td>
			            <td><input type="password" required name="Password"></td>
			        </tr>
			        <tr>
			            <td></td>
			            <td align="right"><input type="submit" class="button" name="login" value="Login"></td>
			        </tr>
			    
			    </table>
			</center>
		    </form>
		    
		</div>
		
		<div id="register" class="rfloat" width="50%">
		    <h2>Register</h2>
		    		    
		    <form method="post" action="index.php">
		    <center>
			    <table>
			    	<tr>
				        <td><label>First Name</label></td>
			            <td><input type="text" required name="FirstName" /></td>
			        </tr>
			    	<tr>
				        <td><label>Father Name</label></td>
			            <td><input type="text" required name="FatherName" /></td>
			        </tr>
			    	<tr>
				        <td><label>Grand Father Name</label></td>
			            <td><input type="text" required name="GrandFatherName" /></td>
			        </tr>
			    	<tr>
				        <td><label>Last Name</label></td>
			            <td><input type="text" required name="LastName" /></td>
			        </tr>
			        <tr>
			            <td><label>Birth Date</label></td>
			            <td><input type="date" required name="Birthdate"></td>
			        </tr>
			        <tr>
			            <td><label>Qualification</label></td>
			            <td>
                            <select name="qualification">
                                <option value="Baccalaureate">Baccalaureate (secondary 3d)</option>
                                <option value="Bachelor">Bachelor (BSc)</option>
                                <option value="Other">Other</option>
                            </select>
                        </td>
			        </tr>
			        <tr>
			            <td><label>Email</label></td>
			            <td><input type="email" required name="Email"></td>
			        </tr>
			    	<tr>
				        <td><label>Username</label></td>
			            <td><input type="text" required name="LoginName" /></td>
			        </tr>
			        <tr>
			            <td><label>Password</label></td>
			            <td><input type="password" required name="Password"></td>
			        </tr>
			        <tr>
			            <td></td>
			            <td align="right"><input type="submit" class="button" name="register" value="Register"></td>
			        </tr>
			    
			    </table>
			</center>
		    </form>
		    
		</div>
	</div>

	<div class="clearfix"></div>

</div>

</body>
</html>
