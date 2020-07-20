<?php
session_start();

header('Content-Type: application/json');
include_once __DIR__ .'/../models/user.php';

if ($_REQUEST['action'] === 'post') {
  $request_body = file_get_contents('php://input');
  $body_object = json_decode($request_body);
  $new_user = new User(null,$body_object->name,$body_object->password);
  $user = Users::get($new_user);
  if($user !== ''){
    $_SESSION['user'] = $user;
  }
  echo json_encode($user);
}
elseif ($_REQUEST['action'] === 'delete') {
  session_destroy();
  echo json_encode('Logged Out');
} elseif ($_REQUEST['action'] === 'index') {
  echo json_encode($_SESSION['user']);
}


?>
