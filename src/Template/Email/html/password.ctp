<?= __('Your password has been reseted and set to') ?> : <span style="font-weight: bold; text-decoration: line-through;"><?= $password ?></span><br />
<br />
<?= __('Please click on the button below to log you in, and don\'t forget to change it as soon as possible') ?> :
<br />
<a href="<?= $this->Url->build('/login', true) ?>" target="_blank" class="button-a">
    <?= __('Show me the login page !') ?>
</a>