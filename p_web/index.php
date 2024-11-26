<?php 
// Mulai session paling awal
session_start();

require_once 'fungsi.php';

// Alert message dengan session
$alertMessage = '';
if (isset($_SESSION['status'])) {
    switch ($_SESSION['status']) {
        case 'added':
            $alertMessage = '<div class="alert alert-success alert-dismissible fade show" role="alert">
            Data berhasil ditambahkan!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
            break;
        case 'edit':
            $alertMessage = '<div class="alert alert-success alert-dismissible fade show" role="alert">
            Data berhasil diubah!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
            break;
        case 'deleted':
            $alertMessage = '<div class="alert alert-success alert-dismissible fade show" role="alert">
            Data berhasil dihapus!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
            break;
        case 'error':
            $alertMessage = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            Terjadi kesalahan saat memproses data!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
            break;
    }
    
    // Hapus session setelah ditampilkan
    unset($_SESSION['status']);
}

// Handle tambah
if (isset($_POST['tambah'])) {
    if (tambah($_POST) > 0) {
        $_SESSION['status'] = 'added';
        header('Location: index.php');
        exit;
    } else {
        $_SESSION['status'] = 'error';
        header('Location: index.php');
        exit;
    }
}

if(isset($_POST['edit'])) {
    if(edit($_POST) > 0) {
        $_SESSION['status'] = 'edit';
        header('Location: index.php');
        exit;
    } else {
        $_SESSION['status'] = 'error';
        header('Location: index.php');
        exit;
    }
}

// Handle hapus
if (isset($_POST['hapus'])) {
    if (hapus($_POST['id']) > 0) {
        $_SESSION['status'] = 'deleted';
        header('Location: index.php');
        exit;
    } else {
        $_SESSION['status'] = 'error';
        header('Location: index.php');
        exit;
    }
}

// Fetch data
$karyawan = query("SELECT * FROM karyawan");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tugas P_WEB CRUD</title>
    <!-- Bootstrap CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <div class="container">
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">P_WEB</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                    <div class="navbar-nav">
                        <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                    </div>
                </div>
            </div>
        </nav>
    </div>

    <!-- Alert -->
    <div class="container mt-4">
        <?= $alertMessage; ?>
    </div>

    <!-- Table -->
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-10">
                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambahData">
                    Add Data
                </button>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Name</th>
                            <th scope="col">Address</th>
                            <th scope="col">Job</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        <?php foreach ($karyawan as $kryn): ?>
                        <tr>
                            <th scope="row"><?= $i; ?></th>
                            <td><?=$kryn['nama']; ?></td>
                            <td><?=$kryn['alamat']; ?></td>
                            <td><?=$kryn['pekerjaan']; ?></td>
                            <td>
                                <a href="fungsi.php?id=<?= $kryn['id']; ?>" class="badge text-bg-primary text-decoration-none me-2" data-bs-toggle="modal" data-bs-target="#editData<?= $kryn['id']; ?>">Edit</a>

                                <button class="badge text-bg-danger text-decoration-none border-0" data-bs-toggle="modal" data-bs-target="#modalHapus<?= $kryn['id']; ?>">
                                    Delete
                                </button>
                            </td>
                        </tr>

                        <!-- Modal Hapus -->
                        <div class="modal fade" id="modalHapus<?= $kryn['id']; ?>" tabindex="-1" aria-labelledby="modalHapusLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title" id="modalHapusLabel">Confirm Delete</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                    Are you sure you want to delete data <strong><?= $kryn['nama']; ?></strong>?
                                    </div>
                                    <div class="modal-footer">
                                        <form action="" method="post">
                                            <input type="hidden" name="id" value="<?= $kryn['id']; ?>">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" name="hapus" class="btn btn-danger">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Edit -->
                        <div class="modal fade" id="editData<?= $kryn['id']; ?>" tabindex="-1" aria-labelledby="editDataLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="editDataLabel">Edit Data</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="" method="post">
                                            <input type="hidden" name="id" value="<?= $kryn['id']; ?>">
                                            <div class="mb-3">
                                                <label for="nama" class="form-label">Name</label>
                                                <input type="text" class="form-control" id="nama" name="nama" value="<?= $kryn['nama']; ?>" required>

                                                <label for="alamat" class="form-label">Address</label>
                                                <input type="text" class="form-control" id="alamat" name="alamat" value="<?= $kryn['alamat']; ?>" required>

                                                <label for="pekerjaan" class="form-label">Job</label>
                                                <input type="text" class="form-control" id="pekerjaan" name="pekerjaan" value="<?= $kryn['pekerjaan']; ?>" required>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" name="edit" class="btn btn-primary">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php $i++; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Data -->
    <div class="modal fade" id="tambahData" tabindex="-1" aria-labelledby="tambahData" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="tambahData">Add Data</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="post">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Name</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>

                            <label for="alamat" class="form-label">Address</label>
                            <input type="text" class="form-control" id="alamat" name="alamat" required>

                            <label for="pekerjaan" class="form-label">Job</label>
                            <input type="text" class="form-control" id="pekerjaan" name="pekerjaan" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="tambah" class="btn btn-primary">Add Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="js/bootstrap.js"></script>

    </script>
</body>
</html>