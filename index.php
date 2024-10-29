<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Library</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="style.css">
</head>

<body>

    <!-- navbar -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Library</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
                aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    <a class="nav-link active" aria-current="page" href="">Dashboard</a>
                    <a class="nav-link" href="./mahasiswa.php">Mahasiswa</a>
                    <a class="nav-link" href="./buku.php">Buku</a>
                </div>
            </div>
        </div>
    </nav>
    <!--end navbar  -->

    <div class="container-all">
        <h1>Tabel Mahasiswa Pinjam Buku</h1>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">ID Mahasiswa</th>
                    <th scope="col">ID Buku</th>
                    <th scope="col">Tanggal</th>
                    <th scope="col">Nama Mahasiswa</th>
                    <th scope="col">Judul Buku</th>
                    <th scope="col">Genre Buku</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include 'db.php'; // Ganti dengan koneksi ke database Anda

                // Mengambil data peminjaman
                $result = $conn->query("SELECT p.id, p.id_mahasiswa, p.id_buku, p.tanggal, m.nama_mahasiswa, b.judul_buku, b.genre_buku 
                                          FROM peminjaman p 
                                          JOIN mahasiswa m ON p.id_mahasiswa = m.id_mahasiswa 
                                          JOIN buku b ON p.id_buku = b.id_buku");

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['id_mahasiswa']}</td>
                                <td>{$row['id_buku']}</td>
                                <td>{$row['tanggal']}</td>
                                <td>{$row['nama_mahasiswa']}</td>
                                <td>{$row['judul_buku']}</td>
                                <td>{$row['genre_buku']}</td>
                                <td>
                                <a class='btn btn-danger' href='index.php?action=delete&id={$row['id']}' onclick='return confirm(\"Apakah Anda yakin ingin menghapus data ini?\");'>Hapus</a>
                            </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='8' class='text-center'>Tidak ada data peminjaman</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="container-button">
        <button class="btn btn-primary" id="openModalBtn">Tambah Data</button>
    </div>

    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3>Tambah Data</h3>
            <form action="" method="POST">
                <div class="mb-3">
                    <label for="mahasiswa" class="form-label">Mahasiswa</label>
                    <select id="mahasiswa" class="form-select" name="mahasiswa" required>
                        <option value="">Pilih Mahasiswa</option>
                        <?php
                        // Mengambil data mahasiswa
                        $result = $conn->query("SELECT id_mahasiswa, nama_mahasiswa FROM mahasiswa");
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='{$row['id_mahasiswa']}'>{$row['id_mahasiswa']} - {$row['nama_mahasiswa']}</option>";
                            }
                        } else {
                            echo "<option value=''>Tidak ada data mahasiswa</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="buku" class="form-label">Buku</label>
                    <select id="buku" class="form-select" name="buku" required>
                        <option value="">Pilih Buku</option>
                        <?php
                        // Mengambil data buku
                        $result = $conn->query("SELECT id_buku, judul_buku, genre_buku FROM buku");
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='{$row['id_buku']}'>{$row['id_buku']} - {$row['judul_buku']} - {$row['genre_buku']}</option>";
                            }
                        } else {
                            echo "<option value=''>Tidak ada data buku</option>";
                        }
                        ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>

    <script>
        // JavaScript untuk menangani buka/tutup modal
        const modal = document.getElementById("myModal");
        const openModalBtn = document.getElementById("openModalBtn");
        const closeModal = document.getElementsByClassName("close")[0];

        // Membuka modal saat tombol ditekan
        openModalBtn.onclick = function () {
            modal.style.display = "block";
        }

        // Menutup modal saat tombol 'X' ditekan
        closeModal.onclick = function () {
            modal.style.display = "none";
        }

        // Menutup modal jika pengguna mengklik di luar modal
        window.onclick = function (event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>

    <?php
    // Cek jika formulir disubmit
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Mengambil data dari formulir
        $id_mahasiswa = $_POST['mahasiswa'];
        $id_buku = $_POST['buku'];
        $tanggal = date('Y-m-d'); // Menetapkan tanggal saat ini

        // Membuat kueri untuk menambah data
        $sql = "INSERT INTO peminjaman (id_mahasiswa, id_buku, tanggal) VALUES (?, ?, ?)";

        // Menyiapkan statement
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iis", $id_mahasiswa, $id_buku, $tanggal); // "iis" sesuai dengan tipe data id_mahasiswa (integer), id_buku (integer), dan tanggal (string)

        // Menjalankan statement
        if ($stmt->execute()) {
            echo '<script>
                    alert("Data mahasiswa berhasil dihapus!");
                    window.location.href = "./index.php"; // Ganti dengan halaman yang diinginkan
                  </script>';
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }

        // Menutup statement
        $stmt->close();
    }
    ?>

    <?php
    
    if (isset($_GET['action']) && $_GET['action'] === 'delete') {
        $id = $_GET['id'];

        // Menyiapkan dan mengeksekusi query untuk menghapus data
        $sql = "DELETE FROM peminjaman WHERE id = '$id'";

        if ($conn->query($sql) === TRUE) {
            // Jika berhasil, tampilkan pesan sukses menggunakan alert
            echo '<script>
                    alert("Data berhasil dihapus!");
                    window.location.href = "./index.php"; // Ganti dengan halaman yang diinginkan
                  </script>';
        } else {
            // Tampilkan pesan error menggunakan alert
            echo '<script>
                    alert("Error: ' . $conn->error . '");
                  </script>';
        }
    }?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>
