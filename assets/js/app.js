console.log("JS LOADED");

// ===============================
// ELEMENTS
// ===============================
const cover = document.getElementById('cover');
const openBtn = document.getElementById('openInvitation');
const profiles = document.getElementById('profiles');
const main = document.getElementById('main');

const tudum = document.getElementById('tudum');
const backsound = document.getElementById('backsound');
const musicToggle = document.getElementById('musicToggle');

const popup = document.getElementById('popup');
const popupTitle = document.getElementById('popupTitle');
const popupMessage = document.getElementById('popupMessage');

// ===============================
// URL PARAM
// ===============================
const urlParams = new URLSearchParams(window.location.search);
const guestSlug = urlParams.get('to');

// ===============================
// STATE
// ===============================
let audioStarted = false;

// ===============================
// FORMAT NAME
// ===============================
function formatName(name) {
  return decodeURIComponent(name)
    .replace(/\+/g, " ")
    .toLowerCase()
    .replace(/\b\w/g, c => c.toUpperCase());
}

// ===============================
// LOAD GUEST
// ===============================
async function loadGuest() {
  if (!guestSlug) return;

  try {
    const res = await fetch("api/get_guest.php?to=" + guestSlug);
    const text = await res.text();
    if (!text) return;

    const data = JSON.parse(text);

    document.querySelectorAll(".guestName").forEach(el => {
      el.textContent = data?.name || formatName(guestSlug);
    });

  } catch (err) {
    console.error("Guest error:", err);
  }
}

// ===============================
// LOAD SETTINGS
// ===============================
async function loadSettings() {
  try {
    const res = await fetch("api/get_settings.php");
    const text = await res.text();
    if (!text) return;

    const data = JSON.parse(text);

    if (data.groom_name) {
      const el = document.getElementById("groomName");
      if (el) el.textContent = data.groom_name;
    }

    if (data.bride_name) {
      const el = document.getElementById("brideName");
      if (el) el.textContent = data.bride_name;
    }

    if (data.wedding_date) {
      const el = document.getElementById("weddingDate");
      if (el) el.textContent = data.wedding_date;
    }

  } catch (err) {
    console.error("Settings error:", err);
  }
}

// ===============================
// 🔥 LOAD GALLERY (NEW)
// ===============================
async function loadGallery() {
  try {
    const res = await fetch("api/get_gallery.php");
    const text = await res.text();
    if (!text) return;

    const data = JSON.parse(text);

    const container = document.getElementById("galleryHome");
    if (!container) return;

    if (!data || data.length === 0) {
      container.innerHTML = "<p>No photos yet</p>";
      return;
    }

    container.innerHTML = data.slice(0, 6).map(img => `
      <img src="${img}" loading="lazy">
    `).join("");

  } catch (err) {
    console.error("Gallery error:", err);
  }
}

// ===============================
// COPY BTN
// ===============================
document.querySelectorAll(".copy-btn").forEach(btn => {
  btn.addEventListener("click", () => {
    const rek = btn.parentElement.getAttribute("data-rek");
    navigator.clipboard.writeText(rek);
    alert("Rekening copied!");
  });
});

// ===============================
// COVER → PROFILE
// ===============================
openBtn.addEventListener('click', () => {
  cover.classList.add('fade-out');

  setTimeout(() => {
    cover.style.display = 'none';
    profiles.classList.remove('hidden');
    profiles.classList.add('show');
  }, 600);
});

// ===============================
// PROFILE → MAIN
// ===============================
document.querySelectorAll('.profile').forEach(profile => {
  profile.addEventListener('click', () => {

    if (!audioStarted) {
      tudum.play().catch(() => {});
      setTimeout(() => {
        backsound.volume = 0.35;
        backsound.play().catch(() => {});
      }, 1200);
      audioStarted = true;
    }

    profiles.classList.add('hidden');
    main.classList.remove('hidden');

    setTimeout(() => {
      loadReviews();
      loadGallery(); // 🔥 load gallery pas masuk
    }, 500);
  });
});

// ===============================
// MUSIC TOGGLE
// ===============================
musicToggle.addEventListener('click', () => {
  if (backsound.paused) {
    backsound.play();
    musicToggle.innerText = 'MUSIC ON';
  } else {
    backsound.pause();
    musicToggle.innerText = 'MUSIC OFF';
  }
});

// ===============================
// SCROLL RSVP
// ===============================
const scrollBtn = document.getElementById('scrollRsvp'); 
if (scrollBtn) {
  scrollBtn.addEventListener('click', () => {
    document.getElementById('rsvp').scrollIntoView({
      behavior: 'smooth' 
    }); 
  }); 
}

// ===============================
// DOM READY
// ===============================
document.addEventListener("DOMContentLoaded", () => {

  loadGuest();
  loadSettings();
  loadGallery(); // 🔥 langsung load juga

  const nameInput = document.querySelector("input[name='name']");
  if (nameInput && guestSlug) {
    nameInput.value = formatName(guestSlug);
  }

  const form = document.getElementById("rsvpForm");

  if (form) {
    form.addEventListener("submit", async function(e){
      e.preventDefault();

      const formData = new FormData(this);

      try {
        const res = await fetch("api/save_rsvp.php", {
          method: "POST",
          body: formData
        });

        const data = await res.json();

        if (data.status === "success") {
          showPopup("Terima Kasih!", "RSVP berhasil ❤️");
          this.reset();
          loadReviews();
        } else {
          showPopup("Error", data.message);
        }

      } catch (err) {
        console.error(err);
        showPopup("Error", "Gagal kirim");
      }
    });
  }

});

// ===============================
// LOAD REVIEWS
// ===============================
async function loadReviews() {
  const container = document.getElementById("reviewList");
  if (!container) return;

  try {
    const res = await fetch("api/get_rsvp.php");
    const text = await res.text();
    if (!text) return;

    const data = JSON.parse(text);

    if (!Array.isArray(data)) return;

    container.innerHTML = data.map(item => {
      const initial = item.name.charAt(0).toUpperCase();
      const colors = ["#3b82f6", "#ec4899", "#22c55e", "#f59e0b"];
      const color = colors[item.id % colors.length];

      return `
        <div class="review-card">
          <div class="review-header">
            <div class="avatar" style="background:${color}">
              ${initial}
            </div>
            <div>
              <div class="review-name">${item.name}</div>
              <div class="review-meta">${item.attendance}</div>
            </div>
          </div>
          <div class="review-text">
            "${item.message || '-'}"
          </div>
        </div>
      `;
    }).join("");

  } catch (err) {
    console.error("Review error:", err);
  }
}

// ===============================
// AUTO REFRESH
// ===============================
setInterval(() => {
  if (!main.classList.contains("hidden")) {
    loadReviews();
  }
}, 5000);

// ===============================
// POPUP
// ===============================
function showPopup(title, message) {
  popupTitle.innerText = title;
  popupMessage.innerText = message;
  popup.classList.remove('hidden');
}

function closePopup() {
  popup.classList.add('hidden');
}

// ===============================
// QR POPUP
// ===============================
document.querySelectorAll(".qr-btn").forEach(btn => {
  btn.addEventListener("click", () => {
    const parent = btn.closest(".gift-item");
    const qrSrc = parent.getAttribute("data-qr");

    document.getElementById("qrImage").src = qrSrc;
    document.getElementById("qrPopup").classList.remove("hidden");
  });
});

function closeQR() {
  document.getElementById("qrPopup").classList.add("hidden");
}

// ===============================
// COUNTDOWN
// ===============================
const weddingDateStatic = new Date('2026-07-12T08:00:00').getTime();

setInterval(() => {
  const now = new Date().getTime();
  const distance = weddingDateStatic - now;
  if (distance < 0) return;

  document.getElementById('days').innerText =
    String(Math.floor(distance / (1000 * 60 * 60 * 24))).padStart(2, '0');

  document.getElementById('hours').innerText =
    String(Math.floor((distance / (1000 * 60 * 60)) % 24)).padStart(2, '0');

  document.getElementById('minutes').innerText =
    String(Math.floor((distance / (1000 * 60)) % 60)).padStart(2, '0');

  document.getElementById('seconds').innerText =
    String(Math.floor((distance / 1000) % 60)).padStart(2, '0');

}, 1000);