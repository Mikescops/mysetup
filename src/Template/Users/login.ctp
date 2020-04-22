<?php

use Cake\Core\Configure;

/**
 * @var \App\View\AppView $this
 */

$seo_title = __('Login | mySetup.co');
$seo_description = __('Login or create your account and start discovering awesome setups!');

$this->assign('title', $seo_title);
echo $this->Html->meta('description', $seo_description, ['block' => true]);
echo $this->Html->meta(['property' => 'og:title', 'content' => $seo_title], null, ['block' => true]);
echo $this->Html->meta(['property' => 'twitter:title', 'content' => $seo_title], null, ['block' => true]);
echo $this->Html->meta(['property' => 'og:description', 'content' => $seo_description], null, ['block' => true]);
echo $this->Html->meta(['property' => 'twitter:description', 'content' => $seo_description], null, ['block' => true]);
echo $this->Html->meta(['property' => 'og:image', 'content' => $this->Url->build('/img/mysetup_header.jpg', true)], null, ['block' => true]);
echo $this->Html->meta(['property' => 'twitter:image', 'content' => $this->Url->build('/img/mysetup_header.jpg', true)], null, ['block' => true]);
echo $this->Html->meta(['property' => 'og:url', 'content' => $this->Url->build('/', true)], null, ['block' => true]);
echo $this->Html->meta(['property' => 'og:type', 'content' => 'website'], null, ['block' => true]);
?>

<div class="container sitecontainer">
    <div class="login-form">
        <ul class="tabs">
            <li>
                <a id="tab-button-login" href="#login" class="active"><?= __('Login') ?></a>
            </li>
            <li>
                <a id="tab-button-register" href="#register"><?= __('Register') ?></a>
            </li>
            <li>
                <a id="tab-button-reset" href="#reset"><?= __('Reset Password') ?></a>
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
                        <?= $this->Form->button(__('Login'), ['class' => 'button large-button']); ?>
                        <div class="separator"><?= __('OR') ?></div>
                        <a class="button button-transparent large-button" onclick="logTwitch('<?= $lang ?>')"><?= __('Connect with') ?> Twitch <i class="fab fa-twitch"></i> </a>
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
                        <div class="g-recaptcha" data-sitekey="<?= Configure::read('Credentials.Google.CAPTCHA.site') ?>" data-size="invisible" data-badge="bottomleft" data-callback="onSubmit">
                        </div>
                        <?= $this->Form->button(__('Sign up'), ['class' => 'button large-button']) ?>
                        <div class="separator"><?= __('OR') ?></div>
                        <a class="button button-transparent large-button" onclick="logTwitch('<?= $lang ?>')"><?= __('Sign up with') ?> Twitch <i class="fab fa-twitch"></i> </a>
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
                        <?= $this->Form->button(__('Send'), ['class' => 'button large-button']); ?>
                    </li>
                </ul>
            </fieldset>
            <?= $this->Form->end() ?>
        </div>
        <!--/#reset.form-action-->
    </div>
</div>