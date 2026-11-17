<?php
require_once 'models/Buku.php';

$bukuModel = new Buku();
$errors = [];

// HANDLER HAPUS BUKU
if (isset($_GET['action']) && $_GET['action'] === 'hapus') {
    $id = (int) $_GET['id'];
    $bukuModel->delete($id);
    header ("Location: index.php");
    exit;
}

// Handler Form Post (Tambah Data Buku)
if ($_SERVER ['REQUEST_METHOD'] === 'POST' && isset ($_POST ['action']) && $_POST ['action'] === 'tambah') {
    $nama_file_cover = '';

    if (isset ($_FILES ['cover']) && $_FILES ['cover']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES ['cover']['tmp_name'];
        $original_name = $_FILES ['cover']['name'];
        $ext = strtolower (pathinfo ($original_name, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array ($ext, $allowed_ext)) {
            $errors [] = "Format file cover tidak valid. Hanya diperbolehkan: " . implode (', ', $allowed_ext);
        } else {
            if (!is_dir ('uploads')) {
                mkdir ('uploads', 0777, true);
            }
            $nama_file_cover = uniqid ('cover_') . '.' . $ext;
            move_uploaded_file ($file_tmp, 'uploads/' . $nama_file_cover);
        }
    } else {
        $errors [] = "File cover wajib diunggah.";
    }

    if (empty ($errors)) {
        $data_buku = [
            'judul'     => $_POST ['judul'] ?? '',
            'penulis'   => $_POST ['penulis'] ?? '',
            'tahun'     => (int) ($_POST ['tahun'] ?? 0),
            'kategori'  => $_POST ['kategori'] ?? '',
            'stok'      => (int) ($_POST ['stok'] ?? 0),
            'cover'     => $nama_file_cover
        ];

        try {
            $bukuModel->create ($data_buku);
            header ("Location: index.php");
            exit;
        } catch (InvalidArgumentException $e) {
            $errors [] = "Terjadi kesalahan saat menambahkan buku: " . $e->getMessage();
            error_log ("Error terjadi di File : " . $e->getFile() . " baris " . $e->getLine());

            if (!empty ($nama_file_cover) && file_exists ('uploads/' . $nama_file_cover)) {
                unlink ('uploads/' . $nama_file_cover);
            }
        }
    }
}

// Handler Search (Pencarian Buku)
$search_keyword = $_GET ['search'] ?? '';
$daftar_buku = $bukuModel->all ($search_keyword);

// Load Tampilan
require_once 'views/buku_view.php';
?>