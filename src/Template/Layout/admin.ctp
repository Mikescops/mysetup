<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="<?= $lang ?>"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="<?= $lang ?>"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="<?= $lang ?>"> <![endif]-->
<!--[if gt IE 8]><!-->
<html lang="<?= $lang ?>">
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>
        <?= $this->fetch('title') ?>
    </title>

    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>

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

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" crossorigin="anonymous">

    <style type="text/css">
      a .feather{
        height: 20px;
        vertical-align: text-bottom;
      }
      .feather{
        vertical-align: middle;
      }
    </style>
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
            <a class="nav-link" href="<?= $this->Url->build('/admin/setups'); ?>"><i data-feather="hard-drive"></i> <?= __('Setups') ?></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= $this->Url->build('/admin/users'); ?>"><i data-feather="users"></i> <?= __('Users') ?></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= $this->Url->build('/admin/comments'); ?>"><i data-feather="message-circle"></i> <?= __('Comments') ?></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= $this->Url->build('/admin/resources'); ?>"><i data-feather="layers"></i> <?= __('Resources') ?></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= $this->Url->build(['controller' => 'Articles', 'action' => 'add']); ?>"><i data-feather="edit"></i> <?= __('Add Article') ?></a>
          </li>
        </ul>
        <ul class="navbar-nav my-2 my-lg-0">
          <li class="nav-item">
            <a class="nav-link" href="<?= $this->Url->build('/'); ?>"><i data-feather="external-link"></i> <?= __('View website') ?></a>
          </li>
        </ul>
      </div>
    </nav>

    <div class="container-fluid pt-4">

      <div class="row">
        <div class="col-12 col-md-3 col-xl-2 d-none d-md-block">

            <div class="list-group">
              <a href="<?=$this->Url->build('/users/'. $authUser['id'])?>" class="list-group-item active"><i data-feather="package"></i> <?= __('Welcome') ?> <?= h($authUser['name']) ?></a>
              <a href="<?= $this->Url->build('/admin/sendNotification'); ?>" class="list-group-item list-group-item-action"><i data-feather="send"></i> <?= __('Send notification') ?></a>
              <?= $this->Form->postLink('<i data-feather="rotate-ccw"></i> ' . __('Clear applications caches'), ['controller' => 'Admin', 'action' => 'clear_caches'], ['class' => 'list-group-item list-group-item-action', 'confirm' => __('Are you sure you want to clear the application caches ?'), 'escape' => false]) ?>
              <a href="https://github.com/Mikescops/mysetup" class="list-group-item list-group-item-action" target="_blank"><i data-feather="github"></i> Github Repo</a>
              <a href="https://github.com/Mikescops/mysetup-twitch-extension" class="list-group-item list-group-item-action" target="_blank"><i data-feather="airplay"></i> Extension Twitch Repo</a>
              <?php if($debug): ?>
                  <a class="list-group-item list-group-item-action" style="color: red; cursor: initial;"><i data-feather="git-branch"></i> <?= __('Development Instance') ?></a>
              <?php endif; ?>
            </div>

        </div>

        <?= $this->fetch('content') ?>

      </div>

    </div>

    <hr>

    <footer class="footer">
        <div class="container-fluid">
            <p class="text-center text-muted">mySetup.co - Admin Panel</p>
        </div>
    </footer>

    <script>
        const webRootJs = "<?= $this->Url->build('/', true); ?>";
    </script>

    <!-- Set the Flash dependencies on this layout (needed for entities related actions) -->
    <?= $this->Html->script('libs.min.js') ?>
    <?= $this->Html->script('app.min.js') ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.4/js/tether.min.js" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <script>
      feather.replace()
    </script>
    <script>const toast = new siiimpleToast();</script>
    <script>$(function () {$('[data-toggle="tooltip"]').tooltip()})</script>
    <?= $this->Flash->render() ?>
    <?= $this->fetch('scriptBottom') ?>
</body>
</html>
