<?
function render_latex($thunk, $hash) {
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
             $hash . ".tex";
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
?>