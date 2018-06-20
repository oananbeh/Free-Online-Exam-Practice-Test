window.onload = function() {
	var timerSapn = window.document.getElementById('timer');
	var minutesInput = window.document.getElementById('minutes');
	var secondsInput = window.document.getElementById('seconds');
	
	var minutes = minutesInput.value;
	var seconds = secondsInput.value;
	
	function refreshTime() {
		var minutesString = (minutes < 10 ? "0" : "") + minutes;
		var secondsString = (seconds < 10 ? "0" : "") + seconds;
		
		timerSapn.innerHTML = minutesString + ":" + secondsString; 
	}
	
	function secondElapsed() {
		seconds -= 1;
		if (seconds < 0) {
			seconds = 59;
			minutes -= 1;
		}
		
		minutesInput.value = minutes;
		secondsInput.value = seconds;
		
		refreshTime();
		
		if (minutes == 0 && seconds == 0) {
			//window.location.href = "score.php";
            document.forms['question-form'].submit();
		}
	}
	
	refreshTime();
	
	setInterval(function() { secondElapsed(); }, 1000);
	   
};