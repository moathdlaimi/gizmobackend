<?php
$dbconn = null;
if(getenv('DATABASE_URL')){
    $connectionConfig = parse_url(getenv('DATABASE_URL'));
    $host = $connectionConfig['host'];
    $user = $connectionConfig['user'];
    $password = $connectionConfig['pass'];
    $port = $connectionConfig['port'];
    $dbname = trim($connectionConfig['path'],'/');
    $dbconn = pg_connect(
        "host=".$host." ".
        "user=".$user." ".
        "password=".$password." ".
        "port=".$port." ".
        "dbname=".$dbname
    );
} else {
  $dbconn = pg_connect("host=localhost dbname=gizmo");
}

class User {
  public $id;
  public $name;
  public $password;


  public function __construct($id, $name, $password) {
    $this->id = $id;
    $this->name = $name;
    $this->password = $password;

  }
}

class Users {
  static function all(){
    $users = array();

    $results = pg_query("SELECT * FROM users ORDER by id ASC");

    $row_object = pg_fetch_object($results);
    while($row_object){
      $new_user = new User(
        intval($row_object->id),
        $row_object->name,
        $row_object->password,

      );
      $users[] = $new_user;
      $row_object = pg_fetch_object($results);
    }
    return $users;
  }

  static function get($user){
    $query = "SELECT * FROM users WHERE name = $1 AND password = $2";
    $query_params = array($user->name, $user->password);
    $results = pg_query_params($query, $query_params);
    $row_object = pg_fetch_object($results);
    if($row_object){
      return $row_object->name;
    }else {
      return '';
    }

  }
  //
  // CREATE
  //
  //
  static function create($user){
    $query = "INSERT INTO users (name,password) VALUES ($1, $2)";
    $query_params = array($user->name, $user->password);
    pg_query_params($query, $query_params);
    return self::all();
  }
  //
  // UPDATE
  //
  //FUTURE GOAL
  //
  // DELETE
  //
  static function delete($id){
    $query = "DELETE FROM users WHERE id = $1";
    $query_params = array($id);
    $result = pg_query_params($query, $query_params);
    return self::all();
  }
}


?>
