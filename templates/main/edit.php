<!-- форма для изменения данных  -->
<div class="form-edit base-style-form">
	<div class="form-base__wrapper">
		<form action="/edit/<?= $user['id'] ?>" method="POST">
			<div class="remove-form remove-form" id="<?= $user['id'] ?>">Cкрыть</div>
			<input type="hidden" name="id" value="<?= $user['id']? $user['id'] : '' ?>" />
			Name
			<div class="child">
				<input type="text" name="name" value="<?= $user['name'] ? $user['name'] : '' ?>" />
			</div>
			email
			<div class="child">
				<input type="text" name="email" value="<?= $user['email'] ? $user['email'] : ''?>" />
			</div>
			phone
			<div class="child">
				<input type="text" name="phone" value="<?= $user['phone'] ? $user['phone'] : ''?>" />
			</div>
			<div class="child">
				<input type="submit" value="Редактировать" />
			</div>
		</form>
	</div>

	<script src="js/edit-inf/remove-form.js"></script>
</div>
