# Design Documentation
## Overview
Website Cafe & Restaurant menggunakan konsep desain yang modern, clean, warm, friendly, dan professional.
Desain dibuat dengan fokus pada:
- Tampilan yang bersih dan nyaman
- Warna yang memberikan kesan cafe yang hangat
- Layout yang sederhana dan mudah digunakan
- Komponen dengan rounded corner
- Spacing yang cukup agar tampilan tidak terlalu padat
- Visual menu yang menjadi salah satu elemen utama
- Responsive design untuk desktop, tablet, dan mobile

# Colors
## Brand Colors
### Primary Green
` #2E5E4E`
Digunakan sebagai warna utama website.
Digunakan pada:
- Primary button
- Active navigation
- CTA
- Icon tertentu
- Highlight
- Elemen penting website

### Dark Green
` #234839`
Digunakan sebagai warna pendukung primary green.
Digunakan pada:
- Heading tertentu
- Hover state
- Footer
- Elemen dengan emphasis tinggi

### Warm Accent
` #F59E0B`
Digunakan sebagai warna aksen untuk memberikan kesan hangat dan menarik perhatian.
Digunakan pada:
- Best Seller
- Rating
- Highlight
- Promo tertentu
- Icon atau elemen dekoratif

## Surface Colors
### White
` #FFFFFF`
Digunakan sebagai warna utama untuk:
- Card
- Input
- Navigation
- Content area
- Background section tertentu

### Warm White
` #FCFBF8`
Digunakan sebagai background utama website agar tampilan terasa lebih hangat dibandingkan putih murni.

### Cream
` #F7F4ED`
Digunakan sebagai secondary background.
Digunakan pada:
- Section background
- Highlight section
- Background card tertentu
- Area yang membutuhkan pemisahan visual

## Text Colors
### Dark Text
` #1F2937`
Digunakan untuk:
- Heading
- Body text
- Navigation
- Menu name
- Informasi penting

### Secondary Text
` #64748B`
Digunakan untuk:
- Description
- Caption
- Metadata
- Informasi tambahan
- Placeholder

## Border Color
### Light Border
` #E5E7EB`
Digunakan untuk:
- Input border
- Card border
- Table border
- Divider
- Form element

# Typography
## Font Family
Font utama menggunakan:
**Poppins**
Poppins digunakan karena memiliki bentuk yang modern, clean, dan mudah dibaca.

## Typography Hierarchy
### Display
Digunakan untuk:
- Hero heading
- Judul utama halaman

Karakteristik:
- Ukuran besar
- Bold
- Strong visual hierarchy

### Heading
Digunakan untuk:
- Section title
- Page title
- Card title

Karakteristik:
- Bold atau semi-bold
- Warna dark text
- Spacing yang cukup

### Body
Digunakan untuk:
- Description
- Informasi menu
- Paragraph
- Content

Karakteristik:
- Regular
- Mudah dibaca
- Line-height nyaman

### Caption
Digunakan untuk:
- Rating
- Preparation time
- Metadata
- Informasi tambahan

Karakteristik:
- Ukuran lebih kecil
- Warna secondary text

# Layout
## General Layout
Website menggunakan layout yang:
- Clean
- Spacious
- Responsive
- Terstruktur
- Tidak terlalu padat
Content memiliki maximum width agar tampilan tetap rapi pada layar besar.

## Container
Content menggunakan container dengan:
- Maximum width
- Margin auto
- Padding horizontal
- Responsive spacing

Container digunakan pada:
- Navbar
- Hero
- Menu
- About
- Facilities
- Contact
- Footer
- Admin content

## Spacing
Sistem spacing menggunakan jarak yang konsisten.

### Small
Digunakan untuk:
- Icon dan text
- Label dan input
- Element dalam card

### Medium
Digunakan untuk:
- Antar content
- Card padding
- Button padding

### Large
Digunakan untuk:
- Antar section
- Hero spacing
- Page section
Whitespace digunakan agar setiap bagian website terasa lebih lega dan mudah dibaca.

# Border Radius
Website menggunakan rounded corner sebagai salah satu karakteristik utama desain.

### Button
Rounded / Pill

### Card
`18px - 24px`

### Input
`12px - 18px`

### Modal
`18px`

### Badge
Pill shape

### Image
Rounded corner
Rounded corner memberikan kesan:
**Modern + Friendly + Soft**

# Shadow
Shadow digunakan secara ringan agar elemen tetap terlihat clean dan tidak terlalu berat.
Konsep shadow:
box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
Digunakan pada:
- Card
- Menu card
- Modal
- Dropdown
- Floating element
- Navbar tertentu

# Buttons
## Primary Button
Menggunakan warna Primary Green.
Digunakan untuk:
- Pesan Sekarang
- Tambah ke Keranjang
- Checkout
- Simpan
- Submit
- CTA utama

Karakteristik:
- Background Primary Green
- Text putih
- Rounded / Pill
- Padding cukup besar
- Font semi-bold
- Hover menggunakan Dark Green

## Secondary Button
Digunakan untuk aksi tambahan.
Karakteristik:
- Background transparan atau putih
- Border Primary Green
- Text Primary Green
- Rounded
- Hover menggunakan warna hijau muda

## Danger Button
Digunakan untuk aksi yang bersifat berisiko.
Contoh:
- Hapus
- Batalkan
- Remove
Menggunakan warna merah sebagai indikator danger.

# Navigation
Navbar menggunakan desain yang clean dan sederhana.
Karakteristik:
- Background putih atau Warm White
- Logo di sisi kiri
- Navigation menu di tengah atau kanan
- Tombol aksi di sisi kanan
- Rounded element
- Spacing antar menu cukup
- Responsive pada mobile

## Active Navigation
Menu yang sedang aktif menggunakan:
- Primary Green
- Font lebih tebal
- Indicator atau underline jika diperlukan

# Hero Section
Hero merupakan bagian visual utama pada halaman Home.
Karakteristik:
- Ukuran besar
- Visual makanan atau suasana cafe
- Heading besar
- Deskripsi singkat
- CTA utama
- Background yang warm
- Layout yang tidak terlalu padat
Hero digunakan untuk memberikan kesan pertama terhadap website.

# Menu Card
Menu card menjadi salah satu komponen utama website.
Setiap card dapat menampilkan:

- Foto menu
- Nama menu
- Deskripsi singkat
- Harga
- Rating
- Kategori
- Badge
- Favorite button
- Add to cart button

Karakteristik:
- Rounded corner
- Image dengan aspect ratio konsisten
- Background putih
- Shadow ringan
- Spacing yang cukup
- Hover effect sederhana

# Menu Detail
Halaman detail menu menampilkan informasi menu secara lebih lengkap.
Informasi yang dapat ditampilkan:
- Foto menu
- Nama menu
- Harga
- Deskripsi
- Rating
- Preparation time
- Calories
- Allergen
- Variant
- Add-on
- Quantity
- Favorite
- Add to cart
Layout menggunakan kombinasi image dan informasi menu.

# Favorite
Fitur favorite menggunakan icon hati.
Karakteristik:
- Berada pada menu card atau menu detail
- Mudah ditemukan
- Menggunakan accent color ketika aktif
- Tidak mengganggu informasi utama

# Search
Search digunakan untuk membantu pengguna menemukan menu.
Karakteristik:
- Input rounded
- Icon search
- Border ringan
- Placeholder menggunakan Secondary Text
- Ukuran mudah digunakan pada mobile

Search dapat ditempatkan pada:
- Menu page
- Navbar
- Menu section

# Badge
Badge digunakan untuk memberikan informasi singkat.
Contoh:
- Best Seller
- New
- Promo
- Popular
- Available

Karakteristik:
- Bentuk pill
- Ukuran kecil
- Font semi-bold
- Warna disesuaikan dengan jenis informasi

# Cart
Cart digunakan untuk menampilkan menu yang dipilih oleh customer.
Informasi yang ditampilkan:
- Foto menu
- Nama menu
- Harga
- Quantity
- Subtotal
- Remove button
- Total pembayaran
Layout dibuat sederhana agar pengguna mudah mengubah pesanan.

# Checkout
Checkout merupakan halaman untuk menyelesaikan pesanan.
Informasi yang ditampilkan:
- Data customer
- Detail pesanan
- Jenis pesanan
- Nomor meja jika Dine In
- Metode pembayaran
- Subtotal
- Tax
- Service
- Total
CTA utama menggunakan Primary Green.

# Order Tracking
Order tracking digunakan untuk menunjukkan status pesanan.
Status yang dapat ditampilkan:
- Pending
- Accepted
- Processing
- Ready
- Completed
- Cancelled
Status menggunakan badge dengan visual berbeda agar mudah dibedakan.

# QR Ordering
QR Ordering digunakan untuk pemesanan melalui QR Code.
Customer dapat:
- Scan QR Code
- Membuka menu
- Memilih makanan
- Melakukan checkout
- Melihat status pesanan
Tampilan QR Ordering dibuat sederhana dan mobile friendly.

# Facilities
Section fasilitas digunakan untuk menampilkan fasilitas cafe.
Contoh:
- WiFi
- Parking
- Air Conditioning
- Outdoor Area
- Indoor Area
- Meeting Room
Setiap fasilitas dapat menggunakan icon dan deskripsi singkat.

# About
Halaman About digunakan untuk memperkenalkan cafe.
Informasi dapat berupa:
- Sejarah cafe
- Konsep cafe
- Nilai atau filosofi
- Informasi singkat perusahaan
Desain menggunakan kombinasi text dan image.

# Contact
Halaman Contact digunakan untuk memberikan informasi kontak.
Informasi yang dapat ditampilkan:
- Alamat
- Nomor telepon
- Email
- Social media
- Jam operasional
- Google Maps
Layout dibuat sederhana agar informasi mudah ditemukan.

# Footer
Footer menggunakan warna Dark Green atau warna gelap yang sesuai dengan identitas brand.
Informasi dapat berisi:
- Logo
- Deskripsi singkat
- Navigation
- Contact
- Social media
- Copyright
Text menggunakan warna putih atau warna dengan contrast yang cukup.

# Admin Design
Admin menggunakan desain yang lebih functional dan professional.
Fokus desain admin:
- Data management
- Readability
- Efficiency
- Clear navigation
- Table readability
- Form usability

# Admin Colors
## Admin Primary
` #1F2937`
Digunakan untuk:
- Sidebar
- Heading
- Navigation
- Admin header

## Admin Background
` #F8FAFC`
Digunakan sebagai background utama dashboard admin.

## Success
` #198754`
Digunakan untuk:
- Success status
- Available
- Completed
- Active

## Warning
` #F59E0B`
Digunakan untuk:
- Pending
- Warning
- Attention

## Danger
` #DC3545`
Digunakan untuk:
- Delete
- Cancelled
- Error
- Failed

# Dashboard
Dashboard admin menampilkan informasi penting dalam bentuk summary card.
Contoh informasi:
- Total Revenue
- Total Orders
- Total Customers
- Total Menu
- Pending Orders
- Completed Orders

Dashboard juga dapat menampilkan:
- Chart
- Recent Orders
- Best Seller
- Order Statistics

# POS
POS menggunakan layout yang fokus pada kecepatan transaksi.
Bagian utama:
- Menu list
- Category filter
- Search
- Cart
- Customer information
- Payment
- Total
Layout dibuat agar cashier dapat melakukan transaksi dengan cepat.

# Kitchen Display
Kitchen menggunakan tampilan yang sederhana dan mudah dibaca.
Setiap order dapat menampilkan:
- Order number
- Customer
- Table
- Ordered menu
- Quantity
- Notes
- Order time
- Status
Status order ditampilkan secara jelas.

# Tables
Table digunakan untuk menampilkan data dalam jumlah banyak.
Karakteristik:
- Header jelas
- Row spacing cukup
- Border ringan
- Action button mudah ditemukan
- Responsive pada layar kecil

Action dapat berupa:
- View
- Edit
- Delete
- Detail

# Forms
Form menggunakan desain yang sederhana dan mudah dipahami.
Komponen:
- Label
- Input
- Select
- Textarea
- Checkbox
- Radio button
- File upload
- Submit button
Setiap field memiliki label yang jelas.

# Status
Status menggunakan badge agar pengguna dapat mengenali kondisi data dengan cepat.
Contoh:
- Pending
- Processing
- Ready
- Completed
- Active
- Inactive
- Cancelled

# Modal
Modal digunakan untuk:
- Confirmation
- Detail
- Edit
- Delete confirmation
- Informasi

Karakteristik:
- Rounded corner
- Shadow ringan
- Background putih
- Overlay
- Padding cukup

# Responsive Design
Website harus dapat digunakan pada:
- Desktop
- Laptop
- Tablet
- Mobile

## Desktop
Menggunakan layout yang lebih luas dengan beberapa column.

## Tablet
Column dikurangi dan spacing disesuaikan.

## Mobile
Layout berubah menjadi satu column.
Navbar dapat berubah menjadi mobile menu.
Button dibuat lebih mudah ditekan.
Card menyesuaikan ukuran layar.
Table dapat menggunakan horizontal scroll jika diperlukan.

# Image Style
Image digunakan sebagai salah satu elemen visual utama website.
Karakteristik:
- Aspect ratio konsisten
- Rounded corner
- Object-fit cover
- High quality
- Tidak terlalu banyak menggunakan gambar dekoratif

Image digunakan pada:
- Hero
- Menu
- About
- Facilities
- Gallery
- Profile

# Icon
Icon digunakan sebagai pendukung visual.
Digunakan pada:
- Navigation
- Button
- Feature
- Menu
- Favorite
- Cart
- User
- Search
- Dashboard
Icon tidak digunakan secara berlebihan agar tampilan tetap clean.

# Hover State
Interactive element memiliki hover state.
Contoh:
- Button berubah warna
- Card sedikit terangkat
- Image memiliki efek ringan
- Navigation berubah warna
- Icon berubah warna
Hover effect dibuat sederhana dan tidak berlebihan.

# Animation
Animation digunakan secara minimal.
Contoh:
- Fade in
- Hover transition
- Button transition
- Card hover
- Modal animation
Durasi animation dibuat singkat agar website tetap terasa cepat.

# Accessibility
Desain memperhatikan kemudahan penggunaan.
Beberapa hal yang diperhatikan:
- Text memiliki contrast yang cukup
- Button mudah dikenali
- Input memiliki label
- Icon tidak menjadi satu-satunya indikator
- Ukuran text mudah dibaca
- Interactive element mudah digunakan

# Design Principles
Desain website mengikuti beberapa prinsip utama:
- Clean
- Modern
- Warm
- Friendly
- Professional
- Simple
- Consistent
- Responsive
- User Friendly

# Overall Visual Style
Secara keseluruhan, website Cafe & Restaurant menggunakan perpaduan warna hijau, putih, cream, dan warm accent.
Visual utama:
- Green sebagai identitas brand
- Cream sebagai background pendukung
- White sebagai surface
- Warm accent sebagai highlight
- Rounded corner sebagai karakteristik UI
- Shadow ringan untuk depth
- Foto makanan sebagai visual utama
- Typography Poppins sebagai font utama

# Design Summary
| Element | Style |
| Primary Color | ` #2E5E4E` |
| Dark Color | ` #234839` |
| Accent | ` #F59E0B` |
| Background | ` #FCFBF8` |
| Secondary Background | ` #F7F4ED` |
| Text | ` #1F2937` |
| Secondary Text | ` #64748B` |
| Border | ` #E5E7EB` |
| Font | Poppins |
| Card Radius | `18px - 24px` |
| Input Radius | `12px - 18px` |
| Button | Rounded / Pill |
| Shadow | Soft |
| Style | Modern, Clean, Warm |
| Responsive | Desktop, Tablet, Mobile |