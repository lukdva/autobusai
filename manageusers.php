<?php
session_start();
include "config.php";

include "manageusersQuerries.php";

$GETArrayHardCopy = array();
foreach ($_GET as $key => $value) {
  $GetArrayHardCopy[$key] = $value;
}
 ?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Bootstrap 101 Template</title>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins)-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.16.0/moment.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.1.0/js/bootstrap-datetimepicker.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.1.0/css/bootstrap-datetimepicker.css" />

    <link rel="stylesheet" type="text/css" href="./css/table-hover.css">

  </head>

  <body>
    <nav class="navbar navbar-inverse navbar-static-top">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
<!--================================NAVBAR LEFT SIDE================================================================ -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="index.php">
        <span class="glyphicon glyphicon-home" aria-hidden="true"></span>
      </a>
    </div>


    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li class="active"><a href="#">Link <span class="sr-only">(current)</span></a></li>
        <li><a href="timetable.php?trips=closest_trips">Timetable</a></li>
        <?php
        if (isset($_SESSION['type']))
        {
            if ($_SESSION['type'] == "Administrator") {
              echo '<li><a href="manageusers.php">User List(adminui)</a></li>';
            }
            if ($_SESSION['type'] == "Manager") {
              echo '<li><a href="manageTimetables.php">Manage Timetables & routes(managerONLY)</a></li>';
            }
        }
        ?>
      </ul>
<!--================================NAVBAR LEFT SIDE================================================================ -->

<!--================================NAVBAR RIGHT SIDE================================================================ -->
      <ul class="nav navbar-nav navbar-right">
        <?php
        if (!isset($_SESSION['id']))
        { ?>
            <li><a href="signup.php">Sign up</a></li>
            <li><a href="login.php">Login</a></li>
        <?php
        }
        else
        { ?>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">User<span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="#">User Name Here</a></li>
                <li><a href="profile.php">Profile</a></li>
                <li role="separator" class="divider"></li>
                <li><a href="logout.php">Logout</a></li>
              </ul>
            </li>
        <?php
        } ?>
      </ul>
<!--================================NAVBAR RIGHT SIDE================================================================ -->
<!--================================SEARCH BY USERNAME FORM================================================================ -->
      <form class="navbar-form navbar-right" method="post">
          <div class="form-group">
            <input type="text" class="form-control" placeholder="Search by username" name="SearchUserByName">
          </div>
          <button href = ""type="submit" class="btn btn-default">Search</button>
      </form>
<!--================================SEARCH BY USERNAME FORM================================================================ -->
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>


      <div class="row">
        <div class="col-md-3">

          <div class="list-group">
            <a href="#" class="list-group-item active">
              Users
            </a>
            <a href="manageusers.php?users=active&page=1" class="list-group-item">Active</a>
            <a href="manageusers.php?users=blocked&page=1" class="list-group-item">Blocked</a>
            <a href="manageusers.php?users=managers&page=1" class="list-group-item">Managers</a>
            <a href="manageusers.php?users=admins&page=1" class="list-group-item">Admins</a>
            <a href="manageusers.php?users=members&page=1" class="list-group-item">Members</a>
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
                            <th>Username</th>
                            <th>Type</th>
                            <th>Is Blocked</th>
                            <?php
                              if ($_SESSION['type'] == "Administrator") {
                                echo "<th>Some Button</th>";
                              }
                            ?>

                        </tr>
                    </thead>


                    <tbody>
                        <?php
                          foreach ($rows as $value)
                          {
                            echo '<tr>
                            <td>'.$value['username'].'</td>
                            <td>'.$value['type'].'</td>
                            <td>'.$value['isBlocked'].'</td>';
                            if (isset($_SESSION['id']))
                            {
                              echo "<td>button</td>";
                            }
                            echo '</tr>';
                          }
                            ?>
                    </tbody>

                </table>
<!--================================USERS TABLE END================================================================ -->

<!--================================PAGINATION BUTTONS================================================================ -->
                <nav aria-label="Page navigation" class="pagination pagination-right">
                  <ul class="pagination">
                    <?php
                      //PREPARATION FOR FIRST PAGE ELEMENT
                      $GETArrayHardCopy['page'] = 1; //setting to link to first page
                      if ($_GET['page'] == 1) //checking if current page is first
                      {
                        $disabledClass= ' class="disabled"';
                      }
                      else
                      {
                        $disabledClass= '';
                      }
                      // FIRST PAGE ELEMENT
                        echo
                      '<li'.$disabledClass.'>
                        <a href="manageusers.php?'.http_build_query($GETArrayHardCopy).'" aria-label="Previous">
                          <span aria-hidden="true">&laquo;</span>
                        </a>
                      </li>';

                      //PAGE NUMBER ELEMENTS
                      for ($i=1; $i <= $pages; $i++)
                      {
                        $GETArrayHardCopy['page'] = $i; //setting page value for http build querry
                        if($_GET['page'] == $i)
                          echo '<li class="active"><a href="manageusers.php?'.http_build_query($GETArrayHardCopy).'">'.$i.'</a></li>';
                        else
                          echo '<li><a href="manageusers.php?'.http_build_query($GETArrayHardCopy).'">'.$i.'</a></li>';
                      }
                      //PREPARATION TO LAST ELEMENT
                      $GETArrayHardCopy['page'] = $pages; //setting to link to last page
                      if ($_GET['page'] == $pages)  //checking if current page is last
                      {
                        $disabledClass= ' class="disabled"';
                      }
                      else
                      {
                        $disabledClass= '';
                      }
                      //LAST PAGE ELEMENT
                      echo
                        '<li'.$disabledClass.'>
                          <a href="manageusers.php?'.http_build_query($GETArrayHardCopy).'" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                          </a>
                        </li>';
                    ?>
                  </ul>
                </nav>
<!--================================PAGINATION BUTTONS END================================================================ -->
            <?php
              }
              else {
                echo"<h1>Deez Nuts </h1>";
              }
           ?>






         </div> <!-- jumbotron end-->


        </div>

      </div>

    </div>









  </body>
</html>
