<?php
add_action('add_meta_boxes', 'adj_register_metabox_verkoop');

function adj_register_metabox_verkoop(){
	add_meta_box('adj_verkoop_metabox', 'Verkocht product', 'adj_verkoop_metabox', 'verkoopbeheer', 'normal', 'default');
}

function adj_verkoop_metabox($post){
	$verkoop_artikel	= esc_html(get_post_meta($post->ID, verkoop_artikel, true));
	$verkoop_prijs		= esc_html(get_post_meta($post->ID, verkoop_prijs, true));
	$verkoop_aantal	= esc_html(get_post_meta($post->ID, verkoop_aantal, true));
	$verkoop_klant		= esc_html(get_post_meta($post->ID, verkoop_klant, true));
	$verkoop_partner	= esc_html(get_post_meta($post->ID, verkoop_partner, true));
	if(empty($verkoop_partner)){
		$verkoop_partner = 25;
	}
?>

<table>
<tr>
    <td style="width: 200px;">Artikel</td>
    <td><select name="verkoop_artikel" id="verkoop_artikel">
<?php
	$args = array('posts_per_page'   => -1,  'orderby' => 'title', 'post_type' => 'voorraadbeheer');
	$myposts = get_posts( $args );
	foreach ( $myposts as $post ) : setup_postdata( $post );
?>
	<option value="<?php echo $post->ID; ?>"<?php if($post->ID == $verkoop_artikel){ ?> selected<?php } ?>><?php echo get_the_title($post->ID); ?></option>
<?
	endforeach; 
	wp_reset_postdata();
?>    
    </select>
    </td>
  </tr>
  <tr>
    <td>Prijs per stuk &euro;</td>
    <td><input type="text" size="40" name="verkoop_prijs" required value="<?php echo number_format($verkoop_prijs, 2, '.', ''); ?>" placeholder="Verkoopprijs" /></td>
  </tr>
  <tr>
    <td>Aantal producten</td>
    <td><input type="text" size="40" name="verkoop_aantal" required value="<?php echo number_format($verkoop_aantal, 0, '.', ''); ?>" placeholder="Aantal verkochte producten" /></td>
  </tr>
  <tr style="display:none">
    <td>Verkocht aan</td>
    <td><input type="text" size="40" name="verkoop_klant" value="<?php echo $verkoop_klant; ?>" placeholder="Naam van de klant" /></td>
  </tr>
  <tr>
    <td>Partner percentage %</td>
    <td><input type="text" size="40" name="verkoop_partner" required value="<?php echo $verkoop_partner; ?>" placeholder="Percentage voor verkooppartner" /></td>
  </tr>
</table>
<?php
}

add_action('save_post', 'adj_save_verkoop', 10, 2);

function adj_save_verkoop($post_id = false, $post = false){
	if($post->post_type == 'verkoopbeheer'){
		if(!empty($_POST['verkoop_artikel'])){
			update_post_meta($post_id, 'verkoop_artikel', $_POST['verkoop_artikel']);
		}
		if(!empty($_POST['verkoop_prijs'])){
			update_post_meta($post_id, 'verkoop_prijs', $_POST['verkoop_prijs']);
		}
		if(!empty($_POST['verkoop_aantal'])){
			update_post_meta($post_id, 'verkoop_aantal', $_POST['verkoop_aantal']);
		}
		if(!empty($_POST['verkoop_klant'])){
			update_post_meta($post_id, 'verkoop_klant', $_POST['verkoop_klant']);
		}
		if(!empty($_POST['verkoop_aantal'])){
			update_post_meta($post_id, 'verkoop_partner', $_POST['verkoop_partner']);
		}
	}
}

add_filter( 'manage_edit-verkoopbeheer_columns', 'adj_set_custom_verkoop' );
add_action( 'manage_verkoopbeheer_posts_custom_column' , 'adj_custom_verkoop_column', 10, 2 );

function adj_set_custom_verkoop($columns) {
	
	$columns['artikel'] 		= __( 'Artikel', 'your_text_domain' );
	$columns['prijs'] 			= __( '&euro; Prijs per stuk', 'your_text_domain' );
	$columns['aantal'] 		= __( 'Aantal', 'your_text_domain' );
	$columns['totaal'] 		= __( '&euro; Totaal', 'your_text_domain' );
	$columns['winst'] 			= __( '&euro; Winst', 'your_text_domain' );
	//$columns['aan']			= __( 'Verkocht aan', 'your_text_domain' );
	$columns['percentage']	= __( '% partner', 'your_text_domain' );
	$columns['partnerdeel']	= __( '&euro; partner', 'your_text_domain' );
	$columns['winkel']			= __( '&euro; winkel', 'your_text_domain' );
	return $columns;
}

function adj_custom_verkoop_column( $column, $post_id ) {
	$artikel_id		= get_post_meta( $post_id , 'verkoop_artikel' , true );
	$artikel			= get_the_title($artikel_id);
	$verkoop_aantal	= get_post_meta( $post_id , 'verkoop_aantal' , true );
	$verkoop_prijs		= get_post_meta( $post_id , 'verkoop_prijs' , true );
	$percentage		= get_post_meta( $post_id , 'verkoop_partner' , true );
	$verkoop_klant		= get_post_meta( $post_id , 'verkoop_klant' , true );
	$winst_regel		= adj_winst_regel($post_id);
	$totaal				= $verkoop_aantal * $verkoop_prijs;
	$partnerdeel		= adj_partnerdeel_regel($post_id);
	switch ( $column ) {
        case 'artikel' :
			echo $artikel; 
			break;
		case 'aantal' :
			echo number_format($verkoop_aantal, 0, '.', ''); 
			break;
		case 'totaal' :
			echo '&euro;&nbsp;'.number_format($totaal, 2, '.', ''); 
			break;
        case 'prijs' :
			echo '&euro;&nbsp;'.number_format($verkoop_prijs, 2, '.', ''); 
			break;
		case 'aan' :
			echo $verkoop_klant; 
			break;
		case 'winst' :
			echo '&euro;&nbsp;'.$winst_regel; 
			break;	
		case 'percentage' :
			echo $percentage.' %'; 
			break;
		case 'partnerdeel' :
			echo '&euro;&nbsp;'.number_format($partnerdeel, 2, '.', ''); 
			break;
		case 'winkel' :
			echo '&euro;&nbsp;'.number_format($winst_regel - $partnerdeel, 2, '.', ''); 
			break;
    }
}


?>