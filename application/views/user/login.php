<div class="container">
		<div class="form-bg"><div id='login'>
			<?php echo form_open('user/checkLogin'); ?>		
				<h2>SGAP - Connexion</h2>
				<p><label>Login :</label> <input type="text" placeholder="Login" name="login" value="<?= set_value('login'); ?>" /> </p>
			
				<p>	<label>Password :</label>
					<input type="password" placeholder="Password" name="passwd"  />
				</p>
				<!--
				<label for="remember">
				  <input type="checkbox" id="remember" value="remember" />
				  <span>Se souvenir de moi sur cette machine</span>
				</label>
				-->
				<button type="submit" class='login'></button>
			</form>
		</div></div>
		<p class="forgot">Mot de passe oublié ? <a href="<?= site_url('user/reset') ?>">Demande de réinitialisation.</a></p>
	</div>
</div>


