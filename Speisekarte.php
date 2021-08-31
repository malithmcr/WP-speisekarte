<?php
require_once dirname(__FILE__) . '/includes/Admin.php';
/*
Plugin Name: Speisekarte
Description: Sei Teil der Zukunft und lasse dein Menü digital werden
Author: Malith Priyashan
Version: 1.0.0
*/
/*  Copyright 2019 Malith Priyashan - email : malith.priyashan.dev@gmail.com

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


if (!class_exists('wpMenuCard')) {

	class wpMenuCard
	{

		public function __construct()
		{
			add_action('admin_menu', array($this, 'addTranslationsAdminMenu'));
			register_activation_hook( __FILE__, array($this, 'create_table') );
			register_deactivation_hook( __FILE__, array($this, 'remove_table') );
			add_shortcode('wp-speisekarte', array($this, 'speisekarte_shortcode'));
		}

		public function addTranslationsAdminMenu()
		{

			add_menu_page(
				'Speisekarte',
				'Speisekarte',
				'manage_options',
				'menu-cards',
				array(
					$this,
					'AdminPage'
				),
				'dashicons-welcome-write-blog',
				73
			);
		}

		public function create_table()
    {
        global $wpdb;
        global $jal_db_version;
				$jal_db_version = '1.0';
        $table_name = $wpdb->prefix . 'menu_cards';

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
					id mediumint(9) NOT NULL AUTO_INCREMENT,
					token text NOT NULL,
					PRIMARY KEY  (id)
				) $charset_collate;";

					require_once ABSPATH . 'wp-admin/includes/upgrade.php';
					dbDelta($sql);

					add_option('jal_db_version', $jal_db_version);
    }

		function remove_table() {
			global $wpdb;
			$table_name = $wpdb->prefix . 'menu_cards';
			$sql = "DROP TABLE IF EXISTS $table_name";
			$wpdb->query($sql);
		}   

		function speisekarte_shortcode($atts = array()) {
			extract(shortcode_atts(array(
				'rid' => null,
				'mid' => null
			 ), $atts));
			 return '<iframe src="https://share.speisekarte-erstellen.de/'.esc_html($rid).'/'.esc_html($mid).'"  style="display:block;border:0;min-width:800px;min-height:800px;" allow="fullscreen"></iframe>';
		}   
 

		/*
		* render AdminPage view
		* */
		public function AdminPage()
		{
			/*Show admin page UI*/
			$adminPage = new WpDraftPublishedAdminPage();
			$adminPage->render();
		}

	}

	// Create an object from the class when the admin_init action fires
	if (class_exists("wpMenuCard")) {
		$wpMenuCard = new wpMenuCard;
	}
}
?>
