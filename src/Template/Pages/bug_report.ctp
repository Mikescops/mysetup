<?php

use Cake\Core\Configure;

/**
  * @var \App\View\AppView $this
  */
$this->assign('title', __('Bug Report | mySetup.co'));
echo $this->Html->meta('description', __('You found an issue on mySetup.co ? Report us and we will analyze this as soon as possible.'), ['block' => true]);

?>
<div class="colored-container">
    <div class="container"><h2 style="text-align: center;"><?= __('Bug Report') ?> <i class="fa fa-bug"></i></h2></div><br>
</div>
<div class="container">
<div class="bug-report-form">

    <?= $this->Form->create(null) ?>
    <fieldset style="border:0;">
        <?php
            if(!$authUser)
            {
                echo $this->Form->control('bugMail', ['label' => __('An email to contact you, young visitor !'), 'type' => 'email', 'placeholder' => 'me.name@exemple.com', 'required' => true]);
            }
        ?>

        <?= $this->Form->control('bugDescription', ['label' => __('Bug description'), 'class' => 'textarea', 'rows' => 10, 'style' => 'width:100%', 'maxlength' => 5000, 'placeholder' => __('Please, describe precisely the bug you unfortunately encountered on mySetup.co...'), 'required' => true]) ?>

        <?php
            if(!$authUser)
            {
                echo $this->Captcha->render(['placeholder' => __('Please solve this Captcha'), 'required' => true]);
            }
        ?>

        <?= $this->Form->button(__('Send')) ?>
    </fieldset>
    <?= $this->Form->end() ?>

</div>
</div>
