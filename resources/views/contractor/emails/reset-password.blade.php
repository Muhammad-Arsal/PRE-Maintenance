<?php

  $content = base64_decode($template->content);
  $content =  str_replace('RESET_TOKEN', $link, $content);

  echo $content;

  ?>