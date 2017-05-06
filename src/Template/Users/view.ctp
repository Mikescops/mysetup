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

            <?= ('There is no setup here yet...') ?>

            <?php endif ?>

        </div>
        <div class="column column-25 sidebar">

            <?php if($authUser['admin']): ?>

                <a class="button" href="#edit_user_admin" data-lity><?= ('Edit this user') ?></a>

                <div id="edit_user_admin" class="lity-hide">
                    <?= $this->Form->create($user, ['type' => 'file', 'url' => ['controller' => 'Users', 'action' => 'edit', $user->id]]); ?>
                    <fieldset style="border:0;">
                    <h4><?= ('Change only what you want !') ?></h4>
                    <div class="row">
                    <div class="column column-25">
                    <div class="profile-container">
                       <image id="profileImage" src="<?= $this->Url->build('/'); ?>uploads/files/pics/profile_picture_<?= $user['id'] ?>.png" />
                    </div>

                    <div class="profilepicup">
                        <?php
                        echo $this->Form->input('picture. ', ['label' => __("Change the profile picture"), 'type' => 'file', 'class' => 'inputfile', 'id' => 'profileUpload']);
                        ?>
                    </div>

                    <br>

                    <?php
                        echo $this->Form->select('preferredStore', ["US" => "US", "UK" => "UK", "ES" => "ES", "IT" => "IT", "FR" => "FR", "DE" => "DE"], ['default' => $user['preferredStore']]);
                        ?>
                    </div>
                    <div class="column column-75">
                        <?php
                            echo $this->Form->control('name', ['required' => true, 'label' => '', 'placeholder' => __("Name"), 'default' => $user['name']]);
                            echo $this->Form->control('mail', ['required' => true, 'type' => 'email', 'label' => '', 'placeholder' => __("Email address"), 'default' => $user['mail']]);
                        ?>

                        <?php
                            echo $this->Form->control('secret', ['pattern' => '.{8,}', 'type' => 'password', 'placeholder' => __("Password"), 'class' => 'pwd_field', 'label' => '']);
                            echo $this->Form->control('secret2', ['type' => 'password', 'placeholder' => __("Confirm password"), 'class' => 'pwd_field', 'label' => '']);
                        ?>
                        <a class="reset_pwd float-right"><i class="fa fa-repeat"></i> <?= ('Change the user password') ?></a>
                        <?php
                            echo $this->Form->control('verified', ['type' => 'checkbox', 'label' => 'User verified', 'default' => $user['verified'], 'required' => false]);
                        ?>
                    </div>
                    </div>
                    
                    </fieldset>
                    <?= $this->Form->submit(__('Submit'), ['class' => 'float-right']); ?>
                    <?= $this->Form->end(); ?>

                    <?= $this->Form->postLink('Delete this account', array('controller' => 'Users','action' => 'delete', $user['id']),array('confirm' => 'You are going to delete your account and all its content (profile, setups, comments, likes) ! Are you sure ?')) ?>
                </div>

            <?php endif ?>

            <br><br>
            <a class="twitter-timeline" data-dnt="true" data-theme="dark" href="https://twitter.com/mysetup_co"><?= ('Tweets by mysetup_co') ?></a> <script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
        </div>
    </div>
</div>
