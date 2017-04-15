<?php
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>

<div class="message warning" onclick="this.classList.add('hidden')"><?= $message ?><i class="fa fa-times float-right"></i>
</div>

<script>
	setTimeout('$(".warning").slideUp()', 5000)
</script>
