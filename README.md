# Cafe & Restaurant POS System
Cafe & Restaurant POS System adalah aplikasi berbasis web yang dirancang untuk membantu proses pemesanan, transaksi, pengelolaan menu, pemrosesan pesanan dapur, pencatatan penjualan, dan pengelolaan persediaan bahan baku cafe secara terintegrasi.
Sistem ini memiliki dua sisi utama, yaitu **Customer Website** untuk pelanggan dan **Admin System** untuk mengelola operasional cafe.
Dokumentasi ini menjelaskan **Modul Operasional Inti (Transaksi & Dapur)**, mulai dari Kasir POS sampai Inventory Bahan Baku.
---

# Modul Operasional Inti (Transaksi & Dapur)
## 1. Kasir POS (Point of Sale)
POS digunakan oleh kasir untuk mencatat pesanan pelanggan, menghitung tagihan, memvalidasi pembayaran, dan menyimpan transaksi.
Fitur utama:
- Memilih menu yang dipesan pelanggan.
- Mengatur jumlah setiap item.
- Memilih ukuran menu jika tersedia.
- Memilih opsi atau tambahan menu jika tersedia.
- Menambahkan catatan pesanan.
- Memilih data pelanggan atau memasukkan informasi pelanggan.
- Memilih jenis kunjungan, seperti Dine In, Pickup, Delivery, dan Pre-order sesuai pilihan yang tersedia pada POS.
- Mencatat nomor meja untuk pesanan makan di tempat.
- Menghitung subtotal pesanan.
- Menerapkan diskon.
- Menghitung pajak dan biaya layanan.
- Menghitung total pembayaran.
- Memasukkan informasi metode dan nominal pembayaran.
- Memvalidasi jumlah pembayaran sebelum transaksi disimpan.
- Membuat nomor pesanan dan menyimpan transaksi.
- Menyimpan rincian item pesanan untuk diproses oleh modul selanjutnya.
POS menjadi bagian awal dari alur operasional karena data transaksi yang tersimpan digunakan oleh modul Kitchen dan laporan.

## 2. Manajemen Menu & Recipe
Modul Menu & Recipe digunakan untuk mengelola daftar makanan dan minuman yang dijual serta komposisi bahan yang digunakan untuk membuat setiap menu.

### 2.1 Manajemen Menu
Admin dapat mengelola informasi menu yang digunakan pada sistem.
Fitur utama:
- Menambahkan menu baru.
- Melihat daftar menu.
- Mengedit informasi menu.
- Menghapus menu.
- Mengelola kategori menu.
- Mengatur nama dan deskripsi menu.
- Mengatur harga jual.
- Mengatur harga ukuran Large jika tersedia.
- Mengatur gambar menu.
- Mengatur waktu persiapan.
- Mengatur status ketersediaan menu.
- Mengelola informasi tambahan seperti kalori dan allergen jika tersedia.
- Mengatur status Best Seller, Promo, atau menu baru sesuai fitur yang tersedia.
Data menu digunakan oleh POS untuk menampilkan produk yang dapat dipesan pelanggan atau dipilih oleh kasir.

### 2.2 Ukuran dan Opsi Tambahan Menu
Menu dapat memiliki pilihan tambahan sesuai konfigurasi yang tersedia.
Contoh pilihan:
- Ukuran Small atau Large.
- Varian menu.
- Topping tambahan.
- Extra cheese.
- Pilihan saus.
- Tambahan harga untuk opsi tertentu.
Pilihan yang dipilih pelanggan perlu tersimpan pada detail pesanan agar informasi tersebut dapat ditampilkan kembali pada halaman Kitchen.

### 2.3 Recipe (Resep Bahan Baku)
Recipe digunakan untuk mencatat komposisi bahan yang diperlukan untuk membuat suatu menu.
Fitur utama:
- Menghubungkan menu dengan bahan baku.
- Menentukan nama bahan yang digunakan.
- Menentukan jumlah bahan untuk satu sajian.
- Menentukan satuan bahan, seperti gram, kilogram, mililiter, liter, atau pcs.
- Menghubungkan bahan resep dengan data Inventory jika tersedia.
- Menyimpan informasi biaya bahan untuk membantu estimasi food cost.
Resep yang terhubung dengan bahan Inventory dapat digunakan sebagai dasar pengurangan stok bahan saat transaksi diproses.

### 2.4 Food Cost
Food cost merupakan perkiraan biaya bahan yang digunakan untuk membuat suatu menu.
Informasi biaya bahan dapat digunakan untuk:
- Mengetahui estimasi biaya produksi menu.
- Membandingkan biaya bahan dengan harga jual.
- Membantu evaluasi harga jual.
- Mendukung analisis biaya produk.
Ketepatan perhitungan bergantung pada kelengkapan resep, jumlah bahan, satuan, dan harga bahan yang tercatat.

## 3. Kitchen Display System (KDS)
Kitchen digunakan oleh staf dapur untuk melihat pesanan masuk, membaca rincian pesanan, dan memperbarui status pengerjaan.
Akses halaman: `/admin/kitchen`
Fitur utama:
- Menampilkan daftar pesanan yang perlu diproses.
- Menampilkan nomor pesanan.
- Menampilkan nama pelanggan.
- Menampilkan nomor meja jika tersedia.
- Menampilkan jenis kunjungan.
- Menampilkan waktu masuk pesanan.
- Menampilkan jumlah seluruh item.
- Menampilkan daftar menu dan kuantitasnya.
- Menampilkan ukuran menu jika tersedia.
- Menampilkan pilihan tambahan seperti topping, saus, atau extra apabila tersimpan pada detail pesanan.
- Menampilkan catatan khusus pesanan.
- Menampilkan estimasi waktu persiapan.
- Menampilkan durasi memasak setelah proses dimulai.
- Memberikan notifikasi ketika pesanan baru terdeteksi.
- Mengubah status pengerjaan pesanan.

### Status Pengerjaan Kitchen
Alur utama pengerjaan dapur:
1. **Accepted** — pesanan diterima dan menunggu mulai dikerjakan.
2. **Processing** — pesanan sedang dibuat.
3. **Ready** — pesanan selesai dibuat dan siap disajikan.
Status pesanan selanjutnya dapat diperbarui sesuai alur penyelesaian transaksi pada sistem.
Kitchen membantu staf dapur memantau antrean dan membaca detail permintaan pelanggan dengan lebih teratur.

## 4. Ringkasan Harian dan Laporan Penjualan
Modul ringkasan dan laporan digunakan untuk membantu pemilik atau pengelola memantau aktivitas transaksi serta performa penjualan cafe.
### 4.1 Dashboard Operasional
Dashboard menyajikan ringkasan kondisi operasional berdasarkan data yang tersedia.
Informasi yang dapat ditampilkan meliputi:
- Jumlah pesanan.
- Pendapatan dari transaksi yang memenuhi kriteria laporan.
- Pesanan menunggu.
- Pesanan yang sedang diproses.
- Pesanan siap.
- Pesanan selesai.
- Pesanan dibatalkan.

### 4.2 Laporan Penjualan
Laporan digunakan untuk melihat data transaksi berdasarkan periode yang tersedia.
Periode laporan:
- Daily (Harian).
- Weekly (Mingguan).
- Monthly (Bulanan).
- Yearly (Tahunan).

Informasi laporan dapat mencakup:
- Jumlah transaksi.
- Pendapatan.
- Pajak.
- Biaya layanan.
- Rata-rata nilai transaksi (Average Order Value/AOV).
- Menu terlaris.
- Menu dengan penjualan rendah.
- Jam ramai.
- Pelanggan baru.
- Pelanggan yang kembali melakukan transaksi.
Laporan membantu pengelola mengevaluasi penjualan, mengetahui menu yang diminati, dan memahami waktu operasional yang ramai.

## 5. Inventory Bahan Baku
Inventory digunakan untuk mengelola persediaan bahan baku yang diperlukan untuk operasional cafe.
Akses utama: `/admin/inventory`
### 5.1 Manajemen Data Bahan Baku
Fitur utama:
- Menambahkan bahan baku.
- Melihat daftar bahan baku.
- Mengedit informasi bahan.
- Menghapus bahan sesuai ketentuan sistem.
- Mengelola kategori bahan.
- Mengatur satuan bahan.
- Mencatat jumlah stok.
- Menentukan batas minimum stok.
- Mencatat biaya per unit.
- Mengatur status aktif bahan.
Data Inventory menjadi sumber pencatatan persediaan yang dapat dihubungkan dengan resep menu.

### 5.2 Peringatan Stok Rendah
Sistem memantau stok bahan berdasarkan batas minimum yang ditentukan.
Fitur utama:
- Menampilkan bahan dengan stok rendah.
- Menampilkan bahan yang stoknya habis.
- Membandingkan stok saat ini dengan batas minimum.
- Membantu menentukan bahan yang perlu segera diisi ulang.
Peringatan stok membantu pengelola mengurangi risiko kehabisan bahan ketika cafe sedang beroperasi.

### 5.3 Restock Bahan Baku
Restock digunakan untuk mencatat penerimaan bahan dan memperbarui jumlah stok.
Fitur utama:
- Memilih bahan baku yang akan ditambah.
- Memilih supplier yang terhubung dengan bahan tersebut.
- Memasukkan jumlah bahan yang benar-benar diterima.
- Memvalidasi data restock.
- Menambahkan jumlah penerimaan ke stok bahan.
Jumlah stok diperbarui berdasarkan kuantitas aktual yang dimasukkan saat restock.

### 5.4 Stock Opname
Stock opname digunakan untuk mencocokkan stok pada sistem dengan jumlah fisik bahan yang tersedia.
Fitur utama:
- Menampilkan stok menurut sistem.
- Memasukkan jumlah stok fisik.
- Menghitung selisih antara stok sistem dan stok aktual.
- Menyimpan catatan hasil pemeriksaan.
- Memperbarui stok Inventory berdasarkan jumlah aktual.
- Menyimpan riwayat pemeriksaan stok.
Stock opname membantu menjaga kesesuaian data persediaan dengan kondisi fisik bahan baku.

### 5.5 Manajemen Supplier
Modul Supplier digunakan untuk menyimpan data pemasok bahan baku dan menghubungkannya dengan bahan yang dapat disuplai.
Fitur utama:
- Menambahkan supplier.
- Melihat daftar supplier.
- Mengedit informasi supplier.
- Menghapus supplier sesuai ketentuan sistem.
- Menyimpan nama supplier.
- Menyimpan nama kontak.
- Menyimpan nomor telepon dan email.
- Menyimpan alamat dan catatan supplier.
- Menghubungkan supplier dengan bahan baku.
- Mencatat informasi kuantitas dan satuan pasokan pada relasi supplier dan bahan.
Relasi supplier dengan bahan digunakan untuk membantu memilih pemasok yang sesuai ketika proses restock dilakukan.
---

# Alur Kerja
Alur operasional utama sistem:
1. Admin mengelola menu, kategori, dan resep bahan baku.
2. Kasir memilih menu dan mencatat detail pesanan.
3. Sistem menghitung subtotal, diskon, pajak, biaya layanan, dan total pembayaran.
4. Sistem memvalidasi pembayaran dan menyimpan transaksi.
5. Pesanan yang sesuai ditampilkan pada halaman Kitchen.
6. Staf dapur memproses pesanan dan memperbarui status sampai Ready.
7. Data transaksi digunakan untuk menyusun ringkasan dan laporan penjualan.
8. Stok bahan dipantau melalui modul Inventory.
9. Pengelola melakukan restock ketika diperlukan dan stock opname untuk mencocokkan stok sistem dengan stok fisik.

## Tabel Database Utama
Tabel yang berkaitan dengan operasional Developer 2.1 meliputi:
| Tabel | Fungsi |
|---|---|
| `orders` | Menyimpan informasi utama transaksi dan status pesanan. |
| `order_details` | Menyimpan rincian menu, jumlah, ukuran, opsi, harga, dan catatan item. |
| `menus` | Menyimpan data makanan dan minuman yang dijual. |
| `categories` | Menyimpan kategori menu. |
| `menu_options` | Menyimpan konfigurasi pilihan menu. |
| `menu_option_values` | Menyimpan nilai pilihan menu dan tambahan harga. |
| `ingredients` | Menyimpan komposisi bahan resep menu. |
| `inventory_items` | Menyimpan data bahan baku, satuan, stok, dan stok minimum. |
| `stock_opnames` | Menyimpan catatan pemeriksaan dan penyesuaian stok fisik. |
| `suppliers` | Menyimpan data pemasok bahan baku. |
| `inventory_item_supplier` | Menghubungkan supplier dengan bahan baku yang dipasok. |

## Catatan Implementasi
- Pengurangan stok bahan secara otomatis bergantung pada hubungan resep dengan item Inventory yang valid.
- Informasi ukuran, saus, topping, dan tambahan menu pada Kitchen bergantung pada data pilihan yang tersimpan pada detail pesanan.
- Notifikasi Kitchen pada alur yang menggunakan polling merupakan pemeriksaan berkala dari browser.
- Fitur dan perhitungan mengikuti implementasi kode yang tersedia pada project.
- Penggunaan fitur tertentu tetap bergantung pada konfigurasi menu, data resep, dan validasi transaksi.
---


# Modul Customer, Growth & Manajemen Bisnis (Back-Office & Administration)
Modul-modul ini dirancang khusus untuk manajemen back-office (Owner, Manager, dan Administrator). Berbeda dengan sistem kasir (POS) dan dapur (Kitchen) yang berorientasi pada kecepatan transaksi real-time di outlet, rangkaian modul ini berfokus pada strategi retensi pelanggan, optimalisasi promosi, analisis profitabilitas, ekspansi multi-cabang, tata kelola SDM, standarisasi operasional resep, hingga visualisasi analitik eksekutif.

Setiap modul memiliki menu dan sub-menu mandiri di sidebar admin dengan fitur-fitur kompleks sebagai berikut:

---

## 1. CRM Pelanggan (Customer Relationship Management)
Modul CRM mengelola basis data pelanggan dan memelihara retensi jangka panjang melalui personalisasi layanan dan program loyalitas bertingkat.
* **Akses URL**: `/admin/customers/*`
* **Hak Akses**: Owner, Manager

### Fitur-Fitur CRM Pelanggan:
1. **Database Pelanggan (`/admin/customers/database-pelanggan`)**
   - **Pencatatan Data Lengkap**: Menyimpan identitas pelanggan mencakup Nama, Nomor Telepon/WhatsApp, Alamat Email, Tanggal Lahir, Alamat Tempat Tinggal, dan Catatan Khusus.
   - **Metrik Aktivitas Pelanggan**: Menghitung secara otomatis total akumulasi transaksi belanja (`total_spending`), jumlah frekuensi kunjungan (`visit_count`), saldo poin loyalty yang dimiliki, serta tanggal dan waktu transaksi terakhir (`last_visit`).
   - **Pencarian & Filter Cepat**: Memfilter pelanggan berdasarkan tingkatan membership (Bronze, Silver, Gold, Platinum) serta pencarian real-time berdasarkan nama, no. telepon, atau email.
   - **Export CSV Data Pelanggan**: Menyediakan fitur ekspor data pelanggan ke format file CSV untuk kebutuhan integrasi marketing (misal broadcast WhatsApp/SMS promo atau email newsletter).

2. **Riwayat Pembelian (`/admin/customers/riwayat-pembelian`)**
   - **Pelacakan Transaksi Terintegrasi**: Menampilkan seluruh riwayat pesanan yang terafiliasi dengan akun pelanggan tertentu.
   - **Detail Informasi Pesanan**: Menampilkan nomor invoice/faktur order, tanggal dan jam transaksi, daftar menu makanan/minuman yang dibeli, kuantitas, catatan khusus per menu, subtotal, potongan diskon, metode pembayaran yang digunakan (QRIS, Tunai, Kartu, E-Wallet), serta status pesanan (*Completed, Cancelled, Refund, Void*).
   - **Filter Riwayat**: Pencarian pesanan berdasarkan nama pelanggan dan filter rentang tanggal tertentu.

3. **Membership Tier: Bronze · Silver · Gold · Platinum (`/admin/customers/membership-tier`)**
   - **Sistem Tingkatan Loyalitas Bertingkat**: Mengklasifikasikan pelanggan ke dalam 4 level tier keanggotaan:
     - **Bronze**: Level awal untuk pelanggan baru atau pembelanjaan awal.
     - **Silver**: Level menengah dengan ambang akumulasi belanja dan kunjungan tertentu.
     - **Gold**: Level loyal dengan ambang akumulasi belanja tinggi.
     - **Platinum**: Level VIP tertinggi dengan belanja dan kunjungan paling intensif.
   - **Evaluasi Tier Otomatis (*Recalculate Tiers*)**: Algoritma massal yang mengevaluasi seluruh data pelanggan berdasarkan total akumulasi belanja dan jumlah kunjungan, lalu memperbarui status tier pelanggan secara otomatis.
   - **Penyesuaian Manual (*Manual Override*)**: Admin atau Owner memiliki wewenang untuk menyesuaikan level tier pelanggan secara manual untuk kebutuhan layanan khusus atau pelanggan VIP korporat.

4. **Poin Pelanggan & Loyalty (`/admin/customers/poin-pelanggan`)**
   - **Reward Poin Transaksi**: Pelanggan memperoleh poin loyalitas secara proporsional dari nominal transaksi yang dibelanjakan.
   - **Monitoring Saldo & Mutasi**: Melihat saldo poin aktif dari setiap pelanggan beserta catatan penambahan dan penukaran poin.
   - **Penyesuaian Poin Manual (*Adjust Points*)**: Admin dapat menambah atau memotong saldo poin pelanggan secara manual disertai pencatatan alasan/keterangan penyesuaian (misal kompensasi keluhan layanan atau bonus event khusus).

5. **Menu Favorit Pelanggan (`/admin/customers/menu-favorit`)**
   - **Analisis Preferensi Produk**: Menggunakan algoritma pemetaan riwayat pesanan untuk mengetahui menu makanan dan minuman yang paling sering dipesan oleh setiap pelanggan secara individual.
   - **Statistik Pemesanan**: Menampilkan total kuantitas per menu yang pernah dibeli dan total kontribusi nilai belanjanya.
   - **Personalisasi Layanan**: Membantu staf kasir dan barista mengenali preferensi menu pelanggan setia untuk memberikan rekomendasi yang dipersonalisasi saat berkunjung.

6. **Birthday Reminder (`/admin/customers/birthday-reminder`)**
   - **Deteksi Otomatis Hari Ulang Tahun**: Mengidentifikasi pelanggan yang berulang tahun pada hari ini (*Today's Birthdays*) serta pelanggan yang berulang tahun pada bulan berjalan.
   - **Data Lengkap Ulang Tahun**: Menampilkan nama, tanggal lahir, perkiraan usia, nomor telepon kontak, dan tingkatan membership.
   - **Apresiasi & Hadiah Promo**: Memudahkan tim kafe untuk memberikan ucapan selamat ulang tahun dan mengirimkan voucher hadiah atau diskon spesial ulang tahun demi memperkuat hubungan emosional dengan pelanggan.

---

## 2. Promo & Loyalty Program
Modul Promo & Loyalty menyediakan berbagai instrumen promosi dan retensi pelanggan untuk mendorong peningkatan frekuensi kunjungan (*repeat order*) dan rata-rata nilai transaksi (*basket size*).
* **Akses URL**: `/admin/promo/*`
* **Hak Akses**: Owner, Manager

### Fitur-Fitur Promo & Loyalty:
1. **Voucher & Kupon Promo (`/admin/promo/vouchers`)**
   - **Pembuatan Kode Voucher Unik**: Mengatur kode kupon unik (misal: `DISKON20`, `COFFEELOVER`) yang dapat dimasukkan pelanggan saat pemesanan.
   - **Fleksibilitas Jenis Diskon**: Mendukung diskon berupa **Persentase (%)** dengan batasan diskon maksimal (Maksimal Potongan), maupun **Nominal Tetap (Rp)**.
   - **Persyaratan & Batasan**: Mengatur ketentuan batas minimum belanja (*Minimum Spend*), kuota penggunaan global (*Usage Limit*), serta periode masa berlaku promo (Tanggal Mulai & Tanggal Berakhir).
   - **Toggle Status Instan**: Mengaktifkan atau menonaktifkan voucher sewaktu-waktu dengan satu klik tanpa perlu menghapus master data voucher.

2. **Point Reward / Katalog Hadiah (`/admin/promo/point-rewards`)**
   - **Katalog Penukaran Hadiah**: Mengelola daftar item yang dapat ditukarkan menggunakan poin pelanggan, seperti minuman gratis, dessert, diskon tagihan, maupun merchandise kafe (Tumbler, T-Shirt, Tote Bag).
   - **Aturan Penukaran**: Menetapkan kuota stok hadiah, foto hadiah, deskripsi, dan jumlah poin yang wajib ditukarkan (*Points Required*).
   - **Manajemen Klaim Reward**: Memproses formulir penukaran poin pelanggan di back-office dan melacak status penyelesaian reward (*Pending*, *Completed*, *Cancelled*).

3. **Stamp Card Digital (`/admin/promo/stamp-cards`)**
   - **Digitalisasi Kartu Stamp Fisik**: Menggantikan kartu stempel kertas konvensional dengan sistem stempel digital (contoh: "Kumpulkan 10 Stamp, Dapatkan 1 Kopi Gratis").
   - **Konfigurasi Target Stempel**: Mengatur jumlah stempel yang wajib dikumpulkan serta hadiah reward yang diperoleh saat kartu penuh.
   - **Pemberian Stamp & Penukaran**: Staf dapat menambahkan stempel digital ke kartu pelanggan saat bertransaksi dan memproses penukaran reward saat target stempel tercapai, yang kemudian otomatis mereset kartu stempel pelanggan.

4. **Program Cashback (`/admin/promo/cashback`)**
   - **Konfigurasi Aturan Cashback**: Menetapkan aturan cashback bersyarat, seperti persentase cashback berdasarkan tier membership atau nominal belanja minimum tertentu (misal: Belanja minimal Rp 100.000 mendapatkan cashback 10%).
   - **Batas Maksimal & Validitas**: Menetapkan batas maksimal nominal cashback per transaksi serta masa berlaku aturan promo cashback.
   - **Pemberian Cashback Instan (*Grant Cashback*)**: Menambahkan cashback secara langsung ke saldo poin/kredit loyalty pelanggan dengan catatan audit log yang rapi.

5. **Referral Program (`/admin/promo/referrals`)**
   - **Kode Referral Unik Pelanggan**: Setiap pelanggan memiliki kode rujukan unik untuk dibagikan kepada teman, rekan, atau keluarga.
   - **Reward Dua Arah**: Memberikan insentif poin reward kepada pihak pengundang (*Referrer*) saat teman yang diundang pertama kali bertransaksi, sekaligus memberikan poin sambutan kepada pelanggan baru (*Referee*).
   - **Pelacakan Performa Referral**: Menampilkan statistik pelanggan yang paling aktif mengundang orang lain dan total pelanggan baru yang berhasil diakuisisi melalui program rujukan.

---

## 3. Laporan Owner (Financial & Business Intelligence)
Modul Laporan Owner dirancang khusus bagi pemilik bisnis dan jajaran manajemen eksekutif untuk menganalisis kesehatan keuangan, marjin keuntungan riil, dan efisiensi biaya bahan baku.
* **Akses URL**: `/admin/owner-reports/*`
* **Hak Akses**: Owner, Manager

### Fitur-Fitur Laporan Owner:
1. **Omzet & Tren Penjualan (`/admin/owner-reports/omzet-tren`)**
   - **Visualisasi Grafik Tren**: Menampilkan grafik garis dan batang interaktif (Chart.js) untuk pergerakan omzet harian, mingguan, bulanan, dan tahunan.
   - **Metrik Finansial Komprehensif**: Menghitung secara otomatis Omzet Kotor (*Gross Sales*), Total Potongan Diskon & Promo, dan Pendapatan Bersih (*Net Sales*).
   - **Average Order Value (AOV)**: Menghitung rata-rata pengeluaran belanja per transaksi pelanggan untuk mengukur efektivitas upselling.
   - **Filter Waktu Dinamis**: Mendukung pemilihan periode fleksibel (Hari ini, 7 hari terakhir, 30 hari terakhir, bulan ini, atau custom range tanggal).

2. **Food Cost & COGS / HPP (`/admin/owner-reports/food-cost-cogs`)**
   - **Analisis Biaya Pokok Penjualan (HPP)**: Memantau biaya bahan baku riil (*Cost of Goods Sold*) untuk setiap menu yang terjual.
   - **Rasio Food Cost**: Menghitung persentase rasio Food Cost terhadap harga jual:
     $$\text{Food Cost Ratio (\%)} = \left(\frac{\text{Food Cost (HPP)}}{\text{Harga Jual}}\right) \times 100\%$$
   - **Indikator Kesehatan Biaya**: Mengidentifikasi menu dengan food cost ideal (<30-35%) serta memberikan peringatan visual untuk menu dengan food cost terlalu tinggi yang berpotensi menggerus keuntungan.
   - **Pembaruan HPP Cepat**: Memungkinkan owner memperbarui estimasi nilai HPP per menu secara langsung melalui tabel laporan.

3. **Laba Kotor & Margin (`/admin/owner-reports/laba-margin`)**
   - **Perhitungan Laba Bersih Produk**: Menghitung laba kotor riil (*Gross Profit*) dari selisih antara Pendapatan Bersih dan Total Biaya HPP Bahan Baku.
   - **Gross Margin (%) Keseluruhan**: Menampilkan persentase margin keuntungan operasional bisnis secara menyeluruh pada periode yang dipilih.
   - **Tabel Rincian per Produk**: Menampilkan kontribusi omzet per menu, akumulasi total HPP yang dikeluarkan, nominal laba kotor rupiah, dan persentase margin keuntungan bersih per item.

4. **Produk Paling Menguntungkan (`/admin/owner-reports/produk-menguntungkan`)**
   - **Peringkat Profitabilitas Menu**: Mengurutkan produk bukan hanya berdasarkan kuantitas terjual, melainkan berdasarkan total nominal keuntungan bersih (*Total Gross Profit*) yang dihasilkan.
   - **Menu Engineering Matrix**: Membantu membedakan produk bintang (*Star Menu* dengan penjualan tinggi & margin besar) vs produk dengan margin tipis, sehingga owner dapat menentukan strategi promosi atau penyesuaian harga jual yang tepat.

5. **Pertumbuhan Pelanggan (`/admin/owner-reports/pertumbuhan-pelanggan`)**
   - **Metrik Akuisisi Pelanggan**: Mengukur laju penambahan pelanggan baru (*New Customers*) vs pelanggan yang kembali bertransaksi (*Returning Customers*).
   - **Tingkat Retensi & Repeat Order**: Menghitung persentase *Repeat Order Rate* untuk mengukur kepuasan dan loyalitas pelanggan terhadap kafe.
   - **Grafik Tren Pertumbuhan**: Menampilkan grafik perkembangan pertumbuhan basis data pelanggan dari waktu ke waktu.

---

## 4. Multi-Outlet (Multi-Cabang)
Modul Multi-Outlet memungkinkan bisnis kafe mengelola operasional banyak cabang secara terpusat, memantau performa cabang secara komparatif, serta mengatur perpindahan logistik bahan baku.
* **Akses URL**: `/admin/multi-outlet/*`
* **Hak Akses**: Owner, Manager

### Fitur-Fitur Multi-Outlet:
1. **Kelola Banyak Cabang (`/admin/multi-outlet/cabang`)**
   - **Direktori Master Cabang**: Mendaftarkan dan mengelola seluruh cabang/outlet kafe (Kode Cabang, Nama Outlet, Alamat Lengkap, Nomor Kontak Cabang, dan Nama Manajer / Penanggung Jawab Cabang).
   - **Status Operasional Cabang**: Mengatur status operasional cabang (Aktif/Buka, Tutup Sementara, atau Dalam Renovasi/Maintenance).
   - **Skalabilitas Bisnis**: Memfasilitasi pembukaan cabang-cabang baru seiring ekspansi bisnis tanpa perlu mengubah arsitektur sistem.

2. **Performa & Laporan per Cabang (`/admin/multi-outlet/performa`)**
   - **Konsolidasi Data Lintas Cabang**: Menampilkan perbandingan kinerja finansial seluruh outlet dalam satu layar komparatif.
   - **Metrik Komparasi**: Membandingkan total omzet kotor, total net revenue, jumlah transaksi, nilai rata-rata pesanan (AOV), serta menu terlaris pada masing-masing cabang.
   - **Visualisasi Grafik Multi-Outlet**: Grafik batang performa pendapatan antar cabang untuk mengevaluasi outlet terbaik dan outlet yang membutuhkan intervensi operasional.

3. **Transfer Stok Antar Cabang (`/admin/multi-outlet/transfer-stok`)**
   - **Manajemen Logistik Bahan Baku**: Mengelola alur pemindahan stok bahan baku dari satu cabang ke cabang lainnya untuk mencegah kehabisan stok (*Stockout*).
   - **Dokumen Surat Jalan Transfer**: Setiap mutasi transfer memiliki nomor referensi unik, cabang asal (*Source Outlet*), cabang tujuan (*Destination Outlet*), item bahan baku, jumlah unit yang dikirim, tanggal kirim, dan catatan.
   - **Pelacakan Alur Status Transfer**: Memiliki alur status bertahap yang ketat:
     $$\text{Draft / Pending} \longrightarrow \text{In-Transit (Dalam Perjalanan)} \longrightarrow \text{Completed (Diterima) / Rejected (Ditolak)}$$
   - **Otomatisasi Stok Cabang**: Stok di cabang asal otomatis terpotong saat pengiriman dan stok di cabang tujuan otomatis bertambah saat status transfer disetujui (*Completed*).

4. **Gudang Pusat / Central Kitchen (`/admin/multi-outlet/gudang-pusat`)**
   - **Sentralisasi Inventaris Bahan**: Mengelola persediaan bahan baku pada gudang utama atau dapur pusat (*Central Kitchen*) yang menyuplai cabang-cabang gerai.
   - **Pencatatan Stok Masuk & Restock**: Mencatat riwayat penerimaan bahan dari supplier, penyesuaian stok, satuan bahan (kg, liter, pcs, gram), dan batas stok minimum aman (*Minimum Stock Alert*).
   - **Distribusi ke Gerai**: Memfasilitasi alokasi pengiriman bahan baku dari gudang pusat ke setiap outlet cabang.

5. **Hak Akses Berbasis Cabang (`/admin/multi-outlet/hak-akses`)**
   - **Pembatasan Otoritas Pengguna (*Outlet Assignment*)**: Menugaskan staf kasir, barista, atau manajer ke cabang outlet tertentu.
   - **Isolasi Data Cabang**: Memastikan kasir hanya dapat melihat pesanan dan mengoperasikan kasir di cabang tempat mereka ditugaskan.
   - **Akses Global Eksekutif**: Owner dan Super Admin tetap memiliki akses lintas cabang secara menyeluruh.

---

## 5. Manajemen Karyawan / SDM (Human Resource Management)
Modul SDM menyediakan solusi lengkap pengelolaan tenaga kerja kafe mulai dari pencatatan absensi, penjadwalan kerja, penilaian kinerja (KPI), pengajuan cuti, hingga kalkulasi payroll gaji bulanan.
* **Akses URL**: `/admin/hr/*`
* **Hak Akses**: Owner, Manager

### Fitur-Fitur Manajemen Karyawan:
1. **Absensi Masuk & Pulang (`/admin/hr/absensi`)**
   - **Pencatatan Waktu Digital**: Sistem absensi mandiri dengan tombol **Clock-In** (waktu datang) dan **Clock-Out** (waktu pulang) harian staf.
   - **Deteksi Keterlambatan Otomatis (*Late Tracking*)**: Sistem otomatis membandingkan jam clock-in dengan jam mulai shift kerja yang ditugaskan, mencatat status terlambat serta durasi menit keterlambatan.
   - **Absensi Manual oleh HR**: Formulir pencatatan absensi manual oleh Admin/HR apabila karyawan mengalami kendala perangkat atau lupa absensi, lengkap dengan catatan alasan.

2. **Status Kehadiran (`/admin/hr/status-kehadiran`)**
   - **Rekapitulasi Kehadiran Harian & Bulanan**: Mengklasifikasikan kehadiran staf ke dalam 4 status resmi:
     - **Hadir**: Masuk kerja sesuai jadwal.
     - **Izin**: Tidak masuk dengan pemberitahuan izin sebelumnya.
     - **Sakit**: Tidak masuk karena kondisi kesehatan (dapat disertai bukti surat dokter).
     - **Cuti**: Pengambilan hak cuti resmi.
   - **Monitoring Disiplin Kerja**: Menghitung persentase kedisiplinan kehadiran tepat waktu seluruh karyawan.

3. **Shift Kerja & Penjadwalan (`/admin/hr/shift`)**
   - **Master Data Jenis Shift**: Mengonfigurasi jenis-jenis jam kerja operasional (contoh: Shift Pagi 07:00-15:00, Shift Siang 12:00-20:00, Shift Malam 15:00-23:00).
   - **Penyusunan Roster Jadwal (*Work Roster*)**: Menjadwalkan penugasan karyawan pada shift tertentu, tanggal tertentu, dan cabang outlet tertentu.
   - **Keseimbangan Penugasan**: Memastikan kuota staf barista, kasir, dan dapur tercukupi pada jam-jam sibuk (*Peak Hours*).

4. **Gaji & Payroll Bulanan (`/admin/hr/payroll`)**
   - **Pembuatan Payroll Otomatis (*Generate Monthly Payroll*)**: Menghitung slip gaji bulanan karyawan berdasarkan periode bulan dan tahun yang dipilih.
   - **Komponen Perhitungan Gaji Lengkap**:
     - **Gaji Pokok**: Sesuai kesepakatan kontrak kerja karyawan.
     - **Tunjangan**: Tunjangan harian/makan/transportasi berdasarkan jumlah kehadiran.
     - **Bonus & Insentif**: Bonus kinerja bulanan atau pencapaian target omzet kafe.
     - **Potongan (Deductions)**: Denda keterlambatan akumulatif atau potongan absensi tanpa keterangan (mangkir).
     - **Gaji Bersih (Take-Home Pay)**: Total pendapatan bersih yang diterima staf.
   - **Pengelolaan Status Pembayaran**: Memiliki status alur pembayaran dari **Draft** (tahap verifikasi perhitungan) hingga **Paid** (gaji telah ditransfer/dibayarkan).

5. **Key Performance Indicator / KPI Karyawan (`/admin/hr/kpi`)**
   - **Penilaian Kinerja Terstandarisasi**: Evaluasi berkala performa karyawan setiap bulan berdasarkan 4 pilar indikator utama:
     1. *Punctuality & Discipline* (Kedisiplinan & Ketepatan Waktu Kerja)
     2. *Job Skill & Speed* (Keterampilan Teknis & Kecepatan Penyajian)
     3. *Hospitality & Service* (Keramahan & Kualitas Pelayanan Pelanggan)
     4. *Teamwork & Attitude* (Kerjasama Tim & Sikap Kerja)
   - **Kalkulasi Skor & Grade Nilai**: Menghasilkan skor rata-rata (skala 0 - 100) dan klasifikasi grade performa:
     - **Grade A (90 - 100)**: *Outstanding / Luar Biasa*
     - **Grade B (75 - 89)**: *Good / Sangat Baik*
     - **Grade C (60 - 74)**: *Needs Improvement / Perlu Peningkatan*
     - **Grade D (< 60)**: *Poor / Kurang Memuaskan*

6. **Approval Cuti (`/admin/hr/cuti`)**
   - **Alur Pengajuan Cuti Staf**: Formulir pengajuan cuti resmi mencakup jenis cuti (Tahunan, Sakit, Urusan Mendesak), tanggal mulai, tanggal selesai, total hari, dan alasan cuti.
   - **Otoritas Persetujuan Manajer/Owner**: Tombol verifikasi satu klik untuk menyetujui (**Approve**) atau menolak (**Reject**) pengajuan cuti disertai catatan/alasan penolakan.
   - **Pelacakan Kuota Cuti**: Memantau riwayat dan sisa hak cuti tahunan masing-masing karyawan.

---

## 6. Operasional Lanjutan (Advanced Operations)
Modul Operasional Lanjutan mengatur standarisasi teknis operasional dapur, manajemen meja makan, dan konfigurasi fleksibel aturan loyalitas tingkat lanjut.
* **Akses URL**: `/admin/operasional/*`
* **Hak Akses**: Owner, Manager

### Fitur-Fitur Operasional Lanjutan:
1. **Digital Recipe Management (`/admin/operasional/resep`)**
   - **Buku Resep Digital Terstandarisasi (*Kitchen SOP*)**: Mendokumentasikan resep pembuatan untuk setiap menu makanan dan minuman yang dijual.
   - **Gramasi Presisi Bahan Baku**: Menentukan komposisi dan takaran bahan baku per porsi (contoh: 18g Biji Kopi Espresso, 150ml Susu Segar, 20ml Sirup Karamel, 1 pcs Paper Cup & Tutup).
   - **Kalkulasi Estimasi Food Cost Otomatis**: Menghitung estimasi HPP per sajian secara otomatis dari akumulasi harga beli bahan baku yang tercatat di sistem inventory.
   - **Panduan Langkah Pembuatan (*Preparation Steps*)**: Menyajikan urutan langkah pembuatan dan penyajian untuk memastikan konsistensi rasa dan kualitas produk di seluruh cabang.

2. **QR Table Ordering Management (`/admin/operasional/qr-meja`)**
   - **Manajemen Tata Letak Meja**: Mengelola direktori meja kafe (Nomor/Nama Meja, Kapasitas Tempat Duduk, dan Lokasi Area seperti Indoor, Outdoor, VIP Room, atau Lantai 2).
   - **Generator QR Code Unik per Meja**: Menghasilkan kode QR meja otomatis yang terintegrasi langsung dengan nomor meja pada halaman pemesanan mandiri customer (`/customer/menu?table=X`), menghilangkan risiko salah input nomor meja.
   - **Download & Cetak QR Meja**: Fitur unduh dan cetak QR code siap pakai beresolusi tinggi untuk dipasang pada akrilik meja.
   - **Monitoring Status Ketersediaan Meja**: Memantau status meja secara dinamis: **Tersedia (Available)**, **Terisi (Occupied)**, atau **Reservasi (Reserved)**.

3. **Level Membership Lanjutan (`/admin/operasional/membership-lanjutan`)**
   - **Konfigurasi Parameter Tingkat Lanjut**: Mengatur parameter aturan tier membership (Bronze, Silver, Gold, Platinum) secara fleksibel melalui antarmuka admin tanpa perlu mengubah kode sumber program.
   - **Ambang Batas Kualifikasi**: Menentukan syarat minimal akumulasi belanja (*Minimum Spending*) dan syarat minimal kunjungan (*Minimum Visits*) untuk setiap tingkatan tier.
   - **Keuntungan Eksklusif per Tier**:
     - Persentase potongan diskon otomatis member (%) saat transaksi.
     - Kuota bonus poin sambutan saat naik level tier.
     - Pengganda perolehan poin (*Point Multiplier*, contoh: Member Platinum mendapatkan 2x perolehan poin per nominal transaksi belanja).
   - **Evaluasi Massal Member (*Recalculate Tiers*)**: Tombol untuk mengeksekusi perhitungan ulang status tier seluruh pelanggan secara otomatis berdasarkan aturan parameter terbaru.

---

## 7. Dashboard Bisnis (Executive Analytics)
Modul Dashboard Bisnis adalah pusat analitik dan *Business Intelligence* komprehensif bagi Owner dan General Manager untuk memantau performa 4 pilar bisnis: Cabang, Produk, Pelanggan, dan Karyawan.
* **Akses URL**: `/admin/business-dashboard/*`
* **Hak Akses**: Owner, Manager

### Fitur-Fitur Dashboard Bisnis:
1. **Analitik per Cabang (`/admin/business-dashboard/cabang`)**
   - **Dashboard Finansial Multi-Cabang**: Memantau metrik finansial gabungan dan individual seluruh cabang: Total Omzet Kotor (*Gross Revenue*), Potongan Diskon, Total Net Omzet, Total Volume Pesanan, dan Rata-rata Nilai Transaksi (*AOV*).
   - **Grafik Komparasi Antar Cabang**: Visualisasi Bar Chart interaktif untuk membandingkan pendapatan dan volume order antar outlet secara berdampingan.
   - **Distribusi Jam Sibuk (*Peak Hours Analysis*)**: Analisis intensitas transaksi per jam (07:00 s.d. 23:00) pada setiap cabang untuk mengoptimalkan penempatan jadwal staf dan ketersediaan stok bahan.
   - **Menu Best-Seller per Cabang**: Mengetahui menu makanan/minuman yang paling laris pada masing-masing cabang tertentu.

2. **Analitik Produk (`/admin/business-dashboard/produk`)**
   - **Statistik Penjualan Produk**: Menampilkan total unit produk terjual (*Total Units Sold*), total omzet penjualan produk, serta produk bintang terlaris (*Top Star Product*).
   - **Analisis Profit & Marjin per Menu**: Menghitung secara otomatis Estimasi HPP / Food Cost, Total Laba Kotor Rupiah per Menu, dan Persentase Profit Margin (%) untuk setiap produk.
   - **Breakdown Penjualan per Kategori**: Doughnut Chart komposisi kontribusi penjualan berdasarkan kategori menu (Kopi, Non-Kopi, Makanan Berat, Cemilan/Pastry).
   - **Filter Tanggal & Kategori**: Opsi penyaringan data penjualan berdasarkan kategori menu dan rentang periode waktu tertentu.

3. **Analitik Pelanggan (`/admin/business-dashboard/pelanggan`)**
   - **Metrik Kesehatan Basis Pelanggan**: Menampilkan Total Basis Pelanggan Terdaftar, Pelanggan Baru dalam periode berjalan, *Customer Lifetime Value* (CLV / rata-rata akumulasi belanja per pelanggan), dan *Repeat Order Rate* (%).
   - **Proporsi Distribusi Membership Tier**: Visualisasi progres bar proporsi jumlah member pada tingkatan Bronze, Silver, Gold, dan Platinum.
   - **Segmentasi Retensi & Risiko Pelanggan (RFM)**: Mengelompokkan pelanggan ke dalam:
     - **Pelanggan Aktif / Loyal**: Pelanggan dengan frekuensi kunjungan rutin dan konsisten.
     - **Pelanggan Berisiko (*At-Risk Customers*)**: Pelanggan yang telah berkunjung $\ge 2$ kali namun tidak pernah kembali bertransaksi dalam $>45$ hari terakhir, dilengkapi rekomendasi strategi promosi *win-back CRM*.
   - **Peringkat 10 Pelanggan VIP Spenders**: Tabel 10 pelanggan dengan akumulasi belanja tertinggi dilengkapi badge medali (🥇 Emas, 🥈 Perak, 🥉 Perunggu), tier membership, frekuensi kunjungan, perolehan poin, total belanja, dan waktu kunjungan terakhir.

4. **Analitik Karyawan (`/admin/business-dashboard/karyawan`)**
   - **Disiplin & Produktivitas Tim**: Menampilkan total karyawan aktif, persentase kedisiplinan kehadiran tepat waktu (%), rata-rata skor KPI bulanan tim, serta distribusi grade evaluasi kinerja (Grade A, B, C, D).
   - **Sorotan Employee of the Month**: Banner penghargaan otomatis bagi staf dengan perolehan skor KPI tertinggi pada periode bulan tersebut.
   - **Peringkat Kinerja Tim Lengkap**: Tabel evaluasi menyeluruh per karyawan mencakup cabang outlet penugasan, total hari kehadiran, persentase ketepatan waktu, skor KPI bulanan, grade nilai, dan status kedisiplinan kerja.
   - **Filter Periode Bulan & Tahun**: Kemudahan memilih bulan dan tahun evaluasi performa kerja karyawan.

---

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