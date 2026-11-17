<?php
require_once 'models/Buku.php';

$id = (int) ($_GET['id'] ?? 0);

if ($id >0) {
    $bukuModel = new Buku();
    $bukuModel->delete($id);
}

header ("Location: index.php");
exit;

?>