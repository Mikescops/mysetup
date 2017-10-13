<!DOCTYPE html>
<?php
    if(!$lang)
    {
      $lang = ($authUser && $authUser['preferredStore'] !== "US" && $authUser['preferredStore'] !== "UK" ? strtolower($authUser['preferredStore']) : "en");
    }
?>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="<?= $lang ?>"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="<?= $lang ?>"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="<?= $lang ?>"> <![endif]-->
<!--[if gt IE 8]><!-->
<html lang="<?= $lang ?>">
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>
        Admin | <?= $this->fetch('title') ?>
    </title>

    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>

    <?= $this->Html->css('font-awesome.min.css') ?>

    <?= $this->Html->meta(['link' => '/img/favicon/apple-touch-icon.png', 'rel' => 'apple-touch-icon', 'sizes' => '180x180']) ?>
    <?= $this->Html->meta(['link' => '/img/favicon/favicon-32x32.png', 'rel' => 'icon', 'type' => 'image/png', 'sizes' => '32x32']) ?>
    <?= $this->Html->meta(['link' => '/img/favicon/favicon-16x16.png', 'rel' => 'icon', 'type' => 'image/png', 'sizes' => '16x16']) ?>
    <?= $this->Html->meta(['link' => '/img/favicon/manifest.json', 'rel' => 'manifest']) ?>
    <?= $this->Html->meta(['link' => '/img/favicon/safari-pinned-tab.svg', 'rel' => 'mask-icon', 'color' => '#151515']) ?>
    <?= $this->Html->meta(['link' => '/img/favicon/favicon.ico', 'rel' => 'shortcut icon']) ?>
    <?= $this->Html->meta(['link' => '/img/favicon/browserconfig.xml', 'name' => 'msapplication-config']) ?>
    <meta name="theme-color" content="#151515">

    <meta name="twitter:card" value="summary">
    <meta property="og:type" content="article" />
    <meta name="twitter:site" content="@mysetup_co">
    <meta property="og:site_name" content="mySetup.co" />
    <meta property="fb:admins" content="1912097312403661" />

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>


</head>

<body>

    <nav class="navbar navbar-expand-lg sticky-top navbar-dark bg-dark">
      <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <a class="navbar-brand" href="<?= $this->Url->build('/admin'); ?>">myAdmin</a>

      <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
        <ul class="navbar-nav mr-auto mt-2 mt-md-0">
          <li class="nav-item">
            <a class="nav-link" href="<?= $this->Url->build('/admin/setups'); ?>"><i class="fa fa-server"></i> Setups</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= $this->Url->build('/admin/users'); ?>"><i class="fa fa-user-o"></i> Users </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= $this->Url->build('/admin/comments'); ?>"><i class="fa fa-comment-o"></i> Comments </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= $this->Url->build('/admin/resources'); ?>"><i class="fa fa-database"></i> Resources</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= $this->Url->build(['controller' => 'Articles', 'action' => 'add']); ?>"><i class="fa fa-pencil-square-o"></i> Add Article</a>
          </li>
        </ul>
        <ul class="navbar-nav my-2 my-lg-0">
          <li class="nav-item">
            <a class="nav-link" href="<?= $this->Url->build('/'); ?>"><i class="fa fa-external-link"></i> View website</a>
          </li>
        </ul>
      </div>
    </nav>

    <div class="container pt-4"><?= $this->fetch('content') ?></div>

    <?= $this->Html->script('lib.min.js') ?>
    <?= $this->Html->script('app.min.js') ?>

    <script>const toast = new siiimpleToast();</script>
    <?= $this->Flash->render() ?>

</body>
</html>
