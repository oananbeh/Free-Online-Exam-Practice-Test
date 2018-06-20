<?php 

session_start();

if (!isset($_SESSION['UserFullName'])) {
	header("location: index.php");
}

$questions = $_SESSION['questions'];
$answers = $_SESSION['answers'];

$numberOfQuestions = count($questions);
$numberOfCorrectAnswers = 0;

$connection = mysqli_connect("localhost", "root", "", "toefl") or die("Connection Error ".mysqli_error($connection));

for ($i = 0; $i < $numberOfQuestions; $i++) {
	$q = $questions[$i];
	$sql = "SELECT correct_option_id FROM answers WHERE question_id = $q";
	$result = $connection->query($sql) or die(mysqli_error($connection));
	$row = mysqli_fetch_array($result);
	$a = $row['correct_option_id'];
	
	if ($a == $answers[$i]) {
		$numberOfCorrectAnswers++;
	}
}

$score = (100 * $numberOfCorrectAnswers) / $numberOfQuestions;

session_destroy();

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

	<h2>Time out!</h2>
	<br /><br /><br />
	<h3><?php echo $_SESSION['UserFullName']; ?></h3>
	<h3>Your Score is: <?php echo $score; ?>%</h3>

</div>

</body>
</html>
