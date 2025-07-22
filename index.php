<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>BaTal! - Baca Digital</title>
  <link rel="stylesheet" href="css/style.css" />
</head>
<body>
  <header>
    <div class="logo">BaTal!</div>
    <nav>
      <a href="#majalah">Majalah</a>
      <a href="#berita">Berita</a>
      <a href="#dokumentasi">Dokumentasi</a>
      <button id="modeToggle">🌓</button>
    </nav>
  </header>

  <section class="hero">
    <h1>Selamat Datang di <span>BaTal!</span></h1>
    <p>Majalah, Berita, dan Dokumentasi Digital Sekolah</p>
  </section>

  <section id="berita">
    <h2>Berita Terbaru</h2>
    <!-- Nanti pakai include atau fetch dari folder berita -->
    <p>Belum ada berita. Tambahkan segera!</p>
  </section>

  <section id="majalah">
    <h2>Majalah Sekolah</h2>
    <p>Majalah edisi terbaru akan tampil di sini.</p>
  </section>

  <section id="dokumentasi">
    <h2>Dokumentasi Kegiatan</h2>
    <p>Foto dan video kegiatan sekolah akan ditampilkan di sini.</p>
  </section>

  <footer>
    <p>&copy; <?= date('Y') ?> BaTal! - Baca Digital Sekolah</p>
  </footer>

  <script src="js/mode-toggle.js"></script>
</body>
</html>
