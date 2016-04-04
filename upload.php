<?php
if(isset($_POST['btn-upload']))
{
     $pic = rand(1000,100000)."-".$_FILES['pic']['name'];
     $passin = $_POST["inputPassword"];
    $pic_loc = $_FILES['pic']['tmp_name'];
     $folder="data/";
     if(md5($passin) != "4e4b4f519494e4c1b869c56218108681"){
     	?><script>alert('password incorrect');</script><?php
     }else{
	     if(move_uploaded_file($pic_loc,$folder.$pic))
	     {
	        ?><script>alert('successfully uploaded');</script><?php
	     }
	     else
	     {
	        ?><script>alert('error while uploading file');</script><?php
	     }
     }

}

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>File Uploading With PHP and MySql</title>
<link rel="stylesheet" href="https://bootswatch.com/superhero/bootstrap.min.css">
<style type="text/css">
div	{
	float: left;
	margin-right: 3px;
}
</style>
</head>
<body>
<form action="" method="post" enctype="multipart/form-data">
	<div style="width: 75px;">
		<input type="password" class="form-control input-sm" name="inputPassword" placeholder="Password" size="3">
	</div>
	<div>
		<label class="btn btn-primary btn-sm" for="my-file-selector">
		    <input id="my-file-selector" type="file" style="display:none;" name="pic">
		    Browse
		</label>
		<button type="submit" class="btn btn-primary btn-sm" name="btn-upload">Upload</button>
	</div>
</form>
</body>
</html>