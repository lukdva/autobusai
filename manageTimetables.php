<?php
session_start();
if(!isset($_SESSION['type']) || $_SESSION['type'] != "Manager")
{
  header('location: index.php');
}

include "config.php";
$stmt = $db->prepare("SELECT name FROM city");
$stmt->execute();
$cities = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
$pagination = 10;
//include "timetable_Querries.php";
//include "css/listgroup.css"

 ?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Autobusų tvarkaraštis</title>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins)-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.16.0/moment.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.1.0/js/bootstrap-datetimepicker.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.1.0/css/bootstrap-datetimepicker.css" />

<link rel="stylesheet" type="text/css" href="./css/table-hover.css">
<link rel="stylesheet" type="text/css" href="./css/listgroup.css">
  <script>
  $( function() {
    $( "#datetimepicker, #datetimepicker2" ).datetimepicker(
      {
      format: 'YYYY-MM-DD HH:mm:ss',
      value: new Date()
    });
  } );
  </script>

<script>
// for navigation highlighting KO NEVEIKIA??
$(function() {
  $('nav li a[href*="' + location.pathname.split("/")[2] + '"]').parent().addClass('active');
});
//for option list highlighting
$(function() {
  var query = location.href.split("?")[1];
  var firstparameter = query.split("&")[0];
  $('a[href^="?' + firstparameter + '"]').addClass('active');
});
</script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <?php include "navbar.php"; ?>


<?php if (isset($_GET['option']) && ($_GET['option'] == 'displayTrips' || $_GET['option'] == 'displayRoutes'))
{ ?>
  <form id="searchTripForm" method="post" class="form-inline">
      <div class="container">

        <div class="form-group">
          <label class="col-xs-3 control-label">Iš</label>
          <div class="col-xs-5 selectContainer">
              <select class="form-control" name="city_from">
                <option value="">Į</option>
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
          <label class="col-xs-3 control-label">Į</label>
          <div class="col-xs-5 selectContainer">
              <select class="form-control" name="city_to">
                <option value="">Pasirinkti miestą</option>
                  <?php
                  foreach ($cities as $key => $value)
                  {
                    echo '<option value="'.$value.'">'.$value.'</option>';
                  }

                   ?>
              </select>
          </div>
      </div>
  <?php if (isset($_GET['option']) && $_GET['option'] == 'displayTrips')
  { ?>
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
              <div class='col-lg-12 col-md-9 col-sm-6 '>

                  <div class='input-group date' id='datetimepicker2'>
                      <input type='text' class="form-control" name="datetime2"/>
                      <span class="input-group-addon">
                          <span class="glyphicon glyphicon-calendar"></span>
                      </span>
                  </div>
              </div>
          </div>
  <?php
  }?>
      <div class="form-group">
          <div class="col-xs-5 col-xs-offset-3">
              <button type="submit" class="btn btn-default">Filtruoti</button>
          </div>
      </div>

  </form>
<?php
} ?>
      <div class="row">
        <div class="col-md-3">

          <div class="list-group">
            <a class="list-group-item listgrouptitle">Pasirinkimai</a>
            <a href="?option=displayRoutes&page=1" class="list-group-item">Rodyti maršrutus</a>
            <a href="?option=addRoute" class="list-group-item">Pridėti maršrutą</a>
            <a href="?option=displayTrips&page=1" class="list-group-item">Rodyti keliones</a>
            <a href="?option=addTrip" class="list-group-item">Pridėti kelionę</a>
            <a href="?option=displayCities&page=1" class="list-group-item">Rodyti miestus</a>
            <a href="?option=addCity" class="list-group-item">Pridėti miestą</a>
            <a href="?option=displayBusses&page=1" class="list-group-item">Rodyti autobusus</a>
            <a href="?option=addBus" class="list-group-item">Pridėti autobusą</a>
          </div>

        </div>
        <div class="col-md-9">
          <div class="jumbotron">

            <?php
              if (isset($_GET['option']))
              {
                  switch ($_GET['option']) {
                    case 'displayRoutes':
                      include "displayRoutes.php";
                    break;
                    case 'addRoute':
                      include "addRoute.php";
                    break;
                    case 'displayTrips':
                      include "displayTrips.php";
                    break;
                    case 'addTrip':
                      include "addTrip.php";
                    break;
                    case 'displayCities':
                      include "displayCities.php";
                    break;
                    case 'addCity':
                      include "addCity.php";
                    break;
                    case 'displayBusses':
                      include "displayBusses.php";
                    break;
                    case 'addBus':
                      include "addBus.php";
                    break;
                    default:
                      echo"<h1>Pasirinkite</h1>";
                    break;
                  }
              }
              else
              {
                  echo"<h1>Pasirinkite</h1>";
              }
           ?>
          </div>
        </div>
      </div>
    </div>









  </body>
</html>
