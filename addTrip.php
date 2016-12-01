<?php
  include "config.php";
  $today = date("Y-m-d");
  $now = date("H:i:s");

  $isInserted = false;
  $isunique = true;
  $sql = "SELECT route.id AS route, cityFrom.name AS city_from, cityTo.name AS city_to
          FROM route
          LEFT JOIN city AS cityFrom ON cityFrom.id
          LEFT JOIN city AS cityTo ON cityTo.id
          WHERE cityFrom.id = route.city_from_fk AND cityTo.id = route.city_to_fk";
  $stmt = $db->prepare($sql);
  $stmt->execute();
  $routes = $stmt->fetchAll();

  $sql = "SELECT * FROM bus";
  $stmt = $db->prepare($sql);
  $stmt->execute();
  $busses = $stmt->fetchAll();

  if(isset($_POST['route']) && isset($_POST['datetime']) && isset($_POST['bus']) && isset($_POST['price']) &&
  $_POST['route'] != null && $_POST['bus'] != null && $_POST['datetime'] != '' && $_POST['price'] != '')
  {
    $datetime =explode(" ", $_POST['datetime']);
    $date = $datetime[0];
    $time = $datetime[1];
    $isInserted = true;
//==================================TIKRINAM VALIDACIJA DUOMENU================================================================================
    if ($date < $today || ($date == $today && $time == $now))
    {
      $isunique = false;
    }
    //==================================================================================================================
    else
    {
      try
      {
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO `trip` (`id`, `route_fk`, `date`, `time`, `bus_fk`, `price`)
                VALUES( NULL, ?, ?, ?, ? ,?)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(1, $_POST['route'], PDO::PARAM_INT);
        $stmt->bindParam(2, $date);
        $stmt->bindParam(3, $time);
        $stmt->bindParam(4, $_POST['bus']);
        $stmt->bindParam(5, $_POST['price']);
        $stmt->execute();
      }
      catch(PDOException $e)
      {
        //echo $e->getMessage();
        $isInserted = false;
      }
    }
    //==================================================================================================================

  }
 ?>

 <form id="addNewRoute" method="post" class="form-inline">
     <div class="container">

       <div class="form-group">
         <div class="col-xs-5 selectContainer">
             <select class="form-control" name="route">
               <option value="">Choose a route</option>
                 <?php
                 foreach ($routes as $route) {
                   echo '<option value="'.$route['route'].'">'.$route['city_from']." - ".$route['city_to'].'</option>';                }

                  ?>
             </select>
         </div>
       </div>

       <div class="form-group">
             <div class='col-lg-12 col-md-9 col-sm-6 '>

                     <div class='input-group date' id='datetimepicker'>
                         <input type='text' class="form-control" name="datetime"/>
                         <span class="input-group-addon">
                             <span class="glyphicon glyphicon-calendar"></span>
                         </span>
                     </div>
                 </div>
       </div>

       <div class="form-group">
         <div class="col-xs-5 selectContainer">
             <select class="form-control" name="bus">
               <option value="">Choose a bus</option>
                 <?php
                 foreach ($busses as $bus)
                 {
                   echo '<option value="'.$bus['id'].'">'.$bus['id']." - ".$bus['bus_space']." seats".'</option>';
                 }
                  ?>
             </select>
         </div>
       </div>

       <div class="form-group">
          <div class='col-lg-6 col-sm-3 col-xs-3'>
            <input type="number" min=0.01 step=0.01 class="form-control" placeholder="Price" name="price">
          </div>
       </div>

     <div class="form-group">
         <div class="col-xs-5 col-xs-offset-3">
             <button type="submit" class="btn btn-default">Add new Trip</button>
         </div>
     </div>

 </form>

 <?php
 //=============================PRANESIMO TEKSTAS=====================================================================================
 if(isset($_POST['route'])) //uztenka patikrinti viena
 {
   if ($isunique)
   {

     if($isInserted)
     { ?>
         <div class="alert alert-success">
          <strong>Trip has been added.</strong>
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
      <strong>Choose later time</strong>
    </div>
<?php
  }
}
?>
