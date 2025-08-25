<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BaTal!📖</title>
    <style>
        /* Reset and Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #000428 0%, #004e92 100%);
            color: white;
            height: 100vh;
            overflow: hidden;
            position: relative;
            perspective: 1000px;
        }

        body {
      margin: 0;
      background: linear-gradient(160deg, #001F3F, #003366, #004080);
      font-family: Arial, sans-serif;
      color: white;
    }

    /* Header */
    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px 40px;
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      background: rgba(0, 0, 30, 0.3);
      backdrop-filter: blur(10px);
      z-index: 1000;
    }

    .ps2-logo {
      display: flex;
      align-items: center;
      font-size: 24px;
      font-weight: bold;
    }

    .ps2-logo .logo-img {
      width: 32px;
      height: 32px;
      margin-right: 8px;
    }

    /* Tombol Login */
    .login-btn {
      padding: 10px 22px;
      background: #00bfff;
      border: none;
      border-radius: 25px;
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
      color: #fff;
      transition: all 0.3s ease;
      box-shadow: 0 0 10px rgba(0,191,255,0.6);
    }

    .login-btn:hover {
      background: #0099cc;
      box-shadow: 0 0 18px rgba(0,191,255,0.9);
    }

    /* Konten biar tidak ketutup header */
    .content {
      margin-top: 100px;
      text-align: center;
    }

        /* Starfield background */
        .starfield {
            position: absolute;
            width: 100%;
            height: 100%;
            background: transparent;
        }

        .star {
            position: absolute;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 50%;
            animation: twinkle 3s infinite ease-in-out;
        }

        .star:nth-child(1) { width: 1px; height: 1px; top: 10%; left: 20%; animation-delay: 0s; }
        .star:nth-child(2) { width: 2px; height: 2px; top: 25%; left: 80%; animation-delay: 1s; }
        .star:nth-child(3) { width: 1px; height: 1px; top: 40%; left: 15%; animation-delay: 2s; }
        .star:nth-child(4) { width: 1.5px; height: 1.5px; top: 60%; left: 75%; animation-delay: 0.5s; }
        .star:nth-child(5) { width: 1px; height: 1px; top: 75%; left: 30%; animation-delay: 1.5s; }

        @keyframes twinkle {
            0%, 100% { opacity: 0.3; transform: scale(1); }
            50% { opacity: 1; transform: scale(1.2); }
        }

        .container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            position: relative;
            z-index: 10;
        }

        .ps2-logo {
            position: absolute;
            top: 50px;
            left: 50px;
            font-size: 18px;
            font-weight: bold;
            color: #ffffff;
            text-shadow: 0 0 15px rgba(255, 255, 255, 0.6);
        }

        .instructions {
            position: absolute;
            top: 50px;
            right: 50px;
            font-size: 12px;
            color: #aaaaaa;
            text-align: right;
        }

        /* 3D Carousel Container */
        .carousel-container {
            position: relative;
            width: 600px;
            height: 400px;
            margin-bottom: 60px;
            transform-style: preserve-3d;
        }

        /* Orbital track guide */
        .orbital-track {
            position: absolute;
            width: 500px;
            height: 250px;
            top: 75px;
            left: 50px;
            border: 2px solid rgba(0, 170, 255, 0.3);
            border-radius: 50%;
            box-shadow: 0 0 20px rgba(0, 170, 255, 0.2);
        }

        /* Carousel Items */
        .carousel-item {
            position: absolute;
            width: 180px;
            height: 70px;
            background: linear-gradient(145deg, rgba(0, 50, 150, 0.9), rgba(0, 100, 200, 0.7));
            border: 2px solid rgba(255, 255, 255, 0.4);
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            transform-style: preserve-3d;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            z-index: 1;
        }

        .carousel-item:hover {
            border-color: rgba(255, 255, 255, 0.8);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4);
        }

        .carousel-item h3 {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 3px;
            text-shadow: 0 0 8px rgba(255, 255, 255, 0.5);
        }

        .carousel-item p {
            font-size: 9px;
            opacity: 0.9;
            text-align: center;
        }

        /* Active item */
        .carousel-item.active {
            background: linear-gradient(145deg, rgba(255, 255, 0, 0.95), rgba(255, 200, 0, 0.85));
            color: #000;
            border-color: #ffff00;
            box-shadow: 0 0 25px rgba(255, 255, 0, 0.6), 0 8px 30px rgba(0, 0, 0, 0.4);
            z-index: 10;
        }

        .carousel-item.active h3 {
            text-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }

        /* Positioning for 3 items */
        .carousel-item:nth-child(2):not(.repositioned) {
            top: 160px;
            left: 210px;
        }

        .carousel-item:nth-child(3):not(.repositioned) {
            top: 200px;
            left: 150px;
        }

        .carousel-item:nth-child(4):not(.repositioned) {
            top: 200px;
            left: 270px;
        }

        /* Central info display */
        .central-info {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
            background: rgba(0, 0, 0, 0.85);
            padding: 15px 25px;
            border-radius: 8px;
            border: 1px solid rgba(0, 170, 255, 0.5);
            min-width: 200px;
        }

        .selected-title {
            font-size: 18px;
            font-weight: bold;
            color: #ffff00;
            text-shadow: 0 0 12px rgba(255, 255, 0, 0.8);
            margin-bottom: 6px;
        }

        .selected-description {
            font-size: 11px;
            color: #cccccc;
            line-height: 1.3;
        }

        /* Navigation */
        .navigation {
            display: flex;
            gap: 50px;
            font-size: 15px;
            margin-top: 30px;
        }

        .nav-button {
            display: flex;
            align-items: center;
            gap: 10px;
            opacity: 0.9;
            transition: all 0.3s ease;
            cursor: pointer;
            user-select: none;
        }

        .nav-button:hover {
            opacity: 1;
            transform: scale(1.05);
        }

        .nav-button:active {
            transform: scale(0.95);
        }

        .button-icon {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 13px;
            border: 2px solid;
            transition: all 0.3s ease;
        }

        .x-button {
            background: rgba(100, 149, 237, 0.2);
            border-color: #6495ed;
            color: #6495ed;
        }

        .o-button {
            background: rgba(255, 105, 180, 0.2);
            border-color: #ff69b4;
            color: #ff69b4;
        }

        .nav-button:hover .button-icon {
            box-shadow: 0 0 12px currentColor;
        }

        /* Side Navigation Arrows */
        .side-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, 0.6);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 20px;
            color: rgba(255, 255, 255, 0.8);
            user-select: none;
            z-index: 20;
        }

        .side-nav:hover {
            background: rgba(0, 170, 255, 0.3);
            border-color: rgba(0, 170, 255, 0.8);
            color: white;
            box-shadow: 0 0 15px rgba(0, 170, 255, 0.5);
        }

        .side-nav:active {
            transform: translateY(-50%) scale(0.9);
        }

        .side-nav.left {
            left: 30px;
        }

        .side-nav.right {
            right: 30px;
        }

        /* Scroll indicator */
        .scroll-indicator {
            position: absolute;
            bottom: 120px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 11px;
            color: rgba(255, 255, 255, 0.6);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 0.6; }
            50% { opacity: 1; }
        }

        .copyright {
            position: absolute;
            bottom: 20px;
            right: 30px;
            font-size: 11px;
            opacity: 0.7;
            color: #cccccc;
        }

        /* Ambient glow */
        .ambient-glow {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 700px;
            height: 350px;
            transform: translate(-50%, -50%);
            background: radial-gradient(ellipse, rgba(0, 170, 255, 0.08) 0%, transparent 70%);
            border-radius: 50%;
            animation: ambient-pulse 4s ease-in-out infinite;
        }

        @keyframes ambient-pulse {
            0%, 100% { opacity: 0.3; transform: translate(-50%, -50%) scale(1); }
            50% { opacity: 0.6; transform: translate(-50%, -50%) scale(1.1); }
        }

        /* Selection feedback */
        .selection-feedback {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(255, 255, 0, 0.9);
            color: #000;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: bold;
            font-size: 16px;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
            z-index: 100;
        }

        .selection-feedback.show {
            opacity: 1;
        }
    </style>
</head>
<body>
    <!-- Starfield background -->
    <div class="starfield">
        <div class="star"></div>
        <div class="star"></div>
        <div class="star"></div>
        <div class="star"></div>
        <div class="star"></div>
    </div>

    <!-- Ambient glow -->
    <div class="ambient-glow"></div>

    <!-- Side Navigation -->
    <div class="side-nav left" onclick="navigate('prev')" title="Previous">‹</div>
    <div class="side-nav right" onclick="navigate('next')" title="Next">›</div>

    <!-- PlayStation 2 logo -->
    <div class="ps2-logo">
    <img src="images/kotak.png" alt="Logo" class="logo-img"> SINXAPRO

    <style>
    .ps2-logo {
      display: flex;
      align-items: center;
      font-size: 24px;
      font-weight: bold;
      font-family: Arial, sans-serif;
    }

    .ps2-logo .logo-img {
      width: 32px;   /* sesuaikan ukuran */
      height: 32px;
      margin-right: 8px;
    }

    /* Header */
.header {
  display: flex;
  justify-content: flex-end; /* tombol ke kanan */
  align-items: center;
  padding: 15px 40px;
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  background: rgba(3, 3, 109, 0);
  backdrop-filter: blur(0px);
  z-index: 1000;
}

/* Tombol Login */
.login-btn {
  padding: 10px 22px;
  background: #00bfff;
  border: none;
  border-radius: 25px;
  font-size: 16px;
  font-weight: bold;
  cursor: pointer;
  color: #fff;
  transition: all 0.3s ease;
  box-shadow: 0 0 10px rgba(0,191,255,0.6);
  
}

.login-btn:hover {
  background: #0099cc;
  box-shadow: 0 0 18px rgba(0,191,255,0.9);
}

/* Konten biar tidak ketiban header */
body {
  margin: 0;
}
main {
  margin-top: 100px;
}

  </style>
</div>

<!-- Header -->
<div class="header">
    <button class="login-btn" onclick="window.location.href='login.php'">Login</button>
</div>


    <!-- Instructions -->
   
    <!-- Scroll indicator -->
    <div class="scroll-indicator">
        🖱️ Scroll to navigate
    </div>

    <!-- Main container -->
    <div class="container">
        <!-- 3D Carousel -->
        <div class="carousel-container">
            <div class="orbital-track"></div>
            
            <!-- Carousel Items -->
            <div class="carousel-item active" data-title="Majalah" data-desc="Kumpulan Majalah Yang telah di Publish">
                <h3>Majalah</h3>
                <p>Kumpulan Majalah Yang telah di Publish</p>
            </div>
            
            <div class="carousel-item" data-title="Dokumentasi Kegiatan" data-desc="Kumpulan Dokumentasi Kegiatan">
                <h3>Dokumentasi Kegiatan</h3>
                <p>Kumpulan Dokumentasi Kegiatan</p>
            </div>
            
            <div class="carousel-item" data-title="Berita" data-desc="Berita Terkini di Sekolah">
                <h3>Berita</h3>
                <p>Berita Terkini di Sekolah</p>
            </div>
        </div>

        <!-- Central info display -->
        <div class="central-info">
            <div class="selected-title">Majalah</div>
            <div class="selected-description">Kumpulan Majalah Yang telah di Publish</div>
        </div>

       

    <!-- Selection feedback -->
    <div class="selection-feedback"></div>

    <!-- Copyright -->
    <div class="copyright">SMEXAJUARA</div>

    <script>
        // Global variables
        let currentSelection = 0;
        const carouselItems = document.querySelectorAll('.carousel-item');
        const selectedTitle = document.querySelector('.selected-title');
        const selectedDesc = document.querySelector('.selected-description');
        const selectionFeedback = document.querySelector('.selection-feedback');

        // Define 3D positions for the carousel
        const positions = [
            // Position 0: Front center (active)
            { x: 210, y: 140, z: 80, scale: 1.3, opacity: 1, blur: 0 },
            // Position 1: Back right
            { x: 300, y: 180, z: -40, scale: 0.85, opacity: 0.7, blur: 0.8 },
            // Position 2: Back left
            { x: 120, y: 180, z: -40, scale: 0.85, opacity: 0.7, blur: 0.8 }
        ];

        function updateCarousel() {
            carouselItems.forEach((item, index) => {
                // Calculate relative position
                const posIndex = (index - currentSelection + carouselItems.length) % carouselItems.length;
                const pos = positions[posIndex];
                
                // Remove existing classes
                item.classList.remove('active', 'repositioned');
                item.classList.add('repositioned');
                
                // Apply transforms and styling
                item.style.transform = `translate3d(${pos.x}px, ${pos.y}px, ${pos.z}px) scale(${pos.scale})`;
                item.style.opacity = pos.opacity;
                item.style.filter = `blur(${pos.blur}px)`;
                
                // Add active class to front item
                if (posIndex === 0) {
                    item.classList.add('active');
                }
            });
            
            // Update info display
            const activeItem = carouselItems[currentSelection];
            selectedTitle.textContent = activeItem.dataset.title;
            selectedDesc.textContent = activeItem.dataset.desc;
        }

        function navigate(direction) {
            if (direction === 'next') {
                currentSelection = (currentSelection + 1) % carouselItems.length;
            } else if (direction === 'prev') {
                currentSelection = (currentSelection - 1 + carouselItems.length) % carouselItems.length;
            }
            updateCarousel();
        }

        function selectItem() {
            const activeItem = carouselItems[currentSelection];
            showFeedback(`Entering: ${activeItem.dataset.title}`);
            console.log('Entering:', selectedTitle.textContent);
        }

        function goBack() {
            showFeedback('Going back...');
            console.log('Going back');
        }

        function showFeedback(message) {
            selectionFeedback.textContent = message;
            selectionFeedback.classList.add('show');
            setTimeout(() => {
                selectionFeedback.classList.remove('show');
            }, 1500);
        }

        // Scroll handling with throttling
        let scrollTimeout;
        function handleScroll(e) {
            e.preventDefault();
            
            if (scrollTimeout) return;
            
            scrollTimeout = setTimeout(() => {
                scrollTimeout = null;
            }, 200);

            if (e.deltaY > 0) {
                navigate('next');
            } else if (e.deltaY < 0) {
                navigate('prev');
            }
        }

        // Event listeners
        document.addEventListener('keydown', function(e) {
            switch(e.key) {
                case 'ArrowRight':
                case 'ArrowDown':
                    e.preventDefault();
                    navigate('next');
                    break;
                case 'ArrowLeft':
                case 'ArrowUp':
                    e.preventDefault();
                    navigate('prev');
                    break;
                case 'Enter':
                case ' ':
                    e.preventDefault();
                    selectItem();
                    break;
                case 'Escape':
                case 'Backspace':
                    e.preventDefault();
                    goBack();
                    break;
            }
        });

        // Mouse wheel navigation
        document.addEventListener('wheel', handleScroll, { passive: false });

        // Touch/swipe support for mobile
        let touchStartX = 0;
        let touchStartY = 0;

        document.addEventListener('touchstart', function(e) {
            touchStartX = e.touches[0].clientX;
            touchStartY = e.touches[0].clientY;
        });

        document.addEventListener('touchend', function(e) {
            const touchEndX = e.changedTouches[0].clientX;
            const touchEndY = e.changedTouches[0].clientY;
            const deltaX = touchEndX - touchStartX;
            const deltaY = touchEndY - touchStartY;

            // Horizontal swipe (more sensitive)
            if (Math.abs(deltaX) > Math.abs(deltaY) && Math.abs(deltaX) > 50) {
                if (deltaX > 0) {
                    navigate('prev');
                } else {
                    navigate('next');
                }
            }
            // Vertical swipe
            else if (Math.abs(deltaY) > 50) {
                if (deltaY > 0) {
                    navigate('prev');
                } else {
                    navigate('next');
                }
            }
        });

        // Mouse navigation on carousel items
        carouselItems.forEach((item, index) => {
            item.addEventListener('click', () => {
                if (index !== currentSelection) {
                    currentSelection = index;
                    updateCarousel();
                } else {
                    // Double-click to select
                    selectItem();
                }
            });
        });

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            updateCarousel();
            
        });
    </script>
</body>
</html>