<?php
/**
  * @var \App\View\AppView $this
  */

    $this->layout = 'admin';
    $this->assign('title', __('Send Notification | myAdmin'));
?>

<style type="text/css">
	/*
	Make bootstrap-select work with bootstrap 4 see:
	https://github.com/silviomoreto/bootstrap-select/issues/1135
	*/
	.bootstrap-select{
		width: 100%;
	}

	.dropdown-toggle.btn-default {
	  color: #292b2c;
	  background-color: #fff;
	  border-color: #ccc;
	  text-align: left;
	  border: none;
	  border-bottom: 1px solid #303f9f;
	  border-radius: 0px;
	}
	.dropdown-toggle.btn-default:focus,
	.dropdown-toggle.btn-default:hover{
	  color: #292b2c;
	  background-color: #fff;
	  border-color: #ccc;
	  text-align: left;
	  border: none;
	  border-bottom: 2px solid #303f9f;
	  border-radius: 0px;
	}
	.bootstrap-select.show>.dropdown-menu>.dropdown-menu {
	  display: block;
	}

	.bootstrap-select > .dropdown-menu > .dropdown-menu li.hidden{
	  display:none;
	}

	.bootstrap-select > .dropdown-menu > .dropdown-menu li a{
	  display: block;
	  width: 100%;
	  padding: 3px 1.5rem;
	  clear: both;
	  font-weight: 400;
	  color: #292b2c;
	  text-align: inherit;
	  white-space: nowrap;
	  background: 0 0;
	  border: 0;
	}

	.dropdown-menu > li.active > a {
	  color: #fff !important;
	  background-color: #3f51b5 !important;
	}

	.bootstrap-select .check-mark::after {
	  content: "✓";
	}
	.bootstrap-select button {
	  overflow: hidden;
	  text-overflow: ellipsis;
	}

	/* Make filled out selects be the same size as empty selects */
	.bootstrap-select.btn-group .dropdown-toggle .filter-option {
	  display: inline !important;
	}

</style>

<div class="col-12" style="max-width: 800px;">
	<h3><?= __('Send notification') ?></h3>
    <?= $this->Form->create(null); ?>
    <fieldset>
		<div class="input-group">
            <?= $this->Form->select('user_id', $usersList, ['class' => 'selectpicker', 'data-live-search' => true]) ?>
		</div>
		<br />
		<div class="form-group">
            <?= $this->Form->control('message', ['class' => 'form-control', 'id' => 'formControlTextArea', 'type' => 'textarea', 'rows' => 3, 'label' => __('Content of notification (you can use HTML)')]) ?>
		</div>
		<button type="submit" class="btn btn-primary"><?= __('Push notification') ?></button>
    </fieldset>
	<?= $this->Form->end(); ?>

</div>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>

<!-- Set the Flash dependencies on this view (not needed on the layout) -->
<?= $this->Html->script('jquery-3.2.0.min.js') ?>
<?= $this->Html->script('lib.min.js') ?>
<?= $this->Html->script('app.min.js') ?>
<script>const toast = new siiimpleToast();</script>
<?= $this->Flash->render() ?>
