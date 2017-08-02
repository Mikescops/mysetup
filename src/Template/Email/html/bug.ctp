
<p style="color: #dddddd; font-size: 18px; font-family: sans-serif; line-height: 20px">
    Hello Sam' !
    <br />
    <br />
    The following has been posted by <a href="mailto:<?= $email ?>"><?= h($email) ?></a> :
    <br />
    <br />
    <blockquote style="color: #dddddd; font-size: 20px; line-height: 20px; font-family: serif; font-style: italic; margin: 30px; border-left: 4px solid #ffff; padding-left: 15px;">
    	<?= h($content) ?>
    </blockquote>
</p>
