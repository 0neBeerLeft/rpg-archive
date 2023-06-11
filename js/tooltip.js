this.tooltip = function(){			
		xOffset = 10;
		yOffset = 20;			
	$(".tooltip").hover(function(e){											  
		this.t = this.title;
		this.title = "";									  
		$("body").append("<p id='tooltip'>"+ this.t +"</p>");
		$("#tooltip")
			.css("top",(e.pageY - xOffset) + "px")
			.css("left",(e.pageX + yOffset) + "px")
			.fadeIn("fast");		
    },
	function(){
		this.title = this.t;		
		$("#tooltip").remove();
    });	
	$(".tooltip").mousemove(function(e){
		$("#tooltip")
			.css("top",(e.pageY - xOffset) + "px")
			.css("left",(e.pageX + yOffset) + "px");
	});			
};

// bccf3 18c72d da38788a4 cee258d cfda8

this.tooltip2 = function(){			
		xOffset = 10;
		yOffset = 20;			
	$(".tooltip2").hover(function(e){											  
		this.t = this.title;
		this.title = "";
		var broken = this.t.split(":");
		var VotePoints = broken[3];
		if(VotePoints == 0){
		var VotePointUitkomst = "";	
		} else {
		var VotePointUitkomst = "<b>"+ broken[3]+"</b> Vote Points<br>";	
		}
		$("body").append("<div id='tooltip2'></a><b>"+ broken[0] +"</b><br><i>"+ broken[1] +"</i><br><br><img src=\"images/items/"+broken[2]+".png\"><br><br><b>Costs:</b><br>"+ VotePointUitkomst +"<b>"+broken[5]+"</b> Donator Points</div>");
		$("#tooltip2")
			.css("top",(e.pageY - xOffset) + "px")
			.css("left",(e.pageX + yOffset) + "px")
			.fadeIn("fast");		
    },
	function(){
		this.title = this.t;		
		$("#tooltip2").remove();
    });	
	$(".tooltip2").mousemove(function(e){
		$("#tooltip2")
			.css("top",(e.pageY - xOffset) + "px")
			.css("left",(e.pageX + yOffset) + "px");
	});			
};


$(document).ready(function(){
	tooltip();
	tooltip2();
});