<?php

session_start();

if (!isset($_SESSION['UserFullName'])) {
	header("location: index.php");
} else if (!isset($_SESSION['questions'])) {
	header("location: welcome.php");
}

if (isset($_SESSION['questions']) && count($_SESSION['questions']) > 0) {
	$connection = mysqli_connect("localhost", "root", "", "toefl") or die("Connection Error ".mysqli_error($connection));
	
	if ($_POST) { // the user clicked one the buttons Back, Next and Finish
        $startIndex = $_SESSION['StartQuestionIndex']; // get the index of the first question in the page
		$qindex = $startIndex;
		$answer = isset($_POST['option1']) ? $_POST['option1'] : -1; // get selected option for the first question
		
		$_SESSION['answers'][$qindex] = $answer; // save the user answer for the first question
        
        $shownQuestionsCount = $_SESSION['ShownQuestionsCount']; // get number of questions shown at the last time when user clicked one of the three buttons
        
        if ($shownQuestionsCount > 1) {
            $qindex = $startIndex + 1; // index of the second question in the page
            $answer = isset($_POST['option2']) ? $_POST['option2'] : -1; // get selected option for the second question
            
            $_SESSION['answers'][$qindex] = $answer; // save the user answer for the second question
        }
        
        if ($shownQuestionsCount > 2) {
            $qindex = $startIndex + 2; // index of the third question in the page
            $answer = isset($_POST['option3']) ? $_POST['option3'] : -1; // get selected option for the third question
            
            $_SESSION['answers'][$qindex] = $answer; // save the user answer for the third question
        }
		
		if (isset($_POST['next'])) { // the user clicked Next
			$startIndex = $startIndex + 3; // move the index 3 forward (3 questions per page)
		}
		
		if (isset($_POST['back'])) { // the user clicked Back
			$startIndex = $startIndex - 3; // move the index 3 backward (3 questions per page)
		}
        
        if (isset($_POST['finish'])) { // the user clicked Finish
            header("location: score.php");
        }
		
        // save time when the user clicked a button
		$_SESSION['minutes'] = $_POST['minutes'];
		$_SESSION['seconds'] = $_POST['seconds'];
        
        if ($_SESSION['minutes'] == 0 && $_SESSION['seconds'] == 0) { // check if the time is out
            header("location: score.php"); // if time is out, redirect to end the exam and show the score
        }
		
		$_SESSION['StartQuestionIndex'] = $startIndex; // save the index for the first question that will be shown in the current page
	}
	else { // this is the first time the user see the questions
		$startIndex = isset($_SESSION['StartQuestionIndex']) ? $_SESSION['StartQuestionIndex'] : 0; 
		$_SESSION['StartQuestionIndex'] = $startIndex;
    }
    
    // display a group of 3 questions

    $startIndex = $_SESSION['StartQuestionIndex']; // index for the first question in the current group
    $shownQuestions = array(); // array of the currently displayed questions
    
    $qindex = $startIndex;
    $qid = $_SESSION['questions'][$qindex]; // id of the first question
    
    // get text for the first question
    $sql = "SELECT Text FROM questions WHERE id = $qid";
    $result = $connection->query($sql) or die(mysqli_error($connection));
    $row = mysqli_fetch_array($result);
    $qText = $row['Text'];
    
    // get options for the first question and save them in the $options array
    // each option in the array will have an id and text
    $sql = "SELECT * FROM options WHERE question_id = $qid";
    $result = $connection->query($sql) or die(mysqli_error($connection));
    $options = array();
    while ($row = mysqli_fetch_array($result)) {
        $options[] = array('id'=>$row['id'], 'text'=>$row['Text']);
    }
    
    // get the selected option for this question, if the user already answered this question, value will
    // be the id of the option selected by the user for this question, if not answered yet by the user, value will be -1
    $selectedAnswer = $_SESSION['answers'][$startIndex];
    
    // add the question to the array of shown questions
    $shownQuestions[] = array(
        'qid'=>$qid,
        'text'=>$qText,
        'options'=>$options,
        'selectedAnswer'=>$selectedAnswer
    );
    
    if ($startIndex+1 < count($_SESSION['questions'])) { 
        $qindex = $startIndex + 1;
        $qid = $_SESSION['questions'][$qindex];
        
        $sql = "SELECT Text FROM questions WHERE id = $qid";
        $result = $connection->query($sql) or die(mysqli_error($connection));
        $row = mysqli_fetch_array($result);
        $qText = $row['Text'];
        
        $sql = "SELECT id, Text FROM options WHERE question_id = $qid";
        $result = $connection->query($sql) or die(mysqli_error($connection));
        $options = array();
        while ($row = mysqli_fetch_array($result)) {
            $options[] = array('id'=>$row['id'], 'text'=>$row['Text']);
        }
        
        $selectedAnswer = $_SESSION['answers'][$qindex];
        
        $shownQuestions[] = array(
            'qid'=>$qid,
            'text'=>$qText,
            'options'=>$options,
            'selectedAnswer'=>$selectedAnswer
        );
    }
    
    if ($startIndex+2 < count($_SESSION['questions'])) { 
        $qindex = $startIndex + 2;
        $qid = $_SESSION['questions'][$qindex];
        
        $sql = "SELECT Text FROM questions WHERE id = $qid";
        $result = $connection->query($sql) or die(mysqli_error($connection));
        $row = mysqli_fetch_array($result);
        $qText = $row['Text'];
        
        $sql = "SELECT id, Text FROM options WHERE question_id = $qid";
        $result = $connection->query($sql) or die(mysqli_error($connection));
        $options = array();
        while ($row = mysqli_fetch_array($result)) {
            $options[] = array('id'=>$row['id'], 'text'=>$row['Text']);
        }
        
        $selectedAnswer = $_SESSION['answers'][$qindex];
        
        $shownQuestions[] = array(
            'qid'=>$qid,
            'text'=>$qText,
            'options'=>$options,
            'selectedAnswer'=>$selectedAnswer
        );
    }
    
    $_SESSION['ShownQuestionsCount'] = count($shownQuestions); // save the number of questions shown in this page
}
else {
	header("location: welcome.php");
}

?>

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Language" content="en" />
	
	<link rel="stylesheet" type="text/css" href="style.css" />
	
	<script type="text/javascript" src="timer.js"></script>	
	
	<title>Free Online  Practice Test</title>
</head>
<body>

<?php include_once('header.php'); ?>

<div id="content" class="content-width-wrapper">

	<form name="question-form" action="question.php" method="post">
	
		<input type="hidden" id="minutes" name="minutes" value="<?php echo $_SESSION['minutes']; ?>">
		<input type="hidden" id="seconds" name="seconds" value="<?php echo $_SESSION['seconds']; ?>">
		
		<div class="lfloat">
			<h3>Questions <?php echo ($startIndex+1)." - ".($startIndex+count($shownQuestions))." From ".count($_SESSION['questions']); ?></h3>
		</div>
		<div class="rfloat">
			<h3>Remaining Time: <span id="timer"></span></h3>
		</div>
				<br/>
		<br/>

		
        <?php $r=1; for ($i = 0; $i < count($shownQuestions); $i++) { $q = $shownQuestions[$i]; ?>
            <h2><?php echo $startIndex+$r.": ". $q['text']; ?></h2>
            <br/>
            <?php foreach ($q['options'] as $opt) { ?>
                <input type="radio" name="option<?php echo $i+1; ?>" value="<?php echo $opt['id']; ?>" <?php if ($opt['id'] == $q['selectedAnswer']) echo 'checked'; ?>/>
                <?php echo $opt['text']; ?><br/>
            <?php  } ?>
        <?php $r++; } ?>
        		<div class="clearfix"></div>
		
		<br/>
		<br/>
		
		<div>
			<?php if ($startIndex > 2) { ?>
			<input type="submit" name="back" class="button" value="Back"/>
			<?php }?>
			
			<?php if ($startIndex+3 < count($_SESSION['questions']) - 1) { ?>
			<input type="submit" name="next" class="button" value="Next" />
			<?php } ?>
            
			<input type="submit" name="finish" class="button" value="Finish" />
		</div>
		
		<br/>
		<br/>

	</form>
</div>

</body>
</html>
