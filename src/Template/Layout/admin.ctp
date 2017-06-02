<!DOCTYPE html>
<?php
    $lang = ($authUser && $authUser['preferredStore'] !== "US" && $authUser['preferredStore'] !== "UK" ? strtolower($authUser['preferredStore']) : "en")
?>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="<?= $lang ?>"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="<?= $lang ?>"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="<?= $lang ?>"> <![endif]-->
<!--[if gt IE 8]><!--> 
<html lang="<?= $lang ?>">
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        Admin | <?= $this->fetch('title') ?>
    </title>

    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>

    <?= $this->Html->css('font-awesome.min.css') ?>

    <link rel="apple-touch-icon" sizes="180x180" href="<?= $this->Url->build('/'); ?>img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= $this->Url->build('/'); ?>img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= $this->Url->build('/'); ?>img/favicon/favicon-16x16.png">
    <link rel="manifest" href="<?= $this->Url->build('/'); ?>img/favicon/manifest.json">
    <link rel="mask-icon" href="<?= $this->Url->build('/'); ?>img/favicon/safari-pinned-tab.svg" color="#328fea">
    <link rel="shortcut icon" href="<?= $this->Url->build('/'); ?>img/favicon/favicon.ico">
    <meta name="msapplication-config" content="<?= $this->Url->build('/'); ?>img/favicon/browserconfig.xml">
    <meta name="theme-color" content="#151515">

    <meta name="twitter:card" value="summary"> 
    <meta property="og:type" content="article" />
    <meta name="twitter:site" content="@mysetup_co">
    <meta property="og:site_name" content="mySetup.co" />
    <meta property="fb:admins" content="1912097312403661" />

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>


</head>

<body>

    <nav class="navbar navbar-toggleable-md navbar-light bg-faded">
      <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <a class="navbar-brand" href="<?= $this->Url->build(['controller' => 'Setups', 'action' => 'index']); ?>">myAdmin</a>

      <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
        <ul class="navbar-nav mr-auto mt-2 mt-md-0">
          <li class="nav-item">
            <a class="nav-link" href="<?= $this->Url->build(['controller' => 'Setups', 'action' => 'index']); ?>"><i class="fa fa-server"></i> Setups</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'index']); ?>"><i class="fa fa-user-o"></i> Users </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= $this->Url->build(['controller' => 'Comments', 'action' => 'index']); ?>"><i class="fa fa-comment-o"></i> Comments </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= $this->Url->build(['controller' => 'Resources', 'action' => 'index']); ?>"><i class="fa fa-database"></i> Resources</a>
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

</body>

<?= $this->Html->script('lib.min.js') ?>
<?= $this->Html->script('app.min.js') ?>

<script>const toast = new siiimpleToast();</script>
<?= $this->Flash->render() ?>

</html>