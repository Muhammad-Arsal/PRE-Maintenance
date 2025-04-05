<?php

  $content = base64_decode($data['template']);

  $content =  str_replace('USER_NAME', $data['user_name'], $content);

  $content = str_replace('DATE', $data['date'], $content);

  $content = str_replace('EVENT_TYPE', $data['event_type'], $content);

  if($data['type'] == 'internal_email') {
    $content = str_replace('DESCRIPTION', $data['description'], $content);
  } else {
    $content = str_replace('DESCRIPTION', '', $content);
  }

  echo $content;

  ?>