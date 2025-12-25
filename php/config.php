<?php
define('DB_HOST','localhost');
define('DB_USER','root');
define('DB_PASS','');
define('DB_NAME','library_db');
define('DB_PORT',3306);

function getConn(){
    $mysqli = @new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

    if($mysqli->connect_errno){
        die('Lỗi kết nối Database. Vui lòng kiểm tra file php/config.php và đảm bảo MySQL đang chạy. Lỗi: ' . $mysqli->connect_error);
    }
    
    $mysqli->set_charset('utf8mb4');
    
    return $mysqli;
}
?>