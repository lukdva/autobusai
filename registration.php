<?php
include "config.php";
    if (!empty($_POST)){
      $errormsg= "";
      if (strlen($_POST['username']) < 3) {
        $errormsg= "Username must be at least 3 symbols";
      }
      if (strlen($_POST['password']) < 3) {
        $errormsg= "Password must be at least 3 symbols";
      }
      if($_POST['password'] != $_POST['repeat_password'])
      {
        $errormsg= "Passwords doesn't match";
      }
      if(empty($errormsg))
      {
          $username = $_POST['username'];
          $password = md5($_POST['password']);
          $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          $sql = "INSERT INTO `login` (`username`, `password`, `type`)
                  VALUES(?, ? , 'Member')";
          $stmt = $db->prepare($sql);
          $stmt->bindParam(1, $username);
          $stmt->bindParam(2, $password);
          $stmt->execute();

        ?>
        <script>window.location.href='login.php'</script>
        <?php

      }
    $db= null;
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

    <!-- Bootstrap -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">

  </head>
  <body>
    <p><br/><br/><br/></p>
    <div class="container">
        <div class="row">
          <div class="col-md-4"></div>
          <div class="col-md-4">
              <div class="panel panel-default">
                  <div class="panel-body">

<?php if(!empty($errormsg))
      {?>
                <div class="alert alert-danger alert-dismissible" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <strong><?php echo $errormsg ?></strong>
                </div>
<?php  } ?>                    <form method="post">
                        <div class="form-group">
                          <label>Vartotojo vardas</label>
                          <input type="text" class="form-control" name="username"/>
                        </div>
                        <div class="form-group">
                          <label>Slaptažodis</label>
                          <input type="password" class="form-control" name="password"/>
                        </div>
                        <div class="form-group">
                          <label>Pakartokite Slaptažodį</label>
                          <input type="password" class="form-control" name="repeat_password"/>
                        </div>
                          <input type="submit" value="Registruotis" class="btn btn-primary"/>
                          <a href="login.php"class="pull-right">Prisijungimas</a>
                      </form>

                  </div>
              </div>
          </div>
          <div class="col-md-4"></div>
        </div>
    </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>
