<?php
/**
  * @var \App\View\AppView $this
  */
$this->assign('title', __('Login | mySetup.co'));
?>
<div class="container sitecontainer">
<div class="login-form">
    <ul class="tabs">
        <li>
            <a href="#login" class="active"><?= __('Login') ?></a>
        </li>
        <li>
            <a href="#register"><?= __('Register') ?></a>
        </li>
        <li>
            <a href="#reset"><?= __('Reset Password') ?></a>
        </li>
    </ul>
    <div id="login" class="form-action show">
        <?= $this->Form->create(null, ['url' => ['controller' => 'Users', 'action' => 'login', '?' => ['redirect' => $this->request->getQuery('redirect')]]]) ?>
        <fieldset>
            <ul>
                <li>
                    <?= $this->Form->control('mail', ['required' => true, 'placeholder' => __('Email'), 'label' => false, 'type' => 'email']) ?>
                </li>
                <li>
                    <?= $this->Form->control('password', ['required' => true, 'placeholder' => __('Password'), 'label' => false]) ?>
                </li>
                <li>
                    <?= $this->Form->button(__('Login'), ['class' => 'button']); ?>
                </li>
            </ul>
        </fieldset>
        <?= $this->Form->end() ?>
    </div>
    <!--/#login.form-action-->
    <div id="register" class="form-action hide">
        <?= $this->Form->create(null, ['url' => ['controller' => 'Users', 'action' => 'add'], 'id' => 'register-form']) ?>
        <fieldset>
            <ul>
                <li>
                    <?= $this->Form->control('mail', ['required' => true, 'placeholder' => __('Email'), 'label' => false, 'type' => 'email', 'style' => 'width: 75%;float: left;}']) ?>
                    <?= $this->Form->select('preferredStore', ["US" => "US", "UK" => "UK", "ES" => "ES", "IT" => "IT", "FR" => "FR", "DE" => "DE"], ['style' => 'width: 20%; float: right', 'default' => "US"]) ?>
                    <?= $this->Form->control('name', ['required' => true, 'placeholder' => __('Name'), 'label' => false, 'type' => 'text', 'style' => 'width: 75%;float: left;}']) ?>
                </li>
                <li>
                    <?= $this->Form->control('password', ['pattern' => '.{8,}', 'required' => true, 'placeholder' => __('Password'), 'label' => false]) ?>
                    <?= $this->Form->control('password2', ['required' => true, 'placeholder' => __('Repeat password'), 'label' => false, 'type' => 'password']) ?>
                    <?= __('Password should be at least 8 characters.') ?>
                </li>
                <li>
                    <div class="g-recaptcha"
                        data-sitekey="6LcLKx0UAAAAADiwOqPFCNOhy-UxotAtktP5AaEJ"
                        data-size="invisible"
                        data-badge="bottomleft"
                        data-callback="onSubmit">
                    </div>
                    <?= $this->Form->button(__('Sign up')) ?>
                </li>
            </ul>
        </fieldset>
        <?= $this->Form->end() ?>

        <?= $this->Html->scriptBlock('
            $("#register-form").submit(function(event) {
                event.preventDefault();
                grecaptcha.reset();
                grecaptcha.execute();
            });

            function onSubmit(token) {
                document.getElementById("register-form").submit();
            }
        ', ['block' => 'scriptBottom']); ?>
        <script src='https://www.google.com/recaptcha/api.js' async defer></script>
    </div>
    <!--/#register.form-action-->
    <div id="reset" class="form-action hide">
        <?= $this->Form->create(null, ['url' => ['controller' => 'Users', 'action' => 'resetPassword']]) ?>
        <fieldset>
            <div>
                <?= __("To reset your password enter your email and we'll send you a new temporary password.") ?>
            </div>
            <br>
            <ul>
                <li>
                    <?= $this->Form->control('mailReset', ['placeholder' => __('Email address'), 'label' => false, 'type' => 'email', 'required' => true]) ?>
                </li>
                <li>
                    <?= $this->Form->button(__('Send'), ['class' => 'button']); ?>
                </li>
            </ul>
        </fieldset>
        <?= $this->Form->end() ?>
    </div>
    <!--/#reset.form-action-->
</div>
</div>
