$(document).ready(function() {

	$('#password').keyup(function(){
		$('#result').html(checkStrength($('#password').val()))
	})	
	
	function checkStrength(password){
    
	//initial strength
    var strength = 0
	
    //if the password length is less than 6, return message.
    if (password.length < 16) { 
		$('#result').removeClass()
		$('#result').addClass('short')
		return 'Zu kurz' 
	}
    
	//if value is less than 2
	if (strength > 16 ) {
		$('#result').removeClass()
		$('#result').addClass('weak')
		return 'Gültig'			
	} 	
	else {
		$('#result').removeClass()
		$('#result').addClass('strong')
		return 'Gültig'
	}
}
});