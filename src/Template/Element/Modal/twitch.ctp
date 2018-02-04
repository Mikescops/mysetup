<div id="embed_twitch_modal" class="lity-hide">
    <h4><?= __('How to embed your setup in Twitch ?') ?></h4>
    <p><?= __('Go to your Twitch channel and toggle panel edition.') ?></p>
    <?= $this->Html->image('howto_twitch.png', array('alt' => 'Twitch Panel Edition')) ?> <br>
    <p><?= __('Copy the following url in the link field') ?> :</p>
    <pre><code><span><?= $this->Url->build('/setups/'.$setup->id."-".$this->Text->slug($setup->title).'?ref='.urlencode($setup->user['name']), true)?></span></code></pre>
    <p><?= __('And add your personal mySetup.co banner image !') ?></p>
    <p style="text-align: center;"><img alt="<?= ('Advert - Setup by') ?> <?= h($setup->user['name']) ?>" src="<?= $this->Url->build('/imgeneration/twitch-promote.php?id='. $setup->user_id . '&name=' . $setup->user['name'] . '&setup=' . $setup->title)?>"></p>

    <p><?= __('You can even configure your Twitch Chat bot to display your link or image.') ?></p>
</div>