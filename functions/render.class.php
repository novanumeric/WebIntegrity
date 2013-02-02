<?

class render {
 var $LATEX_PATH = "/usr/bin/latex";
 var $DVIPS_PATH = "/usr/bin/dvips";
 var $CONVERT_PATH = "/usr/bin/convert";
 var $TMP_DIR;
 var $CACHE_DIR;
 var $URL_PATH;
function wrap($thunk) {
  return <<<EOS
    \documentclass[10pt]{article}
	\usepackage[usenames,dvipsnames]{xcolor}
	\usepackage{color}

	\usepackage[width=45cm,height=20cm]{geometry}
    % add additional packages here
    \usepackage{amsmath}
    \usepackage{amsfonts}
    \usepackage{amssymb}
    \usepackage{pst-plot}
    \usepackage{color}
	\usepackage{rotating}	

    \pagestyle{empty}
    \begin{document}

    $thunk
    \end{document}
EOS;
}

function getImageUrl($text) {
	$this->CACHE_DIR =getcwd() . "/Common/Cache/";
  preg_match_all("/\[tex\](.*?)\[\/tex\]/si", $text, $matches);
  for ($i = 0; $i < count($matches[0]); $i++) {
    $position = strpos($text, $matches[0][$i]);
    $thunk = $matches[1][$i];
    $hash = md5($thunk);
    $full_name = $this->CACHE_DIR . "/" .
                 $hash . ".png";
    $url = $this->URL_PATH . "/" .
           $hash . ".png";
    if (!is_file($full_name)) {
      $this->render_latex($thunk, $hash);
      $this->cleanup($hash);
    }
    $text = $full_name;
  }
  return $text;
}
function render_latex($thunk, $hash) {

	$this->TMP_DIR =getcwd() . "/Common/tmp/";
	$this->URL_PATH = getcwd() . "/Common/Cache/";
	$thunk = $this->wrap($thunk);
	$current_dir = getcwd();

	chdir($this->TMP_DIR);
	// create temporary LaTeX file
	$fp = fopen($this->TMP_DIR . "/$hash.tex", "w+");
	fputs($fp, $thunk);
	fclose($fp);
	// run LaTeX to create temporary DVI file
	$command = $this->LATEX_PATH .
             " --interaction=nonstopmode " .
              "$hash.tex";
	exec($command);
	// run dvips to create temporary PS file
	$command = $this->DVIPS_PATH .
             " -E $hash" .
             ".dvi -o " . "$hash.ps";
	exec($command);
	// run PS file through ImageMagick to
	// create PNG file
	$command = $this->CONVERT_PATH .
             " -density 120 $hash.ps $hash.png";
	exec($command);
	// copy the file to the cache directory
	copy("$hash.png", $this->CACHE_DIR .
       "/$hash.png");
	chdir($current_dir);
}

function cleanup($hash) {
  $current_dir = getcwd();
  chdir($this->TMP_DIR);
	unlink($this->TMP_DIR . "/$hash.tex");
	unlink($this->TMP_DIR . "/$hash.aux");
	unlink($this->TMP_DIR . "/$hash.log");
	unlink($this->TMP_DIR . "/$hash.dvi");
	unlink($this->TMP_DIR . "/$hash.ps");
	unlink($this->TMP_DIR . "/$hash.png");
  chdir($current_dir);
 }
}







?>