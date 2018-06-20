<?php
	session_start(); 
	if (!isset($_SESSION['UserFullName'])) { // if no user has logged in or registered
		header("location: index.php"); // redirect to the home page to force login or new registration
	}
?>

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Language" content="en" />
	
	<link rel="stylesheet" type="text/css" href="style.css" />	
	
	<title>Free Online Exam Practice Test</title>
</head>
<body>

<?php include_once('header.php'); ?>

<div id="content" class="content-width-wrapper">
	<h2>
	Welcome <i><?php echo $_SESSION['UserFullName']; // print the user full name here ?></i> to the Free Online  Practice Test
	</h2>
	<br /><br /><br />
	<h3>
		Exam takes 30 minutes and contains 10 questions.
		Each question has several options, answer a question by checking the correct option.
		If you are ready, click <a class="button" href="start.php">START</a>.
	</h3>
</div>

</body>
</html>
