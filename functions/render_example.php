<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
    "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<?

$equation=$_REQUEST["equation"];
?>
<html>
<head>
<title>LaTeX Equations and Graphics in PHP</title>
</head>
<body>
<!-- form to enter LaTeX code -->
<form action="render_example.php" method="get">
<textarea rows="20"
          cols="60"
          name="equation"><? echo htmlentities($equation); ?></textarea><br />
<input name="submit"
       type="submit"
       value="Render" />
</form>
<?php

   echo '<h1>Result</h1>';
   require('render.class.php');
   $text = $equation;

   $render = new render();

   echo "<img src='"."render.php?equation=".str_replace("\n","<CR>",urlencode($text))."'><br>";
   echo "<BR>Link<BR>";
   echo "render.php?equation=".str_replace("\n","<CR>",urlencode($text));
   
?>
</body>
</html>