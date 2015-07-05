<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Sample Code</title>
    	<meta charset="utf-8"> 
    	<meta name="viewport" content="width=device-width, initial-scale=1">
  		<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  		<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  	</head>
<body>
<div class="container">
<h1>My sample PHP page</h1>
<?php
$name = $color = $personlity = "";
?>

<form class="form-horizontal" role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
	<div class="form-group">
		<label class="control-label col-sm-4">What is your name? </label>
		<div class="col-sm-3">
			<input type="text" class="form-control" name="name" placeholder="Enter your name">
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-4">Which is your fav color?</label>
		<div class="col-sm-3">
			<select class="form-control" name="color">
			<option disabled selected>Select your color</option>
			<option>Black</option>
			<option>Orange</option>
			<option>Blue</option>
			<option>Pink</option>
			<option>Green</option>
			<option>Red</option>
			<option>Yellow</option>
			</select>
		</div>
	</div>
	<div class="form-group"> 
	    <div class="col-sm-offset-4 col-sm-3">
			<button type="submit" class="btn btn-default" name="submit" data-toggle="modal" data-target="#myModal">Submit</button>
		</div>
	</div>	
</form>
<?php if (isset($_POST["name"])||isset($_POST["color"])) : ?>
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
	<div class="modal-content">
<?php
$name=$_POST["name"];
$color=$_POST["color"];
 
    if($color=='Black')
    {
        $personlity = "strong";
    }elseif
    ($color=='Orange')
    {
        $personlity = "energetic";
    }elseif
    ($color=='Blue')
    {
        $personlity = "loyal";
    }
    elseif
    ($color=='Pink')
    {
        $personlity = "kind";
    }elseif
    ($color=='Green')
    {
        $personlity = "responsible";
    }elseif
    ($color=='Red')
    {
        $personlity = "honest";
    }elseif
    ($color=='Yellow')
    {
        $personlity = "cheeful";
    }else
    {
        $personlity = "non-defined";
    }
?>
	    <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	        <h4 class="modal-title"><?php echo "Hi, " . $name ."!";?></h4>
	    </div>
	    <div class="modal-body">
<?php
echo "Your favorite color is " . $color .".<br>";
echo "You are a " . $personlity ." person.<br>";
echo "Today is " . date("m/d/y") . ".<br>";

$time = date("H");

if ($time < "20") {
    echo "Have a good day!";
} else {
    echo "Have a good night!";
}
?>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	      </div>
	 </div>
  </div>
</div>
</div>
</body>
</html>
<script type="text/javascript">
  $(window).load(function(){
    $('#myModal').modal('show');
  });
</script>
<?php endif; ?>