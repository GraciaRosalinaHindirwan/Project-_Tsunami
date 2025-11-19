<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIM TSUNAMI - Sistem Informasi Manajemen Tsunami Indonesia</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="header">
    <div class="header-content">
        <div class="logo">
            <i class="fas fa-water"></i>
            <div>
                <h1>SIM TSUNAMI</h1>
                <p>Sistem Informasi Manajemen Tsunami Indonesia</p>
            </div>
        </div>
        <div class="nav-menu">
            <a href="home.php" class="nav-btn">
                <i class="fas fa-home"></i> Dashboard
            </a>
            <a href="evakuasi.html" class="nav-btn">
                <i class="fas fa-running"></i> Evakuasi
            </a>
            <a href="education.html" class="nav-btn"> 
                <i class="fas fa-book"></i> Edukasi
            </a>
            <a href="mitigasi.html" class="nav-btn">
                <i class="fas fa-shield-alt"></i> Mitigasi
            </a>
            <a href="penanggulangan.html" class="nav-btn">
                <i class="fas fa-hands-helping"></i> Penanggulangan
            </a>
            <a href="peta.php" class="nav-btn active">
                <i class="fas fa-map"></i> Peta
            </a>
</div>
        <div class="header-info">
            <div style="text-align: right; display: none;" id="datetime-display">
                <div style="font-size: 14px; font-weight: 600;" id="datetime"></div>
                <div style="font-size: 12px; opacity: 0.7;">Sleman, Yogyakarta</div>
            </div>
        </div>
    </div>
</div> 

<div id="map-page" class="page-section active">
<div class="map-header-info">
                <i class="fas fa-map map-icon-large"></i>
                <h2 class="map-title">Peta Zona Rawan Tsunami Indonesia</h2>
                <p class="map-desc">
                    Peta ini menampilkan zona rawan tsunami berdasarkan data historis dan analisis risiko dari BMKG dan BNPB. 
                    Wilayah berwarna merah memiliki risiko tertinggi.
                </p>
                
                <div class="legend-row">
                    <div class="legend-item">
                        <span class="legend-box" style="background: #ff3838;"></span> Risiko Tinggi
                    </div>
                    <div class="legend-item">
                        <span class="legend-box" style="background: #ffd500;"></span> Risiko Sedang
                    </div>
                    <div class="legend-item">
                        <span class="legend-box" style="background: #00ff88;"></span> Risiko Rendah
                    </div>
                </div>

                <a href="https://magma.esdm.go.id/" target="_blank" class="btn-map-cyan">
                    <i class="fas fa-expand"></i> Buka Peta Lengkap
                </a>
            </div>


    <!-- 4 INFO CARDS -->
    <div class="content-grid" style="margin-top:20px;">

        <!-- RISIKO TINGGI -->
        <div class="info-card">
            <h3><i class="fas fa-exclamation-circle"></i> Wilayah Risiko Tinggi</h3>
            <ul>
                <li><i class="fas fa-circle" style="font-size:8px; color:#ff3838;"></i> Pantai Barat Sumatra</li>
                <li><i class="fas fa-circle" style="font-size:8px; color:#ff3838;"></i> Pantai Selatan Jawa</li>
                <li><i class="fas fa-circle" style="font-size:8px; color:#ff3838;"></i> Bali & NTB</li>
                <li><i class="fas fa-circle" style="font-size:8px; color:#ff3838;"></i> Sulawesi Tengah</li>
                <li><i class="fas fa-circle" style="font-size:8px; color:#ff3838;"></i> Maluku & Papua</li>
            </ul>
        </div>

        <!-- RISIKO SEDANG -->
        <div class="info-card">
            <h3><i class="fas fa-exclamation-circle"></i> Wilayah Risiko Sedang</h3>
            <ul>
                <li><i class="fas fa-circle" style="font-size:8px; color:#ffd500;"></i> Pantai Utara Jawa</li>
                <li><i class="fas fa-circle" style="font-size:8px; color:#ffd500;"></i> Sebagian Sulawesi Selatan</li>
                <li><i class="fas fa-circle" style="font-size:8px; color:#ffd500;"></i> Sebagian Kalimantan Timur</li>
                <li><i class="fas fa-circle" style="font-size:8px; color:#ffd500;"></i> Kepulauan Nias</li>
            </ul>
        </div>

        <!-- RISIKO RENDAH -->
        <div class="info-card">
            <h3><i class="fas fa-exclamation-circle"></i> Wilayah Risiko Rendah</h3>
            <ul>
                <li><i class="fas fa-circle" style="font-size:8px; color:#00ff88;"></i> Pantai Selatan Kalimantan</li>
                <li><i class="fas fa-circle" style="font-size:8px; color:#00ff88;"></i> Sebagian Maluku Utara</li>
                <li><i class="fas fa-circle" style="font-size:8px; color:#00ff88;"></i> Papua bagian utara</li>
            </ul>
        </div>
        </div>

    </div>

<div class="footer">
    <p>&copy; 2025 Sistem Informasi Manajemen Tsunami Indonesia </p>
    <p style="margin-top: 10px; font-size: 12px;">
        <i class="fas fa-phone"></i> Hotline Darurat: 119 | 
        <i class="fas fa-envelope"></i> SIM-B-TSUNAMI@gmail.com |
    </p>
    <p style="margin-top: 10px; font-size: 11px; opacity: 0.6;">
        Dibuat untuk keselamatan masyarakat Indonesia
    </p>
</div>

</body>
<style>
body {
    font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
    background: linear-gradient(135deg, #0f2027 0%, #203a43 50%, #2c5364 100%);
    color: #fff;
    min-height: 100vh;
}

.header {
    background: rgba(0, 0, 0, 0.4);
    backdrop-filter: blur(10px);
    padding: 8px 30px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    border-bottom: 2px solid #00d9ff;
    position: sticky;
    top: 0;
    z-index: 1000;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    max-width: 1400px;
    margin: 0 auto;
}

.logo {
    display: flex;
    align-items: center;
    gap: 8px;
}

.logo i {
    font-size: 35px;
    color: #00d9ff;
    animation: wave 2s ease-in-out infinite;
}

@keyframes wave {
    0%, 100% { transform: rotate(0deg); }
    25% { transform: rotate(-5deg); }
    75% { transform: rotate(5deg); }
}

.logo h1 {
    font-size: 20px;
    font-weight: 700;
    margin-top: 0; 
    padding: 0;
    line-height: 1;
}

.logo p {
    font-size: 12px;
    color: #00d9ff;
    margin-bottom: 0.5px;
    padding: 0;
    line-height: 1;
}

.nav-menu {
    display: flex;
    gap: 10px;
}

.nav-btn {
    background: rgba(0, 217, 255, 0.1);
    color: #00d9ff;
    border: 1px solid #00d9ff;
    padding: 8px 16px;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s;
    font-weight: 600;
    font-size: 14px;
    text-decoration: none;
}

.nav-btn:hover, .nav-btn.active {
    background: #00d9ff;
    color: #000;
}

.header-info {
    display: flex;
    gap: 30px;
    align-items: center;
}

/* STATUS BADGE STYLES */
.status-badge {
    background: #00ff88;
    color: #000;
    padding: 8px 20px;
    border-radius: 20px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
}

.status-badge.warning {
    background: #ffd500;
}

.status-badge.danger {
    background: #ff3838;
    color: #fff;
    animation: warningPulse 2s ease-in-out infinite;
}

@keyframes warningPulse {
    0%, 100% { box-shadow: 0 0 20px rgba(255, 56, 56, 0.5); }
    50% { box-shadow: 0 0 40px rgba(255, 56, 56, 0.8); }
}

.pulse {
    width: 10px;
    height: 10px;
    background: #000;
    border-radius: 50%;
    animation: pulse 1.5s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.3; }
}

/* --- CSS TAMBAHAN UNTUK HEADER PETA --- */

/* Container Utama Header Peta */
.map-header-info {
    text-align: center; /* <--- INI YANG MEMBUAT TULISAN KE TENGAH */
    padding: 40px 20px;
    margin-bottom: 20px;
    display: flex;           /* Menggunakan Flexbox untuk kontrol posisi lebih baik */
    flex-direction: column;  /* Menyusun elemen secara vertikal (atas ke bawah) */
    align-items: center;     /* Memastikan semua elemen (kotak, tombol) tepat di tengah */
}

/* Ikon Peta Besar */
.map-icon-large {
    font-size: 60px;
    color: #fff;
    opacity: 0.3; /* Membuat ikon agak transparan */
    margin-bottom: 15px;
    display: inline-block;
}

/* Judul Peta */
.map-title {
    font-size: 24px;
    font-weight: 700;
    color: #fff;
    margin-bottom: 15px;
    text-align: center; /* Penegasan agar judul pasti rata tengah */
}

/* Deskripsi Peta */
.map-desc {
    max-width: 700px;
    margin: 0 auto 30px auto; /* margin auto kiri-kanan menjaga blok teks tetap di tengah */
    text-align: center;       /* Meratakan teks deskripsi di tengah */
    opacity: 0.8;
    line-height: 1.6;
    font-size: 14px;
    color: #fff;
}

/* Container Legenda (Baris Warna) */
.legend-row {
    display: flex;
    justify-content: center; /* Rata tengah horizontal */
    flex-wrap: wrap; /* Agar turun ke bawah jika layar kecil */
    gap: 25px; /* Jarak antar item legenda */
    margin-bottom: 35px;
}

/* Item Legenda Individual */
.legend-item {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 14px;
    font-weight: 500;
    color: #fff;
}

/* Kotak Warna Kecil */
.legend-box {
    width: 20px;
    height: 20px;
    border-radius: 4px;
    display: inline-block;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}

/* Tombol Cyan (Buka Peta Lengkap) */
.btn-map-cyan {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #00d9ff; /* Warna Cyan Khas */
    color: #001f2b; /* Warna Teks Gelap */
    padding: 12px 30px;
    border-radius: 8px;
    font-weight: 700;
    font-size: 14px;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 0 15px rgba(0, 217, 255, 0.3);
}

.btn-map-cyan:hover {
    background: #00b8d4;
    transform: translateY(-3px); /* Efek naik sedikit saat di-hover */
    box-shadow: 0 0 25px rgba(0, 217, 255, 0.6);
    color: #000;
}

/* Responsif untuk Layar Kecil (HP) */
@media (max-width: 768px) {
    .map-header-info {
        padding: 30px 15px;
    }
    .map-title {
        font-size: 20px;
    }
    .legend-row {
        gap: 15px;
        flex-direction: column; /* Susun ke bawah di HP */
        align-items: center;
    }
}

/* RESPONSIVENESS NAV BAR ONLY */
@media (max-width: 768px) {
    .header-content {
        flex-direction: column;
        gap: 15px;
    }

    .nav-menu {
        width: 100%;
        justify-content: space-between;
    }

    .nav-btn { 
        background: rgba(0, 217, 255, 0.1); 
        color: #00d9ff; 
        border: 1px solid #00d9ff; 
        padding: 4px 10px; 
        border-radius: 5px; 
        cursor: pointer; 
        transition: all 0.3s; 
        font-weight: 600; 
        font-size: 11px; 
        text-decoration: none; 
    }
    .nav-btn:hover, .nav-btn.active { 
        background: #00d9ff; 
        color: #000; }

   .header-info {
            display: flex;
            gap: 15px;
            align-items: center;
        }
}

/* MAP PAGE */
#map-page .panel {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    padding: 25px;
    border-radius: 15px;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

#map-page h2 {
    font-size: 20px;
    color: #00d9ff;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

/* GRID FOR 3 CARDS */
#map-page .content-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
}

#map-page .info-card {
    background: rgba(255, 255, 255, 0.05);
    padding: 20px;
    border-radius: 12px;
    border-left: 4px solid #00d9ff;
}

#map-page .info-card h3 {
    color: #00d9ff;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 8px;
}

#map-page .info-card ul {
    list-style: none;
    padding: 0;
}

#map-page .info-card li {
    padding: 8px 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

#map-page .btn {
    background: #00d9ff;
    color: #000;
    padding: 10px 18px;
    border-radius: 8px;
    cursor: pointer;
    border: none;
    margin-top: 10px;
    font-weight: 600;
    transition: all 0.3s;
}

#map-page .btn:hover {
    background: #00b8d4;
    transform: translateY(-2px);
}

#map-page .btn-secondary {
    background: rgba(255, 255, 255, 0.1);
    color: #fff;
    border: 1px solid rgba(255, 255, 255, 0.3);
}

#map-page .btn-secondary:hover {
    background: rgba(255, 255, 255, 0.2);
}

.map-info-box {
    background: rgba(0, 0, 0, 0.4);
    padding: 15px 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    font-size: 14px;
    line-height: 1.7;
    opacity: 0.9;
}

/* Styling untuk Footer */
.footer {
    text-align: center;
    padding: 30px;
    opacity: 0.7;
    font-size: 14px;
    background: rgba(0, 0, 0, 0.5); 
    backdrop-filter: blur(5px);
}

/* Penyesuaian Responsif (Opsional, tapi disarankan) */
@media (max-width: 768px) {
    .footer {
        padding: 20px;
        font-size: 12px;
    }
}
</style>
</html>