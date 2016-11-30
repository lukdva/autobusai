<?php
  include "config.php";
  $isInserted = false;
  $isunique = true;
  if(isset($_POST['city_to']) && isset($_POST['city_from']) && $_POST['city_to'] != null && $_POST['city_from'] != null)
  {
    $isInserted = true;

    $sql = "SELECT COUNT(*) AS kiek
            FROM route
            LEFT JOIN city AS cityFrom ON cityFrom.id
            LEFT JOIN city AS cityTo ON cityTo.id
            WHERE cityFrom.name  = ? AND cityTo.name = ? AND cityFrom.id = route.city_from_fk AND cityTo.id = route.city_to_fk";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(1, $_POST['city_from']);
    $stmt->bindParam(2, $_POST['city_to']);

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
        $sql = "INSERT INTO `route` (`id`, `city_from_fk`, `city_to_fk`)
                SELECT NULL, cityFrom.id, cityTo.id
                FROM city AS cityFrom
                LEFT JOIN city AS cityTo ON cityTo.id
                WHERE cityFrom.name  = ? AND cityTo.name = ?";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(1, $_POST['city_from']);
        $stmt->bindParam(2, $_POST['city_to']);

        $stmt->execute();
      }
      catch(PDOException $e)
      {
        //echo $e->getMessage();
        $isInserted = false;
      }
    }
    //Pranesimas kad pavyko
  }
 ?>

 <form id="addNewRoute" method="post" class="form-inline">
     <div class="container">

       <div class="form-group">
         <label class="col-xs-3 control-label">City from</label>
         <div class="col-xs-5 selectContainer">
             <select class="form-control" name="city_from">
               <option value="">Choose a city</option>
                 <?php
                 foreach ($cities as $key => $value) {
                   echo '<option value="'.$value.'">'.$value.'</option>';                }

                  ?>
             </select>
         </div>
     </div>

     <div class="form-group">
         <label class="col-xs-3 control-label">City to</label>
         <div class="col-xs-5 selectContainer">
             <select class="form-control" name="city_to">
               <option value="">Choose a city</option>
                 <?php
                 foreach ($cities as $key => $value)
                 {
                   echo '<option value="'.$value.'">'.$value.'</option>';
                 }

                  ?>
             </select>
         </div>
     </div>

     <div class="form-group">
         <div class="col-xs-5 col-xs-offset-3">
             <button type="submit" class="btn btn-default">Add new Route</button>
         </div>
     </div>

 </form>

 <?php
 if(isset($_POST['city_to']) && isset($_POST['city_from']))
 {
   if ($isunique)
   {

     if($isInserted)
     { ?>
         <div class="alert alert-success">
          <strong>Route has been added.</strong>
        </div>
        <?php
      }
      else
      { ?>
      <div class="alert alert-danger">
        <strong>Unable to insert!</strong>
      </div>
      <?php
      }
  }
  else
  {?>
    <div class="alert alert-warning">
      <strong>This route already exist</strong>
    </div>
<?php
  }
}
?>
