<?php

header('Content-Type: application/json');
include_once __DIR__ .'/../models/user.php';

if($_REQUEST['action'] === 'index') {
  echo json_encode(Users::all());

} elseif ($_REQUEST['action'] === 'post') {
  $request_body = file_get_contents('php://input');
  $body_object = json_decode($request_body);
  $new_user = new User(null,$body_object->name,$body_object->password);
  $all_users = Users::create($new_user);
  echo json_encode($all_users);
}
elseif ($_REQUEST['action'] === 'delete') {
  $all_users = Users::delete($_REQUEST['id']);
  echo json_encode($all_users);
}



?>
