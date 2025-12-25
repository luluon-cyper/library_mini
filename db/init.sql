-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th12 23, 2025 lúc 08:14 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


CREATE TABLE `ct_phieumuon` (
  `IDCTPhieuMuon` int(11) NOT NULL,
  `IDPhieuMuon` int(11) NOT NULL,
  `IDSach` int(11) NOT NULL,
  `NgayTra` date DEFAULT NULL,
  `PhiPhat` decimal(10,2) DEFAULT 0.00,
  `SoLuong` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `ct_phieumuon`
--

INSERT INTO `ct_phieumuon` (`IDCTPhieuMuon`, `IDPhieuMuon`, `IDSach`, `NgayTra`, `PhiPhat`, `SoLuong`) VALUES
(1, 1, 1, '2025-12-18', 0.00, 1),
(2, 1, 2, '2025-12-18', 0.00, 1),
(3, 2, 3, '2025-11-28', 0.00, 1),
(4, 3, 4, NULL, 20000.00, 1),
(16, 8, 14, NULL, 0.00, 1),
(17, 9, 14, '2025-12-18', 0.00, 1),
(18, 10, 14, '2025-12-18', 0.00, 1),
(19, 11, 1, NULL, 0.00, 1),
(20, 12, 1, NULL, 0.00, 1),
(21, 12, 1, NULL, 0.00, 2);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ct_sach`
--

CREATE TABLE `ct_sach` (
  `IDCTSach` int(11) NOT NULL,
  `IDSach` int(11) NOT NULL,
  `MoTa` text DEFAULT NULL,
  `NamXuatBan` int(11) DEFAULT NULL,
  `NhaXuatBan` varchar(150) DEFAULT NULL,
  `NgonNgu` varchar(50) DEFAULT NULL,
  `SoTrang` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `ct_sach`
--

INSERT INTO `ct_sach` (`IDCTSach`, `IDSach`, `MoTa`, `NamXuatBan`, `NhaXuatBan`, `NgonNgu`, `SoTrang`) VALUES
(1, 1, 'Tập thơ thiếu nhi trong sáng, giàu cảm xúc về tuổi thơ', 1941, 'NXB Kim Đồng', 'Tiếng Việt', 120),
(2, 2, 'Tập thơ nổi tiếng của Nguyễn Nhật Ánh viết về thiên nhiên và ký ức', 1984, 'NXB Trẻ', 'Tiếng Việt', 98),
(3, 3, 'Tác phẩm văn học thiếu nhi kinh điển của Tô Hoài', 1941, 'NXB Kim Đồng', 'Tiếng Việt', 145),
(4, 4, 'Tiểu thuyết hiện thực nổi tiếng của nhà văn Nam Cao', 1943, 'NXB Văn Học', 'Tiếng Việt', 180),
(5, 5, 'Tiểu thuyết triết học hiện sinh của Franz Kafka', 1925, 'NXB Văn Học', 'Tiếng Việt', 250),
(6, 6, 'Tập đầu tiên trong loạt truyện Harry Potter nổi tiếng', 1997, 'Bloomsbury', 'Tiếng Anh', 320),
(7, 7, 'Phần tiếp theo trong loạt truyện Harry Potter', 1998, 'Bloomsbury', 'Tiếng Anh', 341),
(8, 8, 'Tiểu thuyết trinh thám – mật mã nổi tiếng thế giới', 2003, 'Doubleday', 'Tiếng Anh', 454),
(9, 9, 'Tiểu thuyết giả tưởng – tôn giáo gây tranh cãi', 2005, 'NXB Hội Nhà Văn', 'Tiếng Việt', 390),
(10, 10, 'Tiểu thuyết phản địa đàng nổi tiếng của George Orwell', 1949, 'Secker & Warburg', 'Tiếng Anh', 328),
(11, 11, 'Tác phẩm châm biếm chính trị nổi tiếng', 1945, 'Secker & Warburg', 'Tiếng Anh', 152),
(12, 12, 'Tiểu thuyết hiện thực phê phán xã hội nông thôn Việt Nam', 1946, 'NXB Văn Học', 'Tiếng Việt', 210),
(13, 13, 'Tác phẩm trinh thám kinh điển của Arthur Conan Doyle', 1892, 'George Newnes', 'Tiếng Anh', 307),
(14, 14, 'Một trong những vụ án nổi tiếng nhất của Sherlock Holmes', 1902, 'George Newnes', 'Tiếng Anh', 256);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danhgia`
--

CREATE TABLE `danhgia` (
  `IDDanhGia` int(11) NOT NULL,
  `IDSach` int(11) NOT NULL,
  `IDTaiKhoan` int(11) NOT NULL,
  `SoSao` int(11) DEFAULT NULL CHECK (`SoSao` between 1 and 5),
  `NoiDungDanhGia` text DEFAULT NULL,
  `NgayDanhGia` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `danhgia`
--

INSERT INTO `danhgia` (`IDDanhGia`, `IDSach`, `IDTaiKhoan`, `SoSao`, `NoiDungDanhGia`, `NgayDanhGia`) VALUES
(1, 1, 1, 5, 'Sách rất hay, đọc lại nhiều lần vẫn thích', '2025-12-17'),
(2, 6, 2, 5, 'Truyện fantasy hấp dẫn', '2025-12-17'),
(3, 10, 1, 4, 'Nội dung sâu sắc', '2025-12-17'),
(4, 13, 2, 4, 'Trinh thám cuốn hút', '2025-12-17'),
(34, 1, 1, 5, 'Rất hay, gợi nhớ tuổi thơ.', '2024-01-10'),
(35, 1, 5, 4, 'Nhẹ nhàng, dễ đọc.', '2024-02-05'),
(36, 2, 1, 4, 'Thơ đẹp, nhiều cảm xúc.', '2024-01-15'),
(37, 2, 5, 5, 'Rất thích phong cách viết.', '2024-02-20'),
(38, 3, 1, 5, 'Tác phẩm thiếu nhi kinh điển.', '2024-01-18'),
(39, 3, 5, 4, 'Nội dung hấp dẫn.', '2024-02-25'),
(40, 4, 1, 5, 'Phản ánh xã hội rất sâu sắc.', '2024-01-22'),
(41, 4, 5, 4, 'Đáng đọc.', '2024-03-01'),
(42, 5, 1, 3, 'Khá khó hiểu nhưng ý nghĩa.', '2024-01-30'),
(43, 5, 5, 4, 'Đọc chậm mới thấm.', '2024-03-05'),
(44, 6, 1, 5, 'Harry Potter không bao giờ chán.', '2024-02-01'),
(45, 6, 5, 5, 'Rất cuốn.', '2024-02-10'),
(46, 7, 1, 5, 'Phần này hay hơn phần 1.', '2024-02-12'),
(47, 7, 5, 4, 'Nhiều tình tiết thú vị.', '2024-03-10'),
(48, 8, 1, 4, 'Cốt truyện thông minh.', '2024-02-18'),
(49, 8, 5, 5, 'Rất hấp dẫn, khó đoán.', '2024-03-12'),
(50, 9, 1, 3, 'Ý tưởng lạ nhưng hơi dài.', '2024-02-22'),
(51, 9, 5, 4, 'Đọc khá cuốn.', '2024-03-15'),
(52, 10, 1, 5, 'Quá xuất sắc, rất đáng suy ngẫm.', '2024-02-28'),
(53, 10, 5, 5, 'Tác phẩm kinh điển.', '2024-03-18'),
(54, 11, 1, 4, 'Châm biếm sâu cay.', '2024-03-02'),
(55, 11, 5, 5, 'Nội dung ngắn gọn, dễ hiểu.', '2024-03-20'),
(56, 12, 1, 4, 'Phản ánh xã hội rất thực.', '2024-03-05'),
(57, 13, 5, 5, 'Sherlock Holmes luôn cuốn.', '2024-03-08'),
(58, 14, 1, 4, 'Vụ án rất hay.', '2024-03-12');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phieumuon`
--

CREATE TABLE `phieumuon` (
  `IDPhieuMuon` int(11) NOT NULL,
  `IDTaiKhoan` int(11) NOT NULL,
  `NgayMuon` date NOT NULL,
  `NgayHenTra` date NOT NULL,
  `TrangThaiMuonTra` enum('dangmuon','datra','quahan') DEFAULT 'dangmuon'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `phieumuon`
--

INSERT INTO `phieumuon` (`IDPhieuMuon`, `IDTaiKhoan`, `NgayMuon`, `NgayHenTra`, `TrangThaiMuonTra`) VALUES
(1, 1, '2025-12-01', '2026-01-24', 'dangmuon'),
(2, 1, '2025-11-20', '2025-11-30', 'datra'),
(3, 1, '2025-11-15', '2025-11-25', 'quahan'),
(8, 1, '2025-12-18', '2026-01-23', 'dangmuon'),
(9, 1, '2025-12-18', '2026-01-23', 'datra'),
(10, 1, '2025-12-19', '2026-02-11', 'datra'),
(11, 5, '2025-12-18', '2026-01-23', 'dangmuon'),
(12, 1, '2025-12-22', '2026-01-27', 'dangmuon');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sach`
--

CREATE TABLE `sach` (
  `IDSach` int(11) NOT NULL,
  `IDTacGia` int(11) DEFAULT NULL,
  `IDTheLoai` int(11) DEFAULT NULL,
  `TenSach` varchar(255) NOT NULL,
  `SoLuong` int(11) DEFAULT 0,
  `TinhTrang` enum('available','borrowed') DEFAULT 'available',
  `Anh` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `sach`
--

INSERT INTO `sach` (`IDSach`, `IDTacGia`, `IDTheLoai`, `TenSach`, `SoLuong`, `TinhTrang`, `Anh`) VALUES
(1, 1, 2, 'Cho tôi xin một vé đi tuổi thơ', 10, 'available', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR-KSMLhFLLFiyyKzrUzMf6Fc4M8CmjviujIA&s'),
(2, 1, 2, 'Tôi thấy hoa vàng trên cỏ xanh', 9, 'available', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQtJD6BGIzKKe4XT3sbav7JhMkoSCVUAPap2g&s'),
(3, 2, 2, 'Dế Mèn phiêu lưu ký', 15, 'available', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQwcCpubUsbXtdJK1O4SD08BYbUJT_xe2RdRA&s'),
(4, 3, 1, 'Rừng Na Uy', 6, 'available', 'https://isach.info/images/story/cover/rung_na_uy__haruki_murakami.jpg'),
(5, 3, 1, 'Kafka bên bờ biển', 0, 'borrowed', 'https://upload.wikimedia.org/wikipedia/vi/5/5b/Kafka_b%C3%AAn_b%E1%BB%9D_bi%E1%BB%83n.JPG'),
(6, 4, 3, 'Harry Potter và Hòn đá Phù thủy', 11, 'available', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTmp4Ybn0qLXFt9UA3Qg6Hu9Q2bAWAA1Y7ImQ&s'),
(7, 4, 3, 'Harry Potter và Phòng chứa bí mật', 9, 'available', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSwyPTgsK8WNtcHeSjlqy9priwClLf3dIIPow&s'),
(8, 5, 4, 'Mật mã Da Vinci', 7, 'borrowed', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQoH35F37Wocn_BujU0dCiQ0pfg0BC5jBdXSg&s'),
(9, 5, 4, 'Thiên thần và Ác quỷ', 6, 'available', NULL),
(10, 6, 6, '1984', 8, 'available', NULL),
(11, 6, 6, 'Trại súc vật', 10, 'available', NULL),
(12, 7, 7, 'Nhà giả kim', 12, 'available', NULL),
(13, 8, 4, 'Sherlock Holmes: Dấu bộ tứ', 0, 'borrowed', NULL),
(14, 8, 4, 'Sherlock Holmes: Con chó Baskerville', 3, 'available', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tacgia`
--

CREATE TABLE `tacgia` (
  `IDTacGia` int(11) NOT NULL,
  `TenTacGia` varchar(100) NOT NULL,
  `NamSinh` int(11) DEFAULT NULL,
  `QuocTich` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tacgia`
--

INSERT INTO `tacgia` (`IDTacGia`, `TenTacGia`, `NamSinh`, `QuocTich`) VALUES
(1, 'Nguyễn Nhật Ánh', 1955, 'Việt Nam'),
(2, 'Tô Hoài', 1920, 'Việt Nam'),
(3, 'Haruki Murakami', 1949, 'Nhật Bản'),
(4, 'J.K. Rowling', 1965, 'Anh'),
(5, 'Dan Brown', 1964, 'Mỹ'),
(6, 'George Orwell', 1903, 'Anh'),
(7, 'Paulo Coelho', 1947, 'Brazil'),
(8, 'Arthur Conan Doyle', 1859, 'Anh');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `taikhoan`
--

CREATE TABLE `taikhoan` (
  `IDTaiKhoan` int(11) NOT NULL,
  `HoTen` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `MatKhau` varchar(255) NOT NULL,
  `VaiTro` enum('user','admin') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `taikhoan`
--

INSERT INTO `taikhoan` (`IDTaiKhoan`, `HoTen`, `Email`, `MatKhau`, `VaiTro`) VALUES
(1, 'a', 'a@gmail.com', '$2y$10$7Upfhc7ZNFVOrpBHCxJIb.OURNl4G1lNn0ioYlUACH.8oPgpPlF.O', ''),
(2, 'Admin', 'admin@example.com', '$2y$10$vmXb3q5pO1Dmhf8kstdjbOMWVGGGLDSpdJcmsBcBrTUhDu00M1ad6', 'admin'),
(5, 'b', 'b@gmail.com', '$2y$10$VbqAD/N0tROJR4OJ/ZD64usLjYmoNnez9Q3AFIfRU.WMuJUkNJxzK', 'user');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `theloai`
--

CREATE TABLE `theloai` (
  `IDTheLoai` int(11) NOT NULL,
  `TenTheLoai` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `theloai`
--

INSERT INTO `theloai` (`IDTheLoai`, `TenTheLoai`) VALUES
(1, 'Tiểu thuyết'),
(2, 'Truyện thiếu nhi'),
(3, 'Fantasy'),
(4, 'Trinh thám'),
(5, 'Khoa học viễn tưởng'),
(6, 'Văn học kinh điển'),
(7, 'Tâm lý - Kỹ năng sống');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `ct_phieumuon`
--
ALTER TABLE `ct_phieumuon`
  ADD PRIMARY KEY (`IDCTPhieuMuon`),
  ADD KEY `fk_ctpm_phieumuon` (`IDPhieuMuon`),
  ADD KEY `fk_ctpm_sach` (`IDSach`);

--
-- Chỉ mục cho bảng `ct_sach`
--
ALTER TABLE `ct_sach`
  ADD PRIMARY KEY (`IDCTSach`),
  ADD UNIQUE KEY `IDSach` (`IDSach`);

--
-- Chỉ mục cho bảng `danhgia`
--
ALTER TABLE `danhgia`
  ADD PRIMARY KEY (`IDDanhGia`),
  ADD KEY `fk_danhgia_sach` (`IDSach`),
  ADD KEY `fk_danhgia_taikhoan` (`IDTaiKhoan`);

--
-- Chỉ mục cho bảng `phieumuon`
--
ALTER TABLE `phieumuon`
  ADD PRIMARY KEY (`IDPhieuMuon`),
  ADD KEY `fk_phieumuon_taikhoan` (`IDTaiKhoan`);

--
-- Chỉ mục cho bảng `sach`
--
ALTER TABLE `sach`
  ADD PRIMARY KEY (`IDSach`),
  ADD KEY `fk_sach_tacgia` (`IDTacGia`),
  ADD KEY `fk_sach_theloai` (`IDTheLoai`);

--
-- Chỉ mục cho bảng `tacgia`
--
ALTER TABLE `tacgia`
  ADD PRIMARY KEY (`IDTacGia`);

--
-- Chỉ mục cho bảng `taikhoan`
--
ALTER TABLE `taikhoan`
  ADD PRIMARY KEY (`IDTaiKhoan`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Chỉ mục cho bảng `theloai`
--
ALTER TABLE `theloai`
  ADD PRIMARY KEY (`IDTheLoai`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `ct_phieumuon`
--
ALTER TABLE `ct_phieumuon`
  MODIFY `IDCTPhieuMuon` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT cho bảng `ct_sach`
--
ALTER TABLE `ct_sach`
  MODIFY `IDCTSach` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT cho bảng `danhgia`
--
ALTER TABLE `danhgia`
  MODIFY `IDDanhGia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT cho bảng `phieumuon`
--
ALTER TABLE `phieumuon`
  MODIFY `IDPhieuMuon` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `sach`
--
ALTER TABLE `sach`
  MODIFY `IDSach` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT cho bảng `tacgia`
--
ALTER TABLE `tacgia`
  MODIFY `IDTacGia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT cho bảng `taikhoan`
--
ALTER TABLE `taikhoan`
  MODIFY `IDTaiKhoan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `theloai`
--
ALTER TABLE `theloai`
  MODIFY `IDTheLoai` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `ct_phieumuon`
--
ALTER TABLE `ct_phieumuon`
  ADD CONSTRAINT `fk_ctpm_phieumuon` FOREIGN KEY (`IDPhieuMuon`) REFERENCES `phieumuon` (`IDPhieuMuon`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ctpm_sach` FOREIGN KEY (`IDSach`) REFERENCES `sach` (`IDSach`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `ct_sach`
--
ALTER TABLE `ct_sach`
  ADD CONSTRAINT `fk_ctsach_sach` FOREIGN KEY (`IDSach`) REFERENCES `sach` (`IDSach`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `danhgia`
--
ALTER TABLE `danhgia`
  ADD CONSTRAINT `fk_danhgia_sach` FOREIGN KEY (`IDSach`) REFERENCES `sach` (`IDSach`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_danhgia_taikhoan` FOREIGN KEY (`IDTaiKhoan`) REFERENCES `taikhoan` (`IDTaiKhoan`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `phieumuon`
--
ALTER TABLE `phieumuon`
  ADD CONSTRAINT `fk_phieumuon_taikhoan` FOREIGN KEY (`IDTaiKhoan`) REFERENCES `taikhoan` (`IDTaiKhoan`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `sach`
--
ALTER TABLE `sach`
  ADD CONSTRAINT `fk_sach_tacgia` FOREIGN KEY (`IDTacGia`) REFERENCES `tacgia` (`IDTacGia`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_sach_theloai` FOREIGN KEY (`IDTheLoai`) REFERENCES `theloai` (`IDTheLoai`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
