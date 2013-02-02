<?
require('render.class.php');

$equation=$_REQUEST["equation"];
if(isset($_REQUEST["text"])) {
	$text=$_REQUEST["text"];
} else  {
	$text="";
}

$equation=str_replace("|n|"," \\\\",$equation);
if($equation!="") {
	$render_text="[tex]\\begin{math}".$equation."\\end{math}\n[/tex]";
} else {
	$render_text="[tex]\\begin{document}".$text."\\end{document}[/tex]";
}

$render = new render();
$fileName= $render->getImageUrl($render_text);
header('Content-Type: image/png');
$fp = fopen($fileName, 'rb'); 
fpassthru($fp);

?> 
 