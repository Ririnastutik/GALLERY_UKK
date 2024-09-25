<?php
    session_start();
    include 'db.php';

    // Cek apakah user sudah login
    if(!isset($_SESSION['status_login']) || $_SESSION['status_login'] != true){
        echo '<script>window.location="login.php"</script>';
        exit;
    }

    // Ambil data produk berdasarkan ID yang dikirimkan
    $image_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $produk = mysqli_query($conn, "SELECT * FROM tb_image WHERE image_id = '$image_id'");

    // Jika data tidak ditemukan, redirect ke halaman data-image.php
    if(mysqli_num_rows($produk) == 0){
        echo '<script>window.location="data-image.php"</script>';
        exit;
    }
    $p = mysqli_fetch_object($produk);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>WEB Galeri Foto</title>
<link rel="stylesheet" type="text/css" href="css/style.css">
<script src="https://cdn.ckeditor.com/4.20.0/standard/ckeditor.js"></script>
</head>

<body>
    <!-- header -->
    <header>
        <div class="container">
            <h1><a href="dashboard.php">GALERI FOTO</a></h1>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="profil.php">Profil</a></li>
                <li><a href="data-image.php">Data Foto</a></li>
                <li><a href="Keluar.php">Keluar</a></li>
            </ul>
        </div>
    </header>
    
    <!-- content -->
    <div class="section">
        <div class="container">
            <h3>Edit Data Foto</h3>
            <div class="box">
                <form action="" method="POST" enctype="multipart/form-data">
                    <input type="text" name="kategori" class="input-control" placeholder="Kategori Foto" value="<?php echo htmlspecialchars($p->category_name); ?>" readonly>
                    <input type="text" name="namauser" class="input-control" placeholder="Nama User" value="<?php echo htmlspecialchars($p->admin_name); ?>" readonly>
                    <input type="text" name="nama" class="input-control" placeholder="Nama Foto" value="<?php echo htmlspecialchars($p->image_name); ?>" required>

                    <img src="foto/<?php echo htmlspecialchars($p->image); ?>" width="100px" />
                    <input type="hidden" name="foto" value="<?php echo htmlspecialchars($p->image); ?>" />
                    <input type="file" name="gambar" class="input-control">
                    <textarea class="input-control" name="deskripsi" placeholder="Deskripsi"><?php echo htmlspecialchars($p->image_description); ?></textarea><br />
                    <select class="input-control" name="status" required>
                        <option value="">--Pilih--</option>
                        <option value="1" <?php echo ($p->image_status == 1) ? 'selected' : ''; ?>>Aktif</option>
                        <option value="0" <?php echo ($p->image_status == 0) ? 'selected' : ''; ?>>Tidak Aktif</option>
                    </select>
                    <input type="submit" name="submit" value="Submit" class="btn">
                </form>

                <?php
                if(isset($_POST['submit'])){
                    // Filter inputan untuk keamanan
                   
                    $user      = mysqli_real_escape_string($conn, $_POST['namauser']);
                    $nama      = mysqli_real_escape_string($conn, $_POST['nama']);
                    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
                    $status    = mysqli_real_escape_string($conn, $_POST['status']);
                    $foto      = mysqli_real_escape_string($conn, $_POST['foto']);

                    // Upload gambar baru jika ada
                    $filename = $_FILES['gambar']['name'];
                    $tmp_name = $_FILES['gambar']['tmp_name'];

                    // Jika ada file gambar baru yang diupload
                    if($filename != ''){
                        $ext = pathinfo($filename, PATHINFO_EXTENSION);
                        $newname = 'foto'.time().'.'.$ext;

                        // Cek format file yang diizinkan
                        $allowed_types = array('jpg', 'jpeg', 'png', 'gif');
                        if(!in_array($ext, $allowed_types)){
                            echo '<script>alert("Format file tidak diizinkan")</script>';
                        } else {
                            // Hapus gambar lama dan simpan gambar baru
                            if(file_exists('./foto/'.$foto)){
                                unlink('./foto/'.$foto);
                            }
                            move_uploaded_file($tmp_name, './foto/'.$newname);
                            $namagambar = $newname;
                        }
                    } else {
                        $namagambar = $foto;
                    }

                    // Update data
                    $update = mysqli_query($conn, "UPDATE tb_image SET
                       
                        admin_name = '$user',
                        image_name = '$nama',
                        image_description = '$deskripsi',
                        image = '$namagambar',
                        image_status = '$status'
                        WHERE image_id = '$p->image_id'");

                    if($update){
                        echo '<script>alert("Ubah data berhasil")</script>';
                        echo '<script>window.location="data-image.php"</script>';
                    } else {
                        echo 'Gagal mengubah data: '.mysqli_error($conn);
                    }
                }
                ?>
            </div>
        </div>
    </div>
    
    <!-- footer -->
    <footer>
        <div class="container">
            <small>Copyright &copy; 2024 - Galeri Foto.</small>
        </div>
    </footer>

    <script>
        CKEDITOR.replace('deskripsi');
    </script>
</body>
</html>
