-- ===============================
-- DATABASE
-- ===============================
CREATE DATABASE IF NOT EXISTS library_db
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE library_db;

-- ===============================
-- TABLE: TAIKHOAN
-- ===============================
CREATE TABLE taikhoan (
  IDTaiKhoan INT AUTO_INCREMENT PRIMARY KEY,
  HoTen VARCHAR(100) NOT NULL,
  Email VARCHAR(100) UNIQUE NOT NULL,
  MatKhau VARCHAR(255) NOT NULL,
  VaiTro ENUM('user','admin') DEFAULT 'user'
) ENGINE=InnoDB;

-- ===============================
-- TABLE: TACGIA
-- ===============================
CREATE TABLE tacgia (
  IDTacGia INT AUTO_INCREMENT PRIMARY KEY,
  TenTacGia VARCHAR(100) NOT NULL,
  NamSinh INT,
  QuocTich VARCHAR(50)
) ENGINE=InnoDB;

-- ===============================
-- TABLE: THELOAI
-- ===============================
CREATE TABLE theloai (
  IDTheLoai INT AUTO_INCREMENT PRIMARY KEY,
  TenTheLoai VARCHAR(100) NOT NULL
) ENGINE=InnoDB;

-- ===============================
-- TABLE: SACH
-- ===============================
CREATE TABLE sach (
  IDSach INT AUTO_INCREMENT PRIMARY KEY,
  IDTacGia INT,
  IDTheLoai INT,
  Anh VARCHAR(255),
  TenSach VARCHAR(255) NOT NULL,
  SoLuong INT DEFAULT 0,
  TinhTrang ENUM('available','borrowed') DEFAULT 'available',

  CONSTRAINT fk_sach_tacgia
    FOREIGN KEY (IDTacGia)
    REFERENCES tacgia(IDTacGia)
    ON DELETE SET NULL,

  CONSTRAINT fk_sach_theloai
    FOREIGN KEY (IDTheLoai)
    REFERENCES theloai(IDTheLoai)
    ON DELETE SET NULL
) ENGINE=InnoDB;

-- ===============================
-- TABLE: CT_SACH
-- ===============================
CREATE TABLE ct_sach (
  IDCTSach INT AUTO_INCREMENT PRIMARY KEY,
  IDSach INT NOT NULL UNIQUE,
  MoTa TEXT,
  NamXuatBan INT,
  NhaXuatBan VARCHAR(150),
  NgonNgu VARCHAR(50),
  SoTrang INT,

  CONSTRAINT fk_ctsach_sach
    FOREIGN KEY (IDSach)
    REFERENCES sach(IDSach)
    ON DELETE CASCADE
) ENGINE=InnoDB;

-- ===============================
-- TABLE: PHIEUMUON
-- ===============================
CREATE TABLE phieumuon (
  IDPhieuMuon INT AUTO_INCREMENT PRIMARY KEY,
  IDTaiKhoan INT NOT NULL,
  NgayMuon DATE NOT NULL,
  NgayHenTra DATE NOT NULL,
  TrangThaiMuonTra ENUM('dangmuon','datra','quahan') DEFAULT 'dangmuon',

  CONSTRAINT fk_phieumuon_taikhoan
    FOREIGN KEY (IDTaiKhoan)
    REFERENCES taikhoan(IDTaiKhoan)
    ON DELETE CASCADE
) ENGINE=InnoDB;

-- ===============================
-- TABLE: CT_PHIEUMUON
-- ===============================
CREATE TABLE ct_phieumuon (
  IDCTPhieuMuon INT AUTO_INCREMENT PRIMARY KEY,
  IDPhieuMuon INT NOT NULL,
  IDSach INT NOT NULL,
  NgayTra DATE,
  PhiPhat DECIMAL(10,2) DEFAULT 0,
  SoLuong INT DEFAULT 1,

  CONSTRAINT fk_ctpm_phieumuon
    FOREIGN KEY (IDPhieuMuon)
    REFERENCES phieumuon(IDPhieuMuon)
    ON DELETE CASCADE,

  CONSTRAINT fk_ctpm_sach
    FOREIGN KEY (IDSach)
    REFERENCES sach(IDSach)
    ON DELETE CASCADE
) ENGINE=InnoDB;

-- ===============================
-- TABLE: DANHGIA
-- ===============================
CREATE TABLE danhgia (
  IDDanhGia INT AUTO_INCREMENT PRIMARY KEY,
  IDSach INT NOT NULL,
  IDTaiKhoan INT NOT NULL,
  SoSao INT CHECK (SoSao BETWEEN 1 AND 5),
  NoiDungDanhGia TEXT,
  NgayDanhGia DATE DEFAULT CURRENT_DATE,

  CONSTRAINT fk_danhgia_sach
    FOREIGN KEY (IDSach)
    REFERENCES sach(IDSach)
    ON DELETE CASCADE,

  CONSTRAINT fk_danhgia_taikhoan
    FOREIGN KEY (IDTaiKhoan)
    REFERENCES taikhoan(IDTaiKhoan)
    ON DELETE CASCADE
) ENGINE=InnoDB;
