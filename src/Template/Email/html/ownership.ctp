Your setup <a href="<?= $this->Url->build('/setups/' . $setup_id, true) ?>"><?= h($setup_title) ?></a> has just been claimed by <a href="<?= $this->Url->build('/users/' . $requester_id, true) ?>"><?= h($requester_name) ?></a> (<a href="mailto:<?= $requester_mail ?>"><?= h($requester_mail) ?></a>) !
<br />
You can either accept or reject its request (you would keep or lose ownership on "<?= h($setup_title) ?>"), the links will stay valid for one month :
<br />
<a href="<?= $this->Url->build('/setups/request/' .  $setup_id . '/' . $token . '/1', true) ?>" target="_blank" class="button-a">
    <span style="color:#129605!important">Accept</span>
</a>
<a href="<?= $this->Url->build('/setups/request/' .  $setup_id . '/' . $token . '/0', true) ?>" target="_blank" class="button-a">
    <span style="color:#f00!important">Reject</span>
</a>