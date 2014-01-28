<div class="container">
		<div class="form-bg"><div id='login'>
			<?php echo form_open('user/reset'); ?>
				<h2>Entrez votre mail</h2>
				<p><input id="reseti" type="text" placeholder="foo@bar.com" name="mail" value="<?= set_value('mail'); ?>" /></p>
				<div id='logsub'><button type="submit">Envoyer</button></div>
			</form>
		</div></div>
</div>
