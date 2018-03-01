<?php
/**
  * @var \App\View\AppView $this
  */
$this->assign('title', __('Bug Report | mySetup.co'));
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
            data-sitekey="6LcLKx0UAAAAADiwOqPFCNOhy-UxotAtktP5AaEJ"
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
