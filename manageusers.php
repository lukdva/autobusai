<?php
session_start();
if(!isset($_SESSION['type']) || ($_SESSION['type'] != "Administrator" && $_SESSION['type'] != "SA"))
{
  header('location: index.php');
}
include "config.php";

include "manageusersQuerries.php";
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

  </head>

  <body>
    <?php include "navbar.php"; ?>


      <div class="row">
        <div class="col-md-3">

          <div class="list-group">
            <a class="list-group-item listgrouptitle">Vartotojų rūšiavimas</a>
            <a href="?users=active&page=1" class="list-group-item">Aktyvūs</a>
            <a href="?users=blocked&page=1" class="list-group-item">Blokuoti</a>
            <a href="?users=managers&page=1" class="list-group-item">Vadybininkai</a>
            <a href="?users=admins&page=1" class="list-group-item">Administratoriai</a>
            <a href="?users=members&page=1" class="list-group-item">Vartotojai</a>
          </div>

        </div>
        <div class="col-md-9">
          <div class="jumbotron">
<!--================================USERS TABLE================================================================ -->
            <?php
            if ($rows != null)
            {
              ?>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Vardas</th>
                            <th>Lygis</th>
                            <th>Ar blokuotas</th>
                            <?php
                              if ($_SESSION['type'] == "Administrator") {
                                echo "<th>Veiksmai</th>";
                              }
                            ?>

                        </tr>
                    </thead>


                    <tbody>
                        <?php
                          foreach ($rows as $value)
                          {
                            if($value['type'] == 0)

                            echo '<tr>
                            <td>'.$value['username'].'</td>
                            <td>'.$value['type'].'</td>
                            <td>'.$value['isBlocked'].'</td>';
                            if (isset($_SESSION['id']))
                            {
                              if($value['isBlocked'] = 0)
                              {
                                  echo "<td><a><button type='button' class='btn btn-danger'>Blokuoti</button></a></td>";
                              }
                              else {
                                  echo "<td><a><button type='button' class='btn btn-success'>Atblokuoti</button></a></td>";
                              }
                            }
                            echo '</tr>';
                          }
                            ?>
                    </tbody>

                </table>
<!--================================USERS TABLE END================================================================ -->

<!--================================PAGINATION BUTTONS================================================================ -->
<?php
      include "pagination.php";
?>
<!--================================PAGINATION BUTTONS END================================================================ -->
            <?php
              }
              else {
                echo"<h1>Nerasta duomenų pagal duotus kriterijus. :(</h1>";
              }
           ?>






         </div> <!-- jumbotron end-->


        </div>

      </div>

    </div>









  </body>
</html>
