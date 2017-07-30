
<p style="color: #dddddd; font-size: 18px; font-family: sans-serif; line-height: 20px">
    Hello <?= $owner_name ?> !
    <br />
    <br />
    Your setup <a href="https://mysetup.co/setups/<?= $setup_id ?>"><?= $setup_title ?></a> has just been claimed by <a href="https://mysetup.co/users/<?= $requester_id ?>"><?= $requester_name ?></a> (<a href="mailto:<?= $requester_mail ?>"><?= $requester_mail ?></a>) !
    <br />
    You can either accept or reject its request (you would keep or lose ownership on "<?= $setup_title ?>") :
</p>
<a href="https://mysetup.co/setups/request/<?= $setup_id ?>/<?= $token ?>/1" target="_blank" class="button-a">
    <span style="color:#129605;" class="button-link">Accept</span>
</a>
<a href="https://mysetup.co/setups/request/<?= $setup_id ?>/<?= $token ?>/0" target="_blank" class="button-a">
    <span style="color:#f00;" class="button-link">Reject</span>
</a>
