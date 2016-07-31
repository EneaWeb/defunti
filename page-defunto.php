<?php
/**
 * Template Name: Pagina Defunto
 *
 * @package WordPress
 * @subpackage blessing
 */

	global $wpdb;
	$error = '';

	if (isset($_GET['defunto_id'])) {
	  $defunto_id = $_GET['defunto_id'];
	  $defunto = $wpdb->get_row( "SELECT * FROM wp_defunti WHERE id = '$defunto_id'" );
	  $dediche = $wpdb->get_results( "SELECT * FROM wp_defunti_dediche WHERE defunto_id = '$defunto_id'");
	  $cerchia = $wpdb->get_results( "SELECT * FROM wp_defunti_users WHERE defunto_id = '$defunto_id'");
	  if ($cerchia) {
	  		$cerchia_user_names = array();
	  		foreach ($cerchia as $c) {
	  			$cerchia_user = get_user_by('id', $c->wp_user_id);
	  			$cerchia_user_name = $cerchia_user->first_name;
	  			$cerchia_user_lastname = $cerchia_user->last_name;
	  			$cerchia_user_names[] = $cerchia_user_name.' '.$cerchia_user_lastname;
	  		}
	  }
	  
	  if (!$defunto) {
	      // defunto non trovato in db
	  		$error .= '<h3>Defunto non trovato in archivio.</h3>
	  				<p>Torna all\'<a href="/defunti">elenco generale</a></p>';
	  }
	  // tutto ok, invio dati del defunto
	  
	} else {
	  // manca il paramentro defunto_id
	  $error .= '<h3>Defunto non trovato in archivio.</h3>
	  				<p>Torna all\'<a href="/defunti">elenco generale</a></p>';
	}
	
?>

<?php get_header() ?> 
<style>
.form-control {font-size:16px!important;}
select.form-control:not([size]):not([multiple]) {height:34px!important;}
.error {color:red; font-size:bold;}
.mini {font-size:0.7em;}
</style>

	<div class="row">
		<div class="col-md-5">
			<img src="<?php echo wp_upload_dir()['baseurl'];?>/defunti/<?php echo $defunto->picture;?>" style="max-width:100%; border-radius: 25px;"/>
		</div>
		<div class="col-md-7">
			<h2><?php echo $defunto->name ?></h2>
			<h5><?php echo $defunto->birthdate ?> <span class="mini"><?php echo $defunto->birthplace ?></span>
				- <?php echo $defunto->deathdate ?> <span class="mini"><?php echo $defunto->deathplace ?></span></h5>
			<h5>Riposa a: <span class="mini"><?php echo $defunto->cemetery ?></span></h5>
		</div>

		<div class="col-md-12">
			<br>
			<h5>Ricordo:</h5>
			<p><?php echo $defunto->ricordo ?> </p>
			<br>
			<h5>Breve Biografia:</h5>
			<p><?php echo $defunto->biography ?> </p>
		</div>
		
		<div class="col-md-12">
			<br>
			<h5>Dediche:</h5>
			<?php if (!$dediche) :
				echo 'Non ci sono dediche inserite per questo defunto.<br>';
			else : ?> 
				<?php foreach ($dediche as $dedica) : ?>
					
					<p>
						<i><strong>
							<?php echo get_userdata($dedica->wp_user_id)->first_name; ?>&nbsp;
							<?php echo get_userdata($dedica->wp_user_id)->last_name; ?>
							</strong> scrive:</i><br>
						<?php echo $dedica->dedica?>
					</p>
					
				<?php endforeach; ?>
			<?php endif; ?>
			<br>
			<form name="dedicaform" id="dedicaform" action="/prodotto/dedica?" method="GET">
				<input type="hidden" name="defunto_id" value="<?php echo $_GET['defunto_id'];?>" />
				<div class="form-group">
					<label for="custom_textarea">Scrivi una dedica:</label>
					<textarea name="custom_textarea" id="dedica" class="input form-control" value="" rows="5"></textarea>
				</div>
				<div class="form-group">
					<input type="submit" name="oksubmit" id="oksubmit" class="button-primary" value="Invia">
				</div>
			</form>
			<br><br>
			<span class="btn" style="font-size:16px; background-color:#C7C7C7; text-transform:none">
				Questa pagina è gestita da: 
				<?php echo get_userdata($defunto->wp_user_id)->first_name; ?>&nbsp;
				<?php echo get_userdata($defunto->wp_user_id)->last_name; ?>
				 - <a href="mailto:<?php echo get_userdata($defunto->wp_user_id)->user_email;?>">Contatta</a>
			</span>
		</div>
		<div class="clearfix"></div>	
	</div>
	
	<div class="row" style="border-radius:20px; background-color:#E7E7E7; margin-top:30px; padding:20px">
	
	<h6 style="margin:0px">AREA PERSONALE</h6>
	<br>
	<p>Fanno parte della cerchia: <?php echo implode(', ', $cerchia_user_names)?></p>
	<br>
	<!--
	
	<form action="" method="POST">
		Aggiungi utente alla cerchia
	</form>
	
	-->
	
	</div>

<?php get_footer() ?>