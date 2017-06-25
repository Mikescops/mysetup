<?php
/**
  * @var \App\View\AppView $this
  */

$this->assign('title', __('Setups by ') . $user->name . ' | mySetup.co');
echo $this->Html->meta('description', __('All the setups shared by ') . $user->name, ['block' => true]);
?>

<div class="container sitecontainer">

<div class="maincontainer">

    <div class="row user-profile">

        <div class="column column-50">
            <img src="<?= $this->Url->build('/uploads/files/pics/profile_picture_'.$user->id.'.png'); ?>">

            <div><h2><?= h($user->name) ?> <?php if($user->verified): echo '<i class="fa fa-check-square verified_account"></i>'; endif ?></h2>
                <ul>
                    <?php if($user->uwebsite): ?><li><i class="fa fa-globe"></i> <?= $user->uwebsite ?></li><?php endif ?>
                    <?php if($user->ufacebook): ?><li><i class="fa fa-twitter"></i> <?= $user->utwitter ?></li><?php endif ?>
                    <?php if($user->utwitter): ?><li><i class="fa fa-facebook"></i> <?= $user->ufacebook ?></li><?php endif ?>
                </ul>

            </div>

        </div>

        <div class="column column-50">
            <ul class="user-stats">
                <li><span><?= count($user['setups']) ?></span> <?= __n('setup', 'setups', count($user['setups'])) ?></li>
                <li><span><?= count($user['likes']) ?></span> <?= __n('like', 'likes', count($user['likes'])) ?></li>
                <li><span><?= count($user['comments']) ?></span> <?= __n('comment', 'comments', count($user['comments'])) ?></li>
            </ul>
        </div>

    </div>

    <div class="row">
        <div class="column column-75">

            <?php  if (!empty($user->setups)): foreach ($user->setups as $setup): ?>
            <div class="fullitem">
                <a href="<?= $this->Url->build('/setups/'.$setup->id.'-'.$this->Text->slug($setup->title)); ?>">
                    <img src="<?= $this->Url->build('/'); ?><?= $setup->resources[0]->src ?>">
                </a>
                <div class="red_like"><i class="fa fa-heart"></i>  <?php echo count($setup->likes) ?></div>

                <div class="fullitem-inner">

                    <div class="row">

                        <div class="column column-75">
                            <a class="featured-user" href="<?=$this->Url->build('/users/'.$setup->user_id)?>">
                                <img src="<?= $this->Url->build('/uploads/files/pics/profile_picture_'.$setup->user_id.'.png'); ?>">
                            </a>

                            <a href="<?= $this->Url->build('/setups/'.$setup->id.'-'.$this->Text->slug($setup->title)); ?>"><h3><?= h($setup->title) ?> <?php if($setup->status == 'DRAFT'): ?><i title="<?= __('Only you can see this setup') ?>" class="fa fa-eye-slash setup-unpublished"></i><?php endif ?></h3></a>

                        </div>

                        <div class="column column-25"></div>

                    </div>
                </div>
            </div>
            <?php endforeach; else: ?>

            <?= __('There is no setup here yet...') ?>

            <?php endif ?>

        </div>
        <div class="column column-25 sidebar sidebar-user">

            <?php if($authUser['admin']): ?>

                <a class="button" href="#edit_user_admin" data-lity><?= __('Edit this user') ?></a>

                <div id="edit_user_admin" class="lity-hide">
                    <?= $this->Form->create($user, ['type' => 'file', 'url' => ['controller' => 'Users', 'action' => 'edit', $user->id]]); ?>
                    <fieldset style="border:0;">
                    <h4><?= __('Change only what you want !') ?></h4>
                    <div class="row">
                    <div class="column column-25">
                    <div class="profile-container">
                       <image id="profileImage" src="<?= $this->Url->build('/'); ?>uploads/files/pics/profile_picture_<?= $user['id'] ?>.png" />
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
                        echo $this->Form->input('picture', ['label' => __("Change the profile picture"), 'type' => 'file', 'class' => 'inputfile', 'id' => 'profileUpload']);
                        ?>

                        <?php
                            echo $this->Form->control('secret', ['pattern' => '.{8,}', 'type' => 'password', 'placeholder' => __("Password"), 'class' => 'pwd_field', 'label' => '']);
                            echo $this->Form->control('secret2', ['type' => 'password', 'placeholder' => __("Confirm password"), 'class' => 'pwd_field', 'label' => '']);
                        ?>
                        <a class="reset_pwd float-right"><i class="fa fa-repeat"></i> <?= __('Change the user password') ?></a>
                        <?php
                            echo $this->Form->control('verified', ['type' => 'checkbox', 'label' => 'User verified', 'default' => $user['verified'], 'required' => false]);
                        ?>
                    </div>
                    </div>
                    
                    </fieldset>
                    <?= $this->Form->submit(__('Submit'), ['class' => 'float-right']); ?>
                    <?= $this->Form->end(); ?>

                    <?= $this->Form->postLink(__('Delete this account'), array('controller' => 'Users','action' => 'delete', $user['id']),array('confirm' => 'You are going to delete your account and all its content (profile, setups, comments, likes) ! Are you sure ?')) ?>
                </div>

            <br><br>

            <?php endif ?>

            <div class="blog-advert">
            <a href="<?=$this->Url->build('/blog/')?>">
              <h5><i class="fa fa-newspaper-o"></i><br><?= __('Read our latest news') ?></h5>
            </a>
            </div>

            <div class="social-networks">
                <a href="https://www.facebook.com/mysetup.co" target="_blank" style="background-color: #3b5998"><i class="fa fa-facebook fa-2x"></i></a>
                <a href="https://twitter.com/mysetup_co" target="_blank" style="background-color: #55acee"><i class="fa fa-twitter fa-2x"></i></a>
                <a href="https://geeks.one/@mysetup_co" title="Mastodon" target="_blank" style="background-color: #45668e"><i class="fa fa-2x">M</i></a>
            </div>
        </div>
    </div>
</div>
</div>