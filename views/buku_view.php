<?php
/** 
 * @var string $search_keyword 
 * @var array $daftar_buku 
 * @var array $errors 
 */
?>

<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Perpustakaan - UTS Backend</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-body-tertiary">

<div class="container py-4">
    <h2 class="mb-4 text-center">📚 Sistem Pengelolaan Buku Perpustakaan</h2>

    <!-- Alert Box Pesan Error Validasi -->
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Terjadi Kesalahan:</strong>
            <ul class="mb-0">
                <?php foreach ($errors as $err): ?>
                    <li><?= htmlspecialchars($err, ENT_QUOTES, 'UTF-8') ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- Form Tambah Buku -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Tambah Buku Baru</h5>
                </div>
                <div class="card-body">
                    <form action="index.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="tambah">

                        <div class="mb-3">
                            <label class="form-label">Judul Buku</label>
                            <input type="text" name="judul" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Penulis</label>
                            <input type="text" name="penulis" class="form-control" required>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label">Tahun</label>
                                <input type="number" name="tahun" class="form-control" required>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">Stok</label>
                                <input type="number" name="stok" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <select name="kategori" class="form-select" required>
                                <option value="">-- Pilih Kategori --</option>
                                <option value="Pemrograman">Pemrograman</option>
                                <option value="Basis Data">Basis Data</option>
                                <option value="Jaringan">Jaringan</option>
                                <option value="Desain Web">Desain Web</option>
                                <option value="Kecerdasan Buatan">Kecerdasan Buatan</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Cover Buku (JPG, PNG, WEBP)</label>
                            <input type="file" name="cover" class="form-control" accept="image/*" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Simpan Buku</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tabel Daftar Buku & Feature Search -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Daftar Buku</h5>
                    
                    <!-- Search Form (LIKE Query) -->
                    <form action="index.php" method="GET" class="d-flex gap-2">
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari judul..." value="<?= htmlspecialchars($search_keyword ?? '', ENT_QUOTES, 'UTF-8') ?>">
                        <button type="submit" class="btn btn-sm btn-outline-primary">Cari</button>
                        <?php if (!empty($search_keyword)): ?>
                            <a href="index.php" class="btn btn-sm btn-outline-secondary">Reset</a>
                        <?php endif; ?>
                    </form>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Cover</th>
                                    <th>Judul / Penulis</th>
                                    <th>Tahun</th>
                                    <th>Stok</th>
                                    <th>Kategori</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($daftar_buku)): ?>
                                    <tr><td colspan="6" class="text-center py-4">Belum ada data buku yang sesuai.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($daftar_buku as $b): ?>
                                        <tr>
                                            <td>
                                                <img src="uploads/<?= htmlspecialchars($b['cover'], ENT_QUOTES, 'UTF-8') ?>" width="50" class="rounded shadow-sm" alt="Cover">
                                            </td>
                                            <td>
                                                <strong><?= htmlspecialchars($b['judul'], ENT_QUOTES, 'UTF-8') ?></strong><br>
                                                <small class="text-muted">Penulis: <?= htmlspecialchars($b['penulis'], ENT_QUOTES, 'UTF-8') ?></small>
                                            </td>
                                            <td><?= (int)$b['tahun'] ?></td>
                                            <td>
                                                <span class="badge <?= $b['stok'] > 0 ? 'bg-success' : 'bg-danger' ?>">
                                                    <?= (int)$b['stok'] ?>
                                                </span>
                                            </td>
                                            <td><span class="badge bg-secondary"><?= htmlspecialchars($b['kategori'], ENT_QUOTES, 'UTF-8') ?></span></td>
                                            <td>
                                                <!-- Tombol Edit -->
                                                <a href="edit.php?id=<?= (int)$b['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
    
                                                <!-- Tombol Hapus -->
                                                <a href="delete.php?id=<?= (int)$b['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus buku ini?')">Hapus</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>