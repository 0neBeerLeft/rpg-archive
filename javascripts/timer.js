var i_timer=-1
var timerid=new Array()
var thiscount= new Date()
var hour
var minute
var second
var left
var hour
var minute
var second

function writetimer(time){
  i_timer++
	if (document.all || document.getElementById || document.layers){
		timerid[i_timer]="timer"+i_timer
		document.write("<span id='"+timerid[i_timer]+"' style='position:relative'>"+thiscount+"</span>")
	}
	count(time)
}

function count(time){
  left = time - 1
  hour = Math.floor(time / 3600)
  minute = Math.floor((time-(hour*3600))/60)
  second = Math.floor(time-((minute*60)+(hour*3600)))
	
	if (eval(hour) <10) 
		hour="0"+hour
	
	if (eval(minute) < 10) 
		minute="0"+minute
	
	if (second < 10) 
		second="0"+second
	
	thiscount = hour+":"+minute+":"+second
	
	if (document.all){
		for (i=0;i<=timerid.length-1;i++) {
			var thisclock=eval(timerid[i])
			thisclock.innerHTML=thiscount
		}
	}
	
	if (document.getElementById){
		for (i=0;i<=timerid.length-1;i++) 
			document.getElementById(timerid[i]).innerHTML=thiscount
	}
	
	if(time < 0)
    location.href=window.location
  
	
	var timer=setTimeout("count("+left+")",1000)
}
