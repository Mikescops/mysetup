
<p style="color: #dddddd; font-size: 18px; font-family: sans-serif; line-height: 20px">
    <?= __('Hello') ?> <?= h($name) ?> !
    <br />
    <br />
    <?= __('Please, in order to activate your account, click on the following button (your account will stay unverified for one month)') ?> :
</p>
<a href="<?= $this->Url->build('/verify/' . $id . '/' . $token, true) ?>" target="_blank" class="button-a">
    <span style="color: #dddddd;" class="button-link"><?= __('Verify my account !') ?></span>
</a>
