<div id="edit_user_admin" class="lity-hide">
    <?= $this->Form->create($user, ['type' => 'file', 'url' => ['controller' => 'Users', 'action' => 'edit', $user->id]]); ?>
    <fieldset style="border:0;">
    <h4><?= __('Change only what you want !') ?></h4>
    <div>
        <?php
            echo $this->Form->control('name', ['required' => true, 'label' => ['text' => '', 'class' => 'fa fa-user'], 'placeholder' => __("Name"), 'default' => $user['name']]);
            echo $this->Form->control('mail', ['required' => true, 'type' => 'email', 'label' => ['text' => '', 'class' => 'fa fa-envelope'], 'placeholder' => __("Email address"), 'default' => $user['mail']]);
            echo $this->Form->select('preferredStore', ["US" => "US", "UK" => "UK", "ES" => "ES", "IT" => "IT", "FR" => "FR", "DE" => "DE"], ['default' => $user['preferredStore']]);
        ?>

        <?php
            echo $this->Form->control('picture', ['type' => 'file', 'label' => __("Change the user profile picture"), 'class' => 'inputfile', 'id' => 'profileUpload']);
        ?>

        <?php
            echo $this->Form->control('uwebsite', ['label' => ['text' => '', 'class' => 'fa fa-globe'], 'placeholder' => "https://website.me", 'default' => $user['uwebsite']]);
            echo $this->Form->control('ufacebook', ['label' => ['text' => '', 'class' => 'fa fa-facebook'], 'placeholder' => "https://facebook.com/me", 'default' => $user['ufacebook']]);
            echo $this->Form->control('utwitter', ['label' => ['text' => '', 'class' => 'fa fa-twitter'], 'placeholder' => "https://twitter.com/me", 'default' => $user['utwitter']]);
            echo $this->Form->control('utwitch', ['label' => ['text' => '', 'class' => 'fa fa-twitch'], 'placeholder' => "https://go.twitch.tv/me", 'default' => $user['utwitch']]);
        ?>

        <?php
            echo $this->Form->control('secret', ['pattern' => '.{8,}', 'type' => 'password', 'placeholder' => __("Password"), 'class' => 'pwd_field', 'label' => '']);
            echo $this->Form->control('secret2', ['type' => 'password', 'placeholder' => __("Confirm password"), 'class' => 'pwd_field', 'label' => '']);
        ?>

        <?php
            echo $this->Form->control('verified', ['type' => 'checkbox', 'label' => ['text' => __('User verified'), 'class' => 'checkbox'], 'default' => $user['verified'], 'required' => false, 'hiddenField' => true]);
        ?>

        <a class="reset_pwd float-right"><i class="fa fa-repeat"></i> <?= __('Change the user password') ?></a>
    </div>
    </fieldset>
    <?= $this->Form->submit(__('Submit'), ['class' => 'float-right']); ?>
    <?= $this->Form->end(); ?>

    <?= $this->Form->postLink(__('Delete this account'), ['controller' => 'Users', 'action' => 'delete', $user['id']], ['confirm' => __('You are going to delete your account and all its content (profile, setups, comments, likes) ! Are you sure ?')]) ?>
</div>