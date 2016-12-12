<?php
  include "config.php";
  $isInserted = false;
  $isunique = true;
  if(isset($_POST['addNewCity']) && $_POST['addNewCity'] != null)
  {
    $isInserted = true;

    $sql = "SELECT COUNT(*) AS kiek
            FROM city
            WHERE name = ?";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(1, $_POST['addNewCity']);

    $stmt->execute();
    $row = $stmt->fetch();
    if ($row['kiek'] > 0) {
      $isunique = false;
    }
    else
    {
      try
      {
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO `city` (`id`, `name`)
                VALUES(NULL , ?)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(1, $_POST['addNewCity']);

        $stmt->execute();
      }
      catch(PDOException $e)
      {
        //echo $e->getMessage();
        $isInserted = false;
      }
    }
  }
 ?>

   <form id="addNewCity" method="post" class="form-inline">
       <div class="form-group">
         <input type="text" class="form-control" placeholder="Miesto pavadinimas" name="addNewCity">
       </div>
       <div class="form-group">
       <button href = ""type="submit" class="btn btn-default">Pridėti</button>
       <div class="form-group">
   </form>
   <br>

 <?php
 if(isset($_POST['addNewCity']))
 {
   if ($isunique)
   {

     if($isInserted)
     { ?>
         <div class="alert alert-success">
          <strong>Miestas buvo pridėtas.</strong>
        </div>
        <?php
      }
      else
      { ?>
      <div class="alert alert-danger">
        <strong>Nepavyko pridėti miesto!</strong>
      </div>
      <?php
      }
  }
  else
  {?>
    <div class="alert alert-warning">
      <strong>Šis miestas jau pridėtas.</strong>
    </div>
<?php
  }
}
?>
