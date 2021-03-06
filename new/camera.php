<?PHP
/*********************HEADER********************/
// //ERROR CHECKING
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

session_start();
include_once "header.php";
include_once "back/connect.back.php";

if (!isset($_SESSION['user_id'])){
	header("Location: index.php?login=required");
	exit();
}
else {
//GET IMAGE DATA FROM DATABASE
try {
	$query = "SELECT *
				FROM images
				WHERE img_user_id=?
				ORDER BY date_created
				DESC";
	$stmt = $pdo->prepare($query);
	$stmt->execute([$_SESSION['user_id']]);
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
catch(PDOException $err){
	echo $err;
}
/*********************END********************/


/*********************BODY********************/
echo '<DIV class=camera>
<VIDEO autoplay="true" id="videoElement" width="400" height="300"></VIDEO>
<A href="#" id="capture" >Capture</A>
<CANVAS id="photoElement" style="display: none" width="400" height="300"></CANVAS>
<CANVAS id="capturedElement" width="400" height="300"> </CANVAS>
</DIV>
<DIV class="camera-stickers">
	<IMG href="#" id="stick1" src="dep/stick1.png" alt="Sticker 1" width="150" height="150">
	<IMG href="#" id="stick2" src="dep/stick2.png" alt="Sticker 2" width="150" height="150">
	<IMG href="#" id="stick3" src="dep/stick3.png" alt="Sticker 3" width="150" height="150">
	<DIV class=save_upload>
		<INPUT class="upload" type="file" id="img">
		<BUTTON id="btn">Save & Upload</BUTTON>
	</DIV>
</DIV>';
//DISPLAY IMAGES
foreach ($result as $img){

	echo '<DIV class="thumbnail">
			<A href="view.php?img='.$img['img_id'].'" alt="'.$img['img_path'].'"><img class="center" src="'.$img['img_path'].'"></A>
			</DIV>';
};
}
/*********************END********************/

/*********************JAVA SCRIPT********************/
?>
<SCRIPT>	
var video			=	document.getElementById('videoElement'),
	canvas			=	document.getElementById('capturedElement'),
	photo			=	document.getElementById('photoElement'),
	context_canvas	=	canvas.getContext('2d'),
	context_photo	=	photo.getContext('2d'),
	stick1			=	document.getElementById('stick1'),
	stick2			=	document.getElementById('stick2'),
	stick3			=	document.getElementById('stick3');
	save			=	document.getElementById('btn');
	ac1				=	0,
	ac2				=	0,
	ac3				=	0;
    xhr				=	new XMLHttpRequest();
	cansave			= 0;

//UPLOAD IMAGE
document.getElementById('img').onchange = function() {
	cansave = 1;
  var img = new Image();
  img.onload = draw;
  img.onerror = failed;
  img.src = URL.createObjectURL(this.files[0]);
};
function draw() {
	context_canvas.drawImage(this, 0, 0, 400, 300);
	context_photo.drawImage(this, 0, 0, 400, 300);
	ac1 = 0;
	ac2 = 0;
	ac3 = 0;
}
function failed() {
  console.error("The provided file couldn't be loaded as an Image media");
}
//REQUEST PERMISSION TO USE CAMERA
if (navigator.mediaDevices.getUserMedia) {    
	 //SHOW VIDEO FEED   
	 navigator.mediaDevices.getUserMedia({video: true})
   .then(function(stream) {
	 video.srcObject = stream;
   })
   .catch(function(error) {
	 console.log("Something went wrong!");
   });
 }
 //TAKE THE PHOTO
 document.getElementById('capture').addEventListener('click',function(){
	cansave = 1;
	context_canvas.drawImage(video, 0, 0, 400, 300);
	context_photo.drawImage(video, 0, 0, 400, 300);
	photoElement.setAttribute('src',canvas.toDataURL('image/png'));
	ac1 = 0;
	ac2 = 0;
	ac3 = 0;
});
//CHECK STICKERS CLICKED
stick1.addEventListener('click',function(){
	if (ac1 == 0){
		ac1 = 1;
		context_canvas.drawImage(stick1, 0, 0, 400, 300);
	}
	else{
		ac1 = 0;
		ac2 = 0;
		ac3 = 0;
		context_canvas.drawImage(photoElement, 0, 0, 400, 300)
	};
});
stick2.addEventListener('click',function(){
	if (ac2 == 0){
		ac2 = 1;
		context_canvas.drawImage(stick2, 0, 0, 400, 300);
	}
	else{
		ac1 = 0;
		ac2 = 0;
		ac3 = 0;
		context_canvas.drawImage(photoElement, 0, 0, 400, 300)
	};
});
stick3.addEventListener('click',function(){
	if (ac3 == 0){
		ac3 = 1;
		context_canvas.drawImage(stick3, 0, 0, 400, 300);
	}
	else{
		ac1 = 0;
		ac2 = 0;
		ac3 = 0;
		context_canvas.drawImage(photoElement, 0, 0, 400, 300)
	};
});
//SAVE BUTTON
save.addEventListener('click',function(){
	if (cansave == 1){
	var Data	=	photo.toDataURL("image/png");
	xhr.open('POST','back/upload.back.php');
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.addEventListener("load", function(event){
		alert(this.response);
		window.location = "index.php";
	});
	xhr.send("img="+Data+"&ac1="+ac1+"&ac2="+ac2+"&ac3="+ac3+"&submit=OK");
	}
	else{
		alert("No Image!")
	}
})
</SCRIPT>
<?PHP
/*********************END********************/

/*********************FOOTER********************/
include_once "footer.php";
/*********************END********************/