<?php
include "config.php";
session_start();
if(!isset($_SESSION['username']))
{
  header('location: login.php');
}

//Select Cities from DB
$stmt = $db->prepare("SELECT name FROM city");
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
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

  <script>
  $( function() {
    $( "#datetimepicker" ).datetimepicker();
  } );
  </script>
  <script>
  // for navigation highlighting NOT working for nav header
  $(function() {
    $('nav div a[href*="' + location.pathname.split("/")[2] + '"]').parent().addClass('active');
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



<form id="searchTripForm" method="post" class="form-inline">
    <div class="container">

      <div class="form-group">
        <label class="col-xs-3 control-label">Iš</label>
        <div class="col-xs-5 selectContainer">
            <select class="form-control" name="city_to">
              <option value="">Pasirinkite miestą</option>
                <?php
                foreach ($rows as $key => $value) {
                  echo '<option value="'.$value.'">'.$value.'</option>';                }

                 ?>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label class="col-xs-3 control-label">Į</label>
        <div class="col-xs-5 selectContainer">
            <select class="form-control" name="color">
              <option value="">Pasirinkite miestą</option>
                <?php
                foreach ($rows as $key => $value) {
                  echo '<option value="'.$value.'">'.$value.'</option>';                }

                 ?>
            </select>
        </div>
    </div>

<div class="form-group">
        <div class='col-lg-12 col-md-9 col-sm-6 '>

                <div class='input-group date' id='datetimepicker'>
                    <input type='text' class="form-control" />
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



        </div>
        <div class="col-md-6">
          <div class="jumbotron">

            <h1>Hello, Admin or Member!</h1>
            <p>This is a simple hero unit, a simple jumbotron-style component for calling extra attention to featured content or information.</p>
            <p><a class="btn btn-primary btn-lg" href="#" role="button">Learn more</a></p>

          </div>
        </div>
      </div>
    </div>

  </body>
</html>
