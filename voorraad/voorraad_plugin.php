<?php
add_action('add_meta_boxes', 'adj_register_metabox');

function adj_register_metabox(){
	add_meta_box('adj_voorraad_metabox', 'Details', 'adj_voorraad_metabox', 'voorraadbeheer', 'normal', 'default');
}

function adj_voorraad_metabox($post){
	$voorraad_prijs				= esc_html(get_post_meta($post->ID, voorraad_prijs				, true));
	$voorraad_aantal				= esc_html(get_post_meta($post->ID, voorraad_aantal			, true));
	$voorraad_adviesprijs			= esc_html(get_post_meta($post->ID, voorraad_adviesprijs		, true));
	$voorraad_datum_gekocht		= esc_html(get_post_meta($post->ID, voorraad_datum_gekocht		, true));
	$voorraad_datum_ontvangen		= esc_html(get_post_meta($post->ID, voorraad_datum_ontvangen	, true));
?>


<table>
<tbody>
  <tr>
    <td style="width: 200px;">Prijs inkoop per stuk &euro;</td>
    <td><input type="text" size="40" name="voorraad_prijs" required value="<?php echo number_format($voorraad_prijs, 2, '.', ''); ?>" /></td>
  </tr>
  <tr>
    <td>Adviesprijs verkoop per stuk &euro;</td>
    <td><input type="text" size="40" name="voorraad_adviesprijs" required value="<?php echo number_format($voorraad_adviesprijs, 2, '.', ''); ?>" /></td>
  </tr>
  <tr>
    <td>Aantal producten</td>
    <td><input type="text" size="40" name="voorraad_aantal" required value="<?php echo number_format($voorraad_aantal, 0, '.', ''); ?>" /></td>
  </tr>
   <tr>
    <td>Datum gekocht</td>
    <td><input type="text" size="40" name="voorraad_datum_gekocht" value="<?php echo $voorraad_datum_gekocht ?>" /></td>
  </tr>
   <tr>
    <td>Datum ontvangen</td>
    <td><input type="text" size="40" name="voorraad_datum_ontvangen" value="<?php echo $voorraad_datum_ontvangen; ?>" /></td>
  </tr>
  </tbody>
</table>

<?php
}

add_action('save_post', 'adj_save_voorraad', 10, 2);

function adj_save_voorraad($post_id = false, $post = false){
	if($post->post_type == 'voorraadbeheer'){
		
		if(!empty($_POST['voorraad_prijs'])){
			update_post_meta($post_id, 'voorraad_prijs', $_POST['voorraad_prijs']);
		}
		if(!empty($_POST['voorraad_adviesprijs'])){
			update_post_meta($post_id, 'voorraad_adviesprijs', $_POST['voorraad_adviesprijs']);
		}
		if(!empty($_POST['voorraad_aantal'])){
			update_post_meta($post_id, 'voorraad_aantal', $_POST['voorraad_aantal']);
		}
		if(!empty($_POST['voorraad_datum_gekocht'])){
			update_post_meta($post_id, 'voorraad_datum_gekocht', $_POST['voorraad_datum_gekocht']);
		}
		if(!empty($_POST['voorraad_datum_ontvangen'])){
			update_post_meta($post_id, 'voorraad_datum_ontvangen', $_POST['voorraad_datum_ontvangen']);
		}
	}
}


add_filter( 'manage_edit-voorraadbeheer_columns', 'adj_set_custom_edit_book_columns' );
add_action( 'manage_voorraadbeheer_posts_custom_column' , 'adj_custom_book_column', 10, 2 );

function adj_set_custom_edit_book_columns($columns) {
	$columns['prijs'] = __( '&euro; Prijs per stuk', 'your_text_domain' );
	$columns['adviesprijs'] = __( '&euro; Adviesprijs per stuk', 'your_text_domain' );
	$columns['aantal'] = __( 'Aantal artikelen', 'your_text_domain' );
	$columns['verkocht'] = __( 'Verkocht', 'your_text_domain' );
	$columns['voorraad'] = __( 'In voorraad', 'your_text_domain' );
	$columns['gekocht'] = __( 'Gekocht op', 'your_text_domain' );
	$columns['ontvangen'] = __( 'Binnen op', 'your_text_domain' );
	return $columns;
}

function adj_custom_book_column( $column, $post_id ) {
	$verkocht				= adj_verkocht($post_id);
	$voorraad_aantal		= get_post_meta( $post_id , 'voorraad_aantal' 				, true );
	$voorraad_prijs		= get_post_meta( $post_id , 'voorraad_prijs' 				, true );
	$voorraad_adviesprijs	= get_post_meta( $post_id , 'voorraad_adviesprijs' 			, true );
	$voorraad_gekocht		= get_post_meta( $post_id , 'voorraad_datum_gekocht' 		, true );
	$voorraad_ontvangen	= get_post_meta( $post_id , 'voorraad_datum_ontvangen' 	, true );
    switch ( $column ) {
        case 'aantal' :
			echo number_format($voorraad_aantal, 0, '.', ''); 
			break;
			case 'adviesprijs' :
			echo number_format($voorraad_adviesprijs, 2, '.', ''); 
			break;
        case 'prijs' :
			echo '&euro;&nbsp;'.number_format($voorraad_prijs, 2, '.', ''); 
			break;
		case 'verkocht' :
			echo number_format($verkocht, 0, '.', ''); 
			break;	
		case 'voorraad' :
			echo $voorraad_aantal - $verkocht; 
			break;	
		case 'gekocht' :
			echo $voorraad_gekocht; 
			break;	
		case 'ontvangen' :
			echo $voorraad_ontvangen; 
			break;	
    }
}
?>