<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Treemap Example</title>
<link href="css/styles.css" rel="stylesheet" type="text/css">
</head>

<body>
<div align="center" style="width: 340px; margin: auto;">
<h1>Treemap Example</h1>
<?php
require_once '../../content/initialize.php';
// include the function
require_once("treemap.php");

// base url for treemap links
$baseurl = "http://www.roomformilk.com/";

// squash time if necessary.  1 is unsquashed.
$timesquash = 1;

// array of items and sizes
$tagArray = array(
	"apples" => 12,
	"oranges" => 38,
	"pears" => 10,
	"mangos" => 24,
	"grapes" => 18,
	"bananas" => 56,
	"watermelons" => 80,
	"lemons" => 12,
	"limes" => 12,
	"pineapples" => 15,
	"strawberries" => 20,
	"coconuts" => 43,
	"cherries" => 20,
	"raspberries" => 8,
	"peaches" => 25
	);
	
// array of items and ages
$taggedArray = array(
	"apples" => "4/21/2006",
	"oranges" => "4/21/2006",
	"pears" => "4/22/2006",
	"mangos" => "4/22/2006",
	"grapes" => "4/23/2006",
	"bananas" => "4/23/2006",
	"watermelons" => "4/24/2006",
	"lemons" => "4/24/2006",
	"limes" => "4/25/2006",
	"pineapples" => "4/26/2006",
	"strawberries" => "4/27/2006",
	"coconuts" => "4/27/2006",
	"cherries" => "4/28/2006",
	"raspberries" => "4/28/2006",
	"peaches" => "4/28/2006"
	);

// define timespan
$fromwhen = date("Y-m-d H:i:s",strtotime("4/21/2006"));
$towhen = date("Y-m-d H:i:s",strtotime("4/28/2006"));

// sort the array according to size
arsort($tagArray);
	
// call the function
echo render_treemap($tagArray, 320, 360, 0, 1);

	
?>

<p>This example is provided by <a href="http://www.neurofuzzy.net">neurofuzzy</a>.  
To see a working example, visit <a href="http://www.roomformilk.com">roomformilk</a> 
or <a href="http://www.reverbiage.com/tags">reverbiage</a>.

If you use this (yeah, right!), please let me know about it, I'd like to see what you've done.
</div>
</body>
</html>
