<?php
/**
  * @var \App\View\AppView $this
  */
?>

<div class="maincontainer">

    <div class="row">
        <div class="column column-75">

            <h3><?= h($user->name) ?>'s setups <?php if($user->verified): echo '<i class="fa fa-check-square verified_account"></i>'; endif ?></h3>

            <?php  if (!empty($user->setups)): $i = 0; foreach ($user->setups as $setups): ?>
            <div class="fullitem">
                <a href="<?= $this->Url->build('/setups/view/'); ?><?= $setups->id ?>">
                    <img src="<?= $this->Url->build('/'); ?><?= $fimage[$i]->src ?>">
                </a>
                <div class="fullitem-inner">

                    <div class="row">

                        <div class="column column-75">
                            <a class="featured-user" href="">
                                <img src="<?= $this->Url->build('/'); ?>uploads/files/profile_picture_<?= $setups->user_id ?>.png">
                            </a>

                            <a href="<?= $this->Url->build('/setups/view/'); ?><?= $setups->id ?>"><h3><?= h($setups->title) ?></h3></a>

                        </div>

                        <div class="column column-25"></div>

                    </div>
                </div>
            </div>
            <?php $i++; endforeach; endif ?>

        </div>
        <div class="column column-25 sidebar">
            <br><br>
            <a class="twitter-timeline" data-dnt="true" data-theme="dark" href="https://twitter.com/mysetup_co">Tweets by mysetup_co</a> <script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
        </div>
    </div>

</div>