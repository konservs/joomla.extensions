<?php
defined('_JEXEC') or die;
?>
<div id="blogin-smslogin">
	<h1>Регистрация на сайте Dorel Store</h1>

	<?php if($this->codeSent): ?>
		<div class="">We already sent <b><?php echo htmlspecialchars($this->phonenum); ?></b>. Please, enter the code:</div>
		<form method="POST" action="/registerphoneconfirm">
			<div class="formfield">
				<label for="blogin-smslogin-code">Code:</label>
				<input type="text" id="blogin-smslogin-code" name="code" value="" />
			</div>
			<div class="formsubmit">
				<input type="hidden" name="phone" value="<?php echo htmlspecialchars($this->phonenum); ?>" />
				<input type="hidden" name="do" value="loginregister" />
				<button type="submit">Check Code</button>
			</div>			
		</form>
	<?php else: ?>
		<form method="POST">
			<div class="formfield">
				<label for="blogin-smslogin-phone">Phone:</label>
				<input type="text" id="blogin-smslogin-phone" name="phone" value="<?php echo htmlspecialchars($this->phonenum); ?>" />
			</div>
			<div class="formsubmit">
				<input type="hidden" name="do" value="loginregister" />
				<button type="submit">Send SMS</button>
			</div>
		</form>
	<?php endif; ?>
</div>
