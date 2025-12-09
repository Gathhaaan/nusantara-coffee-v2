☕ Nusantara Coffee: Jelajah Biji Kopi Indonesia
Proyek ini adalah website statis yang dikembangkan untuk tugas Ujian Tengah Semester (UTS) mata kuliah Pemrograman Web. Tujuannya adalah untuk memperkenalkan kekayaan biji kopi dari berbagai wilayah di Indonesia melalui tampilan yang interaktif dan informatif.

💻 Cara Melihat Halaman Web
Website ini adalah proyek statis (HTML, CSS, JavaScript murni) dan dapat dijalankan langsung di browser modern mana pun.

Kloning Repositori:

Bash
git clone https://github.com/Gathhaaan/nusantara-coffee

Buka File:

Navigasikan ke folder proyek.

Buka file index.html menggunakan browser pilihan Anda (Chrome, Firefox, dll.).

Atau dapat dengan mudah saja untuk melihat websiteny saja bisa klik link ini https://gathhaaan.github.io/nusantara-coffee 

PENJELASAN HTML DAN CSS

1. Struktur HTML
Struktur HTML diorganisasi dengan berfokus pada Semantic HTML5 untuk memastikan aksesibilitas, SEO, dan keterbacaan kode yang baik.

Organisasi Utama
Header : Berisi navigasi utama  dan logo .

Main Content : Berisi semua konten unik halaman, dibagi menjadi beberapa section yang logis (Hero, Map, Showcase, Comments).

Footer : Berisi informasi hak cipta.

Penggunaan Elemen Penting
index.html: Menggunakan section untuk memisahkan fitur utama (hero, peta, showcase) dan article serta elemen grid untuk menampilkan detail kopi.

Fitur interaktif seperti peta menggunakan elemen button (.hotspot) dengan atribut data-region untuk memudahkan interaksi JavaScript.

Modal detail kopi diimplementasikan menggunakan elemen <dialog> yang merupakan praktik terbaik HTML5 modern.

article.html : Menggunakan elemen article sebagai wadah utama untuk konten editorial dan h2 hingga h4 untuk hierarki judul yang tepat.

Fitur baru Article Slider diimplementasikan menggunakan div dengan kelas .article-slider dan navigasi tombol .slider-btn untuk kontrol manual.

Atribut Khusus: Penggunaan atribut aria-label pada elemen navigasi dan tombol untuk meningkatkan aksesibilitas bagi pengguna screen reader.

2. Desain CSS
Desain CSS dikembangkan dengan pendekatan mobile-first dan bertujuan untuk menciptakan tampilan yang elegan, bersih, serta fokus pada estetika yang terinspirasi dari warna kopi dan alam.

Peningkatan Tampilan
Tema Warna dan Tipografi: Menggunakan properti CSS Custom Properties (:root) untuk tema warna (krem, cokelat kopi, hijau hutan) dan font stack modern, memberikan konsistensi visual di seluruh halaman.

Responsiveness (Mobile-First):

Menggunakan media queries secara ekstensif (terutama di style.css dan article.css) untuk memastikan tata letak beradaptasi dengan baik pada berbagai ukuran layar, dari ponsel hingga desktop.

Penggunaan unit relatif (rem, %, clamp) untuk ukuran font dan tata letak yang fleksibel.

Interaksi dan Efek Visual:

Map Tooltips: Efek tooltip muncul dan mengikuti kursor/sentuhan di peta interaktif.

Card Showcase: Efek transform: translateY(-2px) dan box-shadow saat hover memberikan kesan kedalaman pada kartu.

Article Slider: Menggunakan transition: transform 0.4s pada .slides-container untuk menciptakan animasi geser yang halus saat tombol panah diklik.

Hero Slideshow (Index): Menggunakan opacity dan transition di JavaScript untuk efek pergantian gambar latar belakang otomatis yang mulus.

Tata Letak Grid: Menggunakan CSS Grid (.grid, .grid-3, .container-grid) untuk tata letak yang kompleks, seperti kartu showcase dan daftar sejarah di halaman artikel, memastikan penyelarasan yang rapi dan responsif.
