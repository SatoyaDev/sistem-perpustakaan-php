<?php
require_once __DIR__ . '/../config/Database.php';

class Buku extends Database {
    private string $judul;
    private string $penulis;
    private int $tahun;
    private string $kategori;
    private int $stok;
    private string $cover;

    // SETTER DAN GETTER
    public function setJudul (string $judul) : void {
        if (empty (trim($judul))) {
            throw new InvalidArgumentException ("Judul Tidak Boleh Kosong");
        }
        $this -> judul = trim ($judul);
    }
    public function getJudul() : string {
        return $this -> judul;
    }

    public function setPenulis (string $penulis) : void {
        if (empty (trim($penulis))) {
            throw new InvalidArgumentException ("Penulis Tidak Boleh Kosong");
        }
        $this -> penulis = trim ($penulis);
    }
    public function getPenulis() : string {
        return $this -> penulis;
    }

    public function setTahun (int $tahun) : void {
        $tahunSekarang = (int) date ('Y');
        if ($tahun > $tahunSekarang || $tahun < 800)  {
            throw new InvalidArgumentException ("Tahun Tidak Valid");
        }
        $this -> tahun = $tahun;
    }
    public function getTahun () : int {
        return $this -> tahun;
    }

    public function setKategori(string $kategori): void {
        if (empty(trim($kategori))) {
            throw new InvalidArgumentException("Kategori tidak boleh kosong.");
        }
        $this->kategori = trim($kategori);
    }
    public function getKategori() : string {
        return $this -> kategori;
    }

    public function setStok (int $stok): void {
        if ($stok < 0) {
            throw new InvalidArgumentException ("Angka Stok tidak valid");
        }
        $this->stok = $stok;
    }
    public function getStok (): int {
        return $this->stok;
    }

    public function setCover(string $cover): void {
        if (empty(trim($cover))) {
            throw new InvalidArgumentException("Nama file cover tidak boleh kosong.");
        }
        $this->cover = trim($cover);
    }
    public function getCover (): string {
        return $this->cover;
    }

    // CRUD
    // READ 
    public function all (string $keyword = ''): array {
        if (!empty ($keyword)) {
            $stmt = $this->pdo->prepare ("SELECT * FROM buku WHERE judul LIKE :keyword ORDER BY id DESC");
            $stmt->execute ([':keyword' => '%' . $keyword . '%']);
        } else {
            $stmt = $this->pdo->query ("SELECT * FROM buku ORDER BY id DESC");
        }
        return $stmt->fetchALL();
    }
    // FIND 
    public function find (int $id): ?array {
        $stmt = $this->pdo->prepare ("SELECT * FROM buku WHERE id = :id");
        $stmt->execute ([':id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    // CREATE
    public function create (array $data): bool {
        $this->setJudul ($data['judul'] ?? '');
        $this->setPenulis ($data['penulis'] ?? '');
        $this->setTahun ((int)($data['tahun'] ?? 0));
        $this->setKategori ($data['kategori'] ?? '');
        $this->setStok ((int)($data['stok'] ?? 0));
        $this->setCover ($data['cover'] ?? '');

        $sql = "INSERT INTO buku (judul, penulis, tahun, kategori, stok, cover)
        VALUES (:judul, :penulis, :tahun, :kategori, :stok, :cover)";
        $stmt = $this->pdo->prepare ($sql);
        return $stmt->execute ([
            ':judul'    => $this->getJudul (),
            ':penulis'  => $this->getPenulis (),
            ':tahun'    => $this->getTahun (),
            ':kategori' => $this->getKategori (),
            ':stok'     => $this->getStok (),
            ':cover'    => $this->getCover ()
        ]);
    }

    // UPDATE
    public function update (int $id, array $data): bool {
        $this->setJudul ($data['judul'] ?? '');
        $this->setPenulis ($data['penulis'] ?? '');
        $this->setTahun ((int)($data['tahun'] ?? 0));
        $this->setKategori ($data['kategori'] ?? '');
        $this->setStok ((int)($data['stok'] ?? 0));

        $params = [
            ':id'       => $id,
            ':judul'    => $this->getJudul (),
            ':penulis'  => $this->getPenulis (),
            ':tahun'    => $this->getTahun (),
            ':kategori' => $this->getKategori (),
            ':stok'     => $this->getStok ()
        ];

        $sql = "UPDATE buku SET judul = :judul, penulis = :penulis, tahun = :tahun, kategori = :kategori, stok = :stok";

        if (!empty ($data['cover'])) {
            $this->setCover ($data['cover']);
            $sql .= ", cover = :cover";
            $params[':cover'] = $this->getCover();
        }

        $sql .= " WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    // DELETE
    public function delete (int $id): bool {
        $buku = $this->find($id);
        if ($buku && !empty($buku['cover']) && file_exists('uploads/' . $buku['cover'])) {
            unlink ('uploads/' . $buku['cover']);
        }

        $stmt = $this->pdo->prepare("DELETE FROM buku WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

}