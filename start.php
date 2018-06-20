<?php 

session_start();
if (!isset($_SESSION['UserFullName'])) {
	header("location: index.php");
}

// connect to the database
// randomly select 10 questions maximum
$connection = mysqli_connect("localhost", "root", "", "toefl") or die("Connection Error ".mysqli_error($connection));
$sql = "SELECT id FROM questions ORDER BY RAND() LIMIT 10";
$result = $connection->query($sql) or die(mysqli_error($connection));
$questions = array();
while ($row = mysqli_fetch_array($result)) {
	$questions[] = $row['id'];
}

if (count($questions) > 0) {
    $_SESSION['questions'] = $questions; // save selected questions for this user in his session

    // initiate the answers array with -1 for all questions
    // this array will contain the id for the option selected by the user for a question
    // value -1 means the user did not answer the question yet
    $answers = array_fill(0, count($questions), -1); 
    $_SESSION['answers'] = $answers;

    // initiate the timer
    $_SESSION['minutes'] = 30;
    $_SESSION['seconds'] = 0;
    
	header("location: question.php"); // redirect to start displaying questions
}
else {
	echo 'No quesions have been added yet!'; // no questions found
}

?>