<?php

use Cake\Core\Configure;

/**
 * @var \App\View\AppView $this
 */
$this->assign('title', __('Bug Report | mySetup.co'));

?>
<div class="colored-container"></div>
<div class="container">
    <div class="interstitial">
        <i class="<?= h($content->icon) ?>"></i>
        <p><?= h($content->message) ?></p>
    </div>
</div>