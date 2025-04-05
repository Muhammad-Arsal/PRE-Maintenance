<?php

  $content = base64_decode($data['template']);

  $content =  str_replace('USER_NAME', $data['user_name'], $content);

  $content = str_replace('DATE', $data['date'], $content);

  $content = str_replace('NAME', $data['name'], $content);

  $content = str_replace('DESCRIPTION', $data['description'], $content);

  echo $content;

  ?>