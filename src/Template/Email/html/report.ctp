<a href="<?= $this->Url->build('/setups/' . $setup_id, true) ?>">This setup</a> has just been reported by <a href="<?= $this->Url->build('/users/' . $flagger_id, true) ?>"><?= h($flagger_name) ?></a> !
<br />
You can contact him by mail :
<br />
<a href="mailto:<?= $flagger_mail ?>" target="_blank" class="button-a">
    <?= h($flagger_mail) ?>
</a>