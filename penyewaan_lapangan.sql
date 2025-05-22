CREATE DATABASE Persewaan_Lapangan;
USE Persewaan_Lapangan;

CREATE TABLE Pegawai (
    ID_Pegawai INT PRIMARY KEY AUTO_INCREMENT,
    Nama VARCHAR(100),
    Posisi VARCHAR(50),
    No_HP VARCHAR(20),
    Email VARCHAR(100)
    pw VARCHAR(50) 
);


CREATE TABLE Lapangan (
    ID_Lapangan INT PRIMARY KEY AUTO_INCREMENT,
    Nama_Lapangan VARCHAR(100),
    Jenis VARCHAR(50), 
    Harga_Per_Jam DECIMAL(10,2)
);

CREATE TABLE Pelanggan (
    ID_Pelanggan INT PRIMARY KEY AUTO_INCREMENT,
    Nama VARCHAR(100),
    No_HP VARCHAR(20),
    Email VARCHAR(100),
    pw VARCHAR(50)
);

CREATE TABLE Penyewaan_Lapangan (
    ID_Sewa INT PRIMARY KEY AUTO_INCREMENT,
    ID_Pelanggan INT,
    ID_Lapangan INT,
    Tanggal DATE,
    Jam_Mulai TIME,
    Jam_Selesai TIME,
    Total_Biaya DECIMAL(10,2),
    ID_Pegawai INT,
    STATUS ENUM('tersedia', 'terbooking') DEFAULT 'tersedia',
    FOREIGN KEY (ID_Pelanggan) REFERENCES Pelanggan(ID_Pelanggan),
    FOREIGN KEY (ID_Lapangan) REFERENCES Lapangan(ID_Lapangan)
);


CREATE TABLE Pembayaran (
    ID_Pembayaran INT PRIMARY KEY AUTO_INCREMENT,
    ID_Sewa INT,
    ID_Pegawai INT,
    Tanggal_Pembayaran DATE,
    Metode_Pembayaran VARCHAR(50), 
    Jumlah_Bayar DECIMAL(10,2),
    FOREIGN KEY (ID_Sewa) REFERENCES Penyewaan_Lapangan(ID_Sewa)
);



-- stored procedure 
-- 1
DELIMITER //

CREATE PROCEDURE tambahakun (
    IN pNama VARCHAR(100),
    IN pNoHP VARCHAR(20),
    IN pEmail VARCHAR(100),
    IN pPassword VARCHAR(50)
)
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM pelanggan WHERE Email = pEmail
    ) THEN
        INSERT INTO pelanggan (Nama, No_HP, Email, pw)
        VALUES (pNama, pNoHP, pEmail, pPassword);
    ELSE
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Email sudah terdaftar!';
    END IF;
END //

DELIMITER ;
CALL tambahakun ();

-- 2
DELIMITER $$

CREATE PROCEDURE boking_lapangan (
    IN p_id_pelanggan INT,
    IN p_id_lapangan INT,
    IN p_tanggal DATE,
    IN p_jam_mulai TIME,
    IN p_durasi_jam INT,
    IN p_id_pegawai INT
)
BEGIN
    DECLARE v_jam_selesai TIME;
    DECLARE v_harga_per_jam DECIMAL(10,2);
    DECLARE v_total_biaya DECIMAL(10,2);

    SELECT Harga_Per_Jam INTO v_harga_per_jam
    FROM lapangan
    WHERE ID_Lapangan = p_id_lapangan;

    SET v_jam_selesai = ADDTIME(p_jam_mulai, SEC_TO_TIME(p_durasi_jam * 3600));
    SET v_total_biaya = v_harga_per_jam * p_durasi_jam;

    INSERT INTO penyewaan_lapangan (
        ID_Pelanggan,
        ID_Lapangan,
        Tanggal,
        Jam_Mulai,
        Jam_Selesai,
        Total_Biaya,
        ID_Pegawai,
        STATUS
    )
    VALUES (
        p_id_pelanggan,
        p_id_lapangan,
        p_tanggal,
        p_jam_mulai,
        v_jam_selesai,
        v_total_biaya,
        p_id_pegawai,
        'tersedia'  
    );
END$$

DELIMITER ;

-- 3
DELIMITER $$

CREATE PROCEDURE boking_lapangan (
    IN p_id_pelanggan INT,
    IN p_id_lapangan INT,
    IN p_tanggal DATE,
    IN p_jam_mulai TIME,
    IN p_durasi_jam INT,
    IN p_id_pegawai INT
)
BEGIN
    DECLARE v_jam_selesai TIME;
    DECLARE v_harga_per_jam DECIMAL(10,2);
    DECLARE v_total_biaya DECIMAL(10,2);
    DECLARE v_jumlah_bentrok INT;

    SET v_jam_selesai = ADDTIME(p_jam_mulai, SEC_TO_TIME(p_durasi_jam * 3600));

    SELECT COUNT(*) INTO v_jumlah_bentrok
    FROM penyewaan_lapangan
    WHERE ID_Lapangan = p_id_lapangan
      AND Tanggal = p_tanggal
      AND STATUS = 'terboking'
      AND (
          (p_jam_mulai BETWEEN Jam_Mulai AND Jam_Selesai) OR
          (v_jam_selesai BETWEEN Jam_Mulai AND Jam_Selesai) OR
          (Jam_Mulai BETWEEN p_jam_mulai AND v_jam_selesai)
      );

    IF v_jumlah_bentrok > 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Gagal: Lapangan sudah dibooking pada tanggal dan jam tersebut.';
    ELSE
        SELECT Harga_Per_Jam INTO v_harga_per_jam
        FROM lapangan
        WHERE ID_Lapangan = p_id_lapangan;

        SET v_total_biaya = v_harga_per_jam * p_durasi_jam;

        INSERT INTO penyewaan_lapangan (
            ID_Pelanggan,
            ID_Lapangan,
            Tanggal,
            Jam_Mulai,
            Jam_Selesai,
            Total_Biaya,
            ID_Pegawai,
            STATUS
        )
        VALUES (
            p_id_pelanggan,
            p_id_lapangan,
            p_tanggal,
            p_jam_mulai,
            v_jam_selesai,
            v_total_biaya,
            p_id_pegawai,
            'tersedia'
        );
    END IF;
END$$

DELIMITER ;



-- 4
DELIMITER $$

CREATE PROCEDURE updatePelangganEmailPw(
    IN p_Email VARCHAR(100),
    IN p_pw VARCHAR(100),
    IN p_ID_Pelanggan INT
)
BEGIN
    UPDATE pelanggan
    SET Email = p_Email,
        pw = p_pw
    WHERE ID_Pelanggan = p_ID_Pelanggan;
END $$

DELIMITER ;


-- 5
DELIMITER //

CREATE PROCEDURE hapus_pembayaran (
  IN p_id_pembayaran INT
)
BEGIN
  DELETE FROM pembayaran
  WHERE ID_Pembayaran = p_id_pembayaran;
END //

DELIMITER ;


-- 6
DELIMITER $$

CREATE PROCEDURE checkout_pesanan_single_payment(
    IN p_id_pelanggan INT,
    IN p_metode_pembayaran VARCHAR(50)
)
BEGIN
    DECLARE v_total_bayar DECIMAL(10,2) DEFAULT 0;
    DECLARE v_id_pembayaran INT DEFAULT 0;
    DECLARE v_jumlah_pesanan INT DEFAULT 0;
    DECLARE v_id_sewa_ref INT DEFAULT 0;
    DECLARE v_id_pegawai INT DEFAULT 1;
    DECLARE done INT DEFAULT FALSE;
    
    DECLARE v_id_sewa INT;

    DECLARE pesanan_cursor CURSOR FOR 
        SELECT ID_Sewa 
        FROM penyewaan_lapangan 
        WHERE ID_Pelanggan = p_id_pelanggan 
        AND STATUS = 'tersedia';
    
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;
    
    START TRANSACTION;
    
    SELECT COUNT(*), COALESCE(SUM(Total_Biaya), 0) 
    INTO v_jumlah_pesanan, v_total_bayar
    FROM penyewaan_lapangan 
    WHERE ID_Pelanggan = p_id_pelanggan 
    AND STATUS = 'tersedia';
    IF v_jumlah_pesanan = 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Tidak ada pesanan yang tersedia untuk dibayar';
    END IF;
    SELECT ID_Sewa INTO v_id_sewa_ref
    FROM penyewaan_lapangan 
    WHERE ID_Pelanggan = p_id_pelanggan 
    AND STATUS = 'tersedia' 
    LIMIT 1;
    SELECT COALESCE(MIN(ID_Pegawai), 1) INTO v_id_pegawai
    FROM pegawai 
    WHERE ID_Pegawai IS NOT NULL;
    INSERT INTO pembayaran (
        ID_Sewa,
        Tanggal_Pembayaran,
        Metode_Pembayaran,
        Jumlah_Bayar,
        ID_Pegawai
    ) VALUES (
        v_id_sewa_ref,
        NOW(),
        p_metode_pembayaran,
        v_total_bayar,
        v_id_pegawai
    );
    
    SET v_id_pembayaran = LAST_INSERT_ID();
    OPEN pesanan_cursor;
    
    read_loop: LOOP
        FETCH pesanan_cursor INTO v_id_sewa;
        
        IF done THEN
            LEAVE read_loop;
        END IF;
        UPDATE penyewaan_lapangan 
        SET STATUS = 'terbooking'
        WHERE ID_Sewa = v_id_sewa;
        
    END LOOP;
    
    CLOSE pesanan_cursor;
    COMMIT;
    SELECT 
        v_id_pembayaran AS ID_Pembayaran,
        v_total_bayar AS Total_Pembayaran,
        v_jumlah_pesanan AS Jumlah_Pesanan,
        'Pembayaran berhasil diproses' AS Pesan;
        
END$$

DELIMITER ;




-- view
--1
CREATE OR REPLACE VIEW keranjang AS
SELECT 
    l.Nama_Lapangan,
    pl.Tanggal,
    pl.Jam_Mulai,
    pl.Jam_Selesai,
    pl.Total_Biaya
FROM 
    penyewaan_lapangan pl
JOIN 
    lapangan l ON pl.ID_Lapangan = l.ID_Lapangan;


-- 2
CREATE VIEW view_detail_penyewaan AS
SELECT 
    p.ID_Sewa,
    pl.Nama AS Pelanggan,
    l.Nama_Lapangan AS Lapangan,
    l.Jenis AS Jenis,
    p.Tanggal,
    CONCAT(p.Jam_Mulai, ' - ', p.Jam_Selesai) AS Waktu,
    TIMESTAMPDIFF(HOUR, p.Jam_Mulai, p.Jam_Selesai) AS Durasi,
    p.Total_Biaya,
    p.Status,
    pm.Metode_pembayaran
FROM 
    penyewaan_lapangan p
JOIN 
    pelanggan pl ON p.ID_Pelanggan = pl.ID_Pelanggan
JOIN 
    lapangan l ON p.ID_Lapangan = l.ID_Lapangan
LEFT JOIN
    pembayaran pm ON p.ID_Sewa = pm.ID_Sewa;

                                            
-- 3
-- View untuk login admin
CREATE OR REPLACE VIEW login_admin AS 
SELECT 
    ID_Pegawai,
    Email,
    pw,
    'pegawai' AS role
FROM pegawai
WHERE pw IS NOT NULL;

--4
-- View untuk login user
CREATE OR REPLACE VIEW login_user AS 
SELECT 
    ID_Pelanggan,
    Email,
    pw,
    'pelanggan' AS role
FROM pelanggan
WHERE pw IS NOT NULL;


--5
-- view data pembayaran
CREATE OR REPLACE VIEW v_pembayaran_lengkap AS
SELECT 
    p.ID_Pembayaran,
    p.ID_Sewa,
    pel.Nama,          
    p.Tanggal_Pembayaran,
    p.Metode_Pembayaran,
    p.Jumlah_Bayar
FROM Pembayaran p
JOIN Penyewaan_Lapangan pl ON p.ID_Sewa = pl.ID_Sewa
JOIN Pelanggan pel ON pl.ID_Pelanggan = pel.ID_Pelanggan
ORDER BY p.Tanggal_Pembayaran DESC;





-- triger
-- 1
-- insert
DELIMITER $$

CREATE TRIGGER ubah_status_setelah_bayar
AFTER INSERT ON pembayaran
FOR EACH ROW
BEGIN
    -- Update status lapangan jadi 'terboking'
    UPDATE penyewaan_lapangan
    SET STATUS = 'terboking'
    WHERE ID_Sewa = NEW.ID_Sewa;
END$$

DELIMITER ;

--2
-- delete
DELIMITER //

DELIMITER //

CREATE TRIGGER after_delete_pembayaran
AFTER DELETE ON pembayaran
FOR EACH ROW
BEGIN

    UPDATE penyewaan_lapangan 
    SET STATUS = 'tersedia'
    WHERE ID_Sewa = OLD.ID_Sewa;
    
END//

DELIMITER ;

-- 3
-- update
DELIMITER //

CREATE TRIGGER before_update_pelanggan
BEFORE UPDATE ON pelanggan
FOR EACH ROW
BEGIN
    
    -- Validasi Email tidak boleh kosong
    IF NEW.Email = '' OR NEW.Email IS NULL THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'username tidak boleh kosong';
    END IF;
    
    -- Validasi Password tidak boleh kosong
    IF NEW.pw = '' OR NEW.pw IS NULL THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Password tidak boleh kosong';
    END IF;
    
    -- Validasi format email sederhana
    IF NEW.Email NOT LIKE '%@%.%' THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Format email tidak valid';
    END IF;

    -- Cek duplikasi email (kecuali record yang sedang diupdate)
    IF EXISTS (
        SELECT 1 FROM pelanggan 
        WHERE Email = NEW.Email 
        AND ID_Pelanggan != NEW.ID_Pelanggan
    ) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Email sudah digunakan oleh pelanggan lain';
    END IF;
    
   
END//

DELIMITER ;

