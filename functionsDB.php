<?php
// include "config.php";
// $stmt = $db->prepare();
function DeleteRecord($db ,$table, $column, $value){
  // print_r($table);
  // print_r($column);
  print_r($value);
  $query = "DELETE FROM `".$table."` WHERE `".$column."` = '".$value."'";
  print_r($query);
  $stmt = $db->prepare($query);
  // $stmt->bindParam(1, $table);
  // $stmt->bindParam(2, $column);
  // $stmt->bindParam(3, $value, PDO::PARAM_INT);
  $stmt->execute();
  $response = $stmt->rowCount();
  return $response;
}
?>
