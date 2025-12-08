<?php
include 'database.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $conn->query("DELETE FROM products WHERE id=$id");
}

header("Location: index.php");
exit;
?>