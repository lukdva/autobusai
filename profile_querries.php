<?php
$lowTreshold = 1;
$today = date("Y-m-d");                   // 2001-03-10
$now = date("H:i:s");                   // 17:16:18

include "config.php";
if(isset($_GET['ticket_id']))
{
  $stmt = $db->prepare(
      "UPDATE ticket
      SET login_fk= NULL
      WHERE ticket.id= ?");
  $stmt->bindParam(1, $_GET['ticket_id']);
  $stmt->execute();
}
if(isset($_GET['option'])&& $_GET['option'] == 'my_tickets')
{
  $stmt = $db->prepare(
    "SELECT ticket.id as ticket, trip.id, trip.date, trip.time, cityFrom.name as from_city, cityTo.name as to_city
      FROM ticket, trip, route
      LEFT JOIN city AS cityFrom ON  cityFrom.id = route.city_from_fk
      LEFT JOIN city AS cityTo ON cityTo.id = route.city_to_fk
      WHERE trip.route_fk = route.id AND ticket.trip_fk = trip.id
      AND ticket.login_fk = ? AND ticket.trip_fk = trip.id
      AND (trip.date > ? OR (trip.date = ? AND trip.time > ?))
      GROUP BY ticket.id");

      $stmt->bindParam(1, $_SESSION['id']);
      $stmt->bindParam(2, $today);
      $stmt->bindParam(3, $today);
      $stmt->bindParam(4, $now);

      $stmt->execute();
      $rows = $stmt->fetchAll();
}
else {
  $rows = null;
}
?>
