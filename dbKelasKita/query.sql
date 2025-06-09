-- Count Admin Users
SELECT COUNT(*) AS total_laporan
FROM tb_laporan l
LEFT JOIN tb_user u ON l.id_user = u.id_user;

SELECT COUNT(*) as total_users FROM tb_user WHERE status LIKE 'aktif';
SELECT COUNT(*) as total_users FROM tb_user WHERE status LIKE 'aktif' role LIKE 'mentor';
SELECT COUNT(*) as total_users FROM tb_user WHERE status LIKE 'aktif' AND role LIKE 'murid';
SELECT COUNT(*) as total_users FROM tb_user WHERE status LIKE 'non-atif';

-- Count Admin Kelas
SELECT COUNT(*) AS total_laporan
FROM tb_laporan l
LEFT JOIN tb_kelas k ON l.id_kelas = k.id_kelas;

SELECT COUNT(*) as total_users FROM tb_kelas WHERE status_publikasi LIKE 'aktif';

SELECT COUNT(*) as total_users FROM tb_kelas WHERE status_publikasi LIKE 'pending';

SELECT COUNT(*) as total_users FROM tb_kelas WHERE status_publikasi LIKE 'non-aktif';

-- Count Admin Materi
SELECT COUNT(*) as total_users FROM tb_materi;
SELECT COUNT(*) as total_users FROM tb_materi WHERE status LIKE 'aktif';
SELECT COUNT(*) as total_users FROM tb_materi WHERE status LIKE 'non-aktif';

SELECT COUNT(*) as total_users FROM tb_sub_materi;
SELECT COUNT(*) as total_users FROM tb_sub_materi WHERE status LIKE 'aktif';
SELECT COUNT(*) as total_users FROM tb_sub_materi WHERE status LIKE 'non-aktif';

SELECT COUNT(*) as total_users FROM tb_video;
SELECT COUNT(*) as total_users FROM tb_video WHERE status LIKE 'aktif';
SELECT COUNT(*) as total_users FROM tb_video WHERE status LIKE 'non-aktif';

SELECT COUNT(*) as total_users FROM tb_dokumen;
SELECT COUNT(*) as total_users FROM tb_dokumen WHERE status LIKE 'aktif';
SELECT COUNT(*) as total_users FROM tb_dokumen WHERE status LIKE 'non-aktif';

SELECT COUNT(*) as total_users FROM tb_transaksi;
SELECT COUNT(*) as total_users FROM tb_transaksi WHERE status LIKE 'pending';
SELECT COUNT(*) as total_users FROM tb_transaksi WHERE status LIKE 'acc';

-- Kelola Laporan

-- Count total laporan
SELECT
  COUNT(*)
FROM
  tb_laporan r
JOIN
  tb_user u ON u.id_user = r.id_user
JOIN
  tb_kelas k ON k.id_kelas = r.id_kelas;

-- Count laporan berdasarkan kategori ('Penggunaan kata kasar')
SELECT
  r.id_report,
  u.username,
  k.nama_kelas,
  r.kategori_report,
  r.keterangan_report,
  r.tgl_dibuat
FROM
  tb_laporan r
JOIN
  tb_user u ON u.id_user = r.id_user
JOIN
  tb_kelas k ON k.id_kelas = r.id_kelas
WHERE
  r.kategori_report LIKE 'Penggunaan kata kasar';
  ORDER BY r.tgl_dibuat DESC;

-- Count laporan berdasarkan kategori ('Materi tidak relevan')
SELECT
  r.id_report,
  u.username,
  k.nama_kelas,
  r.kategori_report,
  r.keterangan_report,
  r.tgl_dibuat
FROM
  tb_laporan r
JOIN
  tb_user u ON u.id_user = r.id_user
JOIN
  tb_kelas k ON k.id_kelas = r.id_kelas
WHERE
  r.kategori_report LIKE 'Materi tidak relevan';

-- Count laporan berdasarkan kategori ('Pornografi')
SELECT
  r.id_report,
  u.username,
  k.nama_kelas,
  r.kategori_report,
  r.keterangan_report,
  r.tgl_dibuat
FROM
  tb_laporan r
JOIN
  tb_user u ON u.id_user = r.id_user
JOIN
  tb_kelas k ON k.id_kelas = r.id_kelas
WHERE
  r.kategori_report LIKE 'Pornografi';