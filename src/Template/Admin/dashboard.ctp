<?php

use Cake\Core\Configure;

/**
  * @var \App\View\AppView $this
  */

    $this->layout = 'admin';
    $this->assign('title', __('Dashboard | myAdmin'));
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>

<div class="col-12 col-md-9 col-xl-10">

    <div class="row mb-3">
        <div class="col-xl-2 col-sm-6">
            <div class="card text-white bg-dark h-100">
                <div class="card-body bg-dark">
                    <div class="rotate">
                        <h1 class="display-4"><?= $stats['count']['setups'] ?> <i data-toggle="tooltip" data-placement="top" data-feather="hard-drive" width="50px" height="50px"></i></h1> <?= strtoupper(__n('Setup', 'Setups', $stats['count']['setups'])) ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-sm-6">
            <div class="card text-white bg-success h-100">
                <div class="card-body bg-success">
                    <div class="rotate">
                        <h1 class="display-4"><?= $stats['count']['users'] ?> <i data-toggle="tooltip" data-placement="top" data-feather="users" width="50px" height="50px"></i></h1> <?= strtoupper(__n('User', 'Users', $stats['count']['users'])) ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-sm-6">
            <div class="card text-white bg-info h-100">
                <div class="card-body bg-info">
                    <div class="rotate">
                        <h1 class="display-4"><?= $stats['count']['comments'] ?> <i data-toggle="tooltip" data-placement="top" data-feather="message-circle" width="50px" height="50px"></i></h1> <?= strtoupper(__n('Comment', 'Comments', $stats['count']['comments'])) ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-sm-6">
            <div class="card text-white bg-danger h-100">
                <div class="card-body bg-danger">
                    <div class="rotate">
                        <h1 class="display-4"><?= $stats['count']['likes'] ?> <i data-toggle="tooltip" data-placement="top" data-feather="heart" width="50px" height="50px"></i></h1> <?= strtoupper(__n('Like', 'Likes', $stats['count']['likes'])) ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-sm-6">
            <div class="card text-white bg-warning h-100">
                <div class="card-body bg-warning">
                    <div class="rotate">
                        <h1 class="display-4"><?= $stats['count']['resources']['images'] ?> <i data-toggle="tooltip" data-placement="top" data-feather="image" width="50px" height="50px"></i></h1> <?= strtoupper(__n('Image', 'Images', $stats['count']['resources']['images'])) ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-sm-6">
            <div class="card text-white bg-secondary h-100">
                <div class="card-body bg-secondary">
                    <div class="rotate">
                        <h1 class="display-4"><?= $stats['count']['resources']['products'] ?> <i data-toggle="tooltip" data-placement="top" data-feather="package" width="50px" height="50px"></i></h1> <?= strtoupper(__n('Product', 'Products', $stats['count']['resources']['products'])) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr>

    <div class="row mb-3">
        <div class="col-xl-3 col-sm-6">
            <br>
            <h3><?= __('Certified users') ?></h3>
            <canvas id="certified-user" width="400" height="400"></canvas>
        </div>
        <div class="col-xl-3 col-sm-6">
            <br>
            <h3><?= __('Twitch users') ?></h3>
            <canvas id="twitch-user" width="400" height="400"></canvas>
        </div>
        <div class="col-xl-6 col-sm-6">
            <br>
            <h3>Analytics (Production)</h3>
            <div id="widgetIframe">
                <iframe
                    width="100%" height="350"
                    src="https://analytics.geek-mexicain.net/index.php?module=Widgetize&action=iframe&forceView=1&viewDataTable=graphEvolution&widget=1&moduleToWidgetize=VisitsSummary&actionToWidgetize=getEvolutionGraph&idSite=2&period=day&date=today&disableLink=1&widget=1&token_auth=<?= Configure::read('Credentials.Matomo.token') ?>&language=<?= strtolower($authUser['preferredStore']) ?>"
                    scrolling="no" frameborder="0" marginheight="0" marginwidth="0">
                </iframe>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-xl-6 col-sm-6">
            <br>
            <h3><?= __('Recently connected') ?></h3>
            <div class="list-group">
                <?php foreach ($stats['users']['recentConnected'] as $user):?>
                    <a href="<?=$this->Url->build('/users/'.$user->id)?>" targe="_blank" class="list-group-item list-group-item-action flex-column align-items-start">
                        <div class="media">
                            <img class="mr-3 rounded" height="45" src="<?= $this->Url->build('/uploads/files/pics/profile_picture_' . $user->id . '.png?' . $this->Time->format($user->modificationDate, 'mmss', null, null)); ?>">
                            <div class="media-body">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1"><?= h($user->name) ?>
                                        <?php if(is_null($user->mailVerification)):?><i data-toggle="tooltip" data-placement="top" data-feather="mail" title="<?= __('Mail verified') ?>"></i><?php endif;?>
                                        <?php if($user->twitchToken):?><i data-toggle="tooltip" data-placement="top" data-feather="at-sign" title="<?= __('Twitch user id') ?> : <?= $user->twitchUserId ?>"></i><?php endif;?>
                                        <?php if($user->verified == "1"):?><i data-toggle="tooltip" data-placement="top" data-feather="check-circle" title="<?= __('Certified user') ?>"></i><?php endif;?>
                                        <?php if($user->verified == "125"):?><i data-toggle="tooltip" data-placement="top" data-feather="award" title="<?= __('Admin user') ?>"></i><?php endif;?>
                                    </h5>
                                    <small><?= $this->Time->format($user->lastLogginDate, [\IntlDateFormatter::MEDIUM, \IntlDateFormatter::SHORT], $user->lastLogginDate, $authUser['timeZone']) ?></small>
                                </div>
                                <small><?= h($user->mail) ?></small>
                            </div>
                        </div>
                    </a>
                <?php endforeach ?>
            </div>
        </div>

        <div class="col-xl-6 col-sm-6">
            <br>
            <h3><?= __('Recently registered') ?></h3>
            <div class="list-group">
                <?php foreach ($stats['users']['recentCreated'] as $user):?>
                    <a href="<?=$this->Url->build('/users/'.$user->id)?>" targe="_blank" class="list-group-item list-group-item-action flex-column align-items-start">
                        <div class="media">
                            <img class="mr-3 rounded" height="45" src="<?= $this->Url->build('/uploads/files/pics/profile_picture_' . $user->id . '.png?' . $this->Time->format($user->modificationDate, 'mmss', null, null)); ?>">
                            <div class="media-body">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1"><?= h($user->name) ?>
                                        <?php if(is_null($user->mailVerification)):?><i data-toggle="tooltip" data-placement="top" data-feather="mail" title="<?= __('Mail verified') ?>"></i><?php endif;?>
                                        <?php if($user->twitchToken):?><i data-toggle="tooltip" data-placement="top" data-feather="at-sign" title="<?= __('Twitch user id') ?> : <?= $user->twitchUserId ?>"></i><?php endif;?>
                                        <?php if($user->verified == "1"):?><i data-toggle="tooltip" data-placement="top" data-feather="check-circle" title="<?= __('Certified user') ?>"></i><?php endif;?>
                                        <?php if($user->verified == "125"):?><i data-toggle="tooltip" data-placement="top" data-feather="award" title="<?= __('Admin user') ?>"></i><?php endif;?>
                                    </h5>
                                    <small><?= $this->Time->format($user->creationDate, [\IntlDateFormatter::MEDIUM, \IntlDateFormatter::SHORT], $user->creationDate, $authUser['timeZone']) ?></small>
                                </div>
                                <small><?= h($user->mail) ?></small>
                            </div>
                        </div>
                    </a>
                <?php endforeach ?>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-xl-6 col-sm-6">
            <br>
            <h3><?= __('Latest comments') ?></h3>
            <div class="list-group">
                <?php foreach ($stats['comments']['recentCreated'] as $comment):?>
                    <a href="<?=$this->Url->build('/setups/'.$comment->setup_id.'#comments')?>" targe="_blank" class="list-group-item list-group-item-action flex-column align-items-start">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1"><strong><?= h($comment->user->name) ?></strong> <?= __x('commentaire "sur" un setup', 'on') ?> <strong><?= h($comment->setup->title) ?></strong></h5>
                            <small><?= $this->Time->format($comment->dateTime, [\IntlDateFormatter::MEDIUM, \IntlDateFormatter::SHORT], $comment->dateTime, $authUser['timeZone']) ?></small>
                        </div>
                        <p class="mb-1"><?= h($comment->content) ?></p>
                        <small><?= h($comment->user->mail) ?></small>
                    </a>
                <?php endforeach ?>
            </div>
        </div>

        <div class="col-xl-6 col-sm-6">
            <br>
            <h3><?= __('Ownership requests') ?></h3>
            <div class="list-group">
                <?php foreach ($stats['requests']['onGoing'] as $request):?>
                    <a href="<?=$this->Url->build('/setups/'.$request->setup_id)?>" targe="_blank" class="list-group-item list-group-item-action flex-column align-items-start">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1"><strong><?= h($request->user->name) ?></strong> <?= __('asks for ownership on') ?> <strong><?= h($request->setup->title) ?></strong></h5>
                            <small>#<?= $request->token ?></small>
                        </div>
                        <p class="mb-1"><?= h($request->content) ?></p>
                        <small><?= h($request->user->mail) ?></small>
                    </a>
                <?php endforeach ?>
            </div>
        </div>
    </div>

    <script>
        new Chart(document.getElementById("certified-user"),{"type":"doughnut","data":{"labels":["<?=__('Certified')?>","<?=__('Not certified')?>"],"datasets":[{"label":"<?=__('Certified users')?>","data":[<?=$stats['users']['certified']?>,100-<?=$stats['users']['certified']?>],"backgroundColor":["rgb(54, 162, 235)","rgb(255, 99, 132)"]}]}});

        new Chart(document.getElementById("twitch-user"),{"type":"doughnut","data":{"labels":["<?=__('Twitch users')?>","<?=__('Other users')?>"],"datasets":[{"label":"<?=__('Certified users')?>","data":[<?=$stats['users']['twitch']?>,100-<?=$stats['users']['twitch']?>],"backgroundColor":["#6441a5","rgb(255, 99, 132)"]}]}});
    </script>
</div>
