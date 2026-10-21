<?php
session_start();
require_once '../../CONTROLLER/c_aspirasi.php';

if (!isset($_SESSION['admin'])) {
    header("Location: ../auth/v_login.php");
    exit();
}

$aspirasiController = new AspirasiController();

// Mengambil hanya aspirasi yang statusnya selesai
$aspirasiList = $aspirasiController->getAspirasiFiltered(
    'status',
    'selesai'
);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Aspirasi Selesai - Admin Panel</title>

    <link rel="stylesheet" href="../../ASSETS/NavbarAdmin.css">
    <link rel="website icon" href="../../ASSETS/GAMBAR/logoapk.jpeg">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>

    <?php include '../../template/NavbarAdmin.php'; ?>

    <div class="container">
        <div class="card">

            <div class="card-header">
                <h2>
                    <i class="fas fa-check-circle"></i>
                    Aspirasi Selesai
                </h2>
            </div>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?php
                    echo htmlspecialchars($_SESSION['success']);
                    unset($_SESSION['success']);
                    ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error">
                    <?php
                    echo htmlspecialchars($_SESSION['error']);
                    unset($_SESSION['error']);
                    ?>
                </div>
            <?php endif; ?>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Nama Siswa</th>
                            <th>Judul</th>
                            <th>Lokasi</th>
                            <th>Prioritas</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (!empty($aspirasiList)): ?>

                            <?php $no = 1; ?>

                            <?php foreach ($aspirasiList as $aspirasi): ?>

                                <tr>
                                    <td>
                                        <?php echo $no++; ?>
                                    </td>

                                    <td>
                                        <?php
                                        echo date(
                                            'd/m/Y',
                                            strtotime($aspirasi['tanggal_dikirim'])
                                        );
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                        echo htmlspecialchars(
                                            $aspirasi['nama_siswa']
                                        );
                                        ?>
                                        <br>

                                        <small>
                                            <?php
                                            echo htmlspecialchars(
                                                $aspirasi['kelas']
                                            );
                                            ?>
                                        </small>
                                    </td>

                                    <td>
                                        <?php
                                        echo htmlspecialchars(
                                            $aspirasi['judul_laporan']
                                        );
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                        echo htmlspecialchars(
                                            ucfirst($aspirasi['lokasi'])
                                        );
                                        ?>
                                    </td>

                                    <td>
                                        <span class="badge badge-<?php
                                            echo htmlspecialchars(
                                                explode(
                                                    ' ',
                                                    $aspirasi['kategori_prioritas']
                                                )[0]
                                            );
                                        ?>">
                                            <?php
                                            echo htmlspecialchars(
                                                $aspirasi['kategori_prioritas']
                                            );
                                            ?>
                                        </span>
                                    </td>

                                    <td>
                                        <span class="badge badge-<?php
                                            echo htmlspecialchars(
                                                $aspirasi['status']
                                            );
                                        ?>">
                                            <?php
                                            echo htmlspecialchars(
                                                ucfirst($aspirasi['status'])
                                            );
                                            ?>
                                        </span>
                                    </td>

                                    <td>
                                        <button
                                            type="button"
                                            onclick="openDetailModal(<?php
                                                echo (int) $aspirasi['id_aspirasi'];
                                            ?>)"
                                            class="btn btn-info"
                                            title="Lihat Detail">

                                            <i class="fas fa-eye"></i>
                                        </button>

                                        <a
                                            href="UmpanBalik.php?id=<?php
                                                echo (int) $aspirasi['id_aspirasi'];
                                            ?>"
                                            class="btn btn-warning"
                                            title="Umpan Balik">

                                            <i class="fas fa-comment"></i>
                                        </a>
                                    </td>
                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="8" style="text-align: center; padding: 25px;">
                                    <i class="fas fa-inbox"></i>
                                    Belum ada aspirasi yang selesai.
                                </td>
                            </tr>

                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- Modal Detail Aspirasi -->
    <div id="detailModal" class="modal">

        <div class="modal-content" style="max-width: 700px;">

            <div class="modal-header">
                <h2>Detail Aspirasi</h2>

                <span
                    class="modal-close"
                    onclick="closeModal('detailModal')">
                    &times;
                </span>
            </div>

            <div id="detailContent">
                <!-- Detail akan dimuat di sini -->
            </div>

        </div>
    </div>

    <script>
        
function openDetailModal(id) {
    const modal = document.getElementById('detailModal');
    const content = document.getElementById('detailContent');

    modal.classList.add('active');
    content.innerHTML = '<p>Memuat detail aspirasi...</p>';

    fetch('AmbilAspirasi.php?id=' + encodeURIComponent(id))
        .then(response => {
            if (!response.ok) {
                throw new Error('HTTP ' + response.status);
            }
            return response.text();
        })
        .then(data => {
            if (data.trim() === '') {
                content.innerHTML =
                    '<p>Respons detail kosong. Periksa file AmbilAspirasi.php.</p>';
            } else {
                content.innerHTML = data;
            }
        })
        .catch(error => {
            content.innerHTML =
                '<p>Gagal memuat detail: ' + error.message + '</p>';
            console.error(error);
        });
}


        function closeModal(id) {
            document.getElementById(id)
                .classList.remove('active');
        }

        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.classList.remove('active');
            }
        };
    </script>

</body>
</html>
