<?php
$session_name = session_name("AuthCat-SSO");
session_set_cookie_params(0, '/', $_SERVER["SERVER_NAME"]); 
session_start(); session_destroy(); ?>