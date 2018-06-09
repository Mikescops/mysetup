<div id="edit_profile_modal" class="lity-hide">
    <?= $this->Form->create(null, ['type' => 'file', 'url' => ['controller' => 'Users', 'action' => 'edit', $authUser['id']]]); ?>
    <fieldset style="border:0;">
    <h4><?= __('Change only what you want !') ?></h4>
    <div class="row">
    <div class="column column-25">
    <div class="profile-container">
       <img id="profileImage" alt="<?= __('Profile picture of') ?> <?= h($authUser['name']) ?>" src="<?= $this->Url->build('/'); ?>uploads/files/pics/profile_picture_<?= $authUser['id'] ?>.png?<?= $authUser['modificationDate']->format('is') ?>" />
    </div>

    <div class="profilepicup">
        <?php
            echo $this->Form->control('picture', ['type' => 'file', 'label' => __("Change my profile picture"), 'class' => 'inputfile', 'id' => 'profileUpload']);
        ?>
    </div>

    <br>

    <?php
        echo $this->Form->select('preferredStore', ["US" => "US", "UK" => "UK", "ES" => "ES", "IT" => "IT", "FR" => "FR", "DE" => "DE"], ['default' => $authUser['preferredStore']]);
        echo $this->Form->select('timeZone', $timezones, ['default' => $authUser['timeZone']]);
    ?>
    </div>
    <div class="column column-75">
        <?php
            echo $this->Form->control('name', ['required' => true, 'label' => ['text' => '', 'class' => 'fa fa-user'], 'placeholder' => __("Name"), 'default' => $authUser['name']]);
            echo $this->Form->control('mail', ['disabled' => true, 'type' => 'email', 'label' => ['text' => '', 'class' => 'fa fa-envelope'], 'placeholder' => __("Email address"), 'default' => $authUser['mail']]);
        ?>

        <?php
            echo $this->Form->control('uwebsite', ['label' => ['text' => '', 'class' => 'fa fa-globe'], 'placeholder' => "https://website.me", 'default' => $authUser['uwebsite']]);
            echo $this->Form->control('ufacebook', ['label' => ['text' => '', 'class' => 'fa fa-facebook'], 'placeholder' => "https://facebook.com/me", 'default' => $authUser['ufacebook']]);
            echo $this->Form->control('utwitter', ['label' => ['text' => '', 'class' => 'fa fa-twitter'], 'placeholder' => "https://twitter.com/me", 'default' => $authUser['utwitter']]);
            echo $this->Form->control('utwitch', ['label' => ['text' => '', 'class' => 'fa fa-twitch'], 'placeholder' => "https://www.twitch.tv/me", 'default' => $authUser['utwitch']]);
        ?>

        <span><?= __('Choose your main setup : ') ?></span>
        <?php
            echo $this->Form->select('mainSetup_id', $setupsList, ['default' => $authUser['mainSetup_id'], 'class' => 'form-control']);
        ?>

        <?php
            echo $this->Form->control('secret', ['pattern' => '.{8,}', 'type' => 'password', 'placeholder' => __("Password"), 'class' => 'pwd_field', 'label' => '']);
            echo $this->Form->control('secret2', ['type' => 'password', 'placeholder' => __("Confirm password"), 'class' => 'pwd_field', 'label' => '']);
        ?>
        <a class="reset_pwd float-right"><i class="fa fa-repeat"></i> <?= __('Change my password') ?></a>
    </div>
    </div>

    </fieldset>
    <?= $this->Form->submit(__('Submit'), ['class' => 'float-right']); ?>
    <?= $this->Form->end(); ?>

    <?= $this->Form->postLink('<i class="fa fa-trash" aria-hidden="true"></i> ' . __('Delete my account'), ['controller' => 'Users', 'action' => 'delete', $authUser['id']], ['confirm' => __('You are going to delete your account and all its content (profile, setups, comments, likes) ! Are you sure ?'), 'escape' => false]) ?>
    |
    <?= $this->Html->link('<i class="fa fa-database" aria-hidden="true"></i> ' . __('Retrieve my data'), ['controller' => 'Users', 'action' => 'getPersonalData'], ['escape' => false, 'target' => '_blank']) ?>
</div>