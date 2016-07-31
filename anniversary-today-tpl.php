<?php
/**
Template Name: Anniversary Today List
 */

get_header(); 

  global $wpdb;

  $deadMembers = $wpdb->get_results( 
	"
	SELECT wu.ID, wu.user_nicename
	FROM $wpdb->users AS wu,$wpdb->usermeta AS wum
	WHERE wu.ID=wum.user_id AND wum.meta_key='utype' AND wum.meta_value='dead_man'
	"
);

?>
<link rel="stylesheet" href="<?php echo get_template_directory_uri()?>/dist/css/lightbox.min.css">
<div class="item-list-tabs" role="navigation">
			<ul>
				<li class="selected" id="members-all"><a href="#;"><?php printf( __( 'All Dead Members Anniversary today %s', 'buddypress' ), '<span></span>' ); ?></a></li>


			</ul>
</div	

	<ul id="members-list" class="item-list" role="main">

<div style='padding:4px 0 10px 0px'>
<table width=100%>
<tr><th>X</th><th>Photo</th><th>Name</th><th>Birth Date</th><th>Death Date</th><th>Action</th></tr>
<?php 
$i=1;
foreach ( $deadMembers as $deadM ) 
{ 
$fname1=get_user_meta($deadM->ID,'first_name1',true);
$lname1=get_user_meta($deadM->ID,'last_name1',true);
$dd1=get_user_meta($deadM->ID,'date_of_dead',true);
$bdate=get_user_meta($deadM->ID,'bdate',true);
$photo=get_user_meta($deadM->ID,'user_photo_profile',true);
$tdA=explode("/",date("d/m/Y"));
$odA=explode("/",$dd1);
if(($tdA[0] == $odA[0]) && ($tdA[1] == $odA[1]))
{
?>
<tr><td align=center><?php echo $i ?></td><td>
<div class="item-avatar" style='float: left;
margin: 0px 10px 0px 0px;'>
     <?php if(empty($photo)){?>
			<a class="example-image-link" href="<?php echo plugins_url()?>/buddypress/no-imagec.jpg" data-lightbox="example-set" data-title="<?php echo $fname1.' '.$lname1 ?>"><img src="<?php echo plugins_url()?>/buddypress/no-imagec.jpg" width=50 height=50 ></a>
			<?php }else{?>
      <a class="example-image-link" href="<?php echo $photo?>" data-lightbox="example-set" data-title="<?php echo $fname1.' '.$lname1 ?>"><img src="<?php echo $photo ?>" width=50 height=50 ></a>
      <?php } ?>
			</div>
</td><td align=center><?php echo $fname1.' '.$lname1 ?></td>

<td align=center><?php echo $bdate ?></td><td align=center><?php echo $dd1 ?></td><td align=center>
<a href="<?php echo get_option('siteurl').'/membri/'.$deadM->user_nicename ?>"><?php 
          
          echo 'View Profile' ?></a>
</td></tr>
<?php $i++;} } ?>
</table>

</div> 
<script src="<?php echo get_template_directory_uri()?>/dist/js/lightbox-plus-jquery.min.js"></script>
<?php
get_footer();
?>