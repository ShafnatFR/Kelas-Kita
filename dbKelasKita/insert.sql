SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/* -- AUTO_INCREMENT UPDATES -- */
ALTER TABLE `tb_kelas` AUTO_INCREMENT = 18;
ALTER TABLE `tb_komentar` AUTO_INCREMENT = 34;
ALTER TABLE `tb_laporan` AUTO_INCREMENT = 21;
ALTER TABLE `tb_materi` AUTO_INCREMENT = 28;
ALTER TABLE `tb_mentor` AUTO_INCREMENT = 13;
ALTER TABLE `tb_sub_materi` AUTO_INCREMENT = 89;
ALTER TABLE `tb_user` AUTO_INCREMENT = 43;


/* -- DUMMY DATA FOR tb_user -- */
INSERT INTO `tb_user` (`id_user`, `first_name`, `last_name`, `username`, `password`, `role`, `status`, `deskripsi`, `fotoProfil`, `bahasa`, `zona_waktu`, `balasan_ke_komentar`, `komentar_baru`, `notifikasi_postingan_baru`, `email`, `instagram`, `twitter`, `linkdin`, `github`, `tgl_dibuat`) VALUES
(23, 'DummyF23', 'DummyL23', 'dummy_user_23', '$2y$10$J2dkelDrLXK4l0Pt8IwE0Y', 'admin', 'aktif', 'Deskripsi dummy user 23.', 'profile_23.jpg', 'Inggris', 'Jakarta', 1, 0, 1, 'dummy_email_23@example.com', 'insta_user23', 'twitter_user23', 'linkedin_user23', 'github_user23', '2025-05-18 23:41:42');
INSERT INTO `tb_user` (`id_user`, `first_name`, `last_name`, `username`, `password`, `role`, `status`, `deskripsi`, `fotoProfil`, `bahasa`, `zona_waktu`, `balasan_ke_komentar`, `komentar_baru`, `notifikasi_postingan_baru`, `email`, `instagram`, `twitter`, `linkdin`, `github`, `tgl_dibuat`) VALUES
(24, 'DummyF24', 'DummyL24', 'dummy_user_24', '$2y$10$hfGT8d5SQF05loCWi1PFkE', 'murid', 'aktif', 'Deskripsi dummy user 24.', 'profile_24.jpg', 'Bahasa Indonesia', 'Jakarta', 1, 0, 0, 'dummy_email_24@example.com', 'insta_user24', 'twitter_user24', 'linkedin_user24', 'github_user24', '2025-03-24 23:41:42');
INSERT INTO `tb_user` (`id_user`, `first_name`, `last_name`, `username`, `password`, `role`, `status`, `deskripsi`, `fotoProfil`, `bahasa`, `zona_waktu`, `balasan_ke_komentar`, `komentar_baru`, `notifikasi_postingan_baru`, `email`, `instagram`, `twitter`, `linkdin`, `github`, `tgl_dibuat`) VALUES
(25, 'DummyF25', 'DummyL25', 'dummy_user_25', '$2y$10$XbV2l11UWBgKxpFHangk8W', 'admin', 'aktif', 'Deskripsi dummy user 25.', 'profile_25.jpg', 'Bahasa Indonesia', 'Tokyo', 0, 1, 0, 'dummy_email_25@example.com', 'insta_user25', 'twitter_user25', 'linkedin_user25', 'github_user25', '2024-07-28 23:41:42');
INSERT INTO `tb_user` (`id_user`, `first_name`, `last_name`, `username`, `password`, `role`, `status`, `deskripsi`, `fotoProfil`, `bahasa`, `zona_waktu`, `balasan_ke_komentar`, `komentar_baru`, `notifikasi_postingan_baru`, `email`, `instagram`, `twitter`, `linkdin`, `github`, `tgl_dibuat`) VALUES
(26, 'DummyF26', 'DummyL26', 'dummy_user_26', '$2y$10$8zLvSWTVGtE3OI9dkiUPIn', 'murid', 'aktif', 'Deskripsi dummy user 26.', 'profile_26.jpg', 'Jepang', 'London', 0, 0, 0, 'dummy_email_26@example.com', 'insta_user26', 'twitter_user26', 'linkedin_user26', 'github_user26', '2024-11-20 23:41:42');
INSERT INTO `tb_user` (`id_user`, `first_name`, `last_name`, `username`, `password`, `role`, `status`, `deskripsi`, `fotoProfil`, `bahasa`, `zona_waktu`, `balasan_ke_komentar`, `komentar_baru`, `notifikasi_postingan_baru`, `email`, `instagram`, `twitter`, `linkdin`, `github`, `tgl_dibuat`) VALUES
(27, 'DummyF27', 'DummyL27', 'dummy_user_27', '$2y$10$jCxJEluRZS9u8ptY1M5Y5L', 'admin', 'non-aktif', 'Deskripsi dummy user 27.', 'profile_27.jpg', 'Inggris', 'London', 0, 0, 0, 'dummy_email_27@example.com', 'insta_user27', 'twitter_user27', 'linkedin_user27', 'github_user27', '2025-05-24 23:41:42');
INSERT INTO `tb_user` (`id_user`, `first_name`, `last_name`, `username`, `password`, `role`, `status`, `deskripsi`, `fotoProfil`, `bahasa`, `zona_waktu`, `balasan_ke_komentar`, `komentar_baru`, `notifikasi_postingan_baru`, `email`, `instagram`, `twitter`, `linkdin`, `github`, `tgl_dibuat`) VALUES
(28, 'DummyF28', 'DummyL28', 'dummy_user_28', '$2y$10$EOFRfHYQ2fQo8bH9XGNwkb', 'mentor', 'aktif', 'Deskripsi dummy user 28.', 'profile_28.jpg', 'Jepang', 'Tokyo', 1, 1, 0, 'dummy_email_28@example.com', 'insta_user28', 'twitter_user28', 'linkedin_user28', 'github_user28', '2024-12-09 23:41:42');
INSERT INTO `tb_user` (`id_user`, `first_name`, `last_name`, `username`, `password`, `role`, `status`, `deskripsi`, `fotoProfil`, `bahasa`, `zona_waktu`, `balasan_ke_komentar`, `komentar_baru`, `notifikasi_postingan_baru`, `email`, `instagram`, `twitter`, `linkdin`, `github`, `tgl_dibuat`) VALUES
(29, 'DummyF29', 'DummyL29', 'dummy_user_29', '$2y$10$sC77erBAVnt0DDpM2hrTv0', 'admin', 'aktif', 'Deskripsi dummy user 29.', 'profile_29.jpg', 'Jepang', 'Jakarta', 0, 0, 0, 'dummy_email_29@example.com', 'insta_user29', 'twitter_user29', 'linkedin_user29', 'github_user29', '2024-09-08 23:41:42');
INSERT INTO `tb_user` (`id_user`, `first_name`, `last_name`, `username`, `password`, `role`, `status`, `deskripsi`, `fotoProfil`, `bahasa`, `zona_waktu`, `balasan_ke_komentar`, `komentar_baru`, `notifikasi_postingan_baru`, `email`, `instagram`, `twitter`, `linkdin`, `github`, `tgl_dibuat`) VALUES
(30, 'DummyF30', 'DummyL30', 'dummy_user_30', '$2y$10$KjjlDwN2pdpAnsICLOxhVv', 'murid', 'aktif', 'Deskripsi dummy user 30.', 'profile_30.jpg', 'Inggris', 'London', 1, 0, 1, 'dummy_email_30@example.com', 'insta_user30', 'twitter_user30', 'linkedin_user30', 'github_user30', '2024-08-16 23:41:42');
INSERT INTO `tb_user` (`id_user`, `first_name`, `last_name`, `username`, `password`, `role`, `status`, `deskripsi`, `fotoProfil`, `bahasa`, `zona_waktu`, `balasan_ke_komentar`, `komentar_baru`, `notifikasi_postingan_baru`, `email`, `instagram`, `twitter`, `linkdin`, `github`, `tgl_dibuat`) VALUES
(31, 'DummyF31', 'DummyL31', 'dummy_user_31', '$2y$10$eXOrthok8GrTyyqhYEjlnU', 'murid', 'non-aktif', 'Deskripsi dummy user 31.', 'profile_31.jpg', 'Inggris', 'Jakarta', 1, 1, 0, 'dummy_email_31@example.com', 'insta_user31', 'twitter_user31', 'linkedin_user31', 'github_user31', '2024-08-05 23:41:42');
INSERT INTO `tb_user` (`id_user`, `first_name`, `last_name`, `username`, `password`, `role`, `status`, `deskripsi`, `fotoProfil`, `bahasa`, `zona_waktu`, `balasan_ke_komentar`, `komentar_baru`, `notifikasi_postingan_baru`, `email`, `instagram`, `twitter`, `linkdin`, `github`, `tgl_dibuat`) VALUES
(32, 'DummyF32', 'DummyL32', 'dummy_user_32', '$2y$10$477RUjaXGkghmpVGC3iyvY', 'murid', 'non-aktif', 'Deskripsi dummy user 32.', 'profile_32.jpg', 'Inggris', 'Tokyo', 1, 0, 0, 'dummy_email_32@example.com', 'insta_user32', 'twitter_user32', 'linkedin_user32', 'github_user32', '2024-11-21 23:41:42');
INSERT INTO `tb_user` (`id_user`, `first_name`, `last_name`, `username`, `password`, `role`, `status`, `deskripsi`, `fotoProfil`, `bahasa`, `zona_waktu`, `balasan_ke_komentar`, `komentar_baru`, `notifikasi_postingan_baru`, `email`, `instagram`, `twitter`, `linkdin`, `github`, `tgl_dibuat`) VALUES
(33, 'DummyF33', 'DummyL33', 'dummy_user_33', '$2y$10$aRZRElV6XDjov1ifMfn3py', 'murid', 'aktif', 'Deskripsi dummy user 33.', 'profile_33.jpg', 'Inggris', 'Jakarta', 0, 1, 0, 'dummy_email_33@example.com', 'insta_user33', 'twitter_user33', 'linkedin_user33', 'github_user33', '2025-05-18 23:41:42');
INSERT INTO `tb_user` (`id_user`, `first_name`, `last_name`, `username`, `password`, `role`, `status`, `deskripsi`, `fotoProfil`, `bahasa`, `zona_waktu`, `balasan_ke_komentar`, `komentar_baru`, `notifikasi_postingan_baru`, `email`, `instagram`, `twitter`, `linkdin`, `github`, `tgl_dibuat`) VALUES
(34, 'DummyF34', 'DummyL34', 'dummy_user_34', '$2y$10$ODsOguIL0boyPosnuEagAh', 'admin', 'aktif', 'Deskripsi dummy user 34.', 'profile_34.jpg', 'Bahasa Indonesia', 'Jakarta', 1, 1, 1, 'dummy_email_34@example.com', 'insta_user34', 'twitter_user34', 'linkedin_user34', 'github_user34', '2025-01-09 23:41:42');
INSERT INTO `tb_user` (`id_user`, `first_name`, `last_name`, `username`, `password`, `role`, `status`, `deskripsi`, `fotoProfil`, `bahasa`, `zona_waktu`, `balasan_ke_komentar`, `komentar_baru`, `notifikasi_postingan_baru`, `email`, `instagram`, `twitter`, `linkdin`, `github`, `tgl_dibuat`) VALUES
(35, 'DummyF35', 'DummyL35', 'dummy_user_35', '$2y$10$xZQ5XkQvnD5GnAiJdlTIzi', 'admin', 'aktif', 'Deskripsi dummy user 35.', 'profile_35.jpg', 'Inggris', 'Tokyo', 1, 1, 0, 'dummy_email_35@example.com', 'insta_user35', 'twitter_user35', 'linkedin_user35', 'github_user35', '2024-10-21 23:41:42');
INSERT INTO `tb_user` (`id_user`, `first_name`, `last_name`, `username`, `password`, `role`, `status`, `deskripsi`, `fotoProfil`, `bahasa`, `zona_waktu`, `balasan_ke_komentar`, `komentar_baru`, `notifikasi_postingan_baru`, `email`, `instagram`, `twitter`, `linkdin`, `github`, `tgl_dibuat`) VALUES
(36, 'DummyF36', 'DummyL36', 'dummy_user_36', '$2y$10$HmHYi9fVx0q2IrAd4yn7hS', 'murid', 'non-aktif', 'Deskripsi dummy user 36.', 'profile_36.jpg', 'Bahasa Indonesia', 'Jakarta', 1, 1, 1, 'dummy_email_36@example.com', 'insta_user36', 'twitter_user36', 'linkedin_user36', 'github_user36', '2025-05-13 23:41:42');
INSERT INTO `tb_user` (`id_user`, `first_name`, `last_name`, `username`, `password`, `role`, `status`, `deskripsi`, `fotoProfil`, `bahasa`, `zona_waktu`, `balasan_ke_komentar`, `komentar_baru`, `notifikasi_postingan_baru`, `email`, `instagram`, `twitter`, `linkdin`, `github`, `tgl_dibuat`) VALUES
(37, 'DummyF37', 'DummyL37', 'dummy_user_37', '$2y$10$EQmYOr3uG78PzNKUrfnlVC', 'murid', 'aktif', 'Deskripsi dummy user 37.', 'profile_37.jpg', 'Jepang', 'London', 1, 0, 0, 'dummy_email_37@example.com', 'insta_user37', 'twitter_user37', 'linkedin_user37', 'github_user37', '2024-07-28 23:41:42');
INSERT INTO `tb_user` (`id_user`, `first_name`, `last_name`, `username`, `password`, `role`, `status`, `deskripsi`, `fotoProfil`, `bahasa`, `zona_waktu`, `balasan_ke_komentar`, `komentar_baru`, `notifikasi_postingan_baru`, `email`, `instagram`, `twitter`, `linkdin`, `github`, `tgl_dibuat`) VALUES
(38, 'DummyF38', 'DummyL38', 'dummy_user_38', '$2y$10$MUvHRdicuxxDvQhNdAvCTY', 'admin', 'aktif', 'Deskripsi dummy user 38.', 'profile_38.jpg', 'Jepang', 'Jakarta', 0, 0, 1, 'dummy_email_38@example.com', 'insta_user38', 'twitter_user38', 'linkedin_user38', 'github_user38', '2024-11-20 23:41:42');
INSERT INTO `tb_user` (`id_user`, `first_name`, `last_name`, `username`, `password`, `role`, `status`, `deskripsi`, `fotoProfil`, `bahasa`, `zona_waktu`, `balasan_ke_komentar`, `komentar_baru`, `notifikasi_postingan_baru`, `email`, `instagram`, `twitter`, `linkdin`, `github`, `tgl_dibuat`) VALUES
(39, 'DummyF39', 'DummyL39', 'dummy_user_39', '$2y$10$tLJXaxijEpImOVbl1yNqJz', 'murid', 'aktif', 'Deskripsi dummy user 39.', 'profile_39.jpg', 'Bahasa Indonesia', 'Jakarta', 0, 0, 0, 'dummy_email_39@example.com', 'insta_user39', 'twitter_user39', 'linkedin_user39', 'github_user39', '2025-05-17 23:41:42');
INSERT INTO `tb_user` (`id_user`, `first_name`, `last_name`, `username`, `password`, `role`, `status`, `deskripsi`, `fotoProfil`, `bahasa`, `zona_waktu`, `balasan_ke_komentar`, `komentar_baru`, `notifikasi_postingan_baru`, `email`, `instagram`, `twitter`, `linkdin`, `github`, `tgl_dibuat`) VALUES
(40, 'DummyF40', 'DummyL40', 'dummy_user_40', '$2y$10$fwnSCPxOQyVTU6MtBCtEIi', 'mentor', 'aktif', 'Deskripsi dummy user 40.', 'profile_40.jpg', 'Bahasa Indonesia', 'Tokyo', 1, 0, 0, 'dummy_email_40@example.com', 'insta_user40', 'twitter_user40', 'linkedin_user40', 'github_user40', '2024-09-03 23:41:42');
INSERT INTO `tb_user` (`id_user`, `first_name`, `last_name`, `username`, `password`, `role`, `status`, `deskripsi`, `fotoProfil`, `bahasa`, `zona_waktu`, `balasan_ke_komentar`, `komentar_baru`, `notifikasi_postingan_baru`, `email`, `instagram`, `twitter`, `linkdin`, `github`, `tgl_dibuat`) VALUES
(41, 'DummyF41', 'DummyL41', 'dummy_user_41', '$2y$10$RNV7lAO5Pzw9xDOTFORIvJ', 'admin', 'aktif', 'Deskripsi dummy user 41.', 'profile_41.jpg', 'Jepang', 'London', 0, 0, 1, 'dummy_email_41@example.com', 'insta_user41', 'twitter_user41', 'linkedin_user41', 'github_user41', '2024-08-01 23:41:42');
INSERT INTO `tb_user` (`id_user`, `first_name`, `last_name`, `username`, `password`, `role`, `status`, `deskripsi`, `fotoProfil`, `bahasa`, `zona_waktu`, `balasan_ke_komentar`, `komentar_baru`, `notifikasi_postingan_baru`, `email`, `instagram`, `twitter`, `linkdin`, `github`, `tgl_dibuat`) VALUES
(42, 'DummyF42', 'DummyL42', 'dummy_user_42', '$2y$10$xssm0tzzo8AkNY6z3PT5h0', 'mentor', 'non-aktif', 'Deskripsi dummy user 42.', 'profile_42.jpg', 'Bahasa Indonesia', 'Tokyo', 0, 0, 1, 'dummy_email_42@example.com', 'insta_user42', 'twitter_user42', 'linkedin_user42', 'github_user42', '2024-11-09 23:41:42');


/* -- DUMMY DATA FOR tb_mentor -- */
INSERT INTO `tb_mentor` (`id_mentor`, `status`, `id_user`) VALUES
(8, 'Aktif', 23);
INSERT INTO `tb_mentor` (`id_mentor`, `status`, `id_user`) VALUES
(9, 'Aktif', 24);
INSERT INTO `tb_mentor` (`id_mentor`, `status`, `id_user`) VALUES
(10, 'Aktif', 25);
INSERT INTO `tb_mentor` (`id_mentor`, `status`, `id_user`) VALUES
(11, 'Non-Aktif', 27);
INSERT INTO `tb_mentor` (`id_mentor`, `status`, `id_user`) VALUES
(12, 'Non-Aktif', 29);


/* -- DUMMY DATA FOR tb_dokumen -- */
INSERT INTO `tb_dokumen` (`id_dokumen`, `file_path_dokumen`, `status`) VALUES
(6, '../uploads/dokumen/DUMMYDOC_6.pdf', 'aktif');
INSERT INTO `tb_dokumen` (`id_dokumen`, `file_path_dokumen`, `status`) VALUES
(7, '../uploads/dokumen/DUMMYDOC_7.pdf', 'pending');
INSERT INTO `tb_dokumen` (`id_dokumen`, `file_path_dokumen`, `status`) VALUES
(8, '../uploads/dokumen/DUMMYDOC_8.pdf', 'aktif');
INSERT INTO `tb_dokumen` (`id_dokumen`, `file_path_dokumen`, `status`) VALUES
(9, '../uploads/dokumen/DUMMYDOC_9.pdf', 'non-aktif');
INSERT INTO `tb_dokumen` (`id_dokumen`, `file_path_dokumen`, `status`) VALUES
(10, '../uploads/dokumen/DUMMYDOC_10.pdf', 'non-aktif');
INSERT INTO `tb_dokumen` (`id_dokumen`, `file_path_dokumen`, `status`) VALUES
(11, '../uploads/dokumen/DUMMYDOC_11.pdf', 'aktif');
INSERT INTO `tb_dokumen` (`id_dokumen`, `file_path_dokumen`, `status`) VALUES
(12, '../uploads/dokumen/DUMMYDOC_12.pdf', 'non-aktif');
INSERT INTO `tb_dokumen` (`id_dokumen`, `file_path_dokumen`, `status`) VALUES
(13, '../uploads/dokumen/DUMMYDOC_13.pdf', 'non-aktif');
INSERT INTO `tb_dokumen` (`id_dokumen`, `file_path_dokumen`, `status`) VALUES
(14, '../uploads/dokumen/DUMMYDOC_14.pdf', 'aktif');
INSERT INTO `tb_dokumen` (`id_dokumen`, `file_path_dokumen`, `status`) VALUES
(15, '../uploads/dokumen/DUMMYDOC_15.pdf', 'non-aktif');


/* -- DUMMY DATA FOR tb_video -- */
INSERT INTO `tb_video` (`id_video`, `file_path_video`, `status`) VALUES
(6, '../uploads/video/DUMMYVID_6.mp4', 'aktif');
INSERT INTO `tb_video` (`id_video`, `file_path_video`, `status`) VALUES
(7, '../uploads/video/DUMMYVID_7.mp4', 'aktif');
INSERT INTO `tb_video` (`id_video`, `file_path_video`, `status`) VALUES
(8, '../uploads/video/DUMMYVID_8.mp4', 'pending');
INSERT INTO `tb_video` (`id_video`, `file_path_video`, `status`) VALUES
(9, '../uploads/video/DUMMYVID_9.mp4', 'pending');
INSERT INTO `tb_video` (`id_video`, `file_path_video`, `status`) VALUES
(10, '../uploads/video/DUMMYVID_10.mp4', 'aktif');
INSERT INTO `tb_video` (`id_video`, `file_path_video`, `status`) VALUES
(11, '../uploads/video/DUMMYVID_11.mp4', 'non-aktif');
INSERT INTO `tb_video` (`id_video`, `file_path_video`, `status`) VALUES
(12, '../uploads/video/DUMMYVID_12.mp4', 'aktif');
INSERT INTO `tb_video` (`id_video`, `file_path_video`, `status`) VALUES
(13, '../uploads/video/DUMMYVID_13.mp4', 'aktif');
INSERT INTO `tb_video` (`id_video`, `file_path_video`, `status`) VALUES
(14, '../uploads/video/DUMMYVID_14.mp4', 'aktif');
INSERT INTO `tb_video` (`id_video`, `file_path_video`, `status`) VALUES
(15, '../uploads/video/DUMMYVID_15.mp4', 'aktif');


/* -- DUMMY DATA FOR tb_kelas -- */
INSERT INTO `tb_kelas` (`id_kelas`, `id_mentor`, `nama_kelas`, `kategori`, `harga`, `profil_kelas`, `badge`, `description`, `status_publikasi`, `tgl_dibuat`) VALUES
(8, 10, 'Kelas Dummy 8: Intensif Pemrograman', 'SQL', 302061.27, NULL, NULL, 'Deskripsi detail untuk Kelas Dummy 8, mencakup topik-topik penting dan tujuan pembelajaran.', 'pending', '2025-05-14 23:41:42');
INSERT INTO `tb_kelas` (`id_kelas`, `id_mentor`, `nama_kelas`, `kategori`, `harga`, `profil_kelas`, `badge`, `description`, `status_publikasi`, `tgl_dibuat`) VALUES
(9, 11, 'Kelas Dummy 9: Dasar Pemrograman', 'Ekonomi', 323067.89, NULL, NULL, 'Deskripsi detail untuk Kelas Dummy 9, mencakup topik-topik penting dan tujuan pembelajaran.', 'non-aktif', '2025-05-27 23:41:42');
INSERT INTO `tb_kelas` (`id_kelas`, `id_mentor`, `nama_kelas`, `kategori`, `harga`, `profil_kelas`, `badge`, `description`, `status_publikasi`, `tgl_dibuat`) VALUES
(10, 11, 'Kelas Dummy 10: Intensif Desain', 'Web Development', 315488.24, NULL, NULL, 'Deskripsi detail untuk Kelas Dummy 10, mencakup topik-topik penting dan tujuan pembelajaran.', 'non-aktif', '2024-12-21 23:41:42');
INSERT INTO `tb_kelas` (`id_kelas`, `id_mentor`, `nama_kelas`, `kategori`, `harga`, `profil_kelas`, `badge`, `description`, `status_publikasi`, `tgl_dibuat`) VALUES
(11, 10, 'Kelas Dummy 11: Lanjutan Data Science', 'Design', 497886.72, NULL, NULL, 'Deskripsi detail untuk Kelas Dummy 11, mencakup topik-topik penting dan tujuan pembelajaran.', 'aktif', '2025-05-24 23:41:42');
INSERT INTO `tb_kelas` (`id_kelas`, `id_mentor`, `nama_kelas`, `kategori`, `harga`, `profil_kelas`, `badge`, `description`, `status_publikasi`, `tgl_dibuat`) VALUES
(12, 1, 'Kelas Dummy 12: Intensif Pemrograman', 'Web Development', 309710.27, NULL, NULL, 'Deskripsi detail untuk Kelas Dummy 12, mencakup topik-topik penting dan tujuan pembelajaran.', 'non-aktif', '2025-03-24 23:41:42');
INSERT INTO `tb_kelas` (`id_kelas`, `id_mentor`, `nama_kelas`, `kategori`, `harga`, `profil_kelas`, `badge`, `description`, `status_publikasi`, `tgl_dibuat`) VALUES
(13, 3, 'Kelas Dummy 13: Lanjutan Desain', 'Java', 201174.19, NULL, NULL, 'Deskripsi detail untuk Kelas Dummy 13, mencakup topik-topik penting dan tujuan pembelajaran.', 'rejected', '2025-03-29 23:41:42');
INSERT INTO `tb_kelas` (`id_kelas`, `id_mentor`, `nama_kelas`, `kategori`, `harga`, `profil_kelas`, `badge`, `description`, `status_publikasi`, `tgl_dibuat`) VALUES
(14, 11, 'Kelas Dummy 14: Intensif Pemrograman', 'Web Development', 298132.89, NULL, NULL, 'Deskripsi detail untuk Kelas Dummy 14, mencakup topik-topik penting dan tujuan pembelajaran.', 'pending', '2024-12-22 23:41:42');
INSERT INTO `tb_kelas` (`id_kelas`, `id_mentor`, `nama_kelas`, `kategori`, `harga`, `profil_kelas`, `badge`, `description`, `status_publikasi`, `tgl_dibuat`) VALUES
(15, 12, 'Kelas Dummy 15: Dasar Pemrograman', 'Psikologi', 205628.70, NULL, NULL, 'Deskripsi detail untuk Kelas Dummy 15, mencakup topik-topik penting dan tujuan pembelajaran.', 'aktif', '2025-03-20 23:41:42');
INSERT INTO `tb_kelas` (`id_kelas`, `id_mentor`, `nama_kelas`, `kategori`, `harga`, `profil_kelas`, `badge`, `description`, `status_publikasi`, `tgl_dibuat`) VALUES
(16, 1, 'Kelas Dummy 16: Dasar Pemrograman', 'Ekonomi', 477464.73, NULL, NULL, 'Deskripsi detail untuk Kelas Dummy 16, mencakup topik-topik penting dan tujuan pembelajaran.', 'pending', '2025-03-01 23:41:42');
INSERT INTO `tb_kelas` (`id_kelas`, `id_mentor`, `nama_kelas`, `kategori`, `harga`, `profil_kelas`, `badge`, `description`, `status_publikasi`, `tgl_dibuat`) VALUES
(17, 3, 'Kelas Dummy 17: Dasar Data Science', 'Bisnis', 110620.35, NULL, NULL, 'Deskripsi detail untuk Kelas Dummy 17, mencakup topik-topik penting dan tujuan pembelajaran.', 'aktif', '2025-05-18 23:41:42');


/* -- DUMMY DATA FOR tb_materi -- */
INSERT INTO `tb_materi` (`id_materi`, `id_kelas`, `urutan`, `judul_materi`, `status`, `tgl_dibuat_materi`) VALUES
(8, 14, 3, 'Materi Dummy 8: Bab 3 Konsep Dasar', 'aktif', '2025-05-09 23:41:42');
INSERT INTO `tb_materi` (`id_materi`, `id_kelas`, `urutan`, `judul_materi`, `status`, `tgl_dibuat_materi`) VALUES
(9, 13, 10, 'Materi Dummy 9: Bab 10 Studi Kasus', 'non-aktif', '2025-05-10 23:41:42');
INSERT INTO `tb_materi` (`id_materi`, `id_kelas`, `urutan`, `judul_materi`, `status`, `tgl_dibuat_materi`) VALUES
(10, 16, 1, 'Materi Dummy 10: Bab 1 Pendahuluan', 'pending', '2025-05-03 23:41:42');
INSERT INTO `tb_materi` (`id_materi`, `id_kelas`, `urutan`, `judul_materi`, `status`, `tgl_dibuat_materi`) VALUES
(11, 15, 6, 'Materi Dummy 11: Bab 6 Pendahuluan', 'aktif', '2025-05-24 23:41:42');
INSERT INTO `tb_materi` (`id_materi`, `id_kelas`, `urutan`, `judul_materi`, `status`, `tgl_dibuat_materi`) VALUES
(12, 11, 4, 'Materi Dummy 12: Bab 4 Konsep Dasar', 'aktif', '2025-04-14 23:41:42');
INSERT INTO `tb_materi` (`id_materi`, `id_kelas`, `urutan`, `judul_materi`, `status`, `tgl_dibuat_materi`) VALUES
(13, 1, 9, 'Materi Dummy 13: Bab 9 Studi Kasus', 'non-aktif', '2025-05-08 23:41:42');
INSERT INTO `tb_materi` (`id_materi`, `id_kelas`, `urutan`, `judul_materi`, `status`, `tgl_dibuat_materi`) VALUES
(14, 10, 10, 'Materi Dummy 14: Bab 10 Studi Kasus', 'pending', '2025-05-05 23:41:42');
INSERT INTO `tb_materi` (`id_materi`, `id_kelas`, `urutan`, `judul_materi`, `status`, `tgl_dibuat_materi`) VALUES
(15, 14, 7, 'Materi Dummy 15: Bab 7 Konsep Dasar', 'non-aktif', '2025-05-16 23:41:42');
INSERT INTO `tb_materi` (`id_materi`, `id_kelas`, `urutan`, `judul_materi`, `status`, `tgl_dibuat_materi`) VALUES
(16, 16, 1, 'Materi Dummy 16: Bab 1 Pendahuluan', 'aktif', '2025-05-09 23:41:42');
INSERT INTO `tb_materi` (`id_materi`, `id_kelas`, `urutan`, `judul_materi`, `status`, `tgl_dibuat_materi`) VALUES
(17, 13, 8, 'Materi Dummy 17: Bab 8 Pendahuluan', 'aktif', '2025-04-09 23:41:42');
INSERT INTO `tb_materi` (`id_materi`, `id_kelas`, `urutan`, `judul_materi`, `status`, `tgl_dibuat_materi`) VALUES
(18, 16, 7, 'Materi Dummy 18: Bab 7 Pendahuluan', 'aktif', '2025-05-11 23:41:42');
INSERT INTO `tb_materi` (`id_materi`, `id_kelas`, `urutan`, `judul_materi`, `status`, `tgl_dibuat_materi`) VALUES
(19, 13, 5, 'Materi Dummy 19: Bab 5 Studi Kasus', 'aktif', '2025-05-19 23:41:42');
INSERT INTO `tb_materi` (`id_materi`, `id_kelas`, `urutan`, `judul_materi`, `status`, `tgl_dibuat_materi`) VALUES
(20, 10, 10, 'Materi Dummy 20: Bab 10 Studi Kasus', 'pending', '2025-04-26 23:41:42');
INSERT INTO `tb_materi` (`id_materi`, `id_kelas`, `urutan`, `judul_materi`, `status`, `tgl_dibuat_materi`) VALUES
(21, 16, 1, 'Materi Dummy 21: Bab 1 Pendahuluan', 'pending', '2025-05-13 23:41:42');
INSERT INTO `tb_materi` (`id_materi`, `id_kelas`, `urutan`, `judul_materi`, `status`, `tgl_dibuat_materi`) VALUES
(22, 17, 10, 'Materi Dummy 22: Bab 10 Pendahuluan', 'non-aktif', '2025-04-12 23:41:42');
INSERT INTO `tb_materi` (`id_materi`, `id_kelas`, `urutan`, `judul_materi`, `status`, `tgl_dibuat_materi`) VALUES
(23, 4, 3, 'Materi Dummy 23: Bab 3 Studi Kasus', 'pending', '2025-03-23 23:41:42');
INSERT INTO `tb_materi` (`id_materi`, `id_kelas`, `urutan`, `judul_materi`, `status`, `tgl_dibuat_materi`) VALUES
(24, 11, 10, 'Materi Dummy 24: Bab 10 Konsep Dasar', 'aktif', '2025-05-09 23:41:42');
INSERT INTO `tb_materi` (`id_materi`, `id_kelas`, `urutan`, `judul_materi`, `status`, `tgl_dibuat_materi`) VALUES
(25, 9, 3, 'Materi Dummy 25: Bab 3 Studi Kasus', 'aktif', '2025-04-11 23:41:42');
INSERT INTO `tb_materi` (`id_materi`, `id_kelas`, `urutan`, `judul_materi`, `status`, `tgl_dibuat_materi`) VALUES
(26, 16, 7, 'Materi Dummy 26: Bab 7 Konsep Dasar', 'pending', '2025-05-01 23:41:42');
INSERT INTO `tb_materi` (`id_materi`, `id_kelas`, `urutan`, `judul_materi`, `status`, `tgl_dibuat_materi`) VALUES
(27, 6, 8, 'Materi Dummy 27: Bab 8 Konsep Dasar', 'pending', '2025-05-27 23:41:42');


/* -- DUMMY DATA FOR tb_sub_materi -- */
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(8, 14, 14, 8, 5, 'Sub Materi Dummy 8: Latihan Topik B', 'non-aktif', '2025-05-18 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(9, 13, 11, 11, 4, 'Sub Materi Dummy 9: Praktik Topik A', 'pending', '2025-05-23 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(10, 18, 14, 7, 5, 'Sub Materi Dummy 10: Pengantar Topik B', 'aktif', '2025-05-22 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(11, 23, 0, 10, 5, 'Sub Materi Dummy 11: Praktik Topik B', 'pending', '2025-05-18 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(12, 10, 12, 10, 2, 'Sub Materi Dummy 12: Pengantar Topik A', 'aktif', '2025-05-24 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(13, 19, 12, 15, 3, 'Sub Materi Dummy 13: Praktik Topik A', 'non-aktif', '2025-05-26 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(14, 18, 13, 14, 1, 'Sub Materi Dummy 14: Latihan Topik B', 'non-aktif', '2025-05-09 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(15, 11, 15, 14, 3, 'Sub Materi Dummy 15: Latihan Topik B', 'aktif', '2025-05-08 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(16, 17, 7, 13, 1, 'Sub Materi Dummy 16: Pengantar Topik C', 'aktif', '2025-05-07 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(17, 11, 0, 11, 5, 'Sub Materi Dummy 17: Praktik Topik B', 'non-aktif', '2025-05-23 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(18, 13, 4, 3, 4, 'Sub Materi Dummy 18: Praktik Topik B', 'aktif', '2025-05-24 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(19, 21, 1, 10, 2, 'Sub Materi Dummy 19: Latihan Topik A', 'non-aktif', '2025-05-04 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(20, 24, 7, 7, 3, 'Sub Materi Dummy 20: Praktik Topik A', 'aktif', '2025-05-24 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(21, 19, 10, 15, 2, 'Sub Materi Dummy 21: Pengantar Topik C', 'aktif', '2025-05-27 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(22, 10, 0, 8, 4, 'Sub Materi Dummy 22: Latihan Topik C', 'aktif', '2025-05-03 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(23, 20, 14, 1, 3, 'Sub Materi Dummy 23: Praktik Topik B', 'pending', '2025-05-11 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(24, 15, 6, 12, 1, 'Sub Materi Dummy 24: Latihan Topik B', 'aktif', '2025-05-26 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(25, 26, 11, 4, 3, 'Sub Materi Dummy 25: Latihan Topik B', 'non-aktif', '2025-05-27 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(26, 21, 1, 7, 5, 'Sub Materi Dummy 26: Pengantar Topik B', 'aktif', '2025-05-02 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(27, 10, 13, 11, 1, 'Sub Materi Dummy 27: Latihan Topik A', 'aktif', '2025-05-19 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(28, 12, 13, 11, 5, 'Sub Materi Dummy 28: Pengantar Topik B', 'non-aktif', '2025-05-02 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(29, 9, 8, 12, 1, 'Sub Materi Dummy 29: Praktik Topik C', 'non-aktif', '2025-05-18 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(30, 20, 10, 12, 3, 'Sub Materi Dummy 30: Praktik Topik A', 'aktif', '2025-05-13 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(31, 23, 1, 0, 5, 'Sub Materi Dummy 31: Latihan Topik A', 'aktif', '2025-05-18 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(32, 23, 8, 8, 4, 'Sub Materi Dummy 32: Praktik Topik C', 'aktif', '2025-05-02 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(33, 19, 13, 1, 5, 'Sub Materi Dummy 33: Praktik Topik C', 'aktif', '2025-05-23 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(34, 18, 5, 14, 5, 'Sub Materi Dummy 34: Latihan Topik A', 'aktif', '2025-05-24 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(35, 16, 12, 10, 4, 'Sub Materi Dummy 35: Latihan Topik A', 'non-aktif', '2025-05-04 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(36, 27, 4, 14, 4, 'Sub Materi Dummy 36: Latihan Topik B', 'aktif', '2025-05-25 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(37, 24, 7, 10, 1, 'Sub Materi Dummy 37: Praktik Topik C', 'non-aktif', '2025-05-18 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(38, 14, 2, 7, 2, 'Sub Materi Dummy 38: Pengantar Topik B', 'aktif', '2025-05-09 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(39, 21, 14, 15, 3, 'Sub Materi Dummy 39: Praktik Topik A', 'non-aktif', '2025-05-08 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(40, 19, 13, 0, 5, 'Sub Materi Dummy 40: Latihan Topik C', 'aktif', '2025-05-06 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(41, 11, 10, 15, 1, 'Sub Materi Dummy 41: Latihan Topik A', 'non-aktif', '2025-05-24 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(42, 10, 14, 9, 2, 'Sub Materi Dummy 42: Pengantar Topik A', 'aktif', '2025-05-27 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(43, 22, 10, 9, 5, 'Sub Materi Dummy 43: Praktik Topik C', 'aktif', '2025-05-20 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(44, 25, 14, 14, 1, 'Sub Materi Dummy 44: Pengantar Topik B', 'non-aktif', '2025-05-20 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(45, 18, 12, 12, 1, 'Sub Materi Dummy 45: Latihan Topik A', 'aktif', '2025-05-17 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(46, 27, 7, 10, 4, 'Sub Materi Dummy 46: Latihan Topik C', 'non-aktif', '2025-05-04 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(47, 10, 11, 14, 3, 'Sub Materi Dummy 47: Praktik Topik B', 'aktif', '2025-05-26 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(48, 12, 11, 12, 4, 'Sub Materi Dummy 48: Pengantar Topik C', 'aktif', '2025-05-18 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(49, 13, 10, 5, 2, 'Sub Materi Dummy 49: Praktik Topik A', 'non-aktif', '2025-05-02 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(50, 19, 15, 0, 5, 'Sub Materi Dummy 50: Latihan Topik B', 'pending', '2025-05-24 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(51, 19, 13, 7, 4, 'Sub Materi Dummy 51: Latihan Topik C', 'non-aktif', '2025-05-09 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(52, 12, 7, 10, 4, 'Sub Materi Dummy 52: Latihan Topik B', 'pending', '2025-05-18 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(53, 14, 0, 6, 4, 'Sub Materi Dummy 53: Latihan Topik C', 'pending', '2025-05-04 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(54, 25, 14, 1, 3, 'Sub Materi Dummy 54: Pengantar Topik B', 'aktif', '2025-05-08 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(55, 17, 0, 11, 2, 'Sub Materi Dummy 55: Pengantar Topik C', 'aktif', '2025-05-27 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(56, 17, 1, 10, 4, 'Sub Materi Dummy 56: Latihan Topik B', 'pending', '2025-05-16 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(57, 13, 4, 15, 3, 'Sub Materi Dummy 57: Latihan Topik C', 'aktif', '2025-05-07 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(58, 25, 13, 2, 5, 'Sub Materi Dummy 58: Latihan Topik A', 'aktif', '2025-05-18 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(59, 21, 14, 13, 5, 'Sub Materi Dummy 59: Pengantar Topik C', 'aktif', '2025-05-23 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(60, 26, 0, 8, 3, 'Sub Materi Dummy 60: Praktik Topik C', 'non-aktif', '2025-05-14 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(61, 23, 10, 0, 1, 'Sub Materi Dummy 61: Latihan Topik A', 'pending', '2025-05-27 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(62, 16, 5, 1, 4, 'Sub Materi Dummy 62: Latihan Topik A', 'pending', '2025-05-18 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(63, 10, 11, 11, 2, 'Sub Materi Dummy 63: Latihan Topik C', 'pending', '2025-05-14 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(64, 27, 2, 0, 1, 'Sub Materi Dummy 64: Latihan Topik B', 'aktif', '2025-05-15 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(65, 15, 6, 12, 1, 'Sub Materi Dummy 65: Pengantar Topik C', 'aktif', '2025-05-14 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(66, 26, 11, 10, 5, 'Sub Materi Dummy 66: Praktik Topik A', 'non-aktif', '2025-05-02 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(67, 17, 10, 9, 3, 'Sub Materi Dummy 67: Latihan Topik C', 'pending', '2025-05-17 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(68, 11, 15, 11, 5, 'Sub Materi Dummy 68: Latihan Topik A', 'pending', '2025-05-02 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(69, 13, 10, 11, 2, 'Sub Materi Dummy 69: Praktik Topik C', 'aktif', '2025-05-23 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(70, 21, 6, 1, 4, 'Sub Materi Dummy 70: Praktik Topik B', 'aktif', '2025-05-07 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(71, 18, 12, 12, 2, 'Sub Materi Dummy 71: Latihan Topik A', 'aktif', '2025-05-13 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(72, 14, 15, 10, 1, 'Sub Materi Dummy 72: Latihan Topik A', 'pending', '2025-05-04 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(73, 19, 10, 15, 4, 'Sub Materi Dummy 73: Praktik Topik C', 'non-aktif', '2025-05-25 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(74, 15, 10, 8, 1, 'Sub Materi Dummy 74: Pengantar Topik B', 'aktif', '2025-05-23 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(75, 10, 15, 14, 5, 'Sub Materi Dummy 75: Pengantar Topik C', 'aktif', '2025-05-24 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(76, 26, 12, 12, 5, 'Sub Materi Dummy 76: Pengantar Topik A', 'aktif', '2025-05-20 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(77, 23, 0, 15, 1, 'Sub Materi Dummy 77: Praktik Topik C', 'aktif', '2025-05-09 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(78, 20, 12, 11, 4, 'Sub Materi Dummy 78: Latihan Topik A', 'non-aktif', '2025-05-10 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(79, 17, 10, 7, 5, 'Sub Materi Dummy 79: Latihan Topik C', 'non-aktif', '2025-05-27 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(80, 15, 6, 4, 3, 'Sub Materi Dummy 80: Latihan Topik C', 'pending', '2025-05-05 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(81, 10, 10, 11, 2, 'Sub Materi Dummy 81: Praktik Topik B', 'aktif', '2025-05-18 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(82, 19, 15, 15, 2, 'Sub Materi Dummy 82: Pengantar Topik C', 'non-aktif', '2025-05-19 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(83, 18, 5, 0, 5, 'Sub Materi Dummy 83: Pengantar Topik C', 'aktif', '2025-05-22 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(84, 15, 14, 1, 3, 'Sub Materi Dummy 84: Praktik Topik A', 'non-aktif', '2025-05-15 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(85, 27, 8, 13, 1, 'Sub Materi Dummy 85: Pengantar Topik C', 'pending', '2025-05-22 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(86, 20, 12, 14, 4, 'Sub Materi Dummy 86: Latihan Topik C', 'aktif', '2025-05-25 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(87, 19, 11, 2, 4, 'Sub Materi Dummy 87: Latihan Topik B', 'non-aktif', '2025-05-25 23:41:42');
INSERT INTO `tb_sub_materi` (`id_sub_materi`, `id_materi`, `id_dokumen`, `id_video`, `urutan`, `judul_sub_materi`, `status`, `tgl_dibuat_subMateri`) VALUES
(88, 22, 0, 9, 1, 'Sub Materi Dummy 88: Praktik Topik A', 'aktif', '2025-05-15 23:41:42');


/* -- DUMMY DATA FOR tb_komentar -- */
INSERT INTO `tb_komentar` (`id_komentar`, `isi`, `id_user`, `id_kelas`) VALUES
(5, 'Ini adalah komentar dummy ke-5 tentang kelas ini. Sangat membantu.', 39, 12);
INSERT INTO `tb_komentar` (`id_komentar`, `isi`, `id_user`, `id_kelas`) VALUES
(6, 'Ini adalah komentar dummy ke-6 tentang kelas ini. Sangat bagus.', 35, 14);
INSERT INTO `tb_komentar` (`id_komentar`, `isi`, `id_user`, `id_kelas`) VALUES
(7, 'Ini adalah komentar dummy ke-7 tentang kelas ini. Sangat kurang jelas.', 30, 16);
INSERT INTO `tb_komentar` (`id_komentar`, `isi`, `id_user`, `id_kelas`) VALUES
(8, 'Ini adalah komentar dummy ke-8 tentang kelas ini. Sangat informatif.', 27, 17);
INSERT INTO `tb_komentar` (`id_komentar`, `isi`, `id_user`, `id_kelas`) VALUES
(9, 'Ini adalah komentar dummy ke-9 tentang kelas ini. Sangat kurang jelas.', 23, 16);
INSERT INTO `tb_komentar` (`id_komentar`, `isi`, `id_user`, `id_kelas`) VALUES
(10, 'Ini adalah komentar dummy ke-10 tentang kelas ini. Sangat menarik.', 38, 12);
INSERT INTO `tb_komentar` (`id_komentar`, `isi`, `id_user`, `id_kelas`) VALUES
(11, 'Ini adalah komentar dummy ke-11 tentang kelas ini. Sangat kurang jelas.', 24, 15);
INSERT INTO `tb_komentar` (`id_komentar`, `isi`, `id_user`, `id_kelas`) VALUES
(12, 'Ini adalah komentar dummy ke-12 tentang kelas ini. Sangat menarik.', 35, 14);
INSERT INTO `tb_komentar` (`id_komentar`, `isi`, `id_user`, `id_kelas`) VALUES
(13, 'Ini adalah komentar dummy ke-13 tentang kelas ini. Sangat bagus.', 33, 11);
INSERT INTO `tb_komentar` (`id_komentar`, `isi`, `id_user`, `id_kelas`) VALUES
(14, 'Ini adalah komentar dummy ke-14 tentang kelas ini. Sangat membantu.', 39, 9);
INSERT INTO `tb_komentar` (`isi`, `id_user`, `id_kelas`) VALUES
(15, 'Ini adalah komentar dummy ke-15 tentang kelas ini. Sangat membantu.', 28, 14);
INSERT INTO `tb_komentar` (`isi`, `id_user`, `id_kelas`) VALUES
(16, 'Ini adalah komentar dummy ke-16 tentang kelas ini. Sangat membantu.', 30, 14);
INSERT INTO `tb_komentar` (`isi`, `id_user`, `id_kelas`) VALUES
(17, 'Ini adalah komentar dummy ke-17 tentang kelas ini. Sangat informatif.', 34, 15);
INSERT INTO `tb_komentar` (`isi`, `id_user`, `id_kelas`) VALUES
(18, 'Ini adalah komentar dummy ke-18 tentang kelas ini. Sangat bagus.', 35, 16);
INSERT INTO `tb_komentar` (`isi`, `id_user`, `id_kelas`) VALUES
(19, 'Ini adalah komentar dummy ke-19 tentang kelas ini. Sangat bagus.', 28, 10);
INSERT INTO `tb_komentar` (`isi`, `id_user`, `id_kelas`) VALUES
(20, 'Ini adalah komentar dummy ke-20 tentang kelas ini. Sangat membantu.', 27, 16);
INSERT INTO `tb_komentar` (`isi`, `id_user`, `id_kelas`) VALUES
(21, 'Ini adalah komentar dummy ke-21 tentang kelas ini. Sangat membantu.', 27, 17);
INSERT INTO `tb_komentar` (`isi`, `id_user`, `id_kelas`) VALUES
(22, 'Ini adalah komentar dummy ke-22 tentang kelas ini. Sangat menarik.', 36, 17);
INSERT INTO `tb_komentar` (`isi`, `id_user`, `id_kelas`) VALUES
(23, 'Ini adalah komentar dummy ke-23 tentang kelas ini. Sangat membantu.', 31, 10);
INSERT INTO `tb_komentar` (`isi`, `id_user`, `id_kelas`) VALUES
(24, 'Ini adalah komentar dummy ke-24 tentang kelas ini. Sangat bagus.', 32, 10);
INSERT INTO `tb_komentar` (`isi`, `id_user`, `id_kelas`) VALUES
(25, 'Ini adalah komentar dummy ke-25 tentang kelas ini. Sangat menarik.', 27, 11);
INSERT INTO `tb_komentar` (`isi`, `id_user`, `id_kelas`) VALUES
(26, 'Ini adalah komentar dummy ke-26 tentang kelas ini. Sangat kurang jelas.', 30, 9);
INSERT INTO `tb_komentar` (`isi`, `id_user`, `id_kelas`) VALUES
(27, 'Ini adalah komentar dummy ke-27 tentang kelas ini. Sangat bagus.', 26, 15);
INSERT INTO `tb_komentar` (`isi`, `id_user`, `id_kelas`) VALUES
(28, 'Ini adalah komentar dummy ke-28 tentang kelas ini. Sangat kurang jelas.', 34, 16);
INSERT INTO `tb_komentar` (`isi`, `id_user`, `id_kelas`) VALUES
(29, 'Ini adalah komentar dummy ke-29 tentang kelas ini. Sangat informatif.', 27, 17);
INSERT INTO `tb_komentar` (`isi`, `id_user`, `id_kelas`) VALUES
(30, 'Ini adalah komentar dummy ke-30 tentang kelas ini. Sangat bagus.', 27, 13);
INSERT INTO `tb_komentar` (`isi`, `id_user`, `id_kelas`) VALUES
(31, 'Ini adalah komentar dummy ke-31 tentang kelas ini. Sangat informatif.', 26, 12);
INSERT INTO `tb_komentar` (`isi`, `id_user`, `id_kelas`) VALUES
(32, 'Ini adalah komentar dummy ke-32 tentang kelas ini. Sangat menarik.', 31, 15);
INSERT INTO `tb_komentar` (`isi`, `id_user`, `id_kelas`) VALUES
(33, 'Ini adalah komentar dummy ke-33 tentang kelas ini. Sangat kurang jelas.', 34, 10);


/* -- DUMMY DATA FOR tb_laporan -- */
INSERT INTO `tb_laporan` (`id_report`, `kategori_report`, `keterangan_report`, `id_kelas`, `id_user`, `tgl_dibuat`, `status_laporan`) VALUES
(6, 'Penggunaan kata kasar', 'Keterangan laporan dummy ke-6: Ada beberapa kata kasar.', 9, 39, '2025-05-20 23:41:42', 'Diproses');
INSERT INTO `tb_laporan` (`id_report`, `kategori_report`, `keterangan_report`, `id_kelas`, `id_user`, `tgl_dibuat`, `status_laporan`) VALUES
(7, 'Materi tidak relevan', 'Keterangan laporan dummy ke-7: Materi tidak relevan.', 15, 33, '2025-05-24 23:41:42', 'Belum Diproses');
INSERT INTO `tb_laporan` (`id_report`, `kategori_report`, `keterangan_report`, `id_kelas`, `id_user`, `tgl_dibuat`, `status_laporan`) VALUES
(8, 'Materi tidak relevan', 'Keterangan laporan dummy ke-8: Materi tidak relevan.', 17, 36, '2025-05-26 23:41:42', 'Belum Diproses');
INSERT INTO `tb_laporan` (`id_report`, `kategori_report`, `keterangan_report`, `id_kelas`, `id_user`, `tgl_dibuat`, `status_laporan`) VALUES
(9, 'Pornografi', 'Keterangan laporan dummy ke-9: Konten tidak senonoh.', 12, 34, '2025-05-18 23:41:42', 'Diproses');
INSERT INTO `tb_laporan` (`id_report`, `kategori_report`, `keterangan_report`, `id_kelas`, `id_user`, `tgl_dibuat`, `status_laporan`) VALUES
(10, 'Penggunaan kata kasar', 'Keterangan laporan dummy ke-10: Ada beberapa kata kasar.', 11, 28, '2025-05-11 23:41:42', 'Selesai');
INSERT INTO `tb_laporan` (`id_report`, `kategori_report`, `keterangan_report`, `id_kelas`, `id_user`, `tgl_dibuat`, `status_laporan`) VALUES
(11, 'Penggunaan kata kasar', 'Keterangan laporan dummy ke-11: Ada beberapa kata kasar.', 12, 29, '2025-05-24 23:41:42', 'Selesai');
INSERT INTO `tb_laporan` (`id_report`, `kategori_report`, `keterangan_report`, `id_kelas`, `id_user`, `tgl_dibuat`, `status_laporan`) VALUES
(12, 'Pornografi', 'Keterangan laporan dummy ke-12: Konten tidak senonoh.', 13, 23, '2025-05-12 23:41:42', 'Belum Diproses');
INSERT INTO `tb_laporan` (`id_report`, `kategori_report`, `keterangan_report`, `id_kelas`, `id_user`, `tgl_dibuat`, `status_laporan`) VALUES
(13, 'Materi tidak relevan', 'Keterangan laporan dummy ke-13: Materi tidak relevan.', 14, 25, '2025-05-20 23:41:42', 'Belum Diproses');
INSERT INTO `tb_laporan` (`id_report`, `kategori_report`, `keterangan_report`, `id_kelas`, `id_user`, `tgl_dibuat`, `status_laporan`) VALUES
(14, 'Penggunaan kata kasar', 'Keterangan laporan dummy ke-14: Ada beberapa kata kasar.', 16, 26, '2025-05-26 23:41:42', 'Belum Diproses');
INSERT INTO `tb_laporan` (`id_report`, `kategori_report`, `keterangan_report`, `id_kelas`, `id_user`, `tgl_dibuat`, `status_laporan`) VALUES
(15, 'Materi tidak relevan', 'Keterangan laporan dummy ke-15: Materi tidak relevan.', 16, 33, '2025-05-29 23:41:42', 'Belum Diproses');
INSERT INTO `tb_laporan` (`id_report`, `kategori_report`, `keterangan_report`, `id_kelas`, `id_user`, `tgl_dibuat`, `status_laporan`) VALUES
(16, 'Pornografi', 'Keterangan laporan dummy ke-16: Konten tidak senonoh.', 15, 30, '2025-05-13 23:41:42', 'Belum Diproses');
INSERT INTO `tb_laporan` (`id_report`, `kategori_report`, `keterangan_report`, `id_kelas`, `id_user`, `tgl_dibuat`, `status_laporan`) VALUES
(17, 'Pornografi', 'Keterangan laporan dummy ke-17: Konten tidak senonoh.', 10, 31, '2025-05-18 23:41:42', 'Selesai');
INSERT INTO `tb_laporan` (`id_report`, `kategori_report`, `keterangan_report`, `id_kelas`, `id_user`, `tgl_dibuat`, `status_laporan`) VALUES
(18, 'Penggunaan kata kasar', 'Keterangan laporan dummy ke-18: Ada beberapa kata kasar.', 11, 24, '2025-05-27 23:41:42', 'Diproses');
INSERT INTO `tb_laporan` (`id_report`, `kategori_report`, `keterangan_report`, `id_kelas`, `id_user`, `tgl_dibuat`, `status_laporan`) VALUES
(19, 'Pornografi', 'Keterangan laporan dummy ke-19: Konten tidak senonoh.', 13, 27, '2025-05-16 23:41:42', 'Belum Diproses');
INSERT INTO `tb_laporan` (`id_report`, `kategori_report`, `keterangan_report`, `id_kelas`, `id_user`, `tgl_dibuat`, `status_laporan`) VALUES
(20, 'Penggunaan kata kasar', 'Keterangan laporan dummy ke-20: Ada beberapa kata kasar.', 15, 38, '2025-05-14 23:41:42', 'Belum Diproses');


/* -- DUMMY DATA FOR tb_kategori_kelas -- */
INSERT INTO `tb_kategori_kelas` (`id_kategori_kelas`, `id_kelas`, `id_kategori`) VALUES
(6, 17, 3);
INSERT INTO `tb_kategori_kelas` (`id_kategori_kelas`, `id_kelas`, `id_kategori`) VALUES
(7, 10, 1);
INSERT INTO `tb_kategori_kelas` (`id_kategori_kelas`, `id_kelas`, `id_kategori`) VALUES
(8, 17, 6);
INSERT INTO `tb_kategori_kelas` (`id_kategori_kelas`, `id_kelas`, `id_kategori`) VALUES
(9, 13, 3);
INSERT INTO `tb_kategori_kelas` (`id_kategori_kelas`, `id_kelas`, `id_kategori`) VALUES
(10, 16, 5);
INSERT INTO `tb_kategori_kelas` (`id_kategori_kelas`, `id_kelas`, `id_kategori`) VALUES
(11, 10, 1);
INSERT INTO `tb_kategori_kelas` (`id_kategori_kelas`, `id_kelas`, `id_kategori`) VALUES
(12, 11, 5);
INSERT INTO `tb_kategori_kelas` (`id_kategori_kelas`, `id_kelas`, `id_kategori`) VALUES
(13, 16, 4);
INSERT INTO `tb_kategori_kelas` (`id_kategori_kelas`, `id_kelas`, `id_kategori`) VALUES
(14, 15, 5);
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
(2, '4', 'Review dummy ke-2: Kelas ini sangat informatif.', '2025-03-24 23:41:42', 32, 12);
INSERT INTO `tb_review` (`id_review`, `bintang_review`, `isi_review`, `tgl_review`, `id_user`, `id_kelas`) VALUES
(3, '4', 'Review dummy ke-3: Kelas ini sangat luar biasa.', '2025-05-09 23:41:42', 34, 13);
INSERT INTO `tb_review` (`id_review`, `bintang_review`, `isi_review`, `tgl_review`, `id_user`, `id_kelas`) VALUES
(4, '5', 'Review dummy ke-4: Kelas ini sangat bagus.', '2025-04-12 23:41:42', 41, 14);
INSERT INTO `tb_review` (`id_review`, `bintang_review`, `isi_review`, `tgl_review`, `id_user`, `id_kelas`) VALUES
(5, '4', 'Review dummy ke-5: Kelas ini sangat bagus.', '2025-03-09 23:41:42', 35, 10);
INSERT INTO `tb_review` (`id_review`, `bintang_review`, `isi_review`, `tgl_review`, `id_user`, `id_kelas`) VALUES
(6, '2', 'Review dummy ke-6: Kelas ini sangat bagus.', '2025-04-24 23:41:42', 37, 16);
INSERT INTO `tb_review` (`id_review`, `bintang_review`, `isi_review`, `tgl_review`, `id_user`, `id_kelas`) VALUES
(7, '1', 'Review dummy ke-7: Kelas ini sangat kurang memuaskan.', '2025-03-10 23:41:42', 30, 15);
INSERT INTO `tb_review` (`id_review`, `bintang_review`, `isi_review`, `tgl_review`, `id_user`, `id_kelas`) VALUES
(8, '4', 'Review dummy ke-8: Kelas ini sangat luar biasa.', '2025-05-18 23:41:42', 33, 16);
INSERT INTO `tb_review` (`id_review`, `bintang_review`, `isi_review`, `tgl_review`, `id_user`, `id_kelas`) VALUES
(9, '5', 'Review dummy ke-9: Kelas ini sangat informatif.', '2025-05-05 23:41:42', 25, 9);
INSERT INTO `tb_review` (`id_review`, `bintang_review`, `isi_review`, `tgl_review`, `id_user`, `id_kelas`) VALUES
(10, '5', 'Review dummy ke-10: Kelas ini sangat informatif.', '2025-03-23 23:41:42', 36, 15);
INSERT INTO `tb_review` (`id_review`, `bintang_review`, `isi_review`, `tgl_review`, `id_user`, `id_kelas`) VALUES
(11, '5', 'Review dummy ke-11: Kelas ini sangat bagus.', '2025-03-13 23:41:42', 29, 13);


/* -- DUMMY DATA FOR tb_transaksi -- */
INSERT INTO `tb_transaksi` (`id_transaksi`, `id_kelas`, `id_user`, `id_keranjang`, `bukti_transaksi`, `tgl_transaksi`, `status`) VALUES
(4, 9, 39, 11, 'bukti_TRX_DUMMY_4.jpg', '2025-04-06 23:41:42', 'pending');
INSERT INTO `tb_transaksi` (`id_transaksi`, `id_kelas`, `id_user`, `id_keranjang`, `bukti_transaksi`, `tgl_transaksi`, `status`) VALUES
(5, 12, 29, 6, 'bukti_TRX_DUMMY_5.jpg', '2025-05-01 23:41:42', 'acc');
INSERT INTO `tb_transaksi` (`id_transaksi`, `id_kelas`, `id_user`, `id_keranjang`, `bukti_transaksi`, `tgl_transaksi`, `status`) VALUES
(6, 12, 33, 10, 'bukti_TRX_DUMMY_6.jpg', '2025-05-11 23:41:42', 'pending');
INSERT INTO `tb_transaksi` (`id_transaksi`, `id_kelas`, `id_user`, `id_keranjang`, `bukti_transaksi`, `tgl_transaksi`, `status`) VALUES
(7, 10, 31, 10, 'bukti_TRX_DUMMY_7.jpg', '2025-05-17 23:41:42', 'acc');
INSERT INTO `tb_transaksi` (`id_transaksi`, `id_kelas`, `id_user`, `id_keranjang`, `bukti_transaksi`, `tgl_transaksi`, `status`) VALUES
(8, 14, 25, 10, 'bukti_TRX_DUMMY_8.jpg', '2025-05-27 23:41:42', 'acc');
INSERT INTO `tb_transaksi` (`id_transaksi`, `id_kelas`, `id_user`, `id_keranjang`, `bukti_transaksi`, `tgl_transaksi`, `status`) VALUES
(9, 13, 27, 9, 'bukti_TRX_DUMMY_9.jpg', '2025-05-14 23:41:42', 'pending');
INSERT INTO `tb_transaksi` (`id_transaksi`, `id_kelas`, `id_user`, `id_keranjang`, `bukti_transaksi`, `tgl_transaksi`, `status`) VALUES
(10, 16, 23, 11, 'bukti_TRX_DUMMY_10.jpg', '2025-04-18 23:41:42', 'pending');
INSERT INTO `tb_transaksi` (`id_transaksi`, `id_kelas`, `id_user`, `id_keranjang`, `bukti_transaksi`, `tgl_transaksi`, `status`) VALUES
(11, 13, 30, 8, 'bukti_TRX_DUMMY_11.jpg', '2025-04-20 23:41:42', 'acc');
INSERT INTO `tb_transaksi` (`id_transaksi`, `id_kelas`, `id_user`, `id_keranjang`, `bukti_transaksi`, `tgl_transaksi`, `status`) VALUES
(12, 10, 26, 12, 'bukti_TRX_DUMMY_12.jpg', '2025-04-22 23:41:42', 'acc');
INSERT INTO `tb_transaksi` (`id_transaksi`, `id_kelas`, `id_user`, `id_keranjang`, `bukti_transaksi`, `tgl_transaksi`, `status`) VALUES
(13, 12, 31, 6, 'bukti_TRX_DUMMY_13.jpg', '2025-04-12 23:41:42', 'pending');


COMMIT;