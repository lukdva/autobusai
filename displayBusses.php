<?php
include "config.php";

$GETArrayHardCopy = array();
foreach ($_GET as $key => $value) {
  $GETArrayHardCopy[$key] = $value;
}
$
//========================================================= DELETION=================================================
if (isset($_GET['delete']))
{
    stmt = $db->prepare(
      "DELETE
      FROM bus
      WHERE id = ?");
    $stmt->bindParam(1, $_GET["delete"], PDO::PARAM_INT);

    $stmt->execute();
    print_r($stmt->rowCount());
}
//=========================================================END OF DELETION=================================================
$stmt = $db->prepare(
  "SELECT COUNT(*) as kiek
  FROM bus");

$stmt->execute();
$rowCount = $stmt->fetch();
$count = $rowCount['kiek'];
$pages = ceil($count/$pagination);

if (isset($_GET['page']) && $_GET['page'] <= $pages)
{
      $offset = ($_GET['page'] - 1) * $pagination;
      $stmt = $db->prepare(
        "SELECT id, bus_space
        FROM bus
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
 ?>
 <!--================================TRIPS TABLE================================================================ -->
             <?php
             if ($rows != null)
             {
               ?>
                 <table class="table table-hover">
                     <thead>
                         <tr>
                             <th>Id nr.</th>
                             <th>Vietų sk.</th>
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
                               <td>'.$value['id'].'</td>
                               <td>'.$value['bus_space'].'</td>';
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
