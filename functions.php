<?php

function adj_verkocht($artikel_id){
	$aantal = 0;
	$args = array( 'posts_per_page'   => -1, 
					'post_type' => 'verkoopbeheer', 
					'meta_query' => array(
													array(
													   'key' => 'verkoop_artikel',
													   'value' => $artikel_id,
													   'compare' => 'LIKE'
													)
         ));

	$myposts = get_posts( $args );
	foreach ( $myposts as $post ) : setup_postdata( $post );
		$verkoop_aantal	= esc_html(get_post_meta($post->ID, verkoop_aantal, true));
		$verkoop_artikel	= esc_html(get_post_meta($post->ID, verkoop_artikel, true));
		$aantal				= $aantal + $verkoop_aantal;
	endforeach; 
	wp_reset_postdata();
	
	return $aantal;
}


function adj_prijs_verkoop_regel($regel_id){
	$winst 				= 0;
	$post 				= get_post( $regel_id );
	$artikel_id		= get_post_meta( $regel_id , 'verkoop_artikel' , true );
	$artikel 			= get_post( $artikel_id );
	$verkoop_aantal	= get_post_meta($post->ID, verkoop_aantal, true);
	$prijs_verkoop		= get_post_meta($post->ID, verkoop_prijs, true);
	$prijs_inkoop		= get_post_meta($artikel->ID, voorraad_prijs, true);
	$winst				= $prijs_verkoop * $verkoop_aantal;
	//$winst				= $winst * $verkoop_aantal;
	return $winst;
}



function adj_winst_regel($regel_id){
	$winst 				= 0;
	$post 				= get_post( $regel_id );
	$artikel_id		= get_post_meta( $regel_id , 'verkoop_artikel' , true );
	$artikel 			= get_post( $artikel_id );
	$verkoop_aantal	= get_post_meta($post->ID, verkoop_aantal, true);
	$prijs_verkoop		= get_post_meta($post->ID, verkoop_prijs, true);
	$prijs_inkoop		= get_post_meta($artikel->ID, voorraad_prijs, true);
	$winst				= $prijs_verkoop - $prijs_inkoop;
	$winst				= $winst * $verkoop_aantal;
	return $winst;
}

function adj_partnerdeel_regel($regel_id){
	$winst 				= 0;
	$post 				= get_post( $regel_id );
	$artikel_id		= get_post_meta( $regel_id , 'verkoop_artikel' , true );
	$artikel 			= get_post( $artikel_id );
	$verkoop_aantal	= get_post_meta($post->ID, verkoop_aantal, true);
	$prijs_verkoop		= get_post_meta($post->ID, verkoop_prijs, true);
	$prijs_inkoop		= get_post_meta($artikel->ID, voorraad_prijs, true);
	$winst				= $prijs_verkoop - $prijs_inkoop;
	$winst				= $winst * $verkoop_aantal;
	$winst				= ($winst / 100) * get_post_meta($post->ID, verkoop_partner, true);;
	return $winst;
}

function adj_totaal_partner(){
	$aantal = 0;
	$args = array( 'posts_per_page'   => -1, 
					'post_type' => 'verkoopbeheer', 
					'meta_query' => array(
													array(
													   'key' => 'verkoop_artikel',
													   'value' => $artikel_id,
													   'compare' => 'LIKE'
													)
         ));

	$myposts = get_posts( $args );
	foreach ( $myposts as $post ) : setup_postdata( $post );
		$aantal				= $aantal + adj_partnerdeel_regel($post->ID);
	endforeach; 
	wp_reset_postdata();
	return $aantal;
}

function adj_totaal_winkel(){
	$aantal = 0;
	$args = array( 'posts_per_page'   => -1, 
					'post_type' => 'verkoopbeheer', 
					'meta_query' => array(
													array(
													   'key' => 'verkoop_artikel',
													   'value' => $artikel_id,
													   'compare' => 'LIKE'
													)
         ));

	$myposts = get_posts( $args );
	foreach ( $myposts as $post ) : setup_postdata( $post );
		$aantal				= $aantal + (adj_winst_regel($post->ID) - adj_partnerdeel_regel($post->ID));
	endforeach; 
	wp_reset_postdata();
	return $aantal;
}

function adj_totaal_winst(){
	$aantal = 0;
	$args = array( 'posts_per_page'   => -1, 
					'post_type' => 'verkoopbeheer', 
					'meta_query' => array(
													array(
													   'key' => 'verkoop_artikel',
													   'value' => $artikel_id,
													   'compare' => 'LIKE'
													)
         ));

	$myposts = get_posts( $args );
	foreach ( $myposts as $post ) : setup_postdata( $post );
		$aantal				= $aantal + adj_winst_regel($post->ID);
	endforeach; 
	wp_reset_postdata();
	return $aantal;
}

function adj_totaal_verkocht(){
	
	$aantal = 0;
	$args = array( 'posts_per_page'   => -1, 
					'post_type' => 'verkoopbeheer'
					);

	$myposts = get_posts( $args );
	foreach ( $myposts as $post ) : setup_postdata( $post );
		$aantal				= $aantal + adj_prijs_verkoop_regel($post->ID);
	endforeach; 
	wp_reset_postdata();
	return $aantal;
	
}


function adj_prijs_inkoop_regel($regel_id){
	//
	
	$winst 				= 0;
	$post 				= get_post( $regel_id );
	//$artikel_id		= get_post_meta( $regel_id , 'verkoop_artikel' , true );
	//$artikel 			= get_post( $regel_id );
	$verkoop_aantal	= get_post_meta($post->ID, voorraad_aantal, true);
	//$prijs_verkoop		= get_post_meta($post->ID, voorraad_prijs, true);
	$prijs_inkoop		= get_post_meta($post->ID, voorraad_prijs, true);
	$winst				= $prijs_inkoop * $verkoop_aantal;
	//$winst				= $winst * $verkoop_aantal;
	return $winst;
	
}

function adj_totaal_gekocht(){
	
	$aantal = 0;
	$args = array( 'posts_per_page'   => -1, 
					'post_type' => 'voorraadbeheer'
					);

	$myposts = get_posts( $args );
	foreach ( $myposts as $post ) : setup_postdata( $post );
		$aantal				= $aantal + adj_prijs_inkoop_regel($post->ID);
	endforeach; 
	wp_reset_postdata();
	return $aantal;
	
}

?>