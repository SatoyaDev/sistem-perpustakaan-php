<?php
/** 
 * @var array $buku 
 * @var array $errors 
 */
?>
<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Buku - Sistem Perpustakaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-body-tertiary">

<div class="container py-5" style="max-width: 600px;">
    <div class="card shadow-sm">
        <div class="card-header bg-warning text-dark">
            <h5 class="card-title mb-0">✏️ Edit Data Buku</h5>
        </div>
        <div class="card-body">

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errors as $err): ?>
                            <li><?= htmlspecialchars($err, ENT_QUOTES, 'UTF-8') ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="edit.php?id=<?= (int)$buku['id'] ?>" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Judul Buku</label>
                    <input type="text" name="judul" class="form-control" value="<?= htmlspecialchars($buku['judul'], ENT_QUOTES, 'UTF-8') ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Penulis</label>
                    <input type="text" name="penulis" class="form-control" value="<?= htmlspecialchars($buku['penulis'], ENT_QUOTES, 'UTF-8') ?>" required>
                </div>
                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="form-label">Tahun Terbit</label>
                        <input type="number" name="tahun" class="form-control" value="<?= (int)$buku['tahun'] ?>" required>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">Stok</label>
                        <input type="number" name="stok" class="form-control" value="<?= (int)$buku['stok'] ?>" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Kategori</label>
                    <select name="kategori" class="form-select" required>
                        <?php 
                        $kategori_list = ['Pemrograman', 'Basis Data', 'Jaringan', 'Desain Web', 'Kecerdasan Buatan'];
                        foreach ($kategori_list as $kat): 
                        ?>
                            <option value="<?= $kat ?>" <?= $buku['kategori'] === $kat ? 'selected' : '' ?>><?= $kat ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Ganti Cover (Biarkan kosong jika tidak diganti)</label>
                    <input type="file" name="cover" class="form-control" accept="image/*">
                    <small class="text-muted">Cover saat ini: <?= htmlspecialchars($buku['cover'], ENT_QUOTES, 'UTF-8') ?></small>
                </div>
                <button type="submit" class="btn btn-warning w-100">Update Data Buku</button>
                <a href="index.php" class="btn btn-secondary w-100 mt-2">Batal</a>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>