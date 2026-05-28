<?php // phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- File is loaded within a function, variables are not global.
defined( 'ABSPATH' ) || die();
require_once STLSR_PLUGIN_DIR_PATH . 'includes/class-stlsr-helper.php';

$ip_headers = STLSR_Helper::ip_headers();
$misc       = STLSR_Helper::misc();
?>

<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="stlsr-save-options-form">

<?php $nonce = wp_create_nonce( 'save-options' ); ?>
	<input type="hidden" name="save-options" value="<?php echo esc_attr( $nonce ); ?>">

	<input type="hidden" name="action" value="stlsr-save-options">

	<table class="form-table">
		<tbody>

			<tr>
				<th scope="row"><?php esc_html_e( 'IP Detection - Header to Use for Visitor IP', 'login-security-recaptcha' ); ?></th>
				<td>
					<fieldset>
						<legend class="screen-reader-text">
							<span><?php esc_html_e( 'IP Detection - Header to Use for Visitor IP', 'login-security-recaptcha' ); ?></span>
						</legend>
						<label for="stlsr_ip_header">
							<select name="ip_header" id="stlsr_ip_header">
								<?php foreach ( $ip_headers as $key => $value ) { ?>
								<option <?php selected( $misc['ip_header'], $key, true ); ?> value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $value ); ?></option>
								<?php } ?>
							</select>
							<p class="description">
								<?php esc_html_e( "Select which server header should be used primarily to determine the visitor's IP address. If you're not sure, leave this as the default (`REMOTE_ADDR`). Only change this if your site is behind a proxy or firewall (like Cloudflare), and you know the correct header to trust.", 'login-security-recaptcha' ); ?>
							</p>
							<br>
							<p class="description" id="stls-check-ip" data-nonce="<?php echo esc_attr( wp_create_nonce( 'refresh-ip' ) ); ?>">
								<button type="button" id="stls-refresh-ip" class="button"><?php esc_html_e( 'Check Your IP Address', 'login-security-recaptcha' ); ?></button>
								<span id="stls-your-ip"><?php esc_html_e( 'Your IP Address:', 'login-security-recaptcha' ); ?> <strong></strong> (<span id="stls-ip-header-info"></span>)</span>
							</p>
							<p class="description">
								<?php esc_html_e( 'With the above selection, make sure the expected server header is set without using fallback and you see your correct IP address.', 'login-security-recaptcha' ); ?>
							</p>
						</label>
					</fieldset>
				</td>
			</tr>

		</tbody>
	</table>

	<button type="submit" class="button button-primary" id="stlsr-save-options-btn"><?php esc_html_e( 'Save Changes', 'login-security-recaptcha' ); ?></button>

</form>
