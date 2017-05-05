<?php
defined('_JEXEC') or die;
?>
<div id="blogin-smslogin">
	<h1>Регистрация на сайте Dorel Store</h1>

	<form method="POST">
		<div class="formfield">
			<label for="blogin-smslogin-phone">Phone:</label>
			<input type="text" id="blogin-smslogin-phone" name="phone" value="<?php echo htmlspecialchars($this->phonenum); ?>" />
		</div>

		<div class="formsubmit">
			<input type="hidden" name="do" value="loginregister" />
			<button type="submit">Register</button>
		</div>
	</form>
</div>
