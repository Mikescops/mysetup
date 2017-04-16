<?php
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>

<script>
const toast = new siiimpleToast();
toast.alert('<?= $message ?>');
</script>
