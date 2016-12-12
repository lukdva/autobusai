<?php
include "config.php";

$GETArrayHardCopy = array();
foreach ($_GET as $key => $value) {
  $GETArrayHardCopy[$key] = $value;
}

//$isSetCityTo = isset($_POST['city_to']);
//$isSetCityFrom = isset($_POST['city_from']);
//$isSetDateTime = isset($_POST['datetime']);
//$isSetDateTime2 = isset($_POST['datetime2']);
//Setting filters, for pagination reasons

//Setting filters for sql querries
if ( !empty($_POST) )
{
      $_SESSION['city_from'] = $_POST['city_from'];
      $_SESSION['city_to'] = $_POST['city_to'];
      $_SESSION['display_option'] = $_GET['option'];

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
      else{
          $city_from_querry = "";
      }

      $conditions_querry = $city_to_querry.$city_from_querry;
}
//setting filter for pagination purposes
elseif(isset($_GET['option']) && isset($_SESSION['display_option']) && $_GET['option'] == $_SESSION['display_option'])
{
      $city_from_querry = "";
      $city_to_querry = "";
      if(isset($_SESSION['city_from']) && !empty($_SESSION['city_from']))
      {
          $city_from_querry = " AND cityFrom.name = '".$_SESSION['city_from']."'";
      }
      if(isset($_SESSION['city_to']) && !empty($_SESSION['city_to']))
      {
          $city_to_querry = " AND cityTo.name = '".$_SESSION['city_to']."'";
      }
    $conditions_querry = $city_to_querry.$city_from_querry;
}
else
{
    $conditions_querry = "";
}

$stmt = $db->prepare(
  "SELECT COUNT(*) as kiek
  FROM route
  LEFT JOIN city AS cityFrom ON  cityFrom.id = route.city_from_fk
  LEFT JOIN city AS cityTo ON cityTo.id = route.city_to_fk
  WHERE cityFrom.name LIKE '%%' {$conditions_querry}");

$stmt->execute();
$rowCount = $stmt->fetch();
$count = $rowCount['kiek'];
$pages = ceil($count/$pagination);

if (isset($_GET['page']) && $_GET['page'] <= $pages)
{
      $offset = ($_GET['page'] - 1) * $pagination;
      $stmt = $db->prepare(
        "SELECT route.id, cityFrom.name as from_city, cityTo.name as to_city
        FROM route
        LEFT JOIN city AS cityFrom ON  cityFrom.id = route.city_from_fk
        LEFT JOIN city AS cityTo ON cityTo.id = route.city_to_fk
        WHERE cityFrom.name LIKE '%%' {$conditions_querry}
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
                             <th>Iš</th>
                             <th>Į</th>
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
                               <td>'.$value['from_city'].'</td>
                               <td>'.$value['to_city'].'</td>';
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
