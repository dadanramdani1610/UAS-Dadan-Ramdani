<?php
/**
 * Halaman profil staf/tim NusantaraWild.
 */
session_start();
include "includes/koneksi.php";

$halaman_judul = "Tentang Kami";

$query = mysqli_query($koneksi, "
SELECT *
FROM staf
ORDER BY id_staf ASC
");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $halaman_judul ?> - NusantaraWild</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=DM+Sans:wght@300;400;500&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="style/css/style.css">
    <style>
        /* ===== STAF / TEAM SECTION ===== */
        .team-section {
            background: linear-gradient(135deg, #14532d 0%, #166534 50%, #15803d 100%);
            padding: 70px 0 60px;
        }

        .team-title {
            text-align: center;
            margin-bottom: 50px;
        }

        .team-title p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1.05rem;
            letter-spacing: 0.5px;
        }

        .team-card {
            background: #ffffff;
            border: none;
            border-radius: 16px;
            padding: 36px 24px 28px;
            text-align: center;
            transition: all 0.4s ease;
            height: 100%;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .team-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.18);
        }

        .team-img {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #dcfce7;
            margin-bottom: 20px;
            transition: all 0.4s ease;
        }

        .team-card:hover .team-img {
            border-color: #16a34a;
            box-shadow: 0 0 20px rgba(22, 163, 74, 0.25);
        }

        .team-name {
            font-family: 'Playfair Display', serif;
            font-size: 1.25rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 4px;
        }

        .team-role {
            color: #16a34a;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .team-desc {
            color: #6b7280;
            font-size: 0.88rem;
            line-height: 1.6;
            margin-bottom: 16px;
        }

        .team-card .social {
            display: flex;
            justify-content: center;
            gap: 12px;
            margin-top: 8px;
        }

        .team-card .social a {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #dcfce7;
            border: none;
            color: #16a34a;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .team-card .social a:hover {
            background: #16a34a;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(22, 163, 74, 0.3);
        }

        .btn-back {
            margin-top: 40px;
            border-radius: 10px;
            padding: 10px 28px;
            font-weight: 600;
            background: #ffffff;
            color: #166534;
            border-color: #ffffff;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            transform: translateY(-2px);
            background: #dcfce7;
            border-color: #dcfce7;
            color: #166534;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        }

        /* ===== FADE-IN ANIMATION ===== */
        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }

        .fade-in.show {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>

<body>
    <?php include 'includes/navbar.php'; ?>

    <!-- PAGE HEADER -->
    <div class="page-header cs-8597b1">
        <div class="page-header-overlay"></div>
        <div class="container text-white text-center position-relative">
            <h1 class="page-title">Tentang Kami</h1>
        </div>
    </div>

    <!-- TEAM SECTION -->
    <section class="team-section">
        <div class="container">

            <div class="team-title">
                <p>Orang-orang hebat di balik NusantaraWild</p>
            </div>

            <div class="row g-4 justify-content-center">

                <?php while($anggota = mysqli_fetch_assoc($query)): ?>
                    <div class="col-md-6 col-lg-4 fade-in">
                        <div class="team-card">
                            <img src="image/<?= $anggota['foto']; ?>" alt="<?= htmlspecialchars($anggota['nama_staf']); ?>" class="team-img">
                            <div class="team-name"><?= $anggota['nama_staf']; ?></div>
                            <div class="team-role"><?= $anggota['jabatan']; ?></div>
                            <div class="team-desc"><?= $anggota['deskripsi']; ?></div>

                        </div>
                    </div>
                <?php endwhile; ?>

            </div>

            <div class="text-center">
                <a href="index.php" class="btn btn-success btn-back">⬅ Kembali ke Home</a>
            </div>

        </div>
    </section>

    <!-- JS -->
    <script>
        // ANIMASI SCROLL
        const elements = document.querySelectorAll('.fade-in');

        function showOnScroll() {
            const triggerBottom = window.innerHeight * 0.85;

            elements.forEach(el => {
                const boxTop = el.getBoundingClientRect().top;

                if (boxTop < triggerBottom) {
                    el.classList.add('show');
                }
            });
        }

        window.addEventListener('scroll', showOnScroll);
        showOnScroll();
    </script>
    <?php include 'includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>

</html>