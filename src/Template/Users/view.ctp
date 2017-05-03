<?php
/**
  * @var \App\View\AppView $this
  */

$this->assign('title', 'Setups by '.$user->name.' | mySetup.co');
echo $this->Html->meta('description', 'All the setups shared by '. $user->name, ['block' => true]);
?>

<div class="maincontainer">
    <div class="row">
        <div class="column column-75">

            <img class="user-profile" src="<?= $this->Url->build('/uploads/files/pics/profile_picture_'.$user->id.'.png'); ?>">

            <h3 class="user-profile"><?php if($user->name){echo $user->name;}else{echo "Unknown user";} ?>'s setups <?php if($user->verified): echo '<i class="fa fa-check-square verified_account"></i>'; endif ?></h3>

            <?php if($authUser['id'] == $user->id and !$user->name){echo "You didn't set your nickname ! Please click the top right menu and edit your profile.";} ?>

            <?php  if (!empty($user->setups)): $i = 0; foreach ($user->setups as $setup): ?>
            <div class="fullitem">
                <a href="<?= $this->Url->build('/setups/'.$setup->id.'-'.$this->Text->slug($setup->title)); ?>">
                    <img src="<?= $this->Url->build('/'); ?><?= $fimage[$i]->src ?>">
                </a>
                <div class="fullitem-inner">

                    <div class="row">

                        <div class="column column-75">
                            <a class="featured-user" href="<?=$this->Url->build('/users/'.$setup->user_id)?>">
                                <img src="<?= $this->Url->build('/uploads/files/pics/profile_picture_'.$setup->user_id.'.png'); ?>">
                            </a>

                            <a href="<?= $this->Url->build('/setups/'.$setup->id.'-'.$this->Text->slug($setup->title)); ?>"><h3><?= h($setup->title) ?></h3></a>

                        </div>

                        <div class="column column-25"></div>

                    </div>
                </div>
            </div>
            <?php $i++; endforeach; else: ?>

            There is no setup here yet...

            <?php endif ?>

        </div>
        <div class="column column-25 sidebar">
            <br><br>
            <a class="twitter-timeline" data-dnt="true" data-theme="dark" href="https://twitter.com/mysetup_co">Tweets by mysetup_co</a> <script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
        </div>
    </div>
</div>
