<?php
/**
 * Template Name: Pagina Oggi Ricordiamo
 *
 * @package WordPress
 * @subpackage blessing
 */

	global $wpdb;
	$error = '';
	
	$rows_per_page = 16;
	$current = (intval(get_query_var('paged'))) ? intval(get_query_var('paged')) : 1;
	
	$real_date = date('d/m/Y', time());
	$date = date('d/m', time());
	$q = "WHERE deathdate LIKE '%$date%' ";

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

	<h2 style="margin-top:0px">Oggi ricordiamo:</h2>
	
	<div class="clearfix"><br></div>
	<?php
		 
		$start = ($current - 1) * $rows_per_page;
		$end = $start + $rows_per_page;
		$end = (sizeof($rows) < $end) ? sizeof($rows) : $end;
	?>
	
	<?php if ($rows) : ?>
			
		<?php for ($i=$start;$i < $end ;++$i ) : ?>
		<?php $defunto = $rows[$i]; ?>

			<div class="col-md-3" >
				<a href="/defunto?defunto_id=<?php echo $defunto->id?>">
					<div style="width:100%; height:240px; background-position:center; background-image:URL('/wp-content/uploads/defunti/<?php echo $defunto->picture;?>'); background-size:cover;"></div>
				</a>
				<br>
				<a href="/defunto?defunto_id=<?php echo $defunto->id?>">
					<h5 style="margin-top:0px"><?php echo $defunto->name ?></h5>
				</a>
			</div>
			
		<?php endfor; ?> 
	
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