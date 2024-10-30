<?php
/**
 * Plugin Name: Borner inkoop- verkoopbeheer
 * Plugin URI: http://borner.nl
 * Description: Houd op eenvoudige manier voorraad en verkoop bij (Het is geen administratie systeem).
 * Version: 0.3
 * Author: Arvid de Jong
 * Author URI: http://borner.nl
 * License: GPLv2
 *
 */

add_action('init', 'adj_create_post_type');

function adj_create_post_type(){
	register_post_type('verkoopbeheer', 
		array(
			'labels' => array(
				'name'               => 'Verkoop',
				'singular_name'      => 'Verkoop',
				'add_new'            => 'Verkoop toevoegen',
				'add_new_item'       => 'Voeg verkoop toe',
				'edit_item'          => 'Pas verkoop aan',
				'new_item'           => 'Nieuwe verkoop',
				'all_items'          => 'Alle verkopen',
				'view_item'          => 'Bekijk verkoop',
				'search_items'       => 'Zoek verkoop',
				'not_found'          => 'Geen verkopen gevonden',
				'not_found_in_trash' => 'Geen verkopen gevonden in de prullenbak',
				'parent_item_colon'  => '',
				'menu_name'          => 'Verkoop'
			),
			'public' => true,
			'menu_position' => 20,
			'supports' => array('title'),
			'taxonomies' => array(''),
			'has_archive' => true
		)
	);
	
	register_post_type('voorraadbeheer', 
		array(
			'labels' => array(
				'name'               => 'Voorraad',
				'singular_name'      => 'Voorraad',
				'add_new'            => 'Toevoegen',
				'add_new_item'       => 'Voeg artikel toe',
				'edit_item'          => 'Pas artikel aan',
				'new_item'           => 'Nieuw artikel',
				'all_items'          => 'Voorraad',
				'view_item'          => 'Bekijk artikel',
				'search_items'       => 'Zoek artikel',
				'not_found'          => 'Geen artikelen gevonden',
				'not_found_in_trash' => 'Geen artikelen gevonden in de prullenbak',
				'parent_item_colon'  => '',
				'menu_name'          => 'Voorraad'
			),
			'public' => true,
			'menu_position' => 20,
			'supports' => array('title'),
			'taxonomies' => array(''),
			'has_archive' => true,
			'show_in_menu' => 'edit.php?post_type=verkoopbeheer'
		)
	);
}

add_action('admin_menu', 'register_winkel_stats');

function register_winkel_stats() {
	add_submenu_page( 'edit.php?post_type=verkoopbeheer', 'Winkel Statistieken', 'Statistieken', 'manage_options', 'winkel-stats', 'winkel_stats_callback' ); 
}

/*
add_action('admin_menu', 'register_winkel_voorraad');

function register_winkel_voorraad() {
	add_submenu_page( 'edit.php?post_type=verkoopbeheer', 'Winkel voorraad', 'Voorraad', 'manage_options', 'winkel-voorraad'); 
}
*/





function winkel_stats_callback() {
?>

<div class="wrap">
  <div id="icon-tools" class="icon32"></div>
  <h2>Statistieken</h2>
  <br>
  <br>
  <table class="widefat" cellspacing="0" style=" width:200Px;">
    <tr>
      <td style="width: 100px;">Totaal gekocht</td>
      <td>&euro;&nbsp;</td>
      <td align="right"><?php echo number_format(adj_totaal_gekocht(), 2, '.', ''); ?></td>
    </tr>
    <tr>
      <td style="width: 100px;">Totaal verkocht</td>
      <td>&euro;&nbsp;</td>
      <td align="right"><?php echo number_format(adj_totaal_verkocht(), 2, '.', ''); ?></td>
    </tr>
    <tr>
      <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
      <td style="width: 100px;">Winst</td>
      <td>&euro;&nbsp;</td>
      <td align="right"><?php echo number_format(adj_totaal_winst(), 2, '.', ''); ?></td>
    </tr>
    <tr>
      <td>Deel winkel</td>
      <td>&euro;&nbsp;</td>
      <td align="right"><?php echo number_format(adj_totaal_winkel(), 2, '.', ''); ?></td>
    </tr>
    <tr>
      <td>Deel partner</td>
      <td>&euro;&nbsp;</td>
      <td align="right"><?php echo number_format(adj_totaal_partner(), 2, '.', ''); ?></td>
    </tr>
  </table>
</div>
<?php

}

include('functions.php');
include('verkoop/verkoop_plugin.php');
include('voorraad/voorraad_plugin.php');

?>
