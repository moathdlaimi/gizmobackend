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



    class Tool {
      public $id;
      public $title;
      public $img;
      public $description;
      public $price;
      public $tags;
      public $rentee;

      public function __construct($id, $title, $img, $description, $price, $tags,$rentee) {
        $this->id = $id;
        $this->title = $title;
        $this->img = $img;
        $this->description = $description;
        $this->price = $price;
        $this->tags = $tags;
        $this->rentee = $rentee;
      }
    }

    class Tools {
      static function all(){
        $tools = array();

        $results = pg_query("SELECT * FROM tools ORDER by id ASC");

        $row_object = pg_fetch_object($results);
        while($row_object){
          $new_tool = new Tool(
            intval($row_object->id),
            $row_object->title,
            $row_object->img,
            $row_object->description,
            $row_object->price,
            $row_object->tags,
            $row_object->rentee,
          );
          $tools[] = $new_tool;
          $row_object = pg_fetch_object($results);
        }
        return $tools;
      }

    //to show each user with thier own tools
      static function user($username){
        $user_tools = array();
        $results = pg_query("SELECT * FROM tools JOIN users ON tools.rentee = $username");

        $row_object = pg_fetch_object($results);
        while($row_object){
          $new_tool = new Tool(
            intval($row_object->id),
            $row_object->title,
            $row_object->img,
            $row_object->description,
            $row_object->price,
            $row_object->tags,
            $row_object->rentee,
          );
          $user_tools[] = $new_tool;
          $row_object = pg_fetch_object($results);
        }
        return $user_tools;
      }

      //Show Page
        static function show($id){

          $query = "SELECT * FROM tools WHERE id =$1";
          $query_params = array($id);
          $results = pg_query_params($query, $query_params);
          $tools = '';


          $row_object = pg_fetch_object($results);
          while($row_object){
            $new_tool = new Tool(
              intval($row_object->id),
              $row_object->title,
              $row_object->img,
              $row_object->description,
              $row_object->price,
              $row_object->tags,
              $row_object->rentee,
            );
            $tools = $new_tool;
            $row_object = pg_fetch_object($results);
          }
          return $tools;
        }

      //
      // CREATE
      //
      //
      static function create($tool){
        $query = "INSERT INTO tools (title, img, description, price, tags, rentee) VALUES ($1, $2, $3, $4, $5, $6)";
        $query_params = array($tool->title, $tool->img, $tool->description, $tool->price, $tool->tags, $tool->rentee);
        pg_query_params($query, $query_params);
        return self::all();
      }
      //
      // UPDATE
      //
      static function update($updated_tool){
        $query = "UPDATE tools SET title = $1, img = $2, description = $3, price = $4, tags = $5 WHERE id = $6";
        $query_params = array($updated_tool->title, $updated_tool->img, $updated_tool->description, $updated_tool->price, $updated_tool->tags, $updated_tool->id);
        $result = pg_query_params($query, $query_params);
        return self::all();
      }
      //
      // DELETE
      //
      static function delete($id){
        $query = "DELETE FROM tools WHERE id = $1";
        $query_params = array($id);
        $result = pg_query_params($query, $query_params);
        return self::all();
      }
    }
 ?>
