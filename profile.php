<?php
session_start();
include "config.php";


include "profile_querries.php";

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

      <div class="container">
        <div class="col-md-3">

          <div class="list-group">
            <a class="list-group-item listgrouptitle"><?php echo $_SESSION['username'];?></a>
            <a href="?option=my_tickets" class="list-group-item">Užsakyti bilietai</a>
            <a href="?option=settings" class="list-group-item">Nustatymai</a>
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
                            <th>Veiksmai</th>
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
                              <td>'.$value['to_city'].'</td>';
                              $GETArrayHardCopy['ticket_id'] = $value['ticket'];
                              echo "<td><a href=\"?".http_build_query($GETArrayHardCopy)."\"><button type='button' class='btn btn-danger'>Atšaukti</button></a></td>";
                            echo '</tr>';
                          }
                            ?>
                    </tbody>

                </table>
            <?php
              }
              elseif(isset($_GET['option']) && $_GET['option'] == 'settings')
              {
                echo"<h1>Greitu metu atsiras...</h1>";
              }
              elseif(isset($_GET['option'])) {
                echo"<h1>Neturite užsakytų bilietų</h1>";
              }
              else {
                echo"<h1>".$_SESSION['username']." pofilis</h1>";
              }
           ?>
          </div>
        </div>
      </div>
