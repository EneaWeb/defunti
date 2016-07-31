<?php
/**
 * Template Name: Pagina Defunti Tot
 *
 * @package WordPress
 * @subpackage blessing
 */

	global $wpdb;
	$error = '';
	
	$rows_per_page = 16;
	$current = (intval(get_query_var('paged'))) ? intval(get_query_var('paged')) : 1;
	 
	$q = "WHERE id > 0 ";
	// $rows is the array that we are going to paginate.
	if (isset($_GET['from-birthdate']) && $_GET['from-birthdate'] != '') {
		$from_birthdate = $_GET['from-birthdate'];
		$q .= "AND bithdate >= DATE_FORMAT('" . $from_birthdate . "', '%d/%m/%Y') ";
	}
	if (isset($_GET['to-birthdate']) && $_GET['to-birthdate'] != '') {
		$to_birthdate = $_GET['to-birthdate'];
		$q .= "AND bithdate <= DATE_FORMAT('" . $to_birthdate . "', '%d/%m/%Y') ";
	}
	if (isset($_GET['from-deathdate']) && $_GET['from-deathdate'] != '') {
		$from_deathdate = $_GET['from-deathdate'];
		$q .= "AND deathdate >= DATE_FORMAT('" . $from_deathdate . "', '%d/%m/%Y') ";
	}
	if (isset($_GET['to-birthdate']) && $_GET['to-birthdate'] != '') {
		$to_deathdate = $_GET['to-birthdate'];
		$q .= "AND deathdate <= DATE_FORMAT('" . $to_deathdate . "', '%d/%m/%Y') ";
	}
	if (isset($_GET['search-name']) && $_GET['search-name'] != '') {
		$search_name = $_GET['search-name'];
		$q .= "AND name LIKE '%$search_name%' ";
	}
	if (isset($_GET['search-birthplace']) && $_GET['search-birthplace'] != '') {
		$search_birthplace = $_GET['search-birthplace'];
		$q .= "AND birthplace LIKE '%$search_birthplace%' ";
	}
	if (isset($_GET['search-deatplace']) && $_GET['search-deatplace'] != '') {
		$search_deatplace = $_GET['search-deatplace'];
		$q .= "AND deatplace LIKE '%$search_deatplace%' ";
	}

	$rows = $wpdb->get_results( "SELECT * FROM wp_defunti ".$q, OBJECT);
	
	global $wp_rewrite;
	 
	$pagination_args = array(
	 'base' => @add_query_arg('paged','%#%'),
	 'format' => '',
	 'total' => ceil(sizeof($rows)/$rows_per_page),
	 'current' => $current,
	 'show_all' => false,
	 'type' => 'plain',
	);

?>

<?php get_header() ?> 
<style>
.form-control {font-size:16px!important;}
select.form-control:not([size]):not([multiple]) {height:34px!important;}
.error {color:red; font-size:bold;}
.mini {font-size:0.7em;}
.pagination .page-numbers { padding:10px; background-color:#E5DCB3; border:1px solid #E0E0E0; text-decoration:none;}
#tutti-i-defunti a {text-decoration:none;}
</style>

<div id="tutti-i-defunti">

	<h2 style="margin-top:0px">Nel nostro cuore:</h2>
	
	<h5>Cerca:</h5>
	
	<form action="" method="GET">
		<div class="form-group" style="background-color:#E5E5E5; margin-bottom:20px; padding:20px">
			
			<div class="col-md-6">
			
				<div class="col-md-12" style="padding-right:0px;">	
				</div>	
				<div class="col-md-12" style="padding-right:0px;">
					<div class="form-group">
						<label for="search-name">Nome:</label>
						<input type="text" class="input form-control" name="search-name"/>
					</div>
				</div>
				<div class="col-md-6" style="padding-right:0px;">
					<div class="form-group">
						<label for="search-birthplace">Città di nascita:</label>
						<input type="text" class="input form-control" name="search-birthplace"/>
					</div>
				</div>
				<div class="col-md-6" style="padding-right:0px;">
					<div class="form-group">
						<label for="search-deathplace">Città di morte:</label>
						<input type="text" class="input form-control" name="search-deathplace"/>
					</div>
				</div>
				
			</div>
			<div class="col-md-6">
			
				<div class="col-md-6" style="padding-right:0px;">
					<div class="form-group">
						<label for="from-birthdate">Data di nascita DA:</label>
						<input type="text" class="input form-control datepicker" name="from-birthdate"/>
					</div>
				</div>
				<div class="col-md-6" style="padding-right:0px;">
					<div class="form-group">
						<label for="from-birthdate">Data di nascita A:</label>
						<input type="text" class="input form-control datepicker" name="to-birthdate"/>
					</div>
				</div>
				<div class="col-md-6" style="padding-right:0px;">
					<div class="form-group">
						<label for="from-birthdate">Data di morte DA:</label>
						<input type="text" class="input form-control datepicker" name="from-deathdate"/>
					</div>
				</div>
				<div class="col-md-6" style="padding-right:0px;">
					<div class="form-group">
						<label for="from-birthdate">Data di morte A:</label>
						<input type="text" class="input form-control datepicker" name="to-deathdate"/>
					</div>
				</div>
				<div class="col-md-12" style="padding-right:0px; text-align:right">
					<div class="form-group">
						<input type="submit" name="wp-submit" id="wp-submit" class="button" value="Cerca">
						<a href="/defunti" class="btn btn-info" style="padding:10px; font-size:15px; border-radius:0px">Reset</a>
					</div>
				</div>
			</div>
			
			<div class="clearfix"></div>
		</div>
	</form>
	
	<div class="clearfix"><br><br></div>
	<?php
		 
		$start = ($current - 1) * $rows_per_page;
		$end = $start + $rows_per_page;
		$end = (sizeof($rows) < $end) ? sizeof($rows) : $end;
	?>
	
	<?php if ($rows) : ?>
		
		<table width='100%' align='center' border='3px solid grey'>

			<tbody>
			
				<?php for ($i=$start;$i < $end ;++$i ) : ?>
				<?php $defunto = $rows[$i]; ?>
		
					<tr>
						<td style="width:300px; text-align:center">
							<a href="/defunto?defunto_id=<?php echo $defunto->id?>">
								<div style="width:300px; height:300px; background-position:center; background-image:URL('/wp-content/uploads/defunti/<?php echo $defunto->picture;?>'); background-size:cover;"></div>
							</a>
						</td>
						<td>
							<a href="/defunto?defunto_id=<?php echo $defunto->id?>">
								<h3 style="margin-top:0px"><?php echo $defunto->name ?> 
								<span class="mini">- <?php echo $defunto->deathdate ?></span></h3>
								<p style="text-transform:none">
									<?php echo substr($defunto->biography, 0, 400) .((strlen($defunto->biography) > 400) ? '...' : ''); ?>
								</p>
							</a>
						</td>
					</tr>
					
				<?php endfor; ?> 
			
			</tbody>
		</table>

	
	<br><br>
	<div style="text-align:right; float:right;">
		<?php 
			if( $wp_rewrite->using_permalinks() )
			 $pagination_args['base'] = user_trailingslashit( trailingslashit( remove_query_arg('s',get_pagenum_link(1) ) ) . 'page/%#%/', 'paged');
			 
			if( !empty($wp_query->query_vars['s']) )
			 $pagination_args['add_args'] = array('s'=>get_query_var('s'));
			
			echo '<nav aria-label="Page navigation"><ul class="pagination">';
			echo paginate_links($pagination_args);
			echo '</ul></nav>'; 
		?>
	</div>
	
	<?php else : ?>
		
		<p>Nessun risultato trovato.</p>
		
	<?php endif; ?>
</div>

<?php get_footer() ?>