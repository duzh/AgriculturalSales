<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="/croppic/assets/img/favicon.png">

    <title>croppic</title>

    <!-- Bootstrap core CSS -->
    <link href="/croppic/assets/css/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/croppic/assets/css/main.css" rel="stylesheet">
    <link href="/croppic/assets/css/croppic.css" rel="stylesheet">

    <!-- Fonts from Google Fonts -->
	<link href='http://fonts.googleapis.com/css?family=Lato:300,400,900' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Mrs+Sheppards&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
    
	<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
			ga('create', 'UA-10627690-5', 'auto');
			ga('send', 'pageview');

	</script>
	
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>
			
		<div class="row mt ">
			<div class="col-lg-4 ">
				<h4 class="centered"> MODAL </h4>
				<p class="centered">( open in modal window )</p>
				<div id="cropContainerModal" ></div>
			</div>
			
		<!-- 	<div class="col-lg-4 ">
				<h4 class="centered"> OUTPUT </h4>
				<p class="centered">( display url after cropping )</p>
				<div id="cropContaineroutput"></div>
				<input type="text" id="cropOutput" style="width:100%; padding:5px 4%; margin:20px auto; display:block; border: 1px solid #CCC;" />
			</div>
			
			<div class="col-lg-4 ">
				<h4 class="centered"> EYECANDY </h4>
				<p class="centered">( no background image )</p>
				<div id="cropContainerEyecandy"></div>
			</div> -->
		</div>
		<!-- <div class="row mt ">
			<div class="col-lg-4 ">
				<h4 class="centered"> Minimal Controls </h4>
				<p class="centered">( define the controls available )</p>
				<div id="cropContainerMinimal"></div>
			</div>		
			<div class="col-lg-4 ">
				<h4 class="centered"> Preload </h4>
				<p class="centered">( if the picture is already available )</p>
				<div id="cropContainerPreload"></div>
			</div>		
			<div class="col-lg-4 ">
				<div id="cropContainerPlaceHolder2"></div>
			</div>
		</div>		 -->
	</div>
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <!-- <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script> -->
	<script src=" https://code.jquery.com/jquery-2.1.3.min.js"></script>
   
	<script src="/croppic/assets/js/bootstrap.min.js"></script>
	<script src="/croppic/assets/js/jquery.mousewheel.min.js"></script>
   	<script src="/croppic/croppic.min.js"></script>
    <script src="/croppic/assets/js/main.js"></script>
    <script>
		var croppicHeaderOptions = {
				//uploadUrl:'img_save_to_file.php',
				cropData:{
					"dummyData":1,
					"dummyData2":"asdas"
				},
				cropUrl:'img_crop_to_file.php',
				customUploadButtonId:'cropContainerHeaderButton',
				modal:false,
				processInline:true,
				loaderHtml:'<div class="loader bubblingG"><span id="bubblingG_1"></span><span id="bubblingG_2"></span><span id="bubblingG_3"></span></div> ',
				onBeforeImgUpload: function(){ console.log('onBeforeImgUpload') },
				onAfterImgUpload: function(){ console.log('onAfterImgUpload') },
				onImgDrag: function(){ console.log('onImgDrag') },
				onImgZoom: function(){ console.log('onImgZoom') },
				onBeforeImgCrop: function(){ console.log('onBeforeImgCrop') },
				onAfterImgCrop:function(){ console.log('onAfterImgCrop') },
				onError:function(errormessage){ console.log('onError:'+errormessage) }
		}	
		var croppic = new Croppic('croppic', croppicHeaderOptions);
		
		
		var croppicContainerModalOptions = {
				uploadUrl:'/upload/save/32',
				cropUrl:'/upload/filetosave/32',
				modal:true,
				imgEyecandyOpacity:0.4,
				loaderHtml:'<div class="loader bubblingG"><span id="bubblingG_1"></span><span id="bubblingG_2"></span><span id="bubblingG_3"></span></div> '
		}
		var cropContainerModal = new Croppic('cropContainerModal', croppicContainerModalOptions);
		
		
		// var croppicContaineroutputOptions = {
		// 		uploadUrl:'img_save_to_file.php',
		// 		cropUrl:'img_crop_to_file.php', 
		// 		outputUrlId:'cropOutput',
		// 		modal:false,
		// 		loaderHtml:'<div class="loader bubblingG"><span id="bubblingG_1"></span><span id="bubblingG_2"></span><span id="bubblingG_3"></span></div> '
		// }
		// var cropContaineroutput = new Croppic('cropContaineroutput', croppicContaineroutputOptions);
		
		// var croppicContainerEyecandyOptions = {
		// 		uploadUrl:'img_save_to_file.php',
		// 		cropUrl:'img_crop_to_file.php',
		// 		imgEyecandy:false,				
		// 		loaderHtml:'<div class="loader bubblingG"><span id="bubblingG_1"></span><span id="bubblingG_2"></span><span id="bubblingG_3"></span></div> '
		// }
		// var cropContainerEyecandy = new Croppic('cropContainerEyecandy', croppicContainerEyecandyOptions);
		
		// var croppicContaineroutputMinimal = {
		// 		uploadUrl:'img_save_to_file.php',
		// 		cropUrl:'img_crop_to_file.php', 
		// 		modal:false,
		// 		doubleZoomControls:false,
		// 	    rotateControls: false,
		// 		loaderHtml:'<div class="loader bubblingG"><span id="bubblingG_1"></span><span id="bubblingG_2"></span><span id="bubblingG_3"></span></div> '
		// }
		// var cropContaineroutput = new Croppic('cropContainerMinimal', croppicContaineroutputMinimal);
		
		// var croppicContainerPreloadOptions = {
		// 		uploadUrl:'img_save_to_file.php',
		// 		cropUrl:'img_crop_to_file.php',
		// 		loadPicture:'/croppic/assets/img/night.jpg',
		// 		enableMousescroll:true,
		// 		loaderHtml:'<div class="loader bubblingG"><span id="bubblingG_1"></span><span id="bubblingG_2"></span><span id="bubblingG_3"></span></div> '
		// }
		// var cropContainerPreload = new Croppic('cropContainerPreload', croppicContainerPreloadOptions);
		
		
	</script>
  </body>
</html>
