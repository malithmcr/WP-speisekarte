<?php
require_once dirname(__FILE__) . '/translations/useTranslations.php';
/**
 * User: Malith Priyashan
 */
class WpDraftPublishedAdminPage
{
    public function __construct()
    {
        $this->language = 'de';
        $this->saveToken();
    }

    /*
     * Just some styling for the admin page
     */
    public function pageStyles()
    {
        echo '	<style>
			table.wp-draft-published {
				font-family: arial, sans-serif;
				border-collapse: collapse;
				margin-top: 20px;
				width: 96%;
			}

			.wp-draft-published td, th {
				border: 1px solid #dddddd;
				text-align: left;
				padding: 8px;
			}

			.wp-draft-published tr:first-child {
				background-color: #dddddd;
			}
		</style>';
    }
    /*
     * Get posts by post Type
     */
    public function getMenus()
    {
        $endpoint = 'https://speisekarte-erstellen.de/user/wp?token='.$this->getToken();
        
				$arg = array ( 'method' => 'GET');
				$response = wp_remote_request( $endpoint , $arg );
				$body = wp_remote_retrieve_body($response);
        return json_decode($body);
    }

    public function getToken()
    {
			global $wpdb;
			$token = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}menu_cards ORDER BY id DESC LIMIT 1");
			return (isset($token->token)) ? $token->token : "";
    }


    public function saveToken()
    {
        if (isset($_POST['wp-menu-card'])) {
            if ($_POST['wp-menu-card'] === 'save-token') {
                if ($_POST['wp-menu-card-token'] !== '') {
                    $data = array(
                        'token' => sanitize_text_field($_POST['wp-menu-card-token']),
                    );
                    global $wpdb;
                    $table_name = $wpdb->prefix . 'menu_cards';
                    $wpdb->insert($table_name, $data, '%s');
                } else {
                    echo esc_html('Invalid Token!');
                }
            }
        }
    }
    /*
     * HTML for the admin page view
     */
    public function render()
    {
        $this->pageStyles();
        $menus = $this->getMenus();
        ?>
		<h2><?php echo esc_html(SKDE_useTranslations($this->language, 'Wordpress Menu Card')); ?></h2>
		<label><?php echo esc_html(SKDE_useTranslations($this->language, 'Create your menucard easily.')); ?> </label>
		<br/>
		<hr/>
		<?php if($this->getToken() != ""): ?> 
		<table class="wp-draft-published">
			<tr>
				<th>Card Name</th>
				<th>Shortcode</th>
			</tr>
			<?php foreach($menus as $menu): ?>
				<tr>
							<td><?php echo esc_html($menu->name); ?></td>
							<td><input type="text" value="[wp-speisekarte rid='<?php echo esc_html($menu->restaurants[0]->id); ?>' mid='<?php echo esc_html($menu->id); ?>']" style="width:100%;" readonly /></td>
						</tr>
						<?php endforeach; ?>
		</table>
		<?php endif;?>
		<br>
		<?php if($this->getToken() == ""): ?> 
		<form method="post" action="admin.php?page=menu-cards">
		<input type="text" id="token" name="wp-menu-card-token" placeholder="token" value="<?php  echo esc_html($this->getToken()); ?>" required/>
		<input type="hidden" name="wp-menu-card" value="save-token"/>
		<input type="submit" id="submit"  target="new" class="button button-primary" value="Connect your account" />
		</form>
		
		<?php endif;?>
		<br /><br />
		<hr />
		<h2><?php echo esc_html(SKDE_useTranslations($this->language, 'Tutorials')); ?></h2>
		<iframe width="560" height="315" src="https://www.youtube.com/embed/hv41Q_C9qik" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>

		<?php
}

}
