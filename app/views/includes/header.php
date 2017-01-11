<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2017
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */

use BYOG\Components\Auth;

if (!isset($GLOBALS['page_title'])) {
    $GLOBALS['page_title'] = 'Untitled Page';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>
    <title><?php echo $GLOBALS['page_title']; ?> | G for Gruyere</title>

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.8/css/materialize.min.css" rel="stylesheet"/>
    <link href="/assets/style.css" type="text/css" rel="stylesheet"/>
</head>
<body>
<nav class="pink darken-4" role="navigation">
    <div class="nav-wrapper container"><a id="logo-container" href="/" class="brand-logo">G for Gruyere</a>

        <?php
        function nav_menu()
        {
            if (Auth::isGuest()) :
                ?>
                <li><a href="/">Home</a></li>
                <li><a href="/login">Login</a></li>
                <li><a href="/sign-up">Sign Up</a></li>
                <?php
            else:
                ?>
                <li><a href="/">Home</a></li>
                <li><a href="/snippets">Snippets</a></li>
                <li><a href="/files">Files</a></li>
                <li><a href="/settings">Settings</a></li>
                <li><a href="/logout">Logout</a></li>
                <?php
            endif;
        }

        ?>

        <ul class="right hide-on-med-and-down">
            <?php nav_menu(); ?>
        </ul>

        <ul id="nav-mobile" class="side-nav">
            <?php nav_menu(); ?>
        </ul>
        <a href="#" data-activates="nav-mobile" class="button-collapse"><i class="material-icons">menu</i></a>
    </div>
</nav>

<div class="container main-content">
    <div class="section">
        <h1><?php echo $GLOBALS['page_title']; ?></h1>