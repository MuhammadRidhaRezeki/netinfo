ada beberapa fitur yang tidak bekerja, pertama saya login sebagai admin sebagai Muhammad Ridha Rezeki, saat saya login menggunakan akun lain, seperti rausan, gatfan, ikhsan, dan atha semua tidak bisa langsung lari Kembali ke Muhammad Ridha Rezeki, dan halaman tetap masuk tanpa ada nya notifikasi bahwa  email salah/sandi salah/akun tidak ditemukan.

kemudian di bagian halaman dashboard, menu pencarian tidak pekerja ( tidak dapat menemukan tiker,pelanggan maupun invoice), selanjut nya pada menu profil/ dan pengaturan di halaman dashboard tidak dapat di klik/ di buka.


pada halaman data pelanggan saya menemukan hal ganjal, pertama menu pencarian tidak berjalan semestinya alias tidak berfungsi, begitu pula dengan filtering status pelanggan. selanjutnya pada fitur tambahkan pelanggan, setelah pelanggan di tambahkan data pelanggan tidak menambahkan pelanggan yang ditambahkan sebelumnya. kemudian pada field aksi saat melakukan perubahan paket layanan tidak berubah. saat admin ingin melakukan isolated pada pelanggan , status pelanggan masi tetap active, dan kamu harusnya menambahkan fitur baru untuk admin dapat melakukan perubahan dari isolated menjadi active dan ini hanya dapat di lakukan oleh admin begitu pula dengan tambahankan pelanggan hanya boleh admin.



halaman selanjutnya network nodes. fitur filtering yang menampilkan hanya active, hanya maintenance, hanya down tidak bekerja jadi hanya bisa tampilkan semua. selanjut nya saat admin atau teknisi melakukan tambahkan network node dan di simpan tampilan note nya masi sama seperti sebelum diubah, contoh sebelum di ubah ip 10.10.1.1 dan status active, diubah menjadi ip 192.168.102 dan status jadi maintenance dan di simpan tampilan data tetap sama seperti sebelum diubah, saat Kembali klik Kelola perubahan masi di simpan tapi hanya di form saja. Di rekapitulasi table node juga begitu setelah disimpan tidak berubah saat klik detail perubahan sebelumnya hanya di simpan di form, fitur tambahkan node baru juga tidak bisa di akses.




Halaman tiket gangguan fitur pencarian dan filtering tidak bekerja. untuk detail tiket sudah detail, kamu harus ingat bahwa saat ada tiket dari pelanggan teknisi dan admin mendapatkan notifkasi dan dapat melakukan perbaikan kepada pelanggan. fitur ekspor rekap tidak berjalan. untuk fitur Ketika ada problem di client admin memilih teknisi sudah mantap. tetapi saat klik tugaskan dan catat history tidak ada perubahan di tampilan asli hanya di form.



halaman selanjutnya billing \& invoice fitur filtering dan pencarian tidak berjalan, fitur ekspor excel tidak bisa, fitur generate invoice bulanan tidak berjalan.



tambahan, untuk halaman after login client dashboard hanya melihat total tagihan, tanggal tagihan keluar paket layanan yang digunakan. dan halaman 1 nya hanya untuk pengajuan komplen( helpcare) di helpcare ini pelanggan dapat melakukan tiket masalah yang langsung terkoneksi dengan akun admin serta teknisi di halaman tiket gangguan.



untuk akun teknisi hanya bole melihat di dashboard client aktif, tiket aktif, dan node operasional normal, kemudian antrean tiket terbaru, untuk data pelanggan teknisi dapat melihat tapi tidak dapat merubah 



untuk halaman network nodes teknisi dapat melakukan semua fitur



untuk halaman tiket gangguan teknisi dapat melihat tiket gangguan yang di tangani oleh nya dan detail keluhan serta dapat melakukan pembaruan Riwayat penanganan.




halaman billing dan invoice teknisi tidak dapat melihat nya



1 lagi, semua email yang di tambahkan dapat di loginkan dan semua itu berbeda beda tiap email contoh email admin 1 dan 2 sama, teknisi 1 \& 2 berbeda dari segi infomarsi nya, untuk client semua di sesuaikan dengan database nya misal client 1 paket layanan nya home 20mbps berarti billing nya 250k begitu juga lain nya.



ralat, untuk client cukup buat 3-5 user saya dapat login agar tidak memberatkan 



## BUG BARU



pada akun admin 

di halaman tiket gangguan tidak bisa klik detail error



error nya

ParseError

resources\\views\\tickets\\show.blade.php:127

syntax error, unexpected end of file, expecting "elseif" or "else" or "endif"



untuk halaman profil saya ingin melakukan sedikit perubahan, profil tetap ada dan biarkan, di pengaturan hapus saja soalnya sudah lengkap di profil saya.



pada akun teknisi juga saya pada halaman detail \& kerjakan terjadi error dengan kode yang sama seperti di atas



fitur tambahan di akun client, di halaman dashboard untuk bayar sekarang nanti kamu tampilkan pop up payment opsi nya ada 2 qr dan metode transfer ke seabank

\*Data terima transfer bank\*

Muhammad Ridha Rezeki

9981237810913



Untuk qr nanti aturkan saja saya nanti saya masukkan file jpg nya sendiri, ketik client klik qris langsung menampilkan jpg yang saya set

kemudian client unggah bukti yang fitur nya telah tersedia di dashboard



di client halamn helpdesk untuk tingkat periotas untuk warna klik high tapi medium tidak terhapus


di bagian data pelanggan dan billing & invoice tidak bisa scroll kesamping, harus scroll kebawah menggunakan scroll bar baru bisa

di admin bagian ticket penanganan, bagian detail, riwayat penanganan status sucses tidak terupdute jadi succes (di ticket penanganan statusnya resulved )

di bagian from penambahan pelanggan baru, dibagian nomor wa seharusS

nya tidak bisa isi huruf dan minimal pengisian angka 10 dan maksimal 15 



# di halaman login
saat input password harusnya ada fitur input passwrod bisa  milhat password yg di inputkan


di dashboard saat pilih paket, ngulang lgi saat isi data


di halaamn login clietn, untuk buka help care tidak usah ada buka 

di role client saat login jangan tulis costumer tulis user

di bagian helpcare di tiket nya masi muncul ini
<span class="animate-pulse h-1.5 w-1.5 rounded-full bg-current"></span>Open
