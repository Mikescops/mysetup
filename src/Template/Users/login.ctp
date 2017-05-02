<?php
/**
  * @var \App\View\AppView $this
  */
$this->assign('title', 'Login | mySetup.co');
?>

<div class="login-form">
    <ul class="tabs">
        <li>
            <a href="#login" class="active">Login</a>
        </li>
        <li>
            <a href="#register">Register</a>
        </li>
        <li>
            <a href="#reset">Reset Password</a>
        </li>
    </ul>
    <div id="login" class="form-action show">
        <?= $this->Form->create(null, ['url' => ['controller' => 'Users', 'action' => 'login']]) ?>
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
        <?= $this->Form->create(null, ['url' => ['controller' => 'Users', 'action' => 'add']]) ?>
        <fieldset>
            <ul>
                <li>
                    <?= $this->Form->control('mail', ['required' => true, 'placeholder' => __('Email'), 'label' => false, 'type' => 'email', 'style' => 'width: 75%;float: left;}']) ?>
                    <?= $this->Form->select('preferredStore', ["UK" => "UK", "ES" => "ES", "IT" => "IT", "FR" => "FR", "DE" => "DE"], ['style' => 'width: 20%; float: right', 'default' => "US"]) ?>
                </li>
                <li>
                    <?= $this->Form->control('password', ['required' => true, 'placeholder' => __('Password'), 'label' => false]) ?>
                </li>
                <li>
                    <?= $this->Form->control('password2', ['required' => true, 'placeholder' => __('Repeat password'), 'label' => false, 'type' => 'password']) ?>
                </li>
                <li>
                    <?= $this->Form->button(__('Sign up'), ['class' => 'button']); ?>
                </li>
            </ul>
        </fieldset>
        <?= $this->Form->end() ?>
    </div>
    <!--/#register.form-action-->
    <div id="reset" class="form-action hide">
        <?= $this->Form->create(null, ['url' => ['controller' => 'Users', 'action' => 'resetPassword']]) ?>
        <fieldset>
            <div>
                To reset your password enter your email and we'll send you a new temporary password.
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
    <!--/#register.form-action-->
</div>