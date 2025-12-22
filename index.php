<?php
session_start();
if(isset($_SESSION['user_id'])){
    if($_SESSION['role'] === 'admin'){
        header('Location: admin.php');
    } else {
        header('Location: book-list.php');
    }
} else {
    header('Location: login.php');
}
exit;
?>