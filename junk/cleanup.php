
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



