<?php

use Cake\Core\Configure;

/**
  * @var \App\View\AppView $this
  */
$this->assign('title', __('Bug Report | mySetup.co'));
echo $this->Html->meta('description', __('You found an issue on mySetup.co ? Report us and we will analyse this as soon as possible.'), ['block' => true]);

?>
<div class="colored-container">
    <div class="container"><h2 style="text-align: center;"><?= __('Bug Report') ?> <i class="fa fa-bug"></i></h2></div><br>
</div>
<div class="container">
<div class="bug-report-form">

    <?= $this->Form->create(null, ['id' => 'bugreport-form']) ?>
    <fieldset style="border:0;">
        <?php
            if(!$authUser)
            {
                echo $this->Form->control('bugMail', ['label' => __('An email to contact you, young visitor !'), 'type' => 'email', 'placeholder' => 'me.name@exemple.com', 'required' => true]);
            }
        ?>

        <?= $this->Form->control('bugDescription', ['label' => __('Bug description'), 'class' => 'textarea', 'rows' => 10, 'style' => 'width:100%', 'maxlength' => 5000, 'placeholder' => __('Please, describe precisely the bug you unfortunately encountered on mySetup.co...'), 'required' => true]) ?>

        <div class="g-recaptcha"
            data-sitekey="<?= Configure::read('Credentials.Google.CAPTCHA.site') ?>"
            data-size="invisible"
            data-badge="bottomleft"
            data-callback="onSubmit">
        </div>

        <?= $this->Form->button(__('Send')) ?>
    </fieldset>
    <?= $this->Form->end() ?>

    <?= $this->Html->scriptBlock('
        $("#bugreport-form").submit(function(event) {
            event.preventDefault();
            grecaptcha.reset();
            grecaptcha.execute();
        });

        function onSubmit(token) {
            document.getElementById("bugreport-form").submit();
        }
    ', ['block' => 'scriptBottom']); ?>
    <script src='https://www.google.com/recaptcha/api.js' async defer></script>

</div>
</div>
