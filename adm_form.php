<?php
  include "formclass.php";
  $base = new formclass;
 ?>
<!DOCTYPE HTML>
<html>
 <head>
  <meta charset="utf-8">
  <title>Test_Form</title>
 </head>
 <body bgcolor="lightblue">
 	<div>
 		<?php
 		$base->sqldel();
		?>
	</div>
 </body>
</html>
