<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_conn = "localhost";
$database_conn = "afrikcom_as_suppdb";
$username_conn = "afrikcom_infouse";
$password_conn = "alerta123";
$conn = mysql_pconnect($hostname_conn, $username_conn, $password_conn) or trigger_error(mysql_error,E_USER_ERROR); 
?>