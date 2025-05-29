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