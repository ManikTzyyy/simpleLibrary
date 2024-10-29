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

    <?php
    include 'db.php';

    // Memeriksa apakah form sudah disubmit
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Mengambil data dari form dan melakukan sanitasi
        $judul_buku = $conn->real_escape_string($_POST['judul']);
        $genre_buku = $conn->real_escape_string($_POST['genre']);

        // Menyiapkan dan mengeksekusi query untuk menyimpan data
        $sql = "INSERT INTO buku (judul_buku, genre_buku) VALUES ('$judul_buku', '$genre_buku')";

        if ($conn->query($sql) === TRUE) {
            // Jika berhasil, tampilkan pesan sukses menggunakan alert
            echo '<script>
                    alert("Data buku berhasil ditambahkan!");
                    window.location.href = "./buku.php"; // Ganti dengan halaman yang diinginkan
                  </script>';
        } else {
            // Tampilkan pesan error menggunakan alert
            echo '<script>
                    alert("Error: ' . $conn->error . '");
                  </script>';
        }
    }

    // Memeriksa apakah ada tindakan hapus
    if (isset($_GET['action']) && $_GET['action'] === 'delete') {
        $id_buku = $conn->real_escape_string($_GET['id']);

        // Menyiapkan dan mengeksekusi query untuk menghapus data
        $sql = "DELETE FROM buku WHERE id_buku = '$id_buku'";

        if ($conn->query($sql) === TRUE) {
            // Jika berhasil, tampilkan pesan sukses menggunakan alert
            echo '<script>
                    alert("Data buku berhasil dihapus!");
                    window.location.href = "./buku.php"; // Ganti dengan halaman yang diinginkan
                  </script>';
        } else {
            // Tampilkan pesan error menggunakan alert
            echo '<script>
                    alert("Error: ' . $conn->error . '");
                  </script>';
        }
    }

    // Mengambil data buku
    $result = $conn->query("SELECT id_buku, judul_buku, genre_buku FROM buku");

    ?>

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
                    <a class="nav-link" href="./index.php">Dashboard</a>
                    <a class="nav-link" href="./mahasiswa.php">Mahasiswa</a>
                    <a class="nav-link active" aria-current="page" href="">Buku</a>
                </div>
            </div>
        </div>
    </nav>
    <!--end navbar  -->

    <div class="container-all">
        <h1>Tabel Buku</h1>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Judul Buku</th>
                    <th scope="col">Genre Buku</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>

                <?php
                if ($result->num_rows > 0) {
                    // Output data dari setiap baris
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>{$row['id_buku']}</td>
                            <td>{$row['judul_buku']}</td>
                            <td>{$row['genre_buku']}</td>
                            <td>
                                <a class='btn btn-danger' href='buku.php?action=delete&id={$row['id_buku']}' onclick='return confirm(\"Apakah Anda yakin ingin menghapus data ini?\");'>Hapus</a>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' style='text-align:center;'>Tidak ada data buku</td></tr>";
                }
                ?>

            </tbody>
        </table>
    </div>

    <div class="container-button">
        <button class="btn btn-primary" id="openModalBtn" type="button">Tambah Buku</button>
    </div>

    <div id="myModal" class="modal" style="display:none;">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3>Tambah Data Buku</h3>
            <form action="buku.php" method="POST">
                <div class="mb-3">
                    <label for="judul" class="form-label">Judul Buku</label>
                    <input type="text" class="form-control" id="judul" name="judul" required>
                </div>

                <div class="mb-3">
                    <label for="genre" class="form-label">Genre Buku</label>
                    <input type="text" class="form-control" id="genre" name="genre" required>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>
