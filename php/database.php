<?php
function getConnection() {
    try {
        $db_username = "root";
        $db_password = "root";
        $conn = new PDO('mysql:host=localhost;dbname=villbiz', $db_username, $db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch(PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }
        return $conn;
    }
?>
