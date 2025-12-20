<?php
// THÔNG TIN CẦN THAY ĐỔI
define('DB_HOST','localhost');
define('DB_USER','root');    // << THAY ĐỔI USERNAME DATABASE
define('DB_PASS','');        // << THAY ĐỔI PASSWORD DATABASE
define('DB_NAME','library_db'); // << PHẢI KHỚP VỚI init.sql
define('DB_PORT',3306);

function getConn(){
    // Tạo kết nối mới
    $mysqli = @new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

    // Xử lý lỗi kết nối
    if($mysqli->connect_errno){
        die('Lỗi kết nối Database. Vui lòng kiểm tra file php/config.php và đảm bảo MySQL đang chạy. Lỗi: ' . $mysqli->connect_error);
    }
    
    // Đảm bảo hỗ trợ tiếng Việt và mã hóa chuẩn
    $mysqli->set_charset('utf8mb4');
    
    return $mysqli;
}
?>