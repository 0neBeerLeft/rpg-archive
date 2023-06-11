var cloudMoved = false;
var cloud2Moved = false;
var WAVE_TIME = 6000;
$(init); 
function init()
{
	cloudMove();
	cloud2Move();
}
function cloudMove()
{
	if (!cloudMoved)
	{
		$(".cloud-01")
			.css("left", $(".cloud-01").offset().left)
	}	
	$(".cloud-01")
		.animate(
			{
				left: $("#clouds").width()
			},
			cloudMoved ? 200000 : 150000,
			"linear",
			function()
			{
				$(this)
					.css("left", -parseInt($(this).css("width")))				
				cloudMoved = true;				
				cloudMove();
			}
		)
}
function cloud2Move()
{
	if (!cloud2Moved)
	{
		$(".cloud-02")
			.css("left", $(".cloud-02").offset().left)
	}	
	$(".cloud-02")
		.animate(
			{
				left: $("#clouds").width()
			},
			cloud2Moved ? 40000 : 20000,
			"linear",
			function()
			{
				$(this)
					.css("left", -parseInt($(this).css("width")))				
				cloud2Moved = true;				
				cloud2Move();
			}
		)
}