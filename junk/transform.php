function transform($text) {
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
    $text = substr_replace($text,
      "<img src=\"$url\" alt=\"Formula: $i\" />",
      $position, strlen($matches[0][$i]));
  }
  return $text;
}