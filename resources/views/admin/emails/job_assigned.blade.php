<?php

  $content = base64_decode($template->content);
  $content =  str_replace('USER_NAME', $name, $content);
  $content = str_replace('VIEW_URL', "<a href=\"{$link}\">Click here to see New Job</a>", $content);
  echo $content;

  ?>