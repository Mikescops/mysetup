<?php
/**
  * @var \App\View\AppView $this
  */
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
            <h3>Login on MySetup.co</h3>
            <ul>
                <li>
                    <?= $this->Form->control('mail', ['placeholder' => 'Email', 'label' => false, 'type' => 'text']) ?>
                </li>
                <li>
                    <?= $this->Form->control('password', ['placeholder' => 'Password', 'label' => false]) ?>
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
            <h3>Register</h3>
            <ul>
                <li>
                    <?= $this->Form->control('mail', ['placeholder' => 'Email', 'label' => false, 'type' => 'text']) ?>
                </li>
                <li>
                    <?= $this->Form->control('password', ['placeholder' => 'Password', 'label' => false]) ?>
                </li>
                <li>
                    <?= $this->Form->control('password2', ['placeholder' => 'Repeat password', 'label' => false, 'type' => 'password']) ?>
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
            <h3>Reset Password</h3>
            <div>
                To reset your password enter your email and we'll send you a link to reset your password.
            </div>
            <br>
            <ul>
                <li>
                    <?= $this->Form->control('mail', ['placeholder' => 'Email address', 'label' => false, 'type' => 'text']) ?>
                </li>
                <li>
                    <?= $this->Form->button(__('Send'), ['class' => 'button']); ?>
                </li>
            </ul>
        </fieldset>
    </div>
    <!--/#register.form-action-->
</div>


<div class="users form">
<?= $this->Flash->render('auth') ?>
    <?= $this->Form->create() ?>
    <fieldset>
        <legend><?= __('Please enter your email and password') ?></legend>
        <?= $this->Form->control('mail') ?>
        <?= $this->Form->control('password') ?>
    </fieldset>
    <?= $this->Form->button(__('Login')); ?>
    <?= $this->Form->end() ?>
</div>
