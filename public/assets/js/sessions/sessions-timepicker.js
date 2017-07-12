/**
 * Sessions Timepicker plugin initialization
 * @author Melcher
 */


$(document).ready(function(){
	// Initialization for timepicker autocomplete script
	$('input.timepicker').timepicker({
		timeFormat: 'H:mm',
		interval: 30,
		minTime: '16:00',
		maxTime: '20:00',
		startTime: '16:00',
		dynamic: false,
		dropdown: true,
		scrollbar: true
	});
});
