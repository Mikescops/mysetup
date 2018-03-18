
<p style="color: #dddddd; font-size: 18px; font-family: sans-serif; line-height: 20px">
    Hello the support !
    <br />
    <br />
    <a href="<?= $this->Url->build('/setups/' . $setup_id, true) ?>">This setup</a> has just been reported by <a href="<?= $this->Url->build('/users/' . $flagger_id, true) ?>"><?= h($flagger_name) ?></a> !
    <br />
    You can contact him by mail :
</p>
<a href="mailto:<?= $flagger_mail ?>" target="_blank" class="button-a">
    <span style="color:#dddddd;" class="button-link"><?= h($flagger_mail) ?></span>
</a>
