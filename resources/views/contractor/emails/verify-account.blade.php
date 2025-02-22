<?php

  $content = base64_decode($template->content);
  $content =  str_replace('USER_NAME', $name, $content);
  $content =  str_replace('VERIFICATION_LINK', $link, $content);
  $content =  str_replace('SYSTEM_APPLICATION_NAME', $system_name, $content);

  echo $content;

  ?>