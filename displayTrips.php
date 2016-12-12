<?php
include "config.php";

//$lowTreshold = 1;
$today = date("Y-m-d");                   // 2001-03-10
$now = date("H:i:s");                   // 17:16:18


$GETArrayHardCopy = array();
foreach ($_GET as $key => $value) {
  $GETArrayHardCopy[$key] = $value;
}

//Setting filters, for pagination reasons
if ( !empty($_POST) )
{
    $_SESSION['city_from'] = $_POST['city_from'];
    $_SESSION['city_to'] = $_POST['city_to'];
    $_SESSION['datetime'] = $_POST['datetime'];
    $_SESSION['datetime2'] = $_POST['datetime2'];
    $_SESSION['display_option'] = $_GET['option'];

}
elseif(isset($_GET['option'])&& isset($_SESSION['display_option']) && $_GET['option'] == $_SESSION['display_option'])
{
  $city_from_querry = "";
  $city_to_querry = "";
  $date_from_querry = "";
  $date_to_querry = "";
  if(isset($_SESSION['city_from']) && !empty($_SESSION['city_from']))
      $city_from_querry = " AND cityFrom.name = '".$_SESSION['city_from']."'";
  if(isset($_SESSION['city_to']) && !empty($_SESSION['city_to']))
      $city_to_querry = " AND cityTo.name = '".$_SESSION['city_to']."'";
  if(isset($_SESSION['datetime']) && !empty($_SESSION['datetime']))
  {
      $datetime =explode(" ", $_SESSION['datetime']);
      $date = $datetime[0];
      $time = $datetime[1];
      $date_from_querry= " AND (trip.date > {$date} OR (trip.date = {$date} AND trip.time > {$time}))";
  }
  if(isset($_SESSION['datetime2']) && !empty($_SESSION['datetime2']))
  {
      $datetime =explode(" ", $_SESSION['datetime']);
      $date = $datetime[0];
      $time = $datetime[1];
      $date_to_querry= " AND (trip.date < {$date} OR (trip.date = {$date} AND trip.time < {$time}))";
  }
}

//Setting filters to sql querry
if ( !empty($_POST) )
{
      if (!empty($_POST['city_to']))
      {
          $city_to_querry = " AND cityTo.name = '".$_POST['city_to']."'";
      }
      else
      {
          $city_to_querry = "";
      }

      if (!empty($_POST['city_from'])) {
          $city_from_querry = " AND cityFrom.name = '".$_POST['city_from']."'";
      }
      else
      {
          $city_from_querry = "";
      }

      if (!empty($_POST['datetime']))
      {
          $datetime =explode(" ", $_POST['datetime']);
          $date = $datetime[0];
          $time = $datetime[1];
          $date_from_querry= " AND (trip.date > {$date} OR (trip.date = {$date} AND trip.time > {$time}))";
      }
      else
      {
          $date_from_querry = "";
      }

      if (!empty($_POST['datetime2']))
      {
          $datetime =explode(" ", $_POST['datetime']);
          $date = $datetime[0];
          $time = $datetime[1];
          $date_to_querry= " AND (trip.date < {$date} OR (trip.date = {$date} AND trip.time < {$time}))";
      }
      else
      {
          $date_to_querry = "";
      }

      $conditions_querry = $city_to_querry.$city_from_querry.$date_from_querry.$date_to_querry;
}
else
{
    $conditions_querry = "";
}

$stmt = $db->prepare(
  "SELECT COUNT(*) as kiek
  FROM ticket, trip, route
  LEFT JOIN city AS cityFrom ON  cityFrom.id = route.city_from_fk
  LEFT JOIN city AS cityTo ON cityTo.id = route.city_to_fk
  WHERE trip.route_fk = route.id AND ticket.trip_fk = trip.id {$conditions_querry}
  GROUP BY trip.id");

$stmt->execute();
$rowCount = $stmt->fetch();
$count = $rowCount['kiek'];
$pages = ceil($count/$pagination);

if (isset($_GET['page']) && $_GET['page'] <= $pages)
{
      $offset = ($_GET['page'] - 1) * $pagination;
      $stmt = $db->prepare(
        "SELECT trip.id, trip.date, trip.time, trip.price, cityFrom.name as from_city, cityTo.name as to_city, SUM(IF(ticket.login_fk IS NULL, 1, 0)) as available_tickets
        FROM ticket, trip, route
        LEFT JOIN city AS cityFrom ON  cityFrom.id = route.city_from_fk
        LEFT JOIN city AS cityTo ON cityTo.id = route.city_to_fk
        WHERE trip.route_fk = route.id AND ticket.trip_fk = trip.id {$conditions_querry}
        GROUP BY trip.id
        LIMIT ?,?");

      $stmt->bindParam(1, $offset, PDO::PARAM_INT);
      $stmt->bindParam(2, $pagination, PDO::PARAM_INT);
      $stmt->execute();
      $rows = $stmt->fetchAll();
}
else
{
  $rows = null;
}
  //print_r($_SERVER['QUERY_STRING']);


 ?>

 <!--================================TRIPS TABLE================================================================ -->
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
                             <th>Kaina</th>
                             <?php
                               if ($_SESSION['type'] == "Manager") {
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
                               <td>'.$value['price'].'</td>';
                             if ($_SESSION['type'] == "Manager")
                             {
                               $GETArrayHardCopy["delete"] = $value['id'];
                               echo "<td><a href='?".http_build_query($GETArrayHardCopy)."'><button type='button' class='btn btn-danger'>Ištrinti</button></a></td>";
                             }
                             echo '</tr>';
                           }
                           unset($GETArrayHardCopy["delete"]); // removing this, for pagination purposes
                             ?>
                     </tbody>

                 </table>
 <!--================================USERS TABLE END================================================================ -->
<!--================================PAGINATION BUTTONS================================================================ -->
    <?php include "pagination.php"; ?>
<!--================================PAGINATION BUTTONS END================================================================ -->
             <?php
               }
               else {
                 echo"<h1>Nerasta duomenų pagal duotus kriterijus. :(</h1>";
               }
            ?>
