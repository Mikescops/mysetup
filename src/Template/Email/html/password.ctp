
<p style="color: #dddddd; font-size: 18px; font-family: sans-serif; line-height: 20px">
    <?= __('Hello') ?> <?= h($name) ?> !
    <br />
    <br />
    <?= __('Your password has been reseted and set to') ?> : <span style="color: black; font-size: 95%; font-weight: bold; text-decoration: line-through;"><?= $password ?></span><br />
    <br />
    <?= __('Please click on the button below to log you in, and don\'t forget to change it as soon as possible') ?> :
</p>
<a href="<?= $this->Url->build('/login', true) ?>" target="_blank" class="button-a">
    <span style="color: #dddddd;" class="button-link"><?= __('Show me the login page !') ?></span>
</a>
