<?php
error_reporting(0);
include 'db.php';
$kontak = mysqli_query($conn, "SELECT admin_telp, admin_email, admin_address FROM tb_admin WHERE admin_id = 2");
$a = mysqli_fetch_object($kontak);

$produk = mysqli_query($conn, "SELECT * FROM tb_image WHERE image_id = '".$_GET['id']."' ");
$p = mysqli_fetch_object($produk);


?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WEB Galeri Foto</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <style>
        .social-interaction {
            display: flex;
            gap: 20px; /* Tambahkan jarak antar elemen */
            align-items: center; /* Rata tengah secara vertikal */
        }
    </style>
</head>

<body>
    <!-- Definisi Icon SVG (Sprite) -->
    <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
        <symbol id="heart" viewBox="0 0 16 16" fill="currentColor">
            <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15"/>
        </symbol>
        <symbol id="chat" viewBox="0 0 16 16" fill="currentColor">
            <path d="M2.678 11.894a1 1 0 0 1 .287.801 11 11 0 0 1-.398 2c1.395-.323 2.247-.697 2.634-.893a1 1 0 0 1 .71-.074A8 8 0 0 0 8 14c3.996 0 7-2.807 7-6s-3.004-6-7-6-7 2.808-7 6c0 1.468.617 2.83 1.678 3.894m-.493 3.905a22 22 0 0 1-.713.129c-.2.032-.352-.176-.273-.362a10 10 0 0 0 .244-.637l.003-.01c.248-.72.45-1.548.524-2.319C.743 11.37 0 9.76 0 8c0-3.866 3.582-7 8-7s8 3.134 8 7-3.582 7-8 7a9 9 0 0 1-2.347-.306c-.52.263-1.639.742-3.468 1.105"/>
        </symbol>
    </svg>
    
   
    
    <!-- Search -->
    <div class="search">
        <div class="container">
            <form action="galeri.php">
                <input type="text" name="search" placeholder="Cari Foto" value="<?php echo $_GET['search'] ?>" />
                <input type="hidden" name="kat" value="<?php echo $_GET['kat'] ?>" />
                <input type="submit" name="cari" value="Cari Foto" />
            </form>
        </div>
    </div>
    
    <!-- Product Detail -->
    <div class="section">
        <div class="container">
            <h3>Detail Foto</h3>
            <div class="box">
                <div class="col-2 image-section">
                    <img src="foto/<?php echo $p->image ?>" width="100%" alt="<?php echo $p->image_name ?>" /> 
                </div>
                <div class="col-2 details-section">
                    <h3><?php echo $p->image_name ?><br />Kategori: <?php echo $p->category_name ?></h3>
                    <h4>Nama User: <?php echo $p->admin_name ?><br />
                        Upload Pada Tanggal: <?php echo $p->date_created ?>
                    </h4>
                    <p>Deskripsi:<br />
                        <?php echo $p->image_description ?>
                    </p>

                    <div class="social-interaction">
                        <div class="likes">
                            <svg width="24" height="24" class="like-btn" data-image-id="<?php echo $p->image_id; ?>" data-admin-id="<?php echo $p->admin_id; ?>">
                                <use href="#heart"></use>
                            </svg>
                            <?php

$pdo = new PDO('mysql:host=localhost;dbname=galerifoto', 'root', '');


$image_id = $p->image_id; 


$totalLikesQuery = $pdo->prepare("SELECT SUM(likeTotal) as totalLikes FROM likes WHERE image_id = :image_id");
$totalLikesQuery->execute(['image_id' => $image_id]);


$totalLikes = $totalLikesQuery->fetch(PDO::FETCH_OBJ)->totalLikes;
$totalLikes = $totalLikes ?: 0; 
?>


<span class="like-count" id="like-count-<?php echo $image_id; ?>">
    <?php echo $totalLikes; ?> Likes
</span>

                        </div>
                    </div><script>
document.addEventListener('DOMContentLoaded', function() {
    const likeButtons = document.querySelectorAll('.like-btn');

    likeButtons.forEach(button => {
        let clickTimeout;

        button.addEventListener('click', function() {
            clearTimeout(clickTimeout);

            clickTimeout = setTimeout(() => {
                handleLike(this, 'like');
            }, 300); 
        });

        button.addEventListener('dblclick', function() {
            clearTimeout(clickTimeout); 
            handleLike(this, 'dislike');
        });
    });

    function handleLike(button, action) {
        const imageId = button.getAttribute('data-image-id');
        const adminId = button.getAttribute('data-admin-id');
        const likeCountSpan = document.getElementById(`like-count-${imageId}`);

        fetch('like_handler.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `image_id=${imageId}&admin_id=${adminId}&action=${action}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                likeCountSpan.textContent = `${data.totalLikes} Likes`;

                if (data.liked) {
                    button.classList.add('liked');
                } else {
                    button.classList.remove('liked');
                }
            } else {
                alert('Gagal memperbarui like.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
});
</script>

                </div>
            </div>
        </div>
    </div>

    <!-- Script AJAX untuk menangani klik like -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <!-- Footer -->
    <footer>
        <div class="container">
            <small>Copyright &copy; 2024 - Galeri Foto.</small>
        </div>
    </footer>
</body>
</html>
