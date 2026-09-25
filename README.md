# Cafe & Restaurant POS System
Cafe & Restaurant POS System adalah aplikasi berbasis web yang dibuat untuk membantu proses pemesanan, transaksi, pengelolaan menu, pelayanan pelanggan, hingga pengelolaan operasional cafe dan restaurant secara terintegrasi
Sistem ini memiliki dua sisi utama, yaitu **Customer Website** untuk pelanggan dan **Admin System** untuk mengelola operasional cafe.

# Fitur Utama
## Customer
Customer dapat menggunakan website untuk melihat menu dan melakukan pemesanan secara mandiri.

### Home
Halaman utama menampilkan:
- Informasi cafe
- Hero section
- Menu favorit
- Kategori menu
- Promo
- Keunggulan cafe
- Informasi fasilitas
- Navigasi menuju menu dan informasi cafe

### Menu
Customer dapat:
- Melihat seluruh menu
- Melihat menu berdasarkan kategori
- Mencari menu
- Melihat harga
- Melihat rating
- Melihat waktu persiapan
- Melihat status ketersediaan menu
- Melihat menu Best Seller
- Melihat menu Promo
- Melihat menu terbaru
- Menandai menu sebagai Favorite

### Detail Menu
Setiap menu memiliki halaman detail yang menampilkan:
- Foto menu
- Nama menu
- Kategori
- Harga
- Deskripsi
- Komposisi
- Rating
- Waktu persiapan
- Kalori
- Informasi allergen
- Ketersediaan stok
- Pilihan ukuran
- Pilihan tambahan
- Jumlah pesanan
- Catatan pesanan

### Favorite
Customer dapat menandai menu yang disukai sebagai Favorite sehingga menu tersebut dapat dikenali sebagai menu favorit.

### Cart
Customer dapat:
- Menambahkan menu ke keranjang
- Mengubah jumlah menu
- Menghapus menu
- Melihat harga setiap menu
- Melihat subtotal
- Melihat pajak
- Melihat service charge
- Melihat total pembayaran

### Checkout
Pada halaman checkout customer dapat mengisi:
- Nama
- Nomor telepon
- Nomor meja
- Catatan pesanan
- Tipe kunjungan
- Metode pembayaran

Tipe kunjungan:
- Dine In
- Take Away

Metode pembayaran:
- QRIS
- Virtual Account
- E-Wallet
- Cash

### QR Ordering
Customer dapat melakukan pemesanan melalui QR Code yang tersedia pada meja.
QR Code dapat digunakan untuk mengenali nomor meja sehingga customer tidak perlu memasukkan nomor meja secara manual.

### Queue Number
Setiap pesanan mendapatkan nomor antrean.
Nomor antrean digunakan untuk membantu customer mengetahui urutan pesanan.

### Estimated Time
Sistem memberikan estimasi waktu pesanan berdasarkan proses dan waktu persiapan menu.

### Order Tracking
Customer dapat melihat perkembangan pesanan melalui status:
- Pending
- Accepted
- Processing
- Ready
- Completed

Pesanan juga dapat memiliki status:
- Cancelled
- Refund
- Void

### Facilities
Customer dapat melihat informasi fasilitas cafe seperti:
- Free WiFi
- Area Parkir
- Charging Station
- Meeting Area
- Premium Coffee
- QR Ordering

### About
Halaman About berisi informasi mengenai cafe, cerita cafe, visi, misi, dan alasan customer memilih cafe.

### Contact
Customer dapat melihat informasi kontak cafe dan informasi yang dapat digunakan untuk menghubungi cafe.

# Fitur Admin
Admin System digunakan oleh pihak cafe untuk mengelola seluruh proses operasional.

## Dashboard
Dashboard menampilkan informasi utama seperti:
- Total pesanan
- Total pendapatan
- Pesanan menunggu
- Pesanan diproses
- Pesanan siap
- Pesanan selesai
- Pesanan dibatalkan
- Pesanan terbaru
Dashboard membantu admin melihat kondisi cafe secara cepat.

# Manajemen Menu
Admin dapat mengelola menu cafe.
Fitur:
- Menambahkan menu
- Melihat menu
- Mengedit menu
- Menghapus menu
- Mengatur kategori
- Mengatur harga
- Mengatur harga ukuran Large
- Mengatur gambar
- Mengatur stok
- Mengatur waktu persiapan
- Mengatur rating
- Mengatur kalori
- Mengatur allergen
- Mengatur status tersedia
- Menandai Best Seller
- Menandai Promo
- Menandai menu baru

Menu juga dapat memiliki pilihan tambahan seperti:
- Ukuran
- Varian
- Tambahan
- Extra price

# Manajemen Kategori
Admin dapat:
- Menambahkan kategori
- Melihat kategori
- Mengedit kategori
- Menghapus kategori
Kategori digunakan untuk mengelompokkan menu agar customer lebih mudah mencari makanan dan minuman.

# Manajemen Pesanan
Admin dapat:
- Melihat seluruh pesanan
- Mencari pesanan
- Melihat detail pesanan
- Melihat data customer
- Melihat item pesanan
- Melihat pembayaran
- Melihat total transaksi
- Mengubah status pesanan
- Membatalkan pesanan
- Melakukan refund
- Melakukan void


# POS
POS digunakan oleh kasir untuk membuat transaksi secara langsung.
Fitur POS:
- Memilih menu
- Memilih customer
- Memilih meja
- Mengatur jumlah pesanan
- Memilih ukuran
- Memilih tambahan
- Menambahkan catatan
- Memilih metode pembayaran
- Menghitung subtotal
- Menghitung pajak
- Menghitung service
- Menghitung total transaksi
- Membuat order

# Kitchen
Kitchen digunakan untuk membantu staff dapur melihat dan memproses pesanan.
Kitchen dapat:
- Melihat pesanan masuk
- Melihat detail pesanan
- Melihat jumlah item
- Melihat pilihan menu
- Melihat catatan pesanan
- Mengubah status pesanan
- Menandai pesanan sedang diproses
- Menandai pesanan siap
Status utama kitchen:

Pending
   ↓
Accepted
   ↓
Processing
   ↓
Ready
   ↓
Completed

# Customer Management
Admin dapat melihat data pelanggan seperti:
- Nama
- Nomor telepon
- Email
- Jumlah kunjungan
- Total pengeluaran
- Kunjungan terakhir
Data customer dapat digunakan untuk melihat riwayat dan aktivitas pelanggan.

# Riwayat Pesanan
Admin dapat melihat transaksi yang telah dilakukan.
Informasi yang dapat dilihat:
- Nomor pesanan
- Customer
- Item
- Total
- Pembayaran
- Status
- Waktu transaksi

# Reports
Sistem menyediakan laporan berdasarkan periode:
- Daily
- Weekly
- Monthly
- Yearly

Informasi laporan meliputi:
- Total pesanan
- Pendapatan
- Pajak
- Service
- Pesanan selesai
- Pesanan dibatalkan
- Metode pembayaran
- Menu terlaris
- Menu dengan penjualan rendah
- Jam ramai
- Hari ramai
- Customer baru
- Customer yang kembali melakukan transaksi

# Best Seller
Sistem dapat menampilkan menu yang paling banyak terjual berdasarkan jumlah pesanan.
Fitur ini membantu cafe mengetahui menu yang paling banyak diminati.

# Worst Seller
Sistem dapat menampilkan menu dengan jumlah penjualan yang rendah.
Informasi ini dapat digunakan sebagai bahan evaluasi menu.

# Peak Hours
Sistem dapat mengetahui jam dengan jumlah transaksi paling tinggi.
Data ini membantu melihat waktu operasional yang paling ramai.

# Peak Days
Sistem dapat melihat hari dengan jumlah transaksi paling tinggi.

# Customer Analysis
Sistem dapat membedakan customer berdasarkan aktivitas transaksi.

### New Customer
Customer yang baru melakukan transaksi.

### Returning Customer
Customer yang melakukan transaksi kembali.

# User Management
Super Admin dapat mengelola pengguna sistem.
Fitur:
- Menambahkan user
- Melihat user
- Mengedit user
- Menghapus user
- Mengatur role user

Role yang tersedia:
- Super Admin
- Owner
- Manager
- Cashier
- Kitchen

# Role & Access
## Super Admin
Memiliki akses untuk mengelola user dan sistem administrasi.

## Owner
Memiliki akses terhadap pengelolaan cafe dan fitur utama sistem.

## Manager
Memiliki akses untuk memantau operasional dan laporan.

## Cashier
Berfokus pada transaksi dan pengelolaan pesanan.

## Kitchen
Berfokus pada pengelolaan pesanan dapur.

# Audit Log
Audit Log digunakan untuk mencatat aktivitas penting dalam sistem.
Aktivitas yang dapat dicatat antara lain:
- Menambahkan data
- Mengubah data
- Menghapus data
- Mengubah status pesanan
- Membatalkan pesanan
- Melakukan void
- Aktivitas pengguna

# QR Ordering Management
Admin dapat mengelola QR Ordering untuk meja cafe.
QR Ordering digunakan untuk:
- Mengidentifikasi meja
- Mempermudah customer melakukan order
- Menghubungkan order dengan nomor meja
- Mengurangi kesalahan pencatatan meja

# Backup Database
Sistem menyediakan fitur backup database untuk menjaga keamanan data.
Admin dapat membuat backup database dan mengunduh hasil backup.

# Restore Database
Database dapat dikembalikan menggunakan file backup yang tersedia.
Fitur ini membantu ketika diperlukan pemulihan data.

# Receipt
Sistem dapat menghasilkan receipt atau struk transaksi.
Receipt berisi informasi seperti:
- Nomor pesanan
- Customer
- Item
- Quantity
- Harga
- Subtotal
- Pajak
- Service
- Total
- Metode pembayaran

# Kitchen Ticket
Sistem dapat menghasilkan kitchen ticket untuk membantu staff dapur melihat detail pesanan.
Kitchen ticket berisi:
- Nomor order
- Customer
- Menu
- Quantity
- Pilihan menu
- Catatan
- Waktu pesanan

# Alur Pemesanan
Customer
   ↓
Melihat Menu
   ↓
Memilih Menu
   ↓
Detail Menu
   ↓
Tambah ke Cart
   ↓
Checkout
   ↓
Mengisi Data
   ↓
Memilih Dine In / Take Away
   ↓
Memilih Pembayaran
   ↓
Order Dibuat
   ↓
Nomor Antrean
   ↓
Kitchen
   ↓
Processing
   ↓
Ready
   ↓
Completed