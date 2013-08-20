<?php
    session_start();

    try {
        if ($_POST['cmd'] == 'log_out') {
            session_destroy();
            header( 'Location: /hot/');
        }
    } catch(Exception $e) {
        $error_message = "<div class='error_icon'></div><div class='error'>".$e->getMessage() . "</div><div class='clear'></div>";
    }

    try {
        if(!($_SESSION['id_user'])) {
            $link = "<a href='/hot/login.php'>Login</a>";
        } else {
            $server = $_SERVER['PHP_SELF'];
            $link = "<form method='post' action='{$server}' id='form' name='form'>
                        <input type='hidden' name='cmd' id='cmd' value='log_out' />
                        <a href='#' onClick=\"document.forms['form'].submit();\">Log Out</a>
                    </form>";
        }
    } catch(Exception $e) {
        $error_message = "<div class='error_icon'></div><div class='error'>".$e->getMessage() . "</div><div class='clear'></div>";
    }

?>
<!--A Design by W3layouts
Author: W3layout
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->

<div class="wrap">
<div class="header">
	<div class="logo"><a href="index.html"><img src="/hot/img/logo.png" /></a></div>
    <div class="search">
    	<form>
        	<input type="text" value="" />
        	<input type="submit" value="" />
        </form>
    </div>
    <div class="social">
    	<ul>
        	<li>Follow us</li>
            <li><a href="#"><span class="icon facebook"></span></a></li>
            <li><a href="#"><span class="icon twitter"></span></a></li>
            <li><a href="#"><span class="icon fav"></span></a></li>
        </ul>
    </div>
    <div class="clear"></div>
</div>
<div class="content">
    <div class="sidebar">
    	<div class="side">
            <h3>Categories</h3>
            <ul>
                <li><a href="category.html">Abstract</a></li>
                <li><a href="category.html">Animals & Pets</a></li>
                <li><a href="category.html">Art & Photography</a></li>
                <li><a href="category.html">Beauty & Fashion</a></li>
                <li><a href="category.html">Business</a></li>
                <li><a href="category.html">Communications</a></li>
                <li><a href="category.html">Computers & Technologys</a></li>
                <li><a href="category.html">Shopping</a></li>
                <li><a href="category.html">Sports & Fitness</a></li>
                <li><a href="category.html">Travel & Hotel</a></li>
                <li><a href="category.html">Web Hosting</a></li>
            </ul>
        </div>
    </div>
    <div class="nav">
        <ul>
            <li><a href="#">Flash Web</a></li>
            <li><a href="#">Blog Web</a></li>
            <li><a href="/hot/account/myaccount.php">My Account</a></li>
            <li><?php echo $link; ?></li>
        </ul>
    </div>
	<div class="main">
    <!--/div>
    <div class="clear"></div>
</div--><!--These end in footer -->

<!--/div--> <!--WRAP ENDS IN FOOTER-->
