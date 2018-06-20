<?php

$questionText = "";
$options = array_fill(0, 6, "");
$optionsIds = array_fill(0, 6, -1);
$correctOptionIndex = -1;
$questionId = -1;

if ($_POST) {
    $connection = mysqli_connect("localhost", "root", "", "toefl") or die("Connection Error ".mysqli_error($connection));
    
    $isDataComplete = true;
    
    $questionText = $_POST['question-text'];
    if (empty($questionText)) {
        echo 'please fill in the question text field <br/>';
        $isDataComplete = false;
    }
    
    $options = $_POST['question-options'];
    $optionsCount = 0;
    foreach ($options as $o) {
        if (!empty($o)) {
            $optionsCount++;
        }
    }
    
    if ($optionsCount < 2) {
        echo 'please provide at least two options <br/>';
        $isDataComplete = false;        
    }
    
    $correctOptionIndex = $_POST['correct-option'];
    
    if ($isDataComplete) {
        $sql = "INSERT INTO questions (Text) VALUES ('$questionText')";
		$connection->query($sql) or print(mysqli_error($connection));
        $questionId = mysqli_insert_id($connection);
        
        for ($i = 0; $i < count($options); $i++) {
            $o = $options[$i];
            if (!empty($o)) {
                $sql = "INSERT INTO options (Text, question_id) VALUES ('$o', $questionId)";
                $connection->query($sql) or print(mysqli_error($connection));
                $oId = mysqli_insert_id($connection);
                $optionsIds[$i] = $oId;
            }
        }
        
        $correctOptionId = $optionsIds[$correctOptionIndex];
        $sql = "INSERT INTO answers (question_id, correct_option_id) VALUES ($questionId, $correctOptionId)";
		mysqli_query($connection, $sql) or die(mysqli_error($connection));
        $questionId = mysqli_insert_id($connection);
        
        $questionText = "";
        $options = array_fill(0, 6, "");
        $optionsIds = array_fill(0, 6, -1);
        $correctOptionIndex = -1;
        $questionId = -1;
    }
}

?>

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Language" content="en" />
	
	<link rel="stylesheet" type="text/css" href="style.css" />
	
	<script type="text/javascript" src="timer.js"></script>	
	
	<title>Free Online TOEFL Practice Test</title>
</head>
<body>

<?php include_once('header.php'); ?>

<div id="content" class="content-width-wrapper">

	<form action="add-question.php" method="post">		
		<div class="lfloat">
            <table>
                <tr>
                    <td valign="top"><label>Question:</label></td>                        
                    <td><textarea name="question-text"><?php echo $questionText; ?></textarea></td>
                </tr>
                
                <tr>
                    <td><label>Option 1:</label></td>
                    <td><input type="text" name="question-options[]" value="<?php echo $options[0]; ?>" /></td>
                </tr>
                
                <tr>
                    <td><label>Option 2:</label></td>
                    <td><input type="text" name="question-options[]" value="<?php echo $options[1]; ?>" /></td>
                </tr>
                
                <tr>
                    <td><label>Option 3:</label></td>
                    <td><input type="text" name="question-options[]" value="<?php echo $options[2]; ?>" /></td>
                </tr>
                
                <tr>
                    <td><label>Option 4:</label></td>
                    <td><input type="text" name="question-options[]" value="<?php echo $options[3]; ?>" /></td>
                </tr>
                
                <tr>
                    <td><label>Option 5:</label></td>
                    <td><input type="text" name="question-options[]" value="<?php echo $options[4]; ?>" /></td>
                </tr>
                
                <tr>
                    <td><label>Option 6:</label></td>
                    <td><input type="text" name="question-options[]" value="<?php echo $options[5]; ?>" /></td>
                </tr>
                
                <tr>
                    <td><label>Correct Option:</label></td>
                    <td>
                        <select name="correct-option">
                            <option value="0">Option 1</option>
                            <option value="1">Option 2</option>
                            <option value="2">Option 3</option>
                            <option value="3">Option 4</option>
                            <option value="4">Option 5</option>
                            <option value="5">Option 6</option>
                        </select>
                    </td>
                </tr>
                
                <tr>
                    <td></td>
                    <td><input type="submit" name="addQuestion" value="Add Question" /></td>
                </tr>
            </table>
		</div>
	</form>
</div>

</body>
</html>