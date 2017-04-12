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
        <h3>Login on mysetup.co</h3>
        <form>
            <ul>
                <li>
                    <input type="text" placeholder="Username" />
                </li>
                <li>
                    <input type="password" placeholder="Password" />
                </li>
                <li>
                    <input type="submit" value="Login" class="button" />
                </li>
            </ul>
        </form>
    </div>
    <!--/#login.form-action-->
    <div id="register" class="form-action hide">
        <h3>Register</h3>
        <form>
            <ul>
                <li>
                    <input type="text" placeholder="Username" />
                </li>
                <li>
                    <input type="password" placeholder="Password" />
                </li>
                <li>
                    <input type="submit" value="Sign Up" class="button" />
                </li>
            </ul>
        </form>
    </div>
    <!--/#register.form-action-->
    <div id="reset" class="form-action hide">
        <h3>Reset Password</h3>
        <div>
            To reset your password enter your email and we'll send you a link to reset your password.
        </div><br>
        <form>
            <ul>
                <li>
                    <input type="text" placeholder="Email" />
                </li>
                <li>
                    <input type="submit" value="Send" class="button" />
                </li>
            </ul>
        </form>
    </div>
    <!--/#register.form-action-->
</div>


<div class="users form">
<?= $this->Flash->render('auth') ?>
    <?= $this->Form->create() ?>
    <fieldset>
        <legend><?= __('Please enter your username and password') ?></legend>
        <?= $this->Form->control('username') ?>
        <?= $this->Form->control('password') ?>
    </fieldset>
    <?= $this->Form->button(__('Login')); ?>
    <?= $this->Form->end() ?>
</div>
