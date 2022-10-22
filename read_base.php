<?php
  
function load(){
  require_once "credentials.php";
  echo ("<script>");
  
  echo ("let error = false;");
  
  $sql = new mysqli($db_ip, $db_user, $db_password, $db_name);
  
  if ($sql->errno > 0)
    goto error;
  
  $result = $sql->query("SELECT * FROM `authorities`");
  if ($result->num_rows == 0)
    goto error;
  echo ("let authorities = [new Map([");
  $i = 0;
  while ($row = $result->fetch_assoc()) {
    $j = 0;
    if ($i != 0)
      echo "]), new Map([";
    foreach ($row as $key => $value) {
      if ($j != 0)
        echo ", ";
      echo "[\"$key\", \"$value\"]";
      $j++;
    }
    $i++;
  }
  echo ("])];");
  
  $result = $sql->query("SELECT * FROM `types`");
  if ($result->num_rows == 0)
    goto error;
  echo ("let types = new Map([");
  $i = 0;
  while ($row = $result->fetch_assoc()) {
    if ($i != 0)
      echo (",");
    echo ("[\"" . $row['id'] . "\", \"" . $row['type'] . "\"]");
    $i++;
  }
  echo ("]);");
  
  $result = $sql->query("SELECT * FROM `addresses`");
  if ($result->num_rows == 0)
    goto error;
  
  echo ("let addresses = new Map([");
  $i = 0;
  while ($row = $result->fetch_assoc()) {
    if ($i != 0)
      echo (",");
    echo ("[\"" . $row['id'] . "\", \"" . $row['address'] . "\"]");
    $i++;
  }
  echo ("]);");
  
  $sql->close();
  
  goto no_error;
  error:
    echo ("error = true;");
  
  no_error:
    echo ("</script>");
}
