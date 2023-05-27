<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "myinventory";

$koneksi = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) {
    die("Gagal terhubung ke database. Silakan cek koneksi atau cara menghubungkan ke database.");
}

$nama_barang = "";
$jumlah_barang = "";
$status_barang = "";

$sukses = "";
$gagal = "";

if (isset($_GET['aksi'])) {
$aksi = $_GET['aksi'];
}else{
    $aksi = "";
}

if($aksi == 'delete'){
    $id_items = $_GET['id_items'];
    $sql1   = "DELETE FROM my_items WHERE id_items = '$id_items'";
    $q1     = mysqli_query($koneksi,$sql1);
    if($q1){
        $sukses  = "BERHASIL DIHAPUS";
    }else{
        $gagal = "GAGAL DIHAPUS!";
    }
}


if($aksi == 'edit'){
    $id_items = $_GET['id_items'];
    $sql1   = "SELECT * FROM my_items WHERE id_items = '$id_items'";
    $q1     = mysqli_query($koneksi,$sql1);
    $r1     = mysqli_fetch_array($q1);
    $nama_barang = $r1['nama_barang'];
    $jumlah_barang = $r1['jumlah_barang'];
    $status_barang = $r1['status_barang'];

    if($nama_barang == ''){
        $gagal  = "DATA TIDAK DITEMUKAN";
    }
}

if (isset($_POST['simpan'])) {
    $nama_barang = $_POST['nama_barang'];
    $jumlah_barang = $_POST['jumlah_barang'];
    $status_barang = $_POST['status_barang'];

    if ($nama_barang && $jumlah_barang && $status_barang) {
        if($aksi == 'edit'){ //edit
            $sql1 = "UPDATE my_items SET nama_barang = '$nama_barang', jumlah_barang = '$jumlah_barang', status_barang = '$status_barang' WHERE id_items = '$id_items'";
            $q1     = mysqli_query($koneksi,$sql1);
            if($q1){
                $sukses = "DATA BERHASIL DIUPDATE";
            }else{
                $gagal = "ERROR, DATA TIDAK TERUPDATE!";
            }
        }else{ //insert data
        $sql1 = "INSERT INTO my_items (nama_barang, jumlah_barang, status_barang) VALUES ('$nama_barang', '$jumlah_barang', '$status_barang')";
        $q1 = mysqli_query($koneksi, $sql1);
        if ($q1) {
            $sukses = "Berhasil menambahkan data.";
        } else {
            $gagal = "Gagal menambahkan data. Silakan coba lagi.";
        }
        }
    } else {
        $gagal = "Gagal menambahkan data. Harap lengkapi semua informasi.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INVENTORY SAYA</title>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <style>
    .mx-auto {
        width: 800px;
    }

    .card {
        margin-top: 10px;
    }
    </style>
</head>

<body>
    <div class="mx-auto">
        <div class="card">
            <div class="card-header">
                CREATE / EDIT DATA
            </div>
            <div class="card-body">
                <?php if ($gagal) { ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $gagal ?>
                </div>
                <?php 
            header("refresh:1;url=index.php");
            } ?>

                <?php if ($sukses) { ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $sukses ?>
                </div>
                <?php
            header("refresh:1;url=index.php");
            } ?>

                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="nama_barang" class="form-label">Nama Barang:</label>
                        <input type="text" class="form-control" id="nama_barang" name="nama_barang"
                            value="<?php echo $nama_barang ?>" required="required">
                    </div>
                    <div class="mb-3">
                        <label for="jumlah_barang" class="form-label">Jumlah Barang:</label>
                        <input type="number" class="form-control" id="jumlah_barang" name="jumlah_barang"
                            value="<?php echo $jumlah_barang ?>" required="required">
                    </div>
                    <div class="mb-3">
                        <label for="jumlah_barang" class="form-label">Status Barang:</label>
                        <select class="form-control" id="status_barang" name="status_barang">
                            <option value=""></option>
                            <option value="READY" <?php if($status_barang == "READY") echo "selected" ?>>READY</option>
                            <option value="NOT READY" <?php if($status_barang == "NOT READY") echo "selected" ?>>NOT
                                READY
                            </option>
                        </select>
                        </label>
                    </div>
                    <hr>
                    <center>
                        <button type="submit" class="btn btn-primary" name="simpan">SIMPAN</button>
                        <button type="reset" class="btn btn-danger">RESET</button>
                    </center>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                INVENTORY KU
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">NOMOR</th>
                            <th scope="col">NAMA BARANG</th>
                            <th scope="col">JUMLAH BARANG</th>
                            <th scope="col">STOCK BARANG</th>
                            <th scope="col">AKSI</th>
                        </tr>
                    <tbody>
                        <?php
                    $sql2 = "SELECT * FROM my_items ORDER BY id_items DESC";
                    $q2   = mysqli_query($koneksi,$sql2);
                    $urut = 1;
                    while($r2 = mysqli_fetch_array($q2)){
                        $id_items = $r2['id_items'];
                        $nama_barang = $r2['nama_barang'];
                        $jumlah_barang = $r2['jumlah_barang'];
                        $status_barang = $r2['status_barang'];

                        ?>
                        <tr>
                            <th scope="row">
                                <?php echo $urut++ ?>
                            </th>
                            <td scope="row">
                                <?php echo $nama_barang ?>
                            </td>
                            <td scope="row">
                                <?php echo $jumlah_barang ?>
                            </td>

                            <!-- <td scope="row"> <?php echo $status_barang ?> </td> -->

                            <td scope="row">
                                <?php if($status_barang == "READY") {
                                echo "<h6><span class=\"badge bg-success\">READY</span></h6>";
                            } else {
                                echo "<h6><span class=\"badge bg-danger\">NOT READY</span></h6>";
                            } ?>
                            </td>

                            <td scope="row">
                                <a href="index.php?aksi=edit&id_items=<?php echo $id_items?>">
                                    <button type="button" class="btn btn-secondary">EDIT DATA</button>
                                </a>
                                /
                                <a href="index.php?aksi=delete&id_items=<?php echo $id_items?>"
                                    onclick="return confirm('APAKAH ANDA YAKIN?')">
                                    <button type="button" class="btn btn-danger">DELETE DATA</button>
                                </a>
                            </td>
                        </tr>
                        <?php

                    }
                    ?>
                    </tbody>
                    </thead>
                </table>

            </div>
        </div>
    </div>
    <br>
    <figure class="text-center">
        <blockquote class="blockquote">
            <p>MyINVENT
                <i>[CRUD Project].</i>
            </p>
        </blockquote>
        <figcaption class="blockquote-footer">
            By
            <cite title="Source Title">Muhammad Azzam Hilmy</cite>
        </figcaption>
    </figure>
</body>

</html>