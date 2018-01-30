navigator.getUserMedia=(navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia);

/*window.addEventListener("DOMContentLoaded",function(){
	//Grab elements, create settings, etc.
	var canvas=document.getElementById("canvas");
	var	context=canvas.getContext("2d");
	var	video=document.getElementById("video");
	var	videoObj={"video":true};
	var	errBack=function(error){
			console.log("Video Capture Error: ",error.code);
		};
		
	//Put video listeners into place
	if (navigator.getUserMedia)
	{//Standard
		navigator.getUserMedia(videoObj,function(stream){
			video.src=stream;
			video.play();
		}.errBack);
	}else if(navigator.webkitGetUserMedia)
	{//webkit-prefixed
		navigator.webkitgetUserMedia(videoObj,function(stream){
			video.src=window.webkitURL.createObjectURL(stream);
			video.play();
		}.errBack);
	}
},false);*/

//Trigger photo take
document.getElementById("snap").addEventListener("click",function(){
	context.drawImage(video,0,0,150,150);alert('Click');
});