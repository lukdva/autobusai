<?php
session_start();
include "config.php";
$stmt = $db->prepare("SELECT name FROM city");
$stmt->execute();
$cities = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

include "timetable_Querries.php";

$GETArrayHardCopy = array();
foreach ($_GET as $key => $value) {
  $GETArrayHardCopy[$key] = $value;
}
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
    $( "#datetimepicker" ).datetimepicker(
      {
      format: 'YYYY-MM-DD HH:mm:ss',
      value: new Date()
    });
  } );
  </script>
  <script>
  // for navigation highlighting
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



<form id="searchTripForm" action="timetable.php"method="post" class="form-inline">
    <div class="container">

      <div class="form-group">
        <label class="col-xs-3 control-label">Iš</label>
        <div class="col-xs-5 selectContainer">
            <select class="form-control" name="city_from">
              <option value="">Pasirinkite miestą</option>
                <?php
                foreach ($cities as $key => $value) {
                  echo '<option value="'.$value.'">'.$value.'</option>';                }

                 ?>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label class="col-xs-3 control-label">Į</label>
        <div class="col-xs-5 selectContainer">
            <select class="form-control" name="city_to">
              <option value="">Pasirinkite miestą</option>
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
        <div class="col-xs-5 col-xs-offset-3">
            <button type="submit" class="btn btn-default">Ieškoti</button>
        </div>
    </div>

</form>

      <div class="row">
        <div class="col-md-3">

          <div class="list-group">
            <a class="list-group-item listgrouptitle">Top 10</a>
            <a href="?trips=sold_out" class="list-group-item">Parduotos kelionės</a>
            <a href="?trips=few_tickets" class="list-group-item">Mažai bilietų</a>
            <a href="?trips=closest_trips" class="list-group-item">Artimiausios</a>
          </div>

        </div>
        <div class="col-md-9">
          <div class="jumbotron">

            <?php
            if ($rows != null)
            {
              ?>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Išvykimas</th>
                            <th>Iš</th>
                            <th>Į</th>
                            <th>Kainas</th>
                            <th>Liko bilietų</th>
                            <?php
                              if (isset($_SESSION['id'])) {
                                echo "<th>Veiksmai</th>";
                              }
                            ?>

                        </tr>
                    </thead>


                    <tbody>
                        <?php
                          foreach ($rows as $value)
                          {
                            echo '<tr>
                            <td>'.$value['date'].'</td>
                            <td>'.$value['time'].'</td>
                            <td>'.$value['from_city'].'</td>
                            <td>'.$value['to_city'].'</td>
                            <td>'.$value['price'].'</td>
                            <td>'.$value['available_tickets'].'</td>';
                            if (isset($_SESSION['id']) && $value['available_tickets'] > 0) //is user logged in AND at least 1 ticket left
                            {
                              $GETArrayHardCopy['trip_id'] = $value['id'];
                              echo "<td><a href=\"?".http_build_query($GETArrayHardCopy)."\"><button type='button' class='btn btn-primary'>Pirkti</button></a></td>";
                            }
                            else
                            {
                              echo "<td><a><button type='button' class='btn btn-primary disabled'>Pirkti</button></a></td>";
                            }
                            echo '</tr>';
                          }
                            ?>
                    </tbody>

                </table>
            <?php
              }
              else {
                echo"<h1>Nerasta duomenų pagal duotus kriterijus. :(</h1>";
              }
           ?>
          </div>
        </div>
      </div>
    </div>









  </body>
</html>
