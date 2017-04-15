<?php
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>

<div class="message success" onclick="this.classList.add('hidden')"><?= $message ?><i class="fa fa-times float-right"></i>
</div>

<script>
	setTimeout('$(".success").slideUp()', 5000)
</script>
