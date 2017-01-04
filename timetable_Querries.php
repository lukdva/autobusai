<?php
$lowTreshold = 1;
$today = date("Y-m-d");                   // 2001-03-10
$now = date("H:i:s");                   // 17:16:18

include "config.php";

//Buying ticket
if(isset($_GET['trip_id']))
{
  //Selecting one tickets id
  $stmt = $db->prepare(
      "SELECT id
      FROM ticket
      WHERE trip_fk = ? AND login_fk IS NULL
      LIMIT 1");
  $stmt->bindParam(1, $_GET['trip_id']);
  $stmt->execute();
  $ticket = $stmt->fetch();
//Assigning user id to that ticket
  $stmt = $db->prepare(
      "UPDATE ticket
      SET login_fk= ?
      WHERE ticket.id= ?");
  $stmt->bindParam(1, $_SESSION['id']);
  $stmt->bindParam(2, $ticket['id']);
  $stmt->execute();

}
// Displaying trips
if(!empty($_POST) && !empty($_POST['city_to']) && !empty($_POST['city_from']) && !empty($_POST['datetime']))
{
  $city_to = $_POST['city_to'];
  $city_from = $_POST['city_from'];
  $datetime =explode(" ", $_POST['datetime']);
  $date = $datetime[0];
  $time = $datetime[1];

  $stmt = $db->prepare(
      "SELECT trip.id, trip.date, trip.time, trip.price, cityFrom.name as from_city, cityTo.name as to_city, SUM(IF(ticket.login_fk IS NULL, 1, 0)) as available_tickets
      FROM ticket, trip, route
      LEFT JOIN city AS cityFrom ON  cityFrom.name = ?
      LEFT JOIN city AS cityTo ON cityTo.name = ?
      WHERE trip.route_fk = route.id AND cityFrom.id = route.city_from_fk AND cityTo.id = route.city_to_fk AND ticket.trip_fk = trip.id
      AND trip.date = ? AND trip.time > ?
      GROUP BY trip.id");
  $stmt->bindParam(1, $city_from);
  $stmt->bindParam(2, $city_to);
  $stmt->bindParam(3, $date);
  $stmt->bindParam(4, $time);

  $stmt->execute();
  $rows = $stmt->fetchAll();
  //print_r($rows);
}
elseif (isset($_GET['trips']))
{
  $getpar= $_GET['trips'];
  if ($getpar == "sold_out")
  {

    $stmt = $db->prepare(
        "SELECT trip.id, trip.date, trip.time, trip.price, cityFrom.name as from_city, cityTo.name as to_city ,SUM(IF(ticket.login_fk IS NULL, 1, 0)) as available_tickets, COUNT(ticket.login_fk) as sold_tickets
        FROM ticket, trip, route
        LEFT JOIN city AS cityFrom ON  cityFrom.id = route.city_from_fk
        LEFT JOIN city AS cityTo ON cityTo.id = route.city_to_fk
        WHERE ticket.trip_fk = trip.id AND trip.route_fk = route.id AND (trip.date > ? OR ( trip.time > ? AND trip.date = ? ))
        GROUP BY trip.id
        HAVING available_tickets = 0
        ORDER BY trip.date ASC, trip.time ASC
        LIMIT 0,10");
    $stmt->bindParam(1, $today);
    $stmt->bindParam(2, $now);
    $stmt->bindParam(3, $today);

    $stmt->execute();
    $rows = $stmt->fetchAll();
  }
  elseif ($getpar == "few_tickets") {
    $stmt = $db->prepare(

    "SELECT trip.id, trip.date, trip.time, trip.price, cityFrom.name as from_city, cityTo.name as to_city ,SUM(IF(ticket.login_fk IS NULL, 1, 0)) as available_tickets, COUNT(ticket.login_fk) as sold_tickets
    FROM ticket, trip, route
    LEFT JOIN city AS cityFrom ON  cityFrom.id = route.city_from_fk
    LEFT JOIN city AS cityTo ON cityTo.id = route.city_to_fk
    WHERE ticket.trip_fk = trip.id AND trip.route_fk = route.id AND (trip.date > ? OR ( trip.time > ? AND trip.date = ? ))
    GROUP BY trip.id
    HAVING available_tickets <= ? AND available_tickets > 0
    ORDER BY trip.date ASC, trip.time ASC
    LIMIT 0,10"
    );
    $stmt->bindParam(1, $today);
    $stmt->bindParam(2, $now);
    $stmt->bindParam(3, $today);
    $stmt->bindParam(4, $lowTreshold);

    $stmt->execute();
    $rows = $stmt->fetchAll();
  }
  elseif ($getpar == "closest_trips") {
    $stmt = $db->prepare(

    "SELECT trip.id, trip.date, trip.time, trip.price, cityFrom.name as from_city, cityTo.name as to_city ,SUM(IF(ticket.login_fk IS NULL, 1, 0)) as available_tickets, COUNT(ticket.login_fk) as sold_tickets
    FROM ticket, trip, route
    LEFT JOIN city AS cityFrom ON  cityFrom.id = route.city_from_fk
    LEFT JOIN city AS cityTo ON cityTo.id = route.city_to_fk
    WHERE ticket.trip_fk = trip.id AND trip.route_fk = route.id AND (trip.date > ? OR ( trip.time > ? AND trip.date = ? ))
    GROUP BY trip.id
    ORDER BY trip.date ASC, trip.time ASC
    LIMIT 0,10"
    );
    $stmt->bindParam(1, $today);
    $stmt->bindParam(2, $now);
    $stmt->bindParam(3, $today);

    $stmt->execute();
    $rows = $stmt->fetchAll();
  }

}
else {
  $rows = null;
}


 ?>
