<?php 
/* 
* @wordpress-plugin
* Plugin Name: Woo Update Products
* Description:       Automatisation de mise de mise a jour de purchase_price et mise en status brouillon des produits dans WooCommerce et redirection de ceux-ci dans leur catégorie parent dans le plugin Redirection
*					 Ajout de l'attribut POIDS
* Version:           1.0.5
* Author:            Fan-Develop
* Author URI:        https://fan-develop.fr
* Requires at least: 5.9
* Requires PHP: 7.4.0
*/
namespace wdp;

defined( 'ABSPATH' ) || exit;
// on utilise l'autoload PSR4 de composer
require __DIR__ . '/vendor/autoload.php';

/* Chemin vers ce fichier dans une constante
* => sera utile pour les hook d'activation et désactivation
*/
define('WUP_MAIN_FILE', __FILE__);
define( 'WUP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'WUP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

/* If this file is called directly, abort.*/
if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once WUP_PLUGIN_DIR . 'inc/scripts.php';
require_once WUP_PLUGIN_DIR . 'plugin.php';

new WUPSettingsPage;