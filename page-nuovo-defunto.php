<?php
/**
 * Template Name: Nuovo defunto
 *
 * @package WordPress
 * @subpackage blessing
 */

// IF USER IS NOT LOGGED IN
if (!is_user_logged_in() ) {
	wp_redirect('/registrati');
	exit;
}

?>

<?php get_header() ?> 
<style>
.form-control {font-size:16px!important;}
select.form-control:not([size]):not([multiple]) {height:34px!important;}
.error {color:red; font-size:bold;}
</style>

<section>

<div class="row">

	<form name="defuntoform" id="defuntoform" action="" method="POST" enctype="multipart/form-data">
		<div class="col-md-3">
			<h3 style="margin-top:0px">Inserisci un defunto</h3>
			<img src="/wp-content/uploads/2016/07/man-white-icon-hi.png" style="border:1px solid #A9A9A9" />
			<br><br>
				CARICA UNA FOTO DEL DEFUNTO
			<br>
			<input type="file" name="fileToUpload" id="fileToUpload"> 
			<input type="hidden" name="wp_user_id" value="<?php echo get_current_user_id();?>" />
		</div>
		<div class="col-md-9">
		
			<?php // In case of a registration error.
			if ( isset( $_GET['defunto_error'] ) ) : ?>
			         <div class="error">
			            <p><?php echo $_GET['defunto_error'] ?></p>
			         </div>
			<?php endif; ?>
			<br>
			<br>
			
			<div class="form-group">
				<label for="name">NOME E COGNOME</label>
				<input type="text" name="name" id="name" class="input form-control" value="" size="20" required="required">
			</div>
			<div class="col-md-6" style="padding-left:0px;">
				<div class="form-group">
					<label for="birthdate">DATA DI NASCITA</label>
					<input type="text" name="birthdate" id="birthdate" class="input form-control datepicker" value="" size="20" required="required">
				</div>
			</div>
			<div class="col-md-6" style="padding-right:0px;">
				<div class="form-group">
					<label for="birthplace">CITTA DI NASCITA</label>
					<input type="text" name="birthplace" id="birthplace" class="input form-control" value="" size="20" required="required">
				</div>
			</div>
			<div class="col-md-6" style="padding-left:0px;">
				<div class="form-group">
					<label for="deathdate">DATA DI MORTE</label>
					<input type="text" name="deathdate" id="deathdate" class="input form-control datepicker" value="" size="20" required="required">
				</div>
			</div>
			<div class="col-md-6" style="padding-right:0px;">
				<div class="form-group">
					<label for="deathplace">CITTA DI MORTE</label>
					<input type="text" name="deathplace" id="deathplace" class="input form-control" value="" size="20" required="required">
				</div>
			</div>
			<div style="max-width:500px">
				<div class="form-group">
					<label for="cemetery">CIMITERO DI SEPOLTURA</label>
					<input type="text" name="cemetery" id="cemetery" class="input form-control" value="" size="20" required="required">
				</div>
			</div>
			<div class="form-group">
				<label for="short_description">TESTO INTRODUTTIVO</label>
				<textarea name="short_description" id="short_description" class="input form-control" value="" rows="4" required="required"></textarea>
			</div>
			<div class="form-group">
				<label for="biography">BREVE BIOGRAFIA</label>
				<textarea name="biography" id="biography" class="input form-control" value="" rows="8" required="required"></textarea>
			</div>
			<div class="form-group">
				<label for="ricordo">AGGIUNGI UN RICORDO</label>
				<textarea name="ricordo" id="ricordo" class="input form-control" value="" rows="4" required="required"></textarea>
			</div>
			<div class="form-group">
				<label for="parentela">Qual è il tuo grado di parentela con il defunto?</label>
				<input type="text" name="parentela" id="parentela" class="input form-control" value="" size="20" required="required">
			</div>
			<br>
			<div class="form-group">
				<label><input type="checkbox" name="declaration_1" value="1"> Dichiaro di aver preso visione... </label>
			</div>
			<br>
			<div class="form-group">
				<input type="submit" name="wp-submit" id="wp-submit" class="button-primary" value="Registra">
			</div>
		</div>
	</form>
			<?php 
		   // In case of a registration error.
         if ( isset( $_GET['regis_error'] ) ) : ?>
 	            <div class="error">
 		            <p><?php echo $_GET['regis_error'] ?></p>
 	            </div>
         <?php endif; ?>
	</div>

</div>
</section>

<?php get_footer() ?>