# PRODUCT REQUIREMENTS DOCUMENT (PRD)
## PRELOVED — Platform Jual Beli Barang Bekas

**Versi:** 1.0  
**Jenis Produk:** Website Marketplace  
**Target Pengguna:** Customer dan Seller  
**Platform:** Website  
**Tujuan:** Mempermudah transaksi jual beli barang bekas atau preloved secara online.

---

## 1. Latar Belakang

Preloved merupakan website marketplace yang menyediakan tempat bagi pengguna untuk menjual dan membeli barang bekas yang masih layak digunakan. Platform ini dirancang agar pengguna dapat melakukan aktivitas jual beli dalam satu akun tanpa harus membuat akun terpisah sebagai pembeli dan penjual.

Konsep sistem mengadaptasi marketplace seperti Shopee, yaitu pengguna dapat menjelajahi produk, memasukkan barang ke keranjang, melakukan checkout, serta mengelola toko sendiri melalui fitur **Toko Saya**.

Dengan demikian, Preloved diharapkan dapat menjadi platform jual beli barang preloved yang praktis, mudah digunakan, dan memiliki pengelolaan transaksi yang terintegrasi.

## 2. Tujuan Produk

1. Menyediakan platform jual beli barang bekas secara online.
2. Memungkinkan pengguna menjadi customer sekaligus seller menggunakan satu akun.
3. Memudahkan seller mengelola toko dan produk yang dijual.
4. Memudahkan customer mencari, memilih, dan membeli produk.
5. Menyediakan pengelolaan pesanan dan transaksi dalam satu sistem.
6. Menerapkan konsep sistem marketplace dengan pembagian hak akses berdasarkan aktivitas pengguna.

## 3. Target Pengguna

### 3.1 Customer
Pengguna yang ingin mencari dan membeli barang preloved.

Kebutuhan:
- Melihat daftar produk.
- Mencari produk berdasarkan nama atau kategori.
- Melihat detail dan kondisi barang.
- Menambahkan produk ke keranjang.
- Melakukan checkout.
- Melihat status pesanan.
- Melihat riwayat pembelian.

### 3.2 Seller
Pengguna yang ingin menjual barang preloved.

Kebutuhan:
- Membuat dan mengelola toko.
- Menambahkan produk.
- Mengubah informasi produk.
- Menghapus atau menonaktifkan produk.
- Mengelola stok.
- Melihat pesanan masuk.
- Memperbarui status pesanan.
- Melihat daftar produk yang dijual.

**Catatan:** Customer dan seller menggunakan akun yang sama. Pengguna tidak perlu melakukan registrasi ulang ketika ingin mulai berjualan.

### 3.3 Admin
Pengelola sistem yang bertugas mengawasi aktivitas marketplace.

Kebutuhan:
- Melihat data pengguna.
- Mengelola produk.
- Mengawasi transaksi.
- Mengelola kategori produk.
- Menonaktifkan produk atau akun yang melanggar aturan.

## 4. Konsep Sistem dan Hak Akses

Preloved menggunakan konsep satu akun dengan dua aktivitas utama, yaitu membeli dan menjual.

| Fitur | Customer | Seller | Admin |
|---|---|---|---|
| Melihat produk | Ya | Ya | Ya |
| Mencari produk | Ya | Ya | Ya |
| Keranjang | Ya | Ya | Tidak wajib |
| Checkout | Ya | Ya | Tidak |
| Riwayat pembelian | Ya | Ya | Tidak |
| Toko Saya | Ya | Ya | Tidak |
| Mengelola produk toko | Tidak | Ya | Ya |
| Mengelola pesanan toko | Tidak | Ya | Ya |
| Mengelola pengguna | Tidak | Tidak | Ya |
| Mengelola kategori | Tidak | Tidak | Ya |

Seller tetap dapat berbelanja seperti customer. Customer juga dapat membuka toko tanpa membuat akun baru.

## 5. Fitur Utama

### 5.1 Splash Screen dan Loading
- Menampilkan logo dan nama Preloved.
- Menampilkan animasi loading ketika halaman sedang diproses.
- Memberikan transisi menuju halaman utama.

### 5.2 Registrasi dan Login
- Registrasi menggunakan nama, email, dan password.
- Login menggunakan email dan password.
- Validasi data saat registrasi dan login.
- Logout dari akun.
- Session pengguna untuk menjaga status login.
- Password disimpan dalam bentuk hash.

### 5.3 Beranda
- Menampilkan navigasi utama.
- Menampilkan banner promosi.
- Menampilkan kategori produk.
- Menampilkan produk terbaru.
- Menampilkan rekomendasi produk.
- Menyediakan pencarian produk.

### 5.4 Katalog dan Detail Produk
- Menampilkan foto produk.
- Menampilkan nama dan harga produk.
- Menampilkan kategori.
- Menampilkan kondisi barang.
- Menampilkan deskripsi produk.
- Menampilkan informasi toko penjual.
- Menampilkan stok produk.

### 5.5 Keranjang
- Menambahkan produk ke keranjang.
- Mengubah jumlah barang.
- Menghapus barang dari keranjang.
- Menghitung total harga.
- Memastikan stok tersedia sebelum checkout.

### 5.6 Checkout
- Menampilkan ringkasan produk.
- Mengisi alamat pengiriman.
- Memilih metode pembayaran yang tersedia.
- Menampilkan total pembayaran.
- Membuat pesanan setelah konfirmasi checkout.

Untuk tahap awal, pembayaran dapat menggunakan metode simulasi atau pembayaran manual karena integrasi payment gateway bukan kebutuhan wajib proyek.

### 5.7 Toko Saya
Fitur ini menjadi bagian utama dari sistem Preloved.

Menu yang tersedia:
- Dashboard toko.
- Informasi toko.
- Daftar produk saya.
- Tambah produk.
- Edit produk.
- Hapus produk.
- Manajemen stok.
- Pesanan masuk.
- Detail pesanan.
- Pembaruan status pesanan.

### 5.8 Pesanan Saya
- Melihat daftar pesanan.
- Melihat detail transaksi.
- Melihat status pesanan.
- Melihat riwayat pembelian.

### 5.9 Profil Pengguna
- Melihat informasi akun.
- Mengubah nama dan informasi profil.
- Mengelola alamat pengiriman.
- Mengakses menu Toko Saya.

### 5.10 Admin
- Dashboard admin.
- Manajemen pengguna.
- Manajemen produk.
- Manajemen kategori.
- Pemantauan transaksi.

## 6. Alur Penggunaan Sistem

### 6.1 Alur Customer
1. Pengguna membuka website.
2. Pengguna masuk atau melakukan registrasi.
3. Pengguna melihat produk di halaman beranda.
4. Pengguna memilih produk.
5. Pengguna melihat detail produk.
6. Pengguna menambahkan produk ke keranjang.
7. Pengguna melakukan checkout.
8. Sistem membuat pesanan.
9. Pengguna melihat status pesanan melalui menu Pesanan Saya.

### 6.2 Alur Seller
1. Pengguna login menggunakan akun yang sama.
2. Pengguna membuka menu Toko Saya.
3. Pengguna membuat informasi toko apabila belum memiliki toko.
4. Pengguna menambahkan produk.
5. Produk ditampilkan pada katalog marketplace.
6. Ketika ada pesanan, seller melihat pesanan masuk.
7. Seller memperbarui status pesanan.
8. Seller dapat mengelola produk dan stok kapan saja.

## 7. Kebutuhan Nonfungsional

- Tampilan responsif untuk desktop dan perangkat mobile.
- Navigasi mudah dipahami.
- Desain konsisten pada seluruh halaman.
- Validasi input pada setiap formulir.
- Password harus disimpan menggunakan hashing.
- Hak akses halaman harus diperiksa berdasarkan session dan kepemilikan data.
- Data transaksi harus tersimpan di database.
- Sistem harus mencegah pengguna mengubah produk atau pesanan milik pengguna lain.
- Gambar produk memiliki batas ukuran dan tipe file yang diperbolehkan.
- Tampilan loading digunakan ketika proses membutuhkan waktu.

## 8. Rancangan Antarmuka

Preloved menggunakan pendekatan desain marketplace modern dengan referensi pengalaman pengguna Shopee, tetapi tetap memiliki identitas visual sendiri.

Prinsip desain:
- Warna utama menggunakan kombinasi krem, putih, dan hijau sebagai identitas marketplace preloved.
- Navigasi utama dibuat sederhana.
- Produk ditampilkan menggunakan card.
- Foto produk menjadi elemen visual utama.
- Tombol aksi dibuat jelas.
- Dashboard seller memiliki sidebar atau navigasi khusus.
- Tampilan customer dan seller tetap berada dalam satu website dengan identitas desain yang konsisten.

## 9. Rancangan Data Utama

Entitas database yang dibutuhkan:

1. **users** — menyimpan informasi akun pengguna.
2. **shops** — menyimpan informasi toko milik pengguna.
3. **categories** — menyimpan kategori produk.
4. **products** — menyimpan informasi produk yang dijual.
5. **carts** — menyimpan keranjang pengguna.
6. **cart_items** — menyimpan detail barang dalam keranjang.
7. **orders** — menyimpan data pesanan.
8. **order_items** — menyimpan detail produk dalam pesanan.
9. **addresses** — menyimpan alamat pengiriman pengguna.

Relasi utama:
- Satu user dapat memiliki satu toko.
- Satu toko dapat memiliki banyak produk.
- Satu kategori dapat memiliki banyak produk.
- Satu user dapat memiliki banyak pesanan.
- Satu pesanan dapat memiliki banyak detail produk.
- Satu user dapat memiliki banyak alamat.

## 10. Prioritas Pengembangan

### Prioritas 1 — Wajib
- Splash/loading.
- Registrasi dan login.
- Beranda dan katalog produk.
- Detail produk.
- Satu akun untuk customer dan seller.
- Toko Saya.
- Tambah, edit, dan hapus produk.
- Keranjang.
- Checkout.
- Pesanan customer.
- Pesanan seller.

### Prioritas 2 — Pengembangan Tambahan
- Pencarian dan filter produk.
- Kategori produk.
- Profil dan alamat pengguna.
- Dashboard statistik toko.
- Notifikasi status pesanan.
- Foto profil dan foto toko.

### Prioritas 3 — Pengembangan Lanjutan
- Integrasi payment gateway.
- Fitur ulasan dan rating.
- Chat customer dan seller.
- Wishlist.
- Rekomendasi produk.

## 11. Kriteria Keberhasilan

Sistem dianggap memenuhi kebutuhan utama apabila:
1. Pengguna dapat melakukan registrasi dan login.
2. Pengguna dapat membeli produk menggunakan akun yang sama dengan akun seller.
3. Pengguna dapat membuat toko melalui menu Toko Saya.
4. Seller dapat menambahkan dan mengelola produk.
5. Produk seller dapat muncul pada katalog marketplace.
6. Customer dapat melakukan checkout.
7. Pesanan tersimpan dan dapat dilihat oleh customer maupun seller sesuai hak aksesnya.
8. Sistem dapat membedakan akses admin, customer, dan seller.
9. Sistem dapat digunakan melalui desktop maupun mobile.

## 12. Batasan Pengembangan

Pengembangan awal difokuskan pada proses utama marketplace, yaitu pengelolaan akun, toko, produk, keranjang, dan transaksi. Integrasi pembayaran otomatis, pengiriman ekspedisi, chat, serta fitur lanjutan lainnya dapat dikembangkan pada tahap berikutnya.

**Kesimpulan:** Preloved merupakan marketplace barang bekas yang menerapkan sistem satu akun untuk dua aktivitas, yaitu membeli dan menjual. Fitur Toko Saya menjadi sarana bagi pengguna untuk beralih dari aktivitas customer ke seller tanpa perlu membuat akun baru.