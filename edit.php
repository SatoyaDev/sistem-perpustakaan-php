<?php
require_once 'models/Buku.php';

$bukuModel = new Buku();
$id = (int) ($_GET['id'] ?? 0);

$buku = $bukuModel->find($id);

if (!$buku) {
    header("Location: index.php");
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_file_cover = '';

    if (isset ($_FILES['cover']) && $_FILES['cover']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['cover']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($ext, $allowed)) {
            $errors[] = "Format file cover tidak valid. Hanya diperbolehkan: " . implode(', ', $allowed);
        } else {
            if (!is_dir('uploads')) {
                mkdir('uploads', 0777, true);
            }
            $nama_file_cover = uniqid('cover_') . '.' . $ext;
            move_uploaded_file($_FILES['cover']['tmp_name'], 'uploads/' . $nama_file_cover);
        }
    }
    
    if (empty($errors)) {
        try {
            $bukuModel->update($id, [
                'judul'     => $_POST['judul'] ?? '',
                'penulis'   => $_POST['penulis'] ?? '',
                'tahun'     => (int) ($_POST['tahun'] ?? 0),
                'kategori'  => $_POST['kategori'] ?? '',
                'stok'      => (int) ($_POST['stok'] ?? 0),
                'cover'     => $nama_file_cover ?: $buku['cover']
            ]);

            header("Location: index.php");
            exit;
        } catch (InvalidArgumentException $e) {
            $errors[] = "Terjadi kesalahan saat memperbarui buku: " . $e->getMessage();
            error_log("Error terjadi di File : " . $e->getFile() . " baris " . $e->getLine());

            if (!empty($nama_file_cover) && file_exists('uploads/' . $nama_file_cover)) {
                unlink('uploads/' . $nama_file_cover);
            }
        }
    }
}

require_once 'views/buku_edit.php';
?>