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
    <li><a href="timetable.php?trips=closest_trips">Tvarkaraščiai</a></li>
    <?php
    if (isset($_SESSION['type']))
    {
        if ($_SESSION['type'] == "Administrator" || $_SESSION['type'] == "SA") {
          echo '<li><a href="manageusers.php">Vartotojų sąrašas</a></li>';
        }
        if ($_SESSION['type'] == "Manager") {
          echo '<li><a href="manageTimetables.php">Koreguoti tvarkaraščius</a></li>';
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
        <li><a href="registration.php">Registruotis</a></li>
        <li><a href="login.php">Prisijungti</a></li>
    <?php
    }
    else
    { ?>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $_SESSION["username"]?><span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="#"><?php echo $_SESSION["type"]?></a></li>
            <li><a href="profile.php">Profilis</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="logout.php">Atsijungti</a></li>
          </ul>
        </li>
    <?php
    } ?>
  </ul>
<!--================================NAVBAR RIGHT SIDE================================================================ -->
<!--================================SEARCH BY USERNAME FORM================================================================ -->
<?php if($_SERVER["PHP_SELF"] == "/Php/manageusers.php")
{ ?>
      <form class="navbar-form navbar-right" method="post">
          <div class="form-group">
            <input type="text" class="form-control" placeholder="Ieškoti pagal vartotojo vardą" name="SearchUserByName">
          </div>
          <button href = ""type="submit" class="btn btn-default">Ieškoti</button>
      </form>
<?php
} ?>
<!--================================SEARCH BY USERNAME FORM================================================================ -->
</div><!-- /.navbar-collapse -->
</div><!-- /.container-fluid -->
</nav>
