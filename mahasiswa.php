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
        // Mengambil data dari form
        $nama_mahasiswa = $_POST['nama'];
        $program_studi = $_POST['prodi'];
        $jenis_kelamin = $_POST['jenis_kelamin'];

        // Menyiapkan dan mengeksekusi query untuk menyimpan data
        $sql = "INSERT INTO mahasiswa (nama_mahasiswa, program_studi, jenis_kelamin) VALUES ('$nama_mahasiswa', '$program_studi', '$jenis_kelamin')";

        if ($conn->query($sql) === TRUE) {
            // Jika berhasil, tampilkan pesan sukses menggunakan alert
            echo '<script>
                    alert("Data mahasiswa berhasil ditambahkan!");
                    window.location.href = "./mahasiswa.php"; // Ganti dengan halaman yang diinginkan
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
        $id_mahasiswa = $_GET['id'];

        // Menyiapkan dan mengeksekusi query untuk menghapus data
        $sql = "DELETE FROM mahasiswa WHERE id_mahasiswa = '$id_mahasiswa'";

        if ($conn->query($sql) === TRUE) {
            // Jika berhasil, tampilkan pesan sukses menggunakan alert
            echo '<script>
                    alert("Data mahasiswa berhasil dihapus!");
                    window.location.href = "./mahasiswa.php"; // Ganti dengan halaman yang diinginkan
                  </script>';
        } else {
            // Tampilkan pesan error menggunakan alert
            echo '<script>
                    alert("Error: ' . $conn->error . '");
                  </script>';
        }
    }

    // Mengambil data mahasiswa
    $result = $conn->query("SELECT id_mahasiswa, nama_mahasiswa, program_studi, jenis_kelamin FROM mahasiswa");
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
                    <a class="nav-link active" aria-current="page" href="">Mahasiswa</a>
                    <a class="nav-link" href="./buku.php">Buku</a>
                </div>
            </div>
        </div>
    </nav>
    <!--end navbar-->

    <div class="container-all">
        <h1>Tabel Mahasiswa</h1>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">ID Mahasiswa</th>
                    <th scope="col">Nama Mahasiswa</th>
                    <th scope="col">Jenis Kelamin</th>
                    <th scope="col">Program Studi</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    // Output data dari setiap baris
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>{$row['id_mahasiswa']}</td>
                            <td>{$row['nama_mahasiswa']}</td>
                            <td>{$row['jenis_kelamin']}</td>
                            <td>{$row['program_studi']}</td>
                            <td>
                                <a class='btn btn-danger' href='mahasiswa.php?action=delete&id={$row['id_mahasiswa']}' onclick='return confirm(\"Apakah Anda yakin ingin menghapus data ini?\");'>Hapus</a>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' style='text-align:center;'>Tidak ada data mahasiswa</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="container-button">
        <button class="btn btn-primary" id="openModalBtn">Tambah Mahasiswa</button>
    </div>

    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3>Tambah Data Mahasiswa</h3>
            <form action="" method="POST">
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama</label>
                    <input type="text" class="form-control" id="nama" name="nama" required>
                </div>

                <div class="mb-3">
                    <label for="prodi" class="form-label">Program Studi</label>
                    <input type="text" class="form-control" id="prodi" name="prodi" required>
                </div>

                <div class="mb-3">
                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                    <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
                        <option value="" disabled selected>Pilih Jenis Kelamin</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>
