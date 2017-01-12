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
if (is_array($GLOBALS['page_title'])) {
    $pageTitle = $GLOBALS['page_title'][count($GLOBALS['page_title']) - 1];
} else {
    $pageTitle = $GLOBALS['page_title'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>
    <title><?= $pageTitle; ?> | G for Gruyere</title>

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.8/css/materialize.min.css" rel="stylesheet"/>
    <link href="/assets/style.css" type="text/css" rel="stylesheet"/>
</head>
<body>
<nav class="pink darken-4" role="navigation">
    <div class="nav-wrapper container"><a id="logo-container" href="/" class="brand-logo">G for Gruyere</a>

        <?php
        function is_active(string $page)
        {
            return $GLOBALS['current_page'] === $page ? ' class="active"' : '';
        }

        function nav_menu()
        {
            ?>
            <li<?= is_active(''); ?>><a href="/">Home</a></li>
            <?php
            if (Auth::isGuest()) :
                ?>
                <li<?= is_active('login'); ?>><a href="/login">Login</a></li>
                <li<?= is_active('sign-up'); ?>><a href="/sign-up">Sign Up</a></li>
                <?php
            else:
                ?>
                <li<?= is_active('snippets'); ?>>
                    <a href="/snippets">Snippets</a>
                </li>
                <li<?= is_active('files'); ?>><a href="/files">Files</a></li>
                <li<?= is_active('settings'); ?>><a href="/settings">Settings</a></li>
                <li><a href="/logout">Logout (<?= $_SESSION['user_name']; ?>)</a></li>
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
        <nav>
            <div class="nav-wrapper pink darken-4">
                <div class="col s12 breadcrumb-col">
                    <?php
                    if (is_array($GLOBALS['page_title'])) :
                        foreach ($GLOBALS['page_title'] as $title) :
                            ?>
                            <a href="" class="breadcrumb"><?= $title; ?></a>
                            <?php
                        endforeach;
                    else :
                        ?>
                        <a href="" class="breadcrumb"><?= $GLOBALS['page_title']; ?></a>
                        <?php
                    endif;
                    ?>
                </div>
            </div>
        </nav>