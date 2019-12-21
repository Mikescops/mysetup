<?php

/**
 * @var \App\View\AppView $this
 */

$this->layout = 'admin';
$this->assign('title', __('Send Notification | myAdmin'));
?>

<div class="col-12" style="max-width: 800px">
	<h3><?= __('Send notification') ?></h3>

	<?= $this->Form->create(null); ?>
	<fieldset>
		<?= $this->Form->input('user_id', ['type' => 'text', 'list' => 'usersList', 'class' => 'form-control', 'placeholder' => __('Type to search'), 'label' => __('User ID')]) ?>
		<datalist id="usersList">
			<?php foreach ($usersList as $user_id => $username) : ?>
				<option value="<?= $user_id ?>"><?= h($username) ?></option>
			<?php endforeach; ?>
		</datalist>
		<br>
		<div class="form-group">
			<?= $this->Form->control('message', ['class' => 'form-control', 'id' => 'formControlTextArea', 'type' => 'textarea', 'rows' => 3, 'label' => __('Content of notification (you can use HTML)')]) ?>
		</div>
		<button type="submit" class="btn btn-primary"><?= __('Push notification') ?></button>
	</fieldset>
	<?= $this->Form->end(); ?>

</div>