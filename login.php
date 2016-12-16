
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
                    <?php
                    include "config.php";
                        if (isset($_POST['username']) && isset($_POST['password'])){
                          $username = $_POST['username'];
                          $password = md5($_POST['password']);
                          $stmt = $db->prepare("SELECT * FROM login WHERE username=? AND password=?");
                          $stmt->bindParam(1, $username);
                          $stmt->bindParam(2, $password);
                          $stmt->execute();
                          $row = $stmt->fetch();
                          $user = $row['username'];
                          $pass = $row['password'];
                          $id = $row['id'];
                          $type = $row['type'];
                          if($username==$user && $pass==$password)
                          {
                            session_start();
                            $_SESSION['username'] = $user;
                            $_SESSION['password'] = $pass;
                            $_SESSION['id'] = $id;
                            $_SESSION['type'] = $type;
                            ?>
                            <script>window.location.href='index.php'</script>
                            <?php
                          }
                          else {
                            ?>

                            <div class="alert alert-danger alert-dismissible" role="alert">
                              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                              <strong>Neteisingas slaptažodis arba vartotojo vardas.</strong>
                            </div>

                            <?php
                          }
                        }
                        $db= null;
                     ?>
                      <form method="post">
                        <div class="form-group">
                          <label>Vartotojo vardas</label>
                          <input type="text" class="form-control" name="username"/>
                        </div>
                        <div class="form-group">
                          <label>Slaptažodis</label>
                          <input type="password" class="form-control" name="password"/>
                        </div>
                          <input type="submit" value="Prisijungti" class="btn btn-primary"/>
                          <a href="registration.php"class="pull-right">Registracija</a>
                          <a href="index.php"class="pull-right">Pagrindinis</a>
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
