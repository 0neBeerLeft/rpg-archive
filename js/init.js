// Initialize JavaScript bccf3 18c72dda387 88a4cee258dcfda8
// RagBrasil
// -------------------------------------------------------
// CSS Hax
// -------------------------------------------------------

$("#navbar ul li:last").addClass('noborder');
$("#secondary .stat-icon:first").addClass('first');
$(".sb-ranking tr:odd").addClass('odd');

// -------------------------------------------------------
// Social Icons Animation
// -------------------------------------------------------
$('.social a').hover(function() {
    $(this).animate({
        height: '30px', 
		opacity: 1.0
    }, { duration: "slow", "easing": "easeOutBounce" });
},function() {
    $(this).animate({
        height: '19px', 
		opacity: 0.7
    }, 200);
});

// -------------------------------------------------------
// Fading
// -------------------------------------------------------
var fadeDuration = 200;
$('#header #logo a').hover(function() {
  $(this).animate({ opacity: '0.7' }, fadeDuration);
}, function() {
  $(this).animate({ opacity: '1.0' }, fadeDuration);       
});

/*$('#navbar a,a.ilink,.btn,.screenshots a img').hover(function() {
  $(this).animate({ opacity: '1.00' }, fadeDuration);
}, function() {
  $(this).animate({ opacity: '0.7' }, fadeDuration);       
});*/

$('.sb-special,.news a').hover(function() {
  $(this).animate({ opacity: '1.00' }, fadeDuration);
}, function() {
  $(this).animate({ opacity: '0.9' }, fadeDuration);       
});

$('.cp-warn, .cp-ok').hover(function() {
  $(this).animate({ opacity: '1.00' }, fadeDuration);
}, function() {
  $(this).animate({ opacity: '0.8' }, fadeDuration);       
});

$('.Button').hover(function() {
  $(this).animate({ opacity: '1.00' }, fadeDuration);
}, function() {
  $(this).animate({ opacity: '0.8' }, fadeDuration);       
});

// -------------------------------------------------------
// Ranking Animation
// -------------------------------------------------------

$('.sb-ranking-div').css('display', 'none');
$('#sb-ranking-revolution-players').css('display', 'block');

$('.switch-menu').click(function () { 
	$('.sb-ranking-div').slideUp("slow"); 
	$('#sb-ranking-menu').delay(500).slideDown("slow"); 
	$('#sb-ranking-title').text("Ranking - Menu");
});

$('.sb-ranking-btn').click(function () { 
	$('#sb-ranking-menu').slideUp("slow"); 
	$('#sb-ranking-title').text($(this).text());	
	$('#sb-'+$(this).attr('id')).delay(500).slideDown("slow"); 
});
