SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/* -- DUMMY DATA FOR tb_user -- */
INSERT INTO `tb_user` (`id_user`, `first_name`, `last_name`, `username`, `password`, `role`, `status`, `deskripsi`, `fotoProfil`, `bahasa`, `zona_waktu`, `balasan_ke_komentar`, `komentar_baru`, `notifikasi_postingan_baru`, `email`, `instagram`, `twitter`, `linkdin`, `github`, `tgl_dibuat`) VALUES
(23, 'DummyF23', 'DummyL23', 'dummy_user_23', '$2y$10$J2dkelDrLXK4l0Pt8IwE0Y', 'admin', 'aktif', 'Deskripsi dummy user 23.', 'profile_23.jpg', 'Inggris', 'London', 0, 1, 1, 'dummy_email_23@example.com', 'insta_user23', 'twitter_user23', 'linkedin_user23', 'github_user23', '2025-05-22 16:38:10');
INSERT INTO `tb_user` (`id_user`, `first_name`, `last_name`, `username`, `password`, `role`, `status`, `deskripsi`, `fotoProfil`, `bahasa`, `zona_waktu`, `balasan_ke_komentar`, `komentar_baru`, `notifikasi_postingan_baru`, `email`, `instagram`, `twitter`, `linkdin`, `github`, `tgl_dibuat`) VALUES
(24, 'DummyF24', 'DummyL24', 'dummy_user_24', '$2y$10$hfGT8d5SQF05loCWi1PFkE', 'murid', 'aktif', 'Deskripsi dummy user 24.', 'profile_24.jpg', 'Bahasa Indonesia', 'Jakarta', 0, 0, 0, 'dummy_email_24@example.com', 'insta_user24', 'twitter_user24', 'linkedin_user24', 'github_user24', '2025-03-17 16:38:10');
INSERT INTO `tb_user` (`id_user`, `first_name`, `last_name`, `username`, `password`, `role`, `status`, `deskripsi`, `fotoProfil`, `bahasa`, `zona_waktu`, `balasan_ke_komentar`, `komentar_baru`, `notifikasi_postingan_baru`, `email`, `instagram`, `twitter`, `linkdin`, `github`, `tgl_dibuat`) VALUES
(25, 'DummyF25', 'DummyL25', 'dummy_user_25', '$2y$10$XbV2l11UWBgKxpFHangk8W', 'murid', 'aktif', 'Deskripsi dummy user 25.', 'profile_25.jpg', 'Jepang', 'Jakarta', 1, 1, 1, 'dummy_email_25@example.com', 'insta_user25', 'twitter_user25', 'linkedin_user25', 'github_user25', '2024-12-31 16:38:10');
INSERT INTO `tb_user` (`id_user`, `first_name`, `last_name`, `username`, `password`, `role`, `status`, `deskripsi`, `fotoProfil`, `bahasa`, `zona_waktu`, `balasan_ke_komentar`, `komentar_baru`, `notifikasi_postingan_baru`, `email`, `instagram`, `twitter`, `linkdin`, `github`, `tgl_dibuat`) VALUES
(26, 'DummyF26', 'DummyL26', 'dummy_user_26', '$2y$10$8zLvSWTVGtE3OI9dkiUPIn', 'mentor', 'aktif', 'Deskripsi dummy user 26.', 'profile_26.jpg', 'Jepang', 'Jakarta', 0, 0, 0, 'dummy_email_26@example.com', 'insta_user26', 'twitter_user26', 'linkedin_user26', 'github_user26', '2025-02-17 16:38:10');
INSERT INTO `tb_user` (`id_user`, `first_name`, `last_name`, `username`, `password`, `role`, `status`, `deskripsi`, `fotoProfil`, `bahasa`, `zona_waktu`, `balasan_ke_komentar`, `komentar_baru`, `notifikasi_postingan_baru`, `email`, `instagram`, `twitter`, `linkdin`, `github`, `tgl_dibuat`) VALUES
(27, 'DummyF27', 'DummyL27', 'dummy_user_27', '$2y$10$jCxJEluRZS9u8ptY1M5Y5L', 'admin', 'non-aktif', 'Deskripsi dummy user 27.', 'profile_27.jpg', 'Bahasa Indonesia', 'Jakarta', 0, 1, 0, 'dummy_email_27@example.com', 'insta_user27', 'twitter_user27', 'linkedin_user27', 'github_user27', '2025-04-03 16:38:10');
INSERT INTO `tb_user` (`id_user`, `first_name`, `last_name`, `username`, `password`, `role`, `status`, `deskripsi`, `fotoProfil`, `bahasa`, `zona_waktu`, `balasan_ke_komentar`, `komentar_baru`, `notifikasi_postingan_baru`, `email`, `instagram`, `twitter`, `linkdin`, `github`, `tgl_dibuat`) VALUES
(28, 'DummyF28', 'DummyL28', 'dummy_user_28', '$2y$10$EOFRfHYQ2fQo8bH9XGNwkb', 'murid', 'non-aktif', 'Deskripsi dummy user 28.', 'profile_28.jpg', 'Inggris', 'London', 0, 1, 0, 'dummy_email_28@example.com', 'insta_user28', 'twitter_user28', 'linkedin_user28', 'github_user28', '2025-02-08 16:38:10');
INSERT INTO `tb_user` (`id_user`, `first_name`, `last_name`, `username`, `password`, `role`, `status`, `deskripsi`, `fotoProfil`, `bahasa`, `zona_waktu`, `balasan_ke_komentar`, `komentar_baru`, `notifikasi_postingan_baru`, `email`, `instagram`, `twitter`, `linkdin`, `github`, `tgl_dibuat`) VALUES
(29, 'DummyF29', 'DummyL29', 'dummy_user_29', '$2y$10$sC77erBAVnt0DDpM2hrTv0', 'admin', 'non-aktif', 'Deskripsi dummy user 29.', 'profile_29.jpg', 'Jepang', 'Tokyo', 1, 1, 0, 'dummy_email_29@example.com', 'insta_user29', 'twitter_user29', 'linkedin_user29', 'github_user29', '2024-10-06 16:38:10');
INSERT INTO `tb_user` (`id_user`, `first_name`, `last_name`, `username`, `password`, `role`, `status`, `deskripsi`, `fotoProfil`, `bahasa`, `zona_waktu`, `balasan_ke_komentar`, `komentar_baru`, `notifikasi_postingan_baru`, `email`, `instagram`, `twitter`, `linkdin`, `github`, `tgl_dibuat`) VALUES
(30, 'DummyF30', 'DummyL30', 'dummy_user_30', '$2y$10$KjjlDwN2pdpAnsICLOxhVv', 'murid', 'non-aktif', 'Deskripsi dummy user 30.', 'profile_30.jpg', 'Inggris', 'Tokyo', 1, 0, 1, 'dummy_email_30@example.com', 'insta_user30', 'twitter_user30', 'linkedin_user30', 'github_user30', '2024-07-12 16:38:10');
INSERT INTO `tb_user` (`id_user`, `first_name`, `last_name`, `username`, `password`, `role`, `status`, `deskripsi`, `fotoProfil`, `bahasa`, `zona_waktu`, `balasan_ke_komentar`, `komentar_baru`, `notifikasi_postingan_baru`, `email`, `instagram`, `twitter`, `linkdin`, `github`, `tgl_dibuat`) VALUES
(31, 'DummyF31', 'DummyL31', 'dummy_user_31', '$2y$10$eXOrthok8GrTyyqhYEjlnU', 'admin', 'aktif', 'Deskripsi dummy user 31.', 'profile_31.jpg', 'Inggris', 'Jakarta', 1, 0, 1, 'dummy_email_31@example.com', 'insta_user31', 'twitter_user31', 'linkedin_user31', 'github_user31', '2024-09-16 16:38:10');
INSERT INTO `tb_user` (`id_user`, `first_name`, `last_name`, `username`, `password`, `role`, `status`, `deskripsi`, `fotoProfil`, `bahasa`, `zona_waktu`, `balasan_ke_komentar`, `komentar_baru`, `notifikasi_postingan_baru`, `email`, `instagram`, `twitter`, `linkdin`, `github`, `tgl_dibuat`) VALUES
(32, 'DummyF32', 'DummyL32', 'dummy_user_32', '$2y$10$477RUjaXGkghmpVGC3iyvY', 'admin', 'aktif', 'Deskripsi dummy user 32.', 'profile_32.jpg', 'Bahasa Indonesia', 'Tokyo', 0, 1, 0, 'dummy_email_32@example.com', 'insta_user32', 'twitter_user32', 'linkedin_user32', 'github_user32', '2025-05-05 16:38:10');
INSERT INTO `tb_user` (`id_user`, `first_name`, `last_name`, `username`, `password`, `role`, `status`, `deskripsi`, `fotoProfil`, `bahasa`, `zona_waktu`, `balasan_ke_komentar`, `komentar_baru`, `notifikasi_postingan_baru`, `email`, `instagram`, `twitter`, `linkdin`, `github`, `tgl_dibuat`) VALUES
(33, 'DummyF33', 'DummyL33', 'dummy_user_33', '$2y$10$aRZRElV6XDjov1ifMfn3py', 'murid', 'non-aktif', 'Deskripsi dummy user 33.', 'profile_33.jpg', 'Inggris', 'Tokyo', 0, 0, 0, 'dummy_email_33@example.com', 'insta_user33', 'twitter_user33', 'linkedin_user33', 'github_user33', '2025-01-24 16:38:10');
INSERT INTO `tb_user` (`id_user`, `first_name`, `last_name`, `username`, `password`, `role`, `status`, `deskripsi`, `fotoProfil`, `bahasa`, `zona_waktu`, `balasan_ke_komentar`, `komentar_baru`, `notifikasi_postingan_baru`, `email`, `instagram`, `twitter`, `linkdin`, `github`, `tgl_dibuat`) VALUES
(34, 'DummyF34', 'DummyL34', 'dummy_user_34', '$2y$10$ODsOguIL0boyPosnuEagAh', 'admin', 'non-aktif', 'Deskripsi dummy user 34.', 'profile_34.jpg', 'Bahasa Indonesia', 'London', 1, 0, 1, 'dummy_email_34@example.com', 'insta_user34', 'twitter_user34', 'linkedin_user34', 'github_user34', '2025-05-25 16:38:10');
INSERT INTO `tb_user` (`id_user`, `first_name`, `last_name`, `username`, `password`, `role`, `status`, `deskripsi`, `fotoProfil`, `bahasa`, `zona_waktu`, `balasan_ke_komentar`, `komentar_baru`, `notifikasi_postingan_baru`, `email`, `instagram`, `twitter`, `linkdin`, `github`, `tgl_dibuat`) VALUES
(35, 'DummyF35', 'DummyL35', 'dummy_user_35', '$2y$10$xZQ5XkQvnD5GnAiJdlTIzi', 'mentor', 'aktif', 'Deskripsi dummy user 35.', 'profile_35.jpg', 'Jepang', 'London', 0, 1, 1, 'dummy_email_35@example.com', 'insta_user35', 'twitter_user35', 'linkedin_user35', 'github_user35', '2024-11-07 16:38:10');
INSERT INTO `tb_user` (`id_user`, `first_name`, `last_name`, `username`, `password`, `role`, `status`, `deskripsi`, `fotoProfil`, `bahasa`, `zona_waktu`, `balasan_ke_komentar`, `komentar_baru`, `notifikasi_postingan_baru`, `email`, `instagram`, `twitter`, `linkdin`, `github`, `tgl_dibuat`) VALUES
(36, 'DummyF36', 'DummyL36', 'dummy_user_36', '$2y$10$HmHYi9fVx0q2IrAd4yn7hS', 'admin', 'aktif', 'Deskripsi dummy user 36.', 'profile_36.jpg', 'Jepang', 'London', 1, 0, 0, 'dummy_email_36@example.com', 'insta_user36', 'twitter_user36', 'linkedin_user36', 'github_user36', '2025-03-16 16:38:10');
INSERT INTO `tb_user` (`id_user`, `first_name`, `last_name`, `username`, `password`, `role`, `status`, `deskripsi`, `fotoProfil`, `bahasa`, `zona_waktu`, `balasan_ke_komentar`, `komentar_baru`, `notifikasi_postingan_baru`, `email`, `instagram`, `twitter`, `linkdin`, `github`, `tgl_dibuat`) VALUES
(37, 'DummyF37', 'DummyL37', 'dummy_user_37', '$2y$10$EQmYOr3uG78PzNKUrfnlVC', 'admin', 'aktif', 'Deskripsi dummy user 37.', 'profile_37.jpg', 'Jepang', 'London', 0, 0, 0, 'dummy_email_37@example.com', 'insta_user37', 'twitter_user37', 'linkedin_user37', 'github_user37', '2024-06-17 16:38:10');
INSERT INTO `tb_user` (`id_user`, `first_name`, `last_name`, `username`, `password`, `role`, `status`, `deskripsi`, `fotoProfil`, `bahasa`, `zona_waktu`, `balasan_ke_komentar`, `komentar_baru`, `notifikasi_postingan_baru`, `email`, `instagram`, `twitter`, `linkdin`, `github`, `tgl_dibuat`) VALUES
(38, 'DummyF38', 'DummyL38', 'dummy_user_38', '$2y$10$MUvHRdicuxxDvQhNdAvCTY', 'mentor', 'aktif', 'Deskripsi dummy user 38.', 'profile_38.jpg', 'Jepang', 'Tokyo', 0, 0, 1, 'dummy_email_38@example.com', 'insta_user38', 'twitter_user38', 'linkedin_user38', 'github_user38', '2024-09-17 16:38:10');
INSERT INTO `tb_user` (`id_user`, `first_name`, `last_name`, `username`, `password`, `role`, `status`, `deskripsi`, `fotoProfil`, `bahasa`, `zona_waktu`, `balasan_ke_komentar`, `komentar_baru`, `notifikasi_postingan_baru`, `email`, `instagram`, `twitter`, `linkdin`, `github`, `tgl_dibuat`) VALUES
(39, 'DummyF39', 'DummyL39', 'dummy_user_39', '$2y$10$tLJXaxijEpImOVbl1yNqJz', 'admin', 'aktif', 'Deskripsi dummy user 39.', 'profile_39.jpg', 'Bahasa Indonesia', 'London', 1, 1, 1, 'dummy_email_39@example.com', 'insta_user39', 'twitter_user39', 'linkedin_user39', 'github_user39', '2024-12-29 16:38:10');
INSERT INTO `tb_user` (`id_user`, `first_name`, `last_name`, `username`, `password`, `role`, `status`, `deskripsi`, `fotoProfil`, `bahasa`, `zona_waktu`, `balasan_ke_komentar`, `komentar_baru`, `notifikasi_postingan_baru`, `email`, `instagram`, `twitter`, `linkdin`, `github`, `tgl_dibuat`) VALUES
(40, 'DummyF40', 'DummyL40', 'dummy_user_40', '$2y$10$fwnSCPxOQyVTU6MtBCtEIi', 'mentor', 'non-aktif', 'Deskripsi dummy user 40.', 'profile_40.jpg', 'Inggris', 'London', 1, 0, 1, 'dummy_email_40@example.com', 'insta_user40', 'twitter_user40', 'linkedin_user40', 'github_user40', '2024-12-13 16:38:10');
INSERT INTO `tb_user` (`id_user`, `first_name`, `last_name`, `username`, `password`, `role`, `status`, `deskripsi`, `fotoProfil`, `bahasa`, `zona_waktu`, `balasan_ke_komentar`, `komentar_baru`, `notifikasi_postingan_baru`, `email`, `instagram`, `twitter`, `linkdin`, `github`, `tgl_dibuat`) VALUES
(41, 'DummyF41', 'DummyL41', 'dummy_user_41', '$2y$10$RNV7lAO5Pzw9xDOTFORIvJ', 'murid', 'non-aktif', 'Deskripsi dummy user 41.', 'profile_41.jpg', 'Jepang', 'Tokyo', 1, 1, 0, 'dummy_email_41@example.com', 'insta_user41', 'twitter_user41', 'linkedin_user41', 'github_user41', '2024-08-25 16:38:10');
INSERT INTO `tb_user` (`id_user`, `first_name`, `last_name`, `username`, `password`, `role`, `status`, `deskripsi`, `fotoProfil`, `bahasa`, `zona_waktu`, `balasan_ke_komentar`, `komentar_baru`, `notifikasi_postingan_baru`, `email`, `instagram`, `twitter`, `linkdin`, `github`, `tgl_dibuat`) VALUES
(42, 'DummyF42', 'DummyL42', 'dummy_user_42', '$2y$10$xssm0tzzo8AkNY6z3PT5h0', 'admin', 'aktif', 'Deskripsi dummy user 42.', 'profile_42.jpg', 'Bahasa Indonesia', 'Jakarta', 0, 1, 1, 'dummy_email_42@example.com', 'insta_user42', 'twitter_user42', 'linkedin_user42', 'github_user42', '2024-10-19 16:38:10');


/* -- DUMMY DATA FOR tb_mentor -- */
INSERT INTO `tb_mentor` (`id_mentor`, `status`, `id_user`) VALUES
(8, 'Non-Aktif', 39);
INSERT INTO `tb_mentor` (`id_mentor`, `status`, `id_user`) VALUES
(9, 'Non-Aktif', 25);
INSERT INTO `tb_mentor` (`id_mentor`, `status`, `id_user`) VALUES
(10, 'Non-Aktif', 4);
INSERT INTO `tb_mentor` (`id_mentor`, `status`, `id_user`) VALUES
(11, 'Aktif', 26);
INSERT INTO `tb_mentor` (`id_mentor`, `status`, `id_user`) VALUES
(12, 'Non-Aktif', 38);


/* -- DUMMY DATA FOR tb_dokumen -- */
INSERT INTO `tb_dokumen` (`id_dokumen`, `file_path_dokumen`, `status`) VALUES
(6, '../uploads/dokumen/DUMMYDOC_6.pdf', 'pending');
INSERT INTO `tb_dokumen` (`id_dokumen`, `file_path_dokumen`, `status`) VALUES
(7, '../uploads/dokumen/DUMMYDOC_7.pdf', 'non-aktif');
INSERT INTO `tb_dokumen` (`id_dokumen`, `file_path_dokumen`, `status`) VALUES
(8, '../uploads/dokumen/DUMMYDOC_8.pdf', 'non-aktif');
INSERT INTO `tb_dokumen` (`id_dokumen`, `file_path_dokumen`, `status`) VALUES
(9, '../uploads/dokumen/DUMMYDOC_9.pdf', 'aktif');
INSERT INTO `tb_dokumen` (`id_dokumen`, `file_path_dokumen`, `status`) VALUES
(10, '../uploads/dokumen/DUMMYDOC_10.pdf', 'non-aktif');
INSERT INTO `tb_dokumen` (`id_dokumen`, `file_path_dokumen`, `status`) VALUES
(11, '../uploads/dokumen/DUMMYDOC_11.pdf', 'aktif');
INSERT INTO `tb_dokumen` (`id_dokumen`, `file_path_dokumen`, `status`) VALUES
(12, '../uploads/dokumen/DUMMYDOC_12.pdf', 'non-aktif');
INSERT INTO `tb_dokumen` (`id_dokumen`, `file_path_dokumen`, `status`) VALUES
(13, '../uploads/dokumen/DUMMYDOC_13.pdf', 'aktif');
INSERT INTO `tb_dokumen` (`id_dokumen`, `file_path_dokumen`, `status`) VALUES
(14, '../uploads/dokumen/DUMMYDOC_14.pdf', 'non-aktif');
INSERT INTO `tb_dokumen` (`id_dokumen`, `file_path_dokumen`, `status`) VALUES
(15, '../uploads/dokumen/DUMMYDOC_15.pdf', 'non-aktif');


/* -- DUMMY DATA FOR tb_video -- */
INSERT INTO `tb_video` (`id_video`, `file_path_video`, `status`) VALUES
(6, '../uploads/video/DUMMYVID_6.mp4', 'aktif');
INSERT INTO `tb_video` (`id_video`, `file_path_video`, `status`) VALUES
(7, '../uploads/video/DUMMYVID_7.mp4', 'non-aktif');
INSERT INTO `tb_video` (`id_video`, `file_path_video`, `status`) VALUES
(8, '../uploads/video/DUMMYVID_8.mp4', 'pending');
INSERT INTO `tb_video` (`id_video`, `file_path_video`, `status`) VALUES
(9, '../uploads/video/DUMMYVID_9.mp4', 'aktif');
INSERT INTO `tb_video` (`id_video`, `file_path_video`, `status`) VALUES
(10, '../uploads/video/DUMMYVID_10.mp4', 'non-aktif');
INSERT INTO `tb_video` (`id_video`, `file_path_video`, `status`) VALUES
(11, '../uploads/video/DUMMYVID_11.mp4', 'non-aktif');
INSERT INTO `tb_video` (`id_video`, `file_path_video`, `status`) VALUES
(12, '../uploads/video/DUMMYVID_12.mp4', 'aktif');
INSERT INTO `tb_video` (`id_video`, `file_path_video`, `status`) VALUES
(13, '../uploads/video/DUMMYVID_13.mp4', 'non-aktif');
INSERT INTO `tb_video` (`id_video`, `file_path_video`, `status`) VALUES
(14, '../uploads/video/DUMMYVID_14.mp4', 'aktif');
INSERT INTO `tb_video` (`id_video`, `file_path_video`, `status`) VALUES
(15, '../uploads/video/DUMMYVID_15.mp4', 'pending');


/* -- DUMMY DATA FOR tb_kelas -- */
INSERT INTO `tb_kelas` (`id_kelas`, `id_mentor`, `nama_kelas`, `kategori`, `harga`, `profil_kelas`, `badge`, `description`, `status_publikasi`, `tgl_dibuat`) VALUES
(8, 3, 'Kelas Dummy 8: Intensif Pemrograman', 'Java', 175842.10, NULL, NULL, 'Deskripsi detail untuk Kelas Dummy 8, mencakup topik-topik penting dan tujuan pembelajaran.', 'non-aktif', '2025-04-21 16:38:10');
INSERT INTO `tb_kelas` (`id_kelas`, `id_mentor`, `nama_kelas`, `kategori`, `harga`, `profil_kelas`, `badge`, `description`, `status_publikasi`, `tgl_dibuat`) VALUES
(9, 5, 'Kelas Dummy 9: Intensif Desain', 'Psikologi', 485576.90, NULL, NULL, 'Deskripsi detail untuk Kelas Dummy 9, mencakup topik-topik penting dan tujuan pembelajaran.', 'aktif', '2025-03-06 16:38:10');
INSERT INTO `tb_kelas` (`id_kelas`, `id_mentor`, `nama_kelas`, `kategori`, `harga`, `profil_kelas`, `badge`, `description`, `status_publikasi`, `tgl_dibuat`) VALUES
(10, 2, 'Kelas Dummy 10: Intensif Data Science', 'IT', 90227.97, NULL, NULL, 'Deskripsi detail untuk Kelas Dummy 10, mencakup topik-topik penting dan tujuan pembelajaran.', 'pending', '2025-03-16 16:38:10');
INSERT INTO `tb_kelas` (`id_kelas`, `id_mentor`, `nama_kelas`, `kategori`, `harga`, `profil_kelas`, `badge`, `description`, `status_publikasi`, `tgl_dibuat`) VALUES
(11, 1, 'Kelas Dummy 11: Dasar Desain', 'Bisnis', 205087.46, NULL, NULL, 'Deskripsi detail untuk Kelas Dummy 11, mencakup topik-topik penting dan tujuan pembelajaran.', 'pending', '2025-04-15 16:38:10');
INSERT INTO `tb_kelas` (`id_kelas`, `id_mentor`, `nama_kelas`, `kategori`, `harga`, `profil_kelas`, `badge`, `description`, `status_publikasi`, `tgl_dibuat`) VALUES
(12, 9, 'Kelas Dummy 12: Dasar Data Science', 'Web Development', 110312.56, NULL, NULL, 'Deskripsi detail untuk Kelas Dummy 12, mencakup topik-topik penting dan tujuan pembelajaran.', 'rejected', '2025-04-19 16:38:10');
INSERT INTO `tb_kelas` (`id_kelas`, `id_mentor`, `nama_kelas`, `kategori`, `harga`, `profil_kelas`, `badge`, `description`, `status_publikasi`, `tgl_dibuat`) VALUES
(13, 11, 'Kelas Dummy 13: Lanjutan Desain', 'Web Development', 186572.29, NULL, NULL, 'Deskripsi detail untuk Kelas Dummy 13, mencakup topik-topik penting dan tujuan pembelajaran.', 'aktif', '2024-12-12 16:38:10');
INSERT INTO `tb_kelas` (`id_kelas`, `id_mentor`, `nama_kelas`, `kategori`, `harga`, `profil_kelas`, `badge`, `description`, `status_publikasi`, `tgl_dibuat`) VALUES
(14, 2, 'Kelas Dummy 14: Dasar Pemrograman', 'Java', 341189.04, NULL, NULL, 'Deskripsi detail untuk Kelas Dummy 14, mencakup topik-topik penting dan tujuan pembelajaran.', 'rejected', '2025-02-27 16:38:10');
INSERT INTO `tb_kelas` (`id_kelas`, `id_mentor`, `nama_kelas`, `kategori`, `harga`, `profil_kelas`, `badge`, `description`, `status_publikasi`, `tgl_dibuat`) VALUES
(15, 11, 'Kelas Dummy 15: Lanjutan Data Science', 'Psikologi', 343470.92, NULL, NULL, 'Deskripsi detail untuk Kelas Dummy 15, mencakup topik-topik penting dan tujuan pembelajaran.', 'pending', '2025-05-09 16:38:10');
INSERT INTO `tb_kelas` (`id_kelas`, `id_mentor`, `nama_kelas`, `kategori`, `harga`, `profil_kelas`, `badge`, `description`, `status_publikasi`, `tgl_dibuat`) VALUES
(16, 7, 'Kelas Dummy 16: Lanjutan Data Science', 'IT', 280536.41, NULL, NULL, 'Deskripsi detail untuk Kelas Dummy 16, mencakup topik-topik penting dan tujuan pembelajaran.', 'aktif', '2025-02-27 16:38:10');
INSERT INTO `tb_kelas` (`id_kelas`, `id_mentor`, `nama_kelas`, `kategori`, `harga`, `profil_kelas`, `badge`, `description`, `status_publikasi`, `tgl_dibuat`) VALUES
(17, 3, 'Kelas Dummy 17: Lanjutan Desain', 'Web Development', 67671.79, NULL, NULL, 'Deskripsi detail untuk Kelas Dummy 17, mencakup topik-topik penting dan tujuan pembelajaran.', 'pending', '2025-01-10 16:38:10');


/* -- DUMMY DATA FOR tb_materi -- */
INSERT INTO `tb_materi` (`id_materi`, `id_kelas`, `urutan`, `judul_materi`, `status`, `tgl_dibuat_materi`) VALUES
(8, 14, 2, 'Materi Dummy 8: Bab 2 Studi Kasus', 'aktif', '2025-03-17 16:38:10');
INSERT INTO `tb_materi` (`id_materi`, `id_kelas`, `urutan`, `judul_materi`, `status`, `tgl_dibuat_materi`) VALUES
(9, 9, 10, 'Materi Dummy 9: Bab 10 Konsep Dasar', 'non-aktif', '2025-04-25 16:38:10');
INSERT INTO `tb_materi` (`id_materi`, `id_kelas`, `urutan`, `judul_materi`, `status`, `tgl_dibuat_materi`) VALUES
(10, 4, 4, 'Materi Dummy 10: Bab 4 Pendahuluan', 'aktif', '2025-03-27 16:38:10');
INSERT INTO `tb_materi` (`id_materi`, `id_kelas`, `urutan`, `judul_materi`, `status`, `tgl_dibuat_materi`) VALUES
(11, 10, 9, 'Materi Dummy 11: Bab 9 Konsep Dasar', 'non-aktif', '2025-05-29 16:38:10');
INSERT INTO `tb_materi` (`id_materi`, `id_kelas`, `urutan`, `judul_materi`, `status`, `tgl_dibuat_materi`) VALUES
(12, 13, 3, 'Materi Dummy 12: Bab 3 Studi Kasus', 'aktif', '2025-05-08 16:38:10');
INSERT INTO `tb_materi` (`id_materi`, `id_kelas`, `urutan`, `judul_materi`, `status`, `tgl_dibuat_materi`) VALUES
(13, 2, 6, 'Materi Dummy 13: Bab 6 Konsep Dasar', 'non-aktif', '2025-04-28 16:38:10');
INSERT INTO `tb_materi` (`id_materi`, `id_kelas`, `urutan`, `judul_materi`, `status`, `tgl_dibuat_materi`) VALUES
(14, 7, 9, 'Materi Dummy 14: Bab 9 Studi Kasus', 'pending', '2025-04-29 16:38:10');
INSERT INTO `tb_materi` (`id_materi`, `id_kelas`, `urutan`, `judul_materi`, `status`, `tgl_dibuat_materi`) VALUES
(15, 13, 7, 'Materi Dummy 15: Bab 7 Pendahuluan', 'pending', '2025-04-25 16:38:10');
INSERT INTO `tb_materi` (`id_materi`, `id_kelas`, `urutan`, `judul_materi`, `status`, `tgl_dibuat_materi`) VALUES
(16, 7, 2, 'Materi Dummy 16: Bab 2 Studi Kasus', 'pending', '2025-05-02 16:38:10');
INSERT INTO `tb_materi` (`id_materi`, `id_kelas`, `urutan`, `judul_materi`, `status`, `tgl_dibuat_materi`) VALUES
(17, 12, 6, 'Materi Dummy 17: Bab 6 Konsep Dasar', 'pending', '2025-03-21 16:38:10');
INSERT INTO `tb_materi` (`id_materi`, `id_kelas`, `urutan`, `judul_materi`, `status`, `tgl_dibuat_materi`) VALUES
(18, 7, 2, 'Materi Dummy 18: Bab 2 Pendahuluan', 'non-aktif', '2025-03-11 16:38:10');
INSERT INTO `tb_materi` (`id_materi`, `id_kelas`, `urutan`, `judul_materi`, `status`, `tgl_dibuat_materi`) VALUES
(19, 3, 9, 'Materi Dummy 19: Bab 9 Studi Kasus', 'pending', '2025-04-28 16:38:10');
INSERT INTO `tb_materi` (`id_materi`, `id_kelas`, `urutan`, `judul_materi`, `status`, `tgl_dibuat_materi`) VALUES
(20, 16, 6, 'Materi Dummy 20: Bab 6 Pendahuluan', 'pending', '2025-05-10 16:38:10');
INSERT INTO `tb_materi` (`id_materi`, `id_kelas`, `urutan`, `judul_materi`, `status`, `tgl_dibuat_materi`) VALUES
(21, 9, 4, 'Materi Dummy 21: Bab 4 Studi Kasus', 'aktif', '2025-04-29 16:38:10');
INSERT INTO `tb_materi` (`id_materi`, `id_kelas`, `urutan`, `judul_materi`, `status`, `tgl_dibuat_materi`) VALUES
(22, 12, 7, 'Materi Dummy 22: Bab 7 Pendahuluan', 'pending', '2025-06-01 16:38:10');
INSERT INTO `tb_materi` (`id_materi`, `id_kelas`, `urutan`, `judul_materi`, `status`, `tgl_dibuat_materi`) VALUES
(23, 1, 7, 'Materi Dummy 23: Bab 7 Studi Kasus', 'aktif', '2025-03-31 16:38:10');
INSERT INTO `tb_materi` (`id_materi`, `id_kelas`, `urutan`, `judul_materi`, `status`, `tgl_dibuat_materi`) VALUES
(24, 16, 9, 'Materi Dummy 24: Bab 9 Studi Kasus', 'pending', '2025-04-21 16:38:10');
INSERT INTO `tb_materi` (`id_materi`, `id_kelas`, `urutan`, `judul_materi`, `status`, `tgl_dibuat_materi`) VALUES
(25, 9, 3, 'Materi Dummy 25: Bab 3 Pendahuluan', 'non-aktif', '2025-06-01 16:38:10');
INSERT INTO `tb_materi` (`id_materi`, `id_kelas`, `urutan`, `judul_materi`, `status`, `tgl_dibuat_materi`) VALUES
(26, 2, 3, 'Materi Dummy 26: Bab 3 Pendahuluan', 'non-aktif', '2025-03-31 16:38:10');
INSERT INTO `tb_materi` (`id_materi`, `id_kelas`, `urutan`, `judul_materi`, `status`, `tgl_dibuat_materi`) VALUES
(27, 13, 2, 'Materi Dummy 27: Bab 2 Pendahuluan', 'pending', '2025-05-22 16:38:10');


/* -- DUMMY DATA FOR tb_sub_materi -- */
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(8, 9, 6, 10, 3, 'Sub Materi Dummy 8: Praktik Topik A', 'aktif', '2025-05-12 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(9, 14, 14, 12, 5, 'Sub Materi Dummy 9: Praktik Topik B', 'pending', '2025-04-12 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(10, 24, 13, 13, 3, 'Sub Materi Dummy 10: Praktik Topik A', 'non-aktif', '2025-04-20 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(11, 15, 2, 6, 2, 'Sub Materi Dummy 11: Praktik Topik B', 'pending', '2025-06-07 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(12, 27, 12, 4, 4, 'Sub Materi Dummy 12: Pengantar Topik C', 'non-aktif', '2025-04-21 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(13, 13, 1, 0, 4, 'Sub Materi Dummy 13: Praktik Topik C', 'non-aktif', '2025-04-27 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(14, 7, 14, 5, 4, 'Sub Materi Dummy 14: Pengantar Topik A', 'aktif', '2025-05-01 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(15, 11, 5, 4, 2, 'Sub Materi Dummy 15: Latihan Topik C', 'aktif', '2025-05-18 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(16, 24, 7, 14, 2, 'Sub Materi Dummy 16: Pengantar Topik B', 'pending', '2025-05-08 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(17, 11, 0, 11, 2, 'Sub Materi Dummy 17: Praktik Topik C', 'non-aktif', '2025-04-20 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(18, 11, 4, 15, 1, 'Sub Materi Dummy 18: Pengantar Topik B', 'aktif', '2025-04-09 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(19, 14, 15, 10, 3, 'Sub Materi Dummy 19: Latihan Topik C', 'non-aktif', '2025-04-23 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(20, 19, 14, 2, 4, 'Sub Materi Dummy 20: Pengantar Topik A', 'aktif', '2025-05-24 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(21, 23, 11, 14, 3, 'Sub Materi Dummy 21: Pengantar Topik B', 'aktif', '2025-05-09 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(22, 19, 9, 8, 2, 'Sub Materi Dummy 22: Latihan Topik B', 'pending', '2025-05-18 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(23, 16, 12, 12, 2, 'Sub Materi Dummy 23: Latihan Topik C', 'pending', '2025-05-28 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(24, 16, 11, 10, 1, 'Sub Materi Dummy 24: Latihan Topik B', 'non-aktif', '2025-05-25 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(25, 20, 10, 15, 3, 'Sub Materi Dummy 25: Pengantar Topik A', 'non-aktif', '2025-05-26 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(26, 21, 6, 8, 3, 'Sub Materi Dummy 26: Latihan Topik A', 'non-aktif', '2025-05-12 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(27, 24, 7, 5, 2, 'Sub Materi Dummy 27: Pengantar Topik A', 'aktif', '2025-05-23 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(28, 12, 10, 9, 3, 'Sub Materi Dummy 28: Latihan Topik A', 'aktif', '2025-05-10 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(29, 13, 11, 11, 5, 'Sub Materi Dummy 29: Praktik Topik B', 'non-aktif', '2025-05-16 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(30, 26, 12, 1, 1, 'Sub Materi Dummy 30: Praktik Topik B', 'pending', '2025-05-06 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(31, 23, 13, 11, 4, 'Sub Materi Dummy 31: Latihan Topik C', 'aktif', '2025-05-26 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(32, 25, 14, 15, 3, 'Sub Materi Dummy 32: Praktik Topik A', 'aktif', '2025-05-11 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(33, 17, 12, 12, 4, 'Sub Materi Dummy 33: Praktik Topik B', 'non-aktif', '2025-05-26 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(34, 16, 1, 13, 4, 'Sub Materi Dummy 34: Pengantar Topik B', 'aktif', '2025-05-27 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(35, 19, 0, 0, 5, 'Sub Materi Dummy 35: Latihan Topik B', 'aktif', '2025-05-20 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(36, 13, 1, 10, 1, 'Sub Materi Dummy 36: Praktik Topik C', 'pending', '2025-05-02 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(37, 24, 15, 6, 2, 'Sub Materi Dummy 37: Latihan Topik B', 'pending', '2025-05-07 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(38, 23, 14, 15, 4, 'Sub Materi Dummy 38: Pengantar Topik C', 'non-aktif', '2025-05-24 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(39, 13, 8, 1, 1, 'Sub Materi Dummy 39: Latihan Topik B', 'pending', '2025-05-07 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(40, 25, 12, 13, 5, 'Sub Materi Dummy 40: Latihan Topik C', 'non-aktif', '2025-05-20 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(41, 14, 0, 0, 5, 'Sub Materi Dummy 41: Praktik Topik A', 'aktif', '2025-05-07 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(42, 10, 11, 4, 3, 'Sub Materi Dummy 42: Praktik Topik B', 'aktif', '2025-05-18 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(43, 18, 12, 0, 3, 'Sub Materi Dummy 43: Pengantar Topik C', 'aktif', '2025-05-26 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(44, 26, 12, 12, 4, 'Sub Materi Dummy 44: Pengantar Topik A', 'aktif', '2025-05-22 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(45, 17, 11, 3, 2, 'Sub Materi Dummy 45: Latihan Topik C', 'non-aktif', '2025-05-21 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(46, 27, 7, 7, 2, 'Sub Materi Dummy 46: Pengantar Topik C', 'aktif', '2025-05-20 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(47, 10, 15, 14, 2, 'Sub Materi Dummy 47: Praktik Topik B', 'non-aktif', '2025-05-17 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(48, 26, 8, 0, 5, 'Sub Materi Dummy 48: Latihan Topik A', 'pending', '2025-05-09 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(49, 10, 9, 14, 5, 'Sub Materi Dummy 49: Praktik Topik A', 'aktif', '2025-05-22 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(50, 20, 10, 2, 4, 'Sub Materi Dummy 50: Latihan Topik B', 'non-aktif', '2025-05-26 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(51, 14, 15, 7, 4, 'Sub Materi Dummy 51: Pengantar Topik B', 'aktif', '2025-05-18 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(52, 22, 13, 10, 5, 'Sub Materi Dummy 52: Pengantar Topik C', 'pending', '2025-05-06 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(53, 21, 10, 15, 2, 'Sub Materi Dummy 53: Praktik Topik B', 'pending', '2025-05-29 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(54, 10, 0, 15, 5, 'Sub Materi Dummy 54: Latihan Topik A', 'non-aktif', '2025-05-28 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(55, 17, 7, 10, 2, 'Sub Materi Dummy 55: Pengantar Topik B', 'aktif', '2025-05-20 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(56, 14, 7, 10, 3, 'Sub Materi Dummy 56: Latihan Topik A', 'non-aktif', '2025-05-23 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(57, 13, 0, 4, 3, 'Sub Materi Dummy 57: Praktik Topik A', 'non-aktif', '2025-05-02 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(58, 25, 3, 11, 4, 'Sub Materi Dummy 58: Latihan Topik C', 'aktif', '2025-05-25 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(59, 21, 10, 13, 1, 'Sub Materi Dummy 59: Latihan Topik B', 'pending', '2025-05-06 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(60, 22, 0, 11, 3, 'Sub Materi Dummy 60: Praktik Topik C', 'aktif', '2025-05-12 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(61, 23, 11, 3, 3, 'Sub Materi Dummy 61: Pengantar Topik A', 'non-aktif', '2025-05-18 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(62, 23, 0, 7, 4, 'Sub Materi Dummy 62: Latihan Topik B', 'aktif', '2025-05-05 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(63, 16, 5, 10, 3, 'Sub Materi Dummy 63: Latihan Topik A', 'pending', '2025-05-24 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(64, 27, 10, 1, 4, 'Sub Materi Dummy 64: Latihan Topik C', 'non-aktif', '2025-05-27 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(65, 15, 1, 6, 2, 'Sub Materi Dummy 65: Praktik Topik A', 'non-aktif', '2025-05-16 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(66, 17, 10, 11, 1, 'Sub Materi Dummy 66: Praktik Topik B', 'non-aktif', '2025-05-07 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(67, 18, 9, 2, 4, 'Sub Materi Dummy 67: Pengantar Topik C', 'non-aktif', '2025-05-28 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(68, 11, 15, 15, 3, 'Sub Materi Dummy 68: Latihan Topik C', 'non-aktif', '2025-05-28 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(69, 9, 14, 11, 5, 'Sub Materi Dummy 69: Praktik Topik A', 'non-aktif', '2025-05-24 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(70, 21, 6, 15, 1, 'Sub Materi Dummy 70: Praktik Topik B', 'aktif', '2025-05-24 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(71, 18, 0, 12, 1, 'Sub Materi Dummy 71: Latihan Topik B', 'pending', '2025-05-09 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(72, 14, 13, 10, 2, 'Sub Materi Dummy 72: Latihan Topik B', 'pending', '2025-05-07 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(73, 19, 15, 2, 4, 'Sub Materi Dummy 73: Pengantar Topik C', 'non-aktif', '2025-05-18 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(74, 15, 1, 10, 4, 'Sub Materi Dummy 74: Pengantar Topik A', 'pending', '2025-05-08 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(75, 10, 4, 9, 3, 'Sub Materi Dummy 75: Latihan Topik B', 'non-aktif', '2025-05-14 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(76, 26, 12, 5, 4, 'Sub Materi Dummy 76: Latihan Topik A', 'aktif', '2025-05-24 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(77, 23, 2, 3, 1, 'Sub Materi Dummy 77: Praktik Topik A', 'aktif', '2025-05-24 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(78, 20, 11, 4, 3, 'Sub Materi Dummy 78: Latihan Topik B', 'aktif', '2025-05-26 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(79, 17, 10, 7, 4, 'Sub Materi Dummy 79: Pengantar Topik B', 'non-aktif', '2025-05-21 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(80, 15, 7, 3, 1, 'Sub Materi Dummy 80: Praktik Topik A', 'pending', '2025-05-09 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(81, 10, 13, 15, 3, 'Sub Materi Dummy 81: Pengantar Topik C', 'aktif', '2025-05-18 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(82, 19, 14, 15, 5, 'Sub Materi Dummy 82: Latihan Topik B', 'non-aktif', '2025-05-03 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(83, 18, 5, 0, 5, 'Sub Materi Dummy 83: Pengantar Topik B', 'aktif', '2025-05-22 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(84, 15, 14, 1, 3, 'Sub Materi Dummy 84: Praktik Topik A', 'non-aktif', '2025-05-15 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(85, 27, 8, 13, 1, 'Sub Materi Dummy 85: Pengantar Topik C', 'pending', '2025-05-22 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(86, 20, 12, 14, 4, 'Sub Materi Dummy 86: Latihan Topik C', 'aktif', '2025-05-25 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(87, 19, 11, 2, 4, 'Sub Materi Dummy 87: Latihan Topik B', 'non-aktif', '2025-05-25 16:38:10');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(88, 22, 0, 9, 1, 'Sub Materi Dummy 88: Praktik Topik A', 'aktif', '2025-05-15 16:38:10');


/* -- DUMMY DATA FOR tb_komentar -- */
INSERT INTO `tb_komentar` (`id_komentar`, `isi`, `id_user`, `id_kelas`) VALUES
(5, 'Ini adalah komentar dummy ke-5 tentang kelas ini. Sangat menarik.', 28, 11);
INSERT INTO `tb_komentar` (`id_komentar`, `isi`, `id_user`, `id_kelas`) VALUES
(6, 'Ini adalah komentar dummy ke-6 tentang kelas ini. Sangat informatif.', 23, 17);
INSERT INTO `tb_komentar` (`id_komentar`, `isi`, `id_user`, `id_kelas`) VALUES
(7, 'Ini adalah komentar dummy ke-7 tentang kelas ini. Sangat kurang jelas.', 30, 11);
INSERT INTO `tb_komentar` (`id_komentar`, `isi`, `id_user`, `id_kelas`) VALUES
(8, 'Ini adalah komentar dummy ke-8 tentang kelas ini. Sangat kurang jelas.', 36, 12);
INSERT INTO `tb_komentar` (`id_komentar`, `isi`, `id_user`, `id_kelas`) VALUES
(9, 'Ini adalah komentar dummy ke-9 tentang kelas ini. Sangat membantu.', 4, 15);
INSERT INTO `tb_komentar` (`id_komentar`, `isi`, `id_user`, `id_kelas`) VALUES
(10, 'Ini adalah komentar dummy ke-10 tentang kelas ini. Sangat bagus.', 29, 11);
INSERT INTO `tb_komentar` (`id_komentar`, `isi`, `id_user`, `id_kelas`) VALUES
(11, 'Ini adalah komentar dummy ke-11 tentang kelas ini. Sangat informatif.', 40, 15);
INSERT INTO `tb_komentar` (`id_komentar`, `isi`, `id_user`, `id_kelas`) VALUES
(12, 'Ini adalah komentar dummy ke-12 tentang kelas ini. Sangat bagus.', 35, 14);
INSERT INTO `tb_komentar` (`id_komentar`, `isi`, `id_user`, `id_kelas`) VALUES
(13, 'Ini adalah komentar dummy ke-13 tentang kelas ini. Sangat membantu.', 31, 15);
INSERT INTO `tb_komentar` (`id_komentar`, `isi`, `id_user`, `id_kelas`) VALUES
(14, 'Ini adalah komentar dummy ke-14 tentang kelas ini. Sangat menarik.', 37, 10);
INSERT INTO `tb_komentar` (`id_komentar`, `isi`, `id_user`, `id_kelas`) VALUES
(15, 'Ini adalah komentar dummy ke-15 tentang kelas ini. Sangat bagus.', 31, 17);
INSERT INTO `tb_komentar` (`id_komentar`, `isi`, `id_user`, `id_kelas`) VALUES
(16, 'Ini adalah komentar dummy ke-16 tentang kelas ini. Sangat membantu.', 27, 12);
INSERT INTO `tb_komentar` (`id_komentar`, `isi`, `id_user`, `id_kelas`) VALUES
(17, 'Ini adalah komentar dummy ke-17 tentang kelas ini. Sangat bagus.', 31, 13);
INSERT INTO `tb_komentar` (`id_komentar`, `isi`, `id_user`, `id_kelas`) VALUES
(18, 'Ini adalah komentar dummy ke-18 tentang kelas ini. Sangat bagus.', 37, 14);
INSERT INTO `tb_komentar` (`id_komentar`, `isi`, `id_user`, `id_kelas`) VALUES
(19, 'Ini adalah komentar dummy ke-19 tentang kelas ini. Sangat informatif.', 28, 16);
INSERT INTO `tb_komentar` (`id_komentar`, `isi`, `id_user`, `id_kelas`) VALUES
(20, 'Ini adalah komentar dummy ke-20 tentang kelas ini. Sangat kurang jelas.', 30, 10);
INSERT INTO `tb_komentar` (`id_komentar`, `isi`, `id_user`, `id_kelas`) VALUES
(21, 'Ini adalah komentar dummy ke-21 tentang kelas ini. Sangat menarik.', 26, 17);
INSERT INTO `tb_komentar` (`id_komentar`, `isi`, `id_user`, `id_kelas`) VALUES
(22, 'Ini adalah komentar dummy ke-22 tentang kelas ini. Sangat informatif.', 33, 9);
INSERT INTO `tb_komentar` (`id_komentar`, `isi`, `id_user`, `id_kelas`) VALUES
(23, 'Ini adalah komentar dummy ke-23 tentang kelas ini. Sangat membantu.', 34, 15);
INSERT INTO `tb_komentar` (`id_komentar`, `isi`, `id_user`, `id_kelas`) VALUES
(24, 'Ini adalah komentar dummy ke-24 tentang kelas ini. Sangat bagus.', 33, 10);
INSERT INTO `tb_komentar` (`id_komentar`, `isi`, `id_user`, `id_kelas`) VALUES
(25, 'Ini adalah komentar dummy ke-25 tentang kelas ini. Sangat bagus.', 42, 10);
INSERT INTO `tb_komentar` (`id_komentar`, `isi`, `id_user`, `id_kelas`) VALUES
(26, 'Ini adalah komentar dummy ke-26 tentang kelas ini. Sangat informatif.', 27, 14);
INSERT INTO `tb_komentar` (`id_komentar`, `isi`, `id_user`, `id_kelas`) VALUES
(27, 'Ini adalah komentar dummy ke-27 tentang kelas ini. Sangat bagus.', 34, 11);
INSERT INTO `tb_komentar` (`id_komentar`, `isi`, `id_user`, `id_kelas`) VALUES
(28, 'Ini adalah komentar dummy ke-28 tentang kelas ini. Sangat bagus.', 39, 13);
INSERT INTO `tb_komentar` (`id_komentar`, `isi`, `id_user`, `id_kelas`) VALUES
(29, 'Ini adalah komentar dummy ke-29 tentang kelas ini. Sangat membantu.', 29, 14);
INSERT INTO `tb_komentar` (`id_komentar`, `isi`, `id_user`, `id_kelas`) VALUES
(30, 'Ini adalah komentar dummy ke-30 tentang kelas ini. Sangat menarik.', 37, 12);
INSERT INTO `tb_komentar` (`id_komentar`, `isi`, `id_user`, `id_kelas`) VALUES
(31, 'Ini adalah komentar dummy ke-31 tentang kelas ini. Sangat bagus.', 41, 10);
INSERT INTO `tb_komentar` (`id_komentar`, `isi`, `id_user`, `id_kelas`) VALUES
(32, 'Ini adalah komentar dummy ke-32 tentang kelas ini. Sangat menarik.', 40, 11);
INSERT INTO `tb_komentar` (`id_komentar`, `isi`, `id_user`, `id_kelas`) VALUES
(33, 'Ini adalah komentar dummy ke-33 tentang kelas ini. Sangat membantu.', 42, 12);


/* -- DUMMY DATA FOR tb_laporan -- */
INSERT INTO `tb_laporan` (`id_report`, `kategori_report`, `keterangan_report`, `id_kelas`, `id_user`, `tgl_dibuat`, `status_laporan`) VALUES
(6, 'Materi tidak relevan', 'Keterangan laporan dummy ke-6: Materi tidak relevan.', 9, 39, '2025-05-18 16:38:10', 'Diproses');
INSERT INTO `tb_laporan` (`id_report`, `kategori_report`, `keterangan_report`, `id_kelas`, `id_user`, `tgl_dibuat`, `status_laporan`) VALUES
(7, 'Materi tidak relevan', 'Keterangan laporan dummy ke-7: Materi tidak relevan.', 15, 34, '2025-05-24 16:38:10', 'Diproses');
INSERT INTO `tb_laporan` (`id_report`, `kategori_report`, `keterangan_report`, `id_kelas`, `id_user`, `tgl_dibuat`, `status_laporan`) VALUES
(8, 'Pornografi', 'Keterangan laporan dummy ke-8: Konten tidak senonoh.', 16, 27, '2025-05-29 16:38:10', 'Belum Diproses');
INSERT INTO `tb_laporan` (`id_report`, `kategori_report`, `keterangan_report`, `id_kelas`, `id_user`, `tgl_dibuat`, `status_laporan`) VALUES
(9, 'Materi tidak relevan', 'Keterangan laporan dummy ke-9: Materi tidak relevan.', 14, 29, '2025-05-13 16:38:10', 'Selesai');
INSERT INTO `tb_laporan` (`id_report`, `kategori_report`, `keterangan_report`, `id_kelas`, `id_user`, `tgl_dibuat`, `status_laporan`) VALUES
(10, 'Penggunaan kata kasar', 'Keterangan laporan dummy ke-10: Ada beberapa kata kasar.', 11, 25, '2025-05-26 16:38:10', 'Belum Diproses');
INSERT INTO `tb_laporan` (`id_report`, `kategori_report`, `keterangan_report`, `id_kelas`, `id_user`, `tgl_dibuat`, `status_laporan`) VALUES
(11, 'Penggunaan kata kasar', 'Keterangan laporan dummy ke-11: Ada beberapa kata kasar.', 14, 30, '2025-05-27 16:38:10', 'Diproses');
INSERT INTO `tb_laporan` (`id_report`, `kategori_report`, `keterangan_report`, `id_kelas`, `id_user`, `tgl_dibuat`, `status_laporan`) VALUES
(12, 'Penggunaan kata kasar', 'Keterangan laporan dummy ke-12: Ada beberapa kata kasar.', 17, 34, '2025-05-12 16:38:10', 'Diproses');
INSERT INTO `tb_laporan` (`id_report`, `kategori_report`, `keterangan_report`, `id_kelas`, `id_user`, `tgl_dibuat`, `status_laporan`) VALUES
(13, 'Penggunaan kata kasar', 'Keterangan laporan dummy ke-13: Ada beberapa kata kasar.', 13, 30, '2025-05-27 16:38:10', 'Diproses');
INSERT INTO `tb_laporan` (`id_report`, `kategori_report`, `keterangan_report`, `id_kelas`, `id_user`, `tgl_dibuat`, `status_laporan`) VALUES
(14, 'Materi tidak relevan', 'Keterangan laporan dummy ke-14: Materi tidak relevan.', 13, 32, '2025-05-23 16:38:10', 'Belum Diproses');
INSERT INTO `tb_laporan` (`id_report`, `kategori_report`, `keterangan_report`, `id_kelas`, `id_user`, `tgl_dibuat`, `status_laporan`) VALUES
(15, 'Materi tidak relevan', 'Keterangan laporan dummy ke-15: Materi tidak relevan.', 14, 25, '2025-05-26 16:38:10', 'Belum Diproses');
INSERT INTO `tb_laporan` (`id_report`, `kategori_report`, `keterangan_report`, `id_kelas`, `id_user`, `tgl_dibuat`, `status_laporan`) VALUES
(16, 'Pornografi', 'Keterangan laporan dummy ke-16: Konten tidak senonoh.', 16, 35, '2025-05-23 16:38:10', 'Belum Diproses');
INSERT INTO `tb_laporan` (`id_report`, `kategori_report`, `keterangan_report`, `id_kelas`, `id_user`, `tgl_dibuat`, `status_laporan`) VALUES
(17, 'Pornografi', 'Keterangan laporan dummy ke-17: Konten tidak senonoh.', 11, 24, '2025-05-18 16:38:10', 'Belum Diproses');
INSERT INTO `tb_laporan` (`id_report`, `kategori_report`, `keterangan_report`, `id_kelas`, `id_user`, `tgl_dibuat`, `status_laporan`) VALUES
(18, 'Penggunaan kata kasar', 'Keterangan laporan dummy ke-18: Ada beberapa kata kasar.', 16, 26, '2025-05-11 16:38:10', 'Belum Diproses');
INSERT INTO `tb_laporan` (`id_report`, `kategori_report`, `keterangan_report`, `id_kelas`, `id_user`, `tgl_dibuat`, `status_laporan`) VALUES
(19, 'Pornografi', 'Keterangan laporan dummy ke-19: Konten tidak senonoh.', 17, 33, '2025-05-18 16:38:10', 'Diproses');
INSERT INTO `tb_laporan` (`id_report`, `kategori_report`, `keterangan_report`, `id_kelas`, `id_user`, `tgl_dibuat`, `status_laporan`) VALUES
(20, 'Penggunaan kata kasar', 'Keterangan laporan dummy ke-20: Ada beberapa kata kasar.', 9, 39, '2025-05-17 16:38:10', 'Belum Diproses');


/* -- DUMMY DATA FOR tb_kategori_kelas -- */
INSERT INTO `tb_kategori_kelas` (`id_kategori_kelas`, `id_kelas`, `id_kategori`) VALUES
(6, 17, 2);
INSERT INTO `tb_kategori_kelas` (`id_kategori_kelas`, `id_kelas`, `id_kategori`) VALUES
(7, 10, 3);
INSERT INTO `tb_kategori_kelas` (`id_kategori_kelas`, `id_kelas`, `id_kategori`) VALUES
(8, 17, 3);
INSERT INTO `tb_kategori_kelas` (`id_kategori_kelas`, `id_kelas`, `id_kategori`) VALUES
(9, 13, 1);
INSERT INTO `tb_kategori_kelas` (`id_kategori_kelas`, `id_kelas`, `id_kategori`) VALUES
(10, 16, 5);
INSERT INTO `tb_kategori_kelas` (`id_kategori_kelas`, `id_kelas`, `id_kategori`) VALUES
(11, 10, 2);
INSERT INTO `tb_kategori_kelas` (`id_kategori_kelas`, `id_kelas`, `id_kategori`) VALUES
(12, 11, 4);
INSERT INTO `tb_kategori_kelas` (`id_kategori_kelas`, `id_kelas`, `id_kategori`) VALUES
(13, 16, 1);
INSERT INTO `tb_kategori_kelas` (`id_kategori_kelas`, `id_kelas`, `id_kategori`) VALUES
(14, 15, 6);
INSERT INTO `tb_kategori_kelas` (`id_kategori_kelas`, `id_kelas`, `id_kategori`) VALUES
(15, 12, 5);


/* -- DUMMY DATA FOR tb_keranjang -- */
INSERT INTO `tb_keranjang` (`id_keranjang`, `tgl_keranjang`, `id_kelas`, `id_user`) VALUES
(5, '2025-05-09', 15, 23);
INSERT INTO `tb_keranjang` (`id_keranjang`, `tgl_keranjang`, `id_kelas`, `id_user`) VALUES
(6, '2025-05-17', 16, 32);
INSERT INTO `tb_keranjang` (`id_keranjang`, `tgl_keranjang`, `id_kelas`, `id_user`) VALUES
(7, '2025-05-01', 15, 29);
INSERT INTO `tb_keranjang` (`id_keranjang`, `tgl_keranjang`, `id_kelas`, `id_user`) VALUES
(8, '2025-05-18', 11, 23);
INSERT INTO `tb_keranjang` (`id_keranjang`, `tgl_keranjang`, `id_kelas`, `id_user`) VALUES
(9, '2025-05-17', 14, 30);
INSERT INTO `tb_keranjang` (`id_keranjang`, `tgl_keranjang`, `id_kelas`, `id_user`) VALUES
(10, '2025-05-16', 15, 41);
INSERT INTO `tb_keranjang` (`id_keranjang`, `tgl_keranjang`, `id_kelas`, `id_user`) VALUES
(11, '2025-05-18', 12, 28);
INSERT INTO `tb_keranjang` (`id_keranjang`, `tgl_keranjang`, `id_kelas`, `id_user`) VALUES
(12, '2025-04-20', 14, 25);
INSERT INTO `tb_keranjang` (`id_keranjang`, `tgl_keranjang`, `id_kelas`, `id_user`) VALUES
(13, '2025-04-20', 13, 27);
INSERT INTO `tb_keranjang` (`id_keranjang`, `tgl_keranjang`, `id_kelas`, `id_user`) VALUES
(14, '2025-05-18', 15, 30);


/* -- DUMMY DATA FOR tb_notifikasi -- */
INSERT INTO `tb_notifikasi` (`id_notifikasi`, `id_user`, `pesan_notif`) VALUES
(1, 35, 'Pesan notifikasi dummy ke-1: Kelas baru tersedia atau ada balasan komentar di kelas Anda.');
INSERT INTO `tb_notifikasi` (`id_notifikasi`, `id_user`, `pesan_notif`) VALUES
(2, 36, 'Pesan notifikasi dummy ke-2: Kelas baru tersedia atau ada balasan komentar di kelas Anda.');
INSERT INTO `tb_notifikasi` (`id_notifikasi`, `id_user`, `pesan_notif`) VALUES
(3, 40, 'Pesan notifikasi dummy ke-3: Kelas baru tersedia atau ada balasan komentar di kelas Anda.');
INSERT INTO `tb_notifikasi` (`id_notifikasi`, `id_user`, `pesan_notif`) VALUES
(4, 28, 'Pesan notifikasi dummy ke-4: Kelas baru tersedia atau ada balasan komentar di kelas Anda.');
INSERT INTO `tb_notifikasi` (`id_notifikasi`, `id_user`, `pesan_notif`) VALUES
(5, 31, 'Pesan notifikasi dummy ke-5: Kelas baru tersedia atau ada balasan komentar di kelas Anda.');
INSERT INTO `tb_notifikasi` (`id_notifikasi`, `id_user`, `pesan_notif`) VALUES
(6, 32, 'Pesan notifikasi dummy ke-6: Kelas baru tersedia atau ada balasan komentar di kelas Anda.');
INSERT INTO `tb_notifikasi` (`id_notifikasi`, `id_user`, `pesan_notif`) VALUES
(7, 30, 'Pesan notifikasi dummy ke-7: Kelas baru tersedia atau ada balasan komentar di kelas Anda.');
INSERT INTO `tb_notifikasi` (`id_notifikasi`, `id_user`, `pesan_notif`) VALUES
(8, 23, 'Pesan notifikasi dummy ke-8: Kelas baru tersedia atau ada balasan komentar di kelas Anda.');
INSERT INTO `tb_notifikasi` (`id_notifikasi`, `id_user`, `pesan_notif`) VALUES
(9, 39, 'Pesan notifikasi dummy ke-9: Kelas baru tersedia atau ada balasan komentar di kelas Anda.');
INSERT INTO `tb_notifikasi` (`id_notifikasi`, `id_user`, `pesan_notif`) VALUES
(10, 29, 'Pesan notifikasi dummy ke-10: Kelas baru tersedia atau ada balasan komentar di kelas Anda.');


/* -- DUMMY DATA FOR tb_progress_kelas -- */
INSERT INTO `tb_progress_kelas` (`id_progress_kelas`, `id_kelas`, `id_user`, `id_materi`) VALUES
(1, 16, 27, 10);
INSERT INTO `tb_progress_kelas` (`id_progress_kelas`, `id_kelas`, `id_user`, `id_materi`) VALUES
(2, 17, 34, 15);
INSERT INTO `tb_progress_kelas` (`id_progress_kelas`, `id_kelas`, `id_user`, `id_materi`) VALUES
(3, 10, 24, 21);
INSERT INTO `tb_progress_kelas` (`id_progress_kelas`, `id_kelas`, `id_user`, `id_materi`) VALUES
(4, 13, 31, 20);
INSERT INTO `tb_progress_kelas` (`id_progress_kelas`, `id_kelas`, `id_user`, `id_materi`) VALUES
(5, 12, 38, 22);
INSERT INTO `tb_progress_kelas` (`id_progress_kelas`, `id_kelas`, `id_user`, `id_materi`) VALUES
(6, 17, 34, 24);
INSERT INTO `tb_progress_kelas` (`id_progress_kelas`, `id_kelas`, `id_user`, `id_materi`) VALUES
(7, 14, 24, 10);
INSERT INTO `tb_progress_kelas` (`id_progress_kelas`, `id_kelas`, `id_user`, `id_materi`) VALUES
(8, 17, 42, 27);
INSERT INTO `tb_progress_kelas` (`id_progress_kelas`, `id_kelas`, `id_user`, `id_materi`) VALUES
(9, 12, 36, 19);
INSERT INTO `tb_progress_kelas` (`id_progress_kelas`, `id_kelas`, `id_user`, `id_materi`) VALUES
(10, 16, 32, 22);


/* -- DUMMY DATA FOR tb_review -- */
INSERT INTO `tb_review` (`id_review`, `bintang_review`, `isi_review`, `tgl_review`, `id_user`, `id_kelas`) VALUES
(2, '4', 'Review dummy ke-2: Kelas ini sangat informatif.', '2025-03-24 16:38:10', 32, 12);
INSERT INTO `tb_review` (`id_review`, `bintang_review`, `isi_review`, `tgl_review`, `id_user`, `id_kelas`) VALUES
(3, '4', 'Review dummy ke-3: Kelas ini sangat luar biasa.', '2025-05-09 16:38:10', 34, 13);
INSERT INTO `tb_review` (`id_review`, `bintang_review`, `isi_review`, `tgl_review`, `id_user`, `id_kelas`) VALUES
(4, '5', 'Review dummy ke-4: Kelas ini sangat bagus.', '2025-04-12 16:38:10', 41, 14);
INSERT INTO `tb_review` (`id_review`, `bintang_review`, `isi_review`, `tgl_review`, `id_user`, `id_kelas`) VALUES
(5, '4', 'Review dummy ke-5: Kelas ini sangat bagus.', '2025-03-09 16:38:10', 35, 10);
INSERT INTO `tb_review` (`id_review`, `bintang_review`, `isi_review`, `tgl_review`, `id_user`, `id_kelas`) VALUES
(6, '2', 'Review dummy ke-6: Kelas ini sangat bagus.', '2025-04-24 16:38:10', 37, 16);
INSERT INTO `tb_review` (`id_review`, `bintang_review`, `isi_review`, `tgl_review`, `id_user`, `id_kelas`) VALUES
(7, '1', 'Review dummy ke-7: Kelas ini sangat kurang memuaskan.', '2025-03-10 16:38:10', 30, 15);
INSERT INTO `tb_review` (`id_review`, `bintang_review`, `isi_review`, `tgl_review`, `id_user`, `id_kelas`) VALUES
(8, '4', 'Review dummy ke-8: Kelas ini sangat luar biasa.', '2025-05-18 16:38:10', 33, 16);
INSERT INTO `tb_review` (`id_review`, `bintang_review`, `isi_review`, `tgl_review`, `id_user`, `id_kelas`) VALUES
(9, '5', 'Review dummy ke-9: Kelas ini sangat informatif.', '2025-05-05 16:38:10', 25, 9);
INSERT INTO `tb_review` (`id_review`, `bintang_review`, `isi_review`, `tgl_review`, `id_user`, `id_kelas`) VALUES
(10, '5', 'Review dummy ke-10: Kelas ini sangat informatif.', '2025-03-23 16:38:10', 36, 15);
INSERT INTO `tb_review` (`id_review`, `bintang_review`, `isi_review`, `tgl_review`, `id_user`, `id_kelas`) VALUES
(11, '5', 'Review dummy ke-11: Kelas ini sangat bagus.', '2025-03-13 16:38:10', 29, 13);


/* -- DUMMY DATA FOR tb_transaksi -- */
INSERT INTO `tb_transaksi` (`id_transaksi`, `id_kelas`, `id_user`, `id_keranjang`, `bukti_transaksi`, `tgl_transaksi`, `status`) VALUES
(4, 9, 39, 11, 'bukti_TRX_DUMMY_4.jpg', '2025-04-06 16:38:10', 'pending');
INSERT INTO `tb_transaksi` (`id_transaksi`, `id_kelas`, `id_user`, `id_keranjang`, `bukti_transaksi`, `tgl_transaksi`, `status`) VALUES
(5, 12, 29, 6, 'bukti_TRX_DUMMY_5.jpg', '2025-05-01 16:38:10', 'acc');
INSERT INTO `tb_transaksi` (`id_transaksi`, `id_kelas`, `id_user`, `id_keranjang`, `bukti_transaksi`, `tgl_transaksi`, `status`) VALUES
(6, 12, 33, 10, 'bukti_TRX_DUMMY_6.jpg', '2025-05-11 16:38:10', 'pending');
INSERT INTO `tb_transaksi` (`id_transaksi`, `id_kelas`, `id_user`, `id_keranjang`, `bukti_transaksi`, `tgl_transaksi`, `status`) VALUES
(7, 10, 31, 10, 'bukti_TRX_DUMMY_7.jpg', '2025-05-17 16:38:10', 'acc');
INSERT INTO `tb_transaksi` (`id_transaksi`, `id_kelas`, `id_user`, `id_keranjang`, `bukti_transaksi`, `tgl_transaksi`, `status`) VALUES
(8, 14, 25, 10, 'bukti_TRX_DUMMY_8.jpg', '2025-05-27 16:38:10', 'acc');
INSERT INTO `tb_transaksi` (`id_transaksi`, `id_kelas`, `id_user`, `id_keranjang`, `bukti_transaksi`, `tgl_transaksi`, `status`) VALUES
(9, 13, 27, 9, 'bukti_TRX_DUMMY_9.jpg', '2025-05-14 16:38:10', 'pending');
INSERT INTO `tb_transaksi` (`id_transaksi`, `id_kelas`, `id_user`, `id_keranjang`, `bukti_transaksi`, `tgl_transaksi`, `status`) VALUES
(10, 16, 23, 11, 'bukti_TRX_DUMMY_10.jpg', '2025-04-18 16:38:10', 'pending');
INSERT INTO `tb_transaksi` (`id_transaksi`, `id_kelas`, `id_user`, `id_keranjang`, `bukti_transaksi`, `tgl_transaksi`, `status`) VALUES
(11, 13, 30, 8, 'bukti_TRX_DUMMY_11.jpg', '2025-04-20 16:38:10', 'acc');
INSERT INTO `tb_transaksi` (`id_transaksi`, `id_kelas`, `id_user`, `id_keranjang`, `bukti_transaksi`, `tgl_transaksi`, `status`) VALUES
(12, 10, 26, 12, 'bukti_TRX_DUMMY_12.jpg', '2025-04-22 16:38:10', 'acc');
INSERT INTO `tb_transaksi` (`id_transaksi`, `id_kelas`, `id_user`, `id_keranjang`, `bukti_transaksi`, `tgl_transaksi`, `status`) VALUES
(13, 12, 31, 6, 'bukti_TRX_DUMMY_13.jpg', '2025-04-12 16:38:10', 'pending');


COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;