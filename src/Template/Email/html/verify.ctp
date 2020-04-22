<?= __('Please, in order to activate your account, click on the following button (your account will stay unverified for one month)') ?> :
<br />
<a href="<?= $this->Url->build('/verify/' . $id . '/' . $token, true) ?>" target="_blank" class="button-a">
    <?= __('Verify my account !') ?>
</a>