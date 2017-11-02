<?php
/**
  * @var \App\View\AppView $this
  */
$this->assign('title', __('Bug Report | mySetup.co'));
?>
<div class="container sitecontainer">
<div class="bug-report-form">

    <?= $this->Form->create(null, ['id' => 'bugreport-form']) ?>
    <fieldset style="border:0;">
        <h4><?= __('Bug Report') ?> <i class="fa fa-bug"></i></h4>
        <?php
            if(!$authUser)
            {
                echo $this->Form->control('bugMail', ['label' => __('An email to contact you, young visitor !'), 'type' => 'email', 'placeholder' => 'me.name@exemple.com', 'required' => true]);
            }
        ?>

        <?= $this->Form->control('bugDescription', ['label' => __('Bug description'), 'class' => 'textarea', 'rows' => 10, 'style' => 'width:100%', 'maxlength' => 5000, 'placeholder' => __('Please, describe precisely the bug you unfortunately encountered on mySetup.co...'), 'required' => true]) ?>

        <?= $this->Form->submit(__('Send'), ['class' => 'g-recaptcha', 'data-sitekey' => '6LcLKx0UAAAAADiwOqPFCNOhy-UxotAtktP5AaEJ', 'data-callback' => 'onSubmit', 'data-badge' => 'bottomleft']); ?>
    </fieldset>
    <?= $this->Form->end() ?>

    <script>
        function onSubmit(token) {
            document.getElementById("bugreport-form").submit();
        }
    </script>
    <!-- <script src='https://www.google.com/recaptcha/api.js' async defer></script> -->

</div>
</div>
