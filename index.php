<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>WEDFLIX – Wedding Invitation</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<!-- ===============================
     COVER
================================ -->
<section id="cover" class="cover">
  <div class="cover-bg"></div>

  <div class="cover-content">
    <h1 class="cover-logo">WEDFLIX</h1>

    <p class="cover-dear">
      Dear,<br>
      <strong class="guestName">Tamu Spesial</strong>
    </p>

    <button id="openInvitation" class="btn primary cover-btn">
      ▶ WATCH FULL
    </button>
  </div>
</section>

<!-- ===============================
     AUDIO
================================ -->
<audio id="tudum" src="assets/sound/tudum.mp3"></audio>
<audio id="backsound" src="assets/sound/bg-music.mp3" loop></audio>
<button id="musicToggle" class="music-btn">MUSIC ON</button>

<!-- ===============================
     PROFILE
================================ -->
<section id="profiles" class="hidden">
  <h2>Siapa yang datang?</h2>

  <div class="profiles">
    <div class="profile">Keluarga</div>
    <div class="profile">Kerabat</div>
    <div class="profile">Sahabat</div>
    <div class="profile">Rekan Kerja</div>
  </div>
</section>

<!-- ===============================
     MAIN
================================ -->
<main id="main" class="hidden">

<!-- HERO -->
<section class="hero-detail">
  <div class="hero-bg"></div>

  <div class="hero-info">

    <span class="badge">ORIGINAL LOVE STORY</span>
    <span class="badge">COMING SOON</span>
    <span class="badge red">SPECIAL FOR YOU</span>

    <h1 class="title">
      <span id="groomName">Adit</span> & 
      <span id="brideName">Dewi</span>
    </h1>

    <div class="meta">
      <span class="match">99% Match</span>
      <span>2026</span>
      <span>13+</span>
      <span>Limited Series</span>
      <span>Ultra HD</span>
    </div>

    <p class="desc">
      Halo <b class="guestName">Tamu Spesial</b>,
      perjalanan cinta ini akan segera mencapai episode terbaiknya.
    </p>

    <div class="event-info">
      <span class="event-date" id="weddingDate">
        Minggu • 12 Juli 2026
      </span>
    </div>

    <!-- COUNTDOWN -->
    <div class="countdown">
      <div class="time-box"><span id="days">00</span><small>Days</small></div>
      <div class="time-box"><span id="hours">00</span><small>Hours</small></div>
      <div class="time-box"><span id="minutes">00</span><small>Minutes</small></div>
      <div class="time-box"><span id="seconds">00</span><small>Seconds</small></div>
    </div>

    <div class="actions">
        <a
          href="https://calendar.google.com/calendar/render?action=TEMPLATE&text=Pernikahan+Adit+%26+Dewi&dates=20260712T080000/20260712T190000&details=Akad+dan+Resepsi+Pernikahan+Adit+%26+Dewi&location=Pariuk+Kalanganyar+Lebak+Banten&trp=false&sprop=&sprop=name:"
          target="_blank"
          class="btn primary"
        >
          ▶ Save The Date
        </a>

        <button class="btn secondary" id="scrollRsvp">ℹ RSVP</button>
      </div>
    </div>

  </div>
</section>

<!-- GALLERY -->
<section class="row">
  <h2>OUR MOMENTS <span>Trending Now</span></h2>
  <div class="row-cards" id="galleryHome"></div>
</section>

<!-- GROOM & BRIDE -->
<section class="cast-section">
  <h2 class="cast-title">Starring</h2>

  <div class="cast-list">

    <!-- GROOM -->
    <div class="cast-card">
      <div class="cast-avatar">
        <img src="assets/img/groom.JPG" alt="Groom">
      </div>

      <h3 class="cast-name" id="groomNameCast">Zulius Aditia SR</h3>
      <span class="cast-role">THE GROOM</span>

      <p class="cast-desc" id="groomDesc">
        Putra dari Bpk. Abdulatif & Ibu Leoni Marghareta.  
        Sosok yang akhirnya menemukan rumah terbaik untuk hatinya.
      </p>

      <a href="https://www.instagram.com/a_dit.ia" target="_blank" class="cast-ig" id="groomIG">
        <span>📷</span> Instagram Groom
      </a>
    </div>

    <!-- BRIDE -->
    <div class="cast-card">
      <div class="cast-avatar">
        <img src="assets/img/bride.jpg" alt="Bride">
      </div>

      <h3 class="cast-name" id="brideNameCast">Dewi Intan SR</h3>
      <span class="cast-role">THE BRIDE</span>

      <p class="cast-desc" id="brideDesc">
        Putri dari Bpk. Muhammad Furqon & Ibu Hayati.  
        Keindahan yang menyempurnakan alur cerita kehidupan Adit.
      </p>

      <a href="https://www.instagram.com/ms.acetaminofen/" target="_blank" class="cast-ig" id="brideIG">
        <span>📷</span> Instagram Bride
      </a>
    </div>

  </div>
</section>
<!-- END CAST -->


    <!-- LOCATION -->
    <section class="location">
      <h2>Location & Sets</h2>

      <div class="location-card">
        <h3 id="venueName">Kediaman Mempelai Wanita</h3>
        <p id="venueAddress">Kalanganyar, Lebak-Banten</p>

        <div class="location-map">
          <iframe id="mapFrame"
            src="https://www.google.com/maps?q=-6.3794168,106.2419866&output=embed"
            loading="lazy"></iframe>
        </div>

        <a id="mapLink"
          href="https://www.google.com/maps?q=-6.3794168,106.2419866"
          target="_blank"
          class="btn primary full">
          📍 Buka Lokasi
        </a>
      </div>
    </section>
  <!-- END LOCATION -->

<!-- RSVP -->
<section class="rsvp-section" id="rsvp">
  <div class="rsvp-card">
    <h2>Join the Cast (RSVP)</h2>

    <form id="rsvpForm">
      <input type="text" name="name" placeholder="Full Name" required>

      <div class="rsvp-row">
        <select name="guest_count" required>
          <option value="">Guests</option>
          <option value="1">1 Person</option>
          <option value="2">2 Person</option>
          <option value="3">3 Person</option>
        </select>

        <select name="attendance" required>
          <option value="">Attendance</option>
          <option value="Hadir">Yes, I'm coming</option>
          <option value="Tidak Hadir">Can't make it</option>
        </select>
      </div>

      <textarea name="message" required></textarea>
      <button type="submit" class="btn primary full">SUBMIT RSVP</button>
    </form>
  </div>
</section>

<!-- REVIEWS -->
<section class="review-section">
  <h2>Reviews From Friends</h2>
  <div class="review-wrapper">
    <div id="reviewList"></div>
  </div>
</section>

<section class="gift-section">
  <div class="gift-card">
    <h2>Wedding Gift</h2>
    <p class="subtitle">Tanda kasih dapat dikirim melalui:</p>

    <div class="gift-item" data-rek="1234567890">
      <div>
        <strong>BCA</strong><br>
        1234567890<br>
        <small>a.n Adit</small>
      </div>
      <button class="btn secondary copy-btn">COPY</button>
    </div>

    <div class="gift-item" data-rek="9876543210">
      <div>
        <strong>Mandiri</strong><br>
        9876543210<br>
        <small>a.n Dewi</small>
      </div>
      <button class="btn secondary copy-btn">COPY</button>
    </div>

    <div class="gift-item qr-main" data-qr="assets/img/qr/qris.png">
      <div>
        <strong>QR Payment</strong><br>
        Scan & kirim hadiah dengan mudah<br>
        <small>Support semua e-wallet & mobile banking</small>
      </div>
      <button class="btn primary qr-btn">SCAN QR</button>
    </div>
  </div>
</section>
<!-- END GIFT -->

<!-- FOOTER -->
<footer>
  <h1>WEDFLIX</h1>
  <p>© 2026 With Love <span id="groomNameFooter">Adit</span> & <span id="brideNameFooter">Dewi</span></p>
</footer>

</main>

<!-- POPUP -->
<div id="popup" class="popup hidden">
  <div class="popup-box">
    <h2 id="popupTitle"></h2>
    <p id="popupMessage"></p>
    <button class="btn primary full" onclick="closePopup()">OK</button>
  </div>
</div>

<div id="qrPopup" class="popup hidden">
  <div class="popup-box">
    <h2>Scan QR</h2>
    <img id="qrImage" src="" style="width:200px; margin:20px auto; display:block;">
    <button class="btn primary full" onclick="closeQR()">Tutup</button>
  </div>
</div>

<script src="assets/js/app.js"></script>
</body>
</html>