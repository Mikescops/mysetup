
<p style="color: #dddddd; font-size: 18px; font-family: sans-serif; line-height: 20px">
    Hello <?= $name ?> !
    <br />
    <br />
    Please, in order to activate your account, click on the following button :
</p>
<a href="https://mysetup.co/verify/<?= $id ?>/<?= $token ?>" target="_blank" class="button-a">
    <span style="color: #dddddd;" class="button-link">Verify my account !</span>
</a>
