<?php
  include "config.php";

  $searchKey = '';
  $manager = 'Manager';
  $admin = 'Administrator';
  $member = 'Member';
  $pagination = 10;
  if (isset($_POST['SearchUserByName'])) {
    $searchKey = $_POST['SearchUserByName'];
    $_SESSION['searchKey'] = $searchKey;
    $_SESSION['users'] = $_GET['users'];
  }
  else {
    if (isset($_SESSION['searchKey']) && isset($_GET['users']) && $_GET['users'] == $_SESSION['users']) //checking for pagination if post method for search was set and we didn't change users cathegory
    {
      $searchKey = $_SESSION['searchKey'];
    }
  }
  $searchKey = "%".$searchKey."%";
  if (isset($_GET['users']))
  {
    switch ($_GET['users']) {
      case 'active':
          $stmt = $db->prepare("SELECT Count(*) AS kiek FROM login WHERE isblocked = 0 AND username LIKE ? ORDER BY username ASC");
          $stmt->bindParam(1, $searchKey);
          $stmt->execute();
          $rowCount = $stmt->fetch();
          $count = $rowCount['kiek'];
          $pages = ceil($count/$pagination);
          if (isset($_GET['page']) && $_GET['page'] <= $pages)
          {
            $offset = ($_GET['page'] - 1) * $pagination;
            $stmt = $db->prepare("SELECT * FROM login WHERE isblocked = 0 AND username LIKE ? ORDER BY username ASC LIMIT ?,?");
            $stmt->bindParam(1, $searchKey);
            $stmt->bindParam(2, $offset, PDO::PARAM_INT);
            $stmt->bindParam(3, $pagination, PDO::PARAM_INT);
            $stmt->execute();
            $rows = $stmt->fetchAll();
          }
          else {
            $rows = null;
          }
      break;
      case 'blocked':
          $stmt = $db->prepare("SELECT Count(*) AS kiek FROM login WHERE isblocked = 1 AND username LIKE ? ORDER BY username ASC");
          $stmt->bindParam(1, $searchKey);
          $stmt->execute();
          $rowCount = $stmt->fetch();
          $count = $rowCount['kiek'];
          $pages = ceil($count/$pagination);
          if (isset($_GET['page']) && $_GET['page'] <= $pages)
          {
            $offset = ($_GET['page'] - 1) * $pagination;
            $stmt = $db->prepare("SELECT * FROM login WHERE isblocked = 1 AND username LIKE ? ORDER BY username ASC LIMIT ?,?");
            $stmt->bindParam(1, $searchKey);
            $stmt->bindParam(2, $offset, PDO::PARAM_INT);
            $stmt->bindParam(3, $pagination, PDO::PARAM_INT);
            $stmt->execute();
            $rows = $stmt->fetchAll();
          }
          else {
            $rows = null;
          }
      break;
      case 'managers':

          $stmt = $db->prepare("SELECT Count(*) AS kiek FROM login WHERE type = ? AND username LIKE ? ORDER BY username ASC");
          $stmt->bindParam(1, $manager);
          $stmt->bindParam(2, $searchKey);
          $stmt->execute();
          $rowCount = $stmt->fetch();
          $count = $rowCount['kiek'];
          $pages = ceil($count/$pagination);
          if (isset($_GET['page']) && $_GET['page'] <= $pages)
          {
            $offset = ($_GET['page'] - 1) * $pagination;
            $stmt = $db->prepare("SELECT * FROM login WHERE type = ? AND username LIKE ? ORDER BY username ASC LIMIT ?,?");
            $stmt->bindParam(1, $manager);
            $stmt->bindParam(2, $searchKey);
            $stmt->bindParam(3, $offset, PDO::PARAM_INT);
            $stmt->bindParam(4, $pagination, PDO::PARAM_INT);
            $stmt->execute();
            $rows = $stmt->fetchAll();
          }
          else {
            $rows = null;
          }
      break;
      case 'admins':
          $stmt = $db->prepare("SELECT Count(*) AS kiek FROM login WHERE type = ? AND username LIKE ? ORDER BY username ASC");
          $stmt->bindParam(1, $admin);
          $stmt->bindParam(2, $searchKey);
          $stmt->execute();
          $rowCount = $stmt->fetch();
          $count = $rowCount['kiek'];
          $pages = ceil($count/$pagination);
          if (isset($_GET['page']) && $_GET['page'] <= $pages)
          {
            $offset = ($_GET['page'] - 1) * $pagination;
            $stmt = $db->prepare("SELECT * FROM login WHERE type = ? AND username LIKE ? ORDER BY username ASC LIMIT ?,?");
            $stmt->bindParam(1, $admin);
            $stmt->bindParam(2, $searchKey);
            $stmt->bindParam(3, $offset, PDO::PARAM_INT);
            $stmt->bindParam(4, $pagination, PDO::PARAM_INT);
            $stmt->execute();
            $rows = $stmt->fetchAll();
          }
          else {
            $rows = null;
          }
      break;
      case 'members':
          $stmt = $db->prepare("SELECT Count(*) AS kiek FROM login WHERE type = ? AND username LIKE ? ORDER BY username ASC");
          $stmt->bindParam(1, $member);
          $stmt->bindParam(2, $searchKey);
          $stmt->execute();
          $rowCount = $stmt->fetch();
          $count = $rowCount['kiek'];
          $pages = ceil($count/$pagination);
          if (isset($_GET['page']) && $_GET['page'] <= $pages)
          {
            $offset = ($_GET['page'] - 1) * $pagination;
            $stmt = $db->prepare("SELECT * FROM login WHERE type = ? AND username LIKE ? ORDER BY username ASC LIMIT ?,?");
            $stmt->bindParam(1, $member);
            $stmt->bindParam(2, $searchKey);
            $stmt->bindParam(3, $offset, PDO::PARAM_INT);
            $stmt->bindParam(4, $pagination, PDO::PARAM_INT);
            $stmt->execute();
            $rows = $stmt->fetchAll();
          }
          else {
            $rows = null;
          }
      break;
      default:
          $stmt = $db->prepare("SELECT Count(*) AS kiek FROM login WHERE username LIKE ? ORDER BY username ASC");
          $stmt->bindParam(1, $searchKey);
          $stmt->execute();
          $rowCount = $stmt->fetch();
          $count = $rowCount['kiek'];
          $pages = ceil($count/$pagination);
          if (isset($_GET['page']) && $_GET['page'] <= $pages)
          {
            $offset = ($_GET['page'] - 1) * $pagination;
            $stmt = $db->prepare("SELECT * FROM login WHERE username LIKE ? ORDER BY isBlocked DESC, username ASC LIMIT ?,?");
            $stmt->bindParam(1, $searchKey);
            $stmt->bindParam(2, $offset, PDO::PARAM_INT);
            $stmt->bindParam(3, $pagination, PDO::PARAM_INT);
            $stmt->execute();
            $rows = $stmt->fetchAll();
          }
          else {
            $rows = null;
          }
      break;
    }
  }
  else
  {
      $stmt = $db->prepare("SELECT Count(*) AS kiek FROM login WHERE username LIKE ? ORDER BY username ASC");
      $stmt->bindParam(1, $searchKey);
      $stmt->execute();
      $rowCount = $stmt->fetch();
      $count = $rowCount['kiek'];
      $pages = ceil($count/$pagination);
      if (isset($_GET['page']) && $_GET['page'] <= $pages)
      {
        $offset = ($_GET['page'] - 1) * $pagination;
        $stmt = $db->prepare("SELECT * FROM login WHERE username LIKE ? ORDER BY isBlocked DESC, username ASC LIMIT ?,?");
        $stmt->bindParam(1, $searchKey);
        $stmt->bindParam(2, $offset, PDO::PARAM_INT);
        $stmt->bindParam(3, $pagination, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll();
      }
      else {
        $rows = null;
      }
  }

 ?>
