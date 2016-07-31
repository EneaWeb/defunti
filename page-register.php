<?php
/**
 * Template Name: Register
 *
 * @package WordPress
 * @subpackage blessing
 */

// If user is already logged in.
if (is_user_logged_in() ) {
wp_redirect('/nuovo-defunto');
exit;
}
?>

<?php get_header(); ?>
<style>
form .form-group input, form .form-group select {font-size:16px}
select.form-control:not([size]):not([multiple]) {height:34px;}
.error {color:red; font-size:bold;}
</style>

<section>

<div class="container">
<div class="col-md-6">

   <section class="aa_loginForm" style="padding:10%">
    
    	<div style="padding-top:40px;">
        <?php 
            global $user_login;
            // In case of a login error.
            if ( isset( $_GET['login'] ) && $_GET['login'] == 'failed' ) : ?>
    	            <div class="error">
    		            <p>Autenticazione fallita. Controlla i tuoi dati di accesso.</p>
    	            </div>
            <?php endif; ?>
           
         <h4>Se sei già registrato esegui il login</h4>
			<form name="loginform" id="loginform" action="http://fmqbmonza.local/wp-login.php" method="post">
				
				<div class="form-group">
					<label for="user_login">NOME UTENTE O EMAIL</label>
					<input type="text" name="log" id="user_login" class="input form-control" value="" size="20">
				</div>
				<div class="form-group">
					<label for="user_pass">PASSWORD</label>
					<input type="password" name="pwd" id="user_pass" class="input form-control" value="" size="20">
				</div>
				
				<p class="login-remember"><label><input name="rememberme" type="checkbox" id="rememberme" value="forever" checked="checked"> Ricordami</label></p>
				<br>
				<div class="form-group">
					<input type="submit" name="wp-submit" id="wp-submit" class="button-primary" value="Login">
					<input type="hidden" name="redirect_to" value="/nuovo-defunto/">
				</div>
			</form>
      </div>

	</section>
</div>
<div class="col-md-6">
	<section class="aa_loginForm" style="padding:10%">
		
		<div style="padding-top:40px;">
			<?php 
		   // In case of a registration error.
         if ( isset( $_GET['regis_error'] ) ) : ?>
 	            <div class="error">
 		            <p><?php echo $_GET['regis_error'] ?></p>
 	            </div>
         <?php endif; ?>
         <h4>Non sei ancora registrato?</h4>
			<form name="registerform" id="registerform" action="" method="POST">
				
				<div class="form-group">
					<label for="name">NOME</label>
					<input type="text" name="name" id="name" class="input form-control" value="" size="20" required="required">
				</div>
				<div class="form-group">
					<label for="surname">COGNOME</label>
					<input type="text" name="surname" id="surname" class="input form-control" value="" size="20" required="required">
				</div>
				<div class="form-group">
					<label for="email">INDIRIZZO EMAIL</label>
					<input type="email" name="email" id="email" class="input form-control" value="" size="20" required="required">
				</div>
				<div class="form-group">
					<label for="username">USERNAME</label>
					<input type="text" name="username" id="username" class="input form-control" value="" size="20" required="required">
				</div>
				<div class="form-group">
					<label for="pass">PASSWORD</label>
					<input type="password" name="pass" id="pass" class="input form-control" value="" size="20" required="required">
				</div>
				<div class="form-group">
					<label for="birthdate">DATA DI NASCITA</label>
					<input type="text" name="birthdate" id="birthdate" class="input form-control datepicker" value="" size="20" required="required">
				</div>
				<div class="form-group">
					<label for="sess">SESSO</label>
					<select name="sess" id="sess" class="input form-control" value="" required="required">
						<option value="M">M</option>
						<option value="F">F</option>
					</select>
				</div>
				<div class="form-group">
					<label for="address">INDIRIZZO RESIDENZA</label>
					<input type="text" name="address" id="address" class="input form-control" value="" size="20" required="required">
				</div>
				<br>
				<div class="checkbox">
				  <label><input type="checkbox" name="declaration_1" value="1">Dichiaro di aver letto... </label>
				</div>				
				<div class="checkbox">
				  <label><input type="checkbox" name="declaration_2" value="2">Dichiaro di aver letto... </label>
				</div>
				<div class="checkbox">
				  <label><input type="checkbox" name="declaration_3" value="3">Dichiaro di aver letto... </label>
				</div>
				<input type="hidden" name="creazione-utente" value="1">
				<br>
				<div class="form-group">
					<input type="submit" name="wp-submit" id="wp-submit" class="button-primary" value="Registrati">
				</div>
			</form>
		</div>
	</section>
</div>

</section>

<?php get_footer(); ?>