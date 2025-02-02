<?php

class WPSC_Settings_Tab_Taxes
{
	public function display() {
		$wpec_taxes_controller = new wpec_taxes_controller;
		$wpec_taxes_options = $wpec_taxes_controller->wpec_taxes->wpec_taxes_get_options();

	?>
				<h3><?php _e( 'Tax Settings', 'wpsc' ); ?></h3>
				<p>
					<label for='wpec_taxes_enabled'>
						<input <?php if ( $wpec_taxes_options['wpec_taxes_enabled'] ) {
			echo 'checked="checked"';
		} ?> type="checkbox" id='wpec_taxes_enabled' name='wpsc_options[wpec_taxes_enabled]' />
	<?php _e( 'Turn tax on', 'wpsc' ); ?>
				</label>
			</p>
			<p>
				<label for='wpec_taxes_inprice1'>
					<input <?php if ( $wpec_taxes_options['wpec_taxes_inprice'] == 'exclusive' ) {
			echo 'checked="checked"';
		} ?> type="radio" value='exclusive' id='wpec_taxes_inprice1' name='wpsc_options[wpec_taxes_inprice]' />
	<?php _e( 'Product prices are tax exclusive - add tax to the price during checkout', 'wpsc' ); ?>
				</label>
			</p>
			<p>
				<label for='wpec_taxes_inprice2'>
					<input <?php if ( $wpec_taxes_options['wpec_taxes_inprice'] == 'inclusive' ) {
			echo 'checked="checked"';
		} ?> type="radio" value='inclusive' id='wpec_taxes_inprice2' name='wpsc_options[wpec_taxes_inprice]' />
	<?php _e( "Product prices are tax inclusive - during checkout the total price doesn't increase but tax is shown as a line item", 'wpsc' ); ?>
				</label>
			</p>
			<h4><?php _e( 'Product Specific Tax', 'wpsc' ); ?></h4>
			<p>
				<label for='wpec_taxes_product_1'>
					<input <?php if ( $wpec_taxes_options['wpec_taxes_product'] == 'add' ) {
			echo 'checked="checked"';
		} ?> type="radio" value='add' id='wpec_taxes_product_1' name='wpsc_options[wpec_taxes_product]' />
	<?php _e( 'Add per product tax to tax percentage if product has a specific tax rate', 'wpsc' ); ?>
				</label>
			</p>
			<p>
				<label for='wpec_taxes_product_2'>
					<input <?php if ( $wpec_taxes_options['wpec_taxes_product'] == 'replace' ) {
			echo 'checked="checked"';
		} ?> type="radio" value='replace' id='wpec_taxes_product_2' name='wpsc_options[wpec_taxes_product]' />
	<?php _e( 'Replace tax percentage with product specific tax rate', 'wpsc' ); ?>
				</label>
			</p>

			<h4><?php _e( 'Tax Logic', 'wpsc' ); ?></h4>
			<p>
				<label for='wpec_taxes_logic_1'>
					<input <?php if ( $wpec_taxes_options['wpec_taxes_logic'] == 'billing_shipping' ) {
							echo 'checked="checked"';
						} ?> type="radio" value='billing_shipping' id='wpec_taxes_logic_1' name='wpsc_options[wpec_taxes_logic]' />
						<?php _e( 'Apply tax when Billing and Shipping Country is the same as Tax Rate', 'wpsc' ); ?>
				</label>
			<div id='billing_shipping_preference_container' style='margin-left: 20px;'>
	            <p>
					<label for='wpec_billing_preference'>
						<input <?php if ( $wpec_taxes_options['wpec_taxes_logic'] == 'billing_shipping' && $wpec_taxes_options['wpec_billing_shipping_preference'] == 'billing_address' ) {
							echo 'checked="checked"';
						} ?> type="radio" value='billing_address' id='wpec_billing_preference' name='wpsc_options[wpec_billing_shipping_preference]' />
					<?php _e( 'Apply tax to Billing Address', 'wpsc' ); ?>
						</label>
		            </p>
		            <p>
						<label for='wpec_shipping_preference'>
							<input <?php if ( $wpec_taxes_options['wpec_taxes_logic'] == 'billing_shipping' && $wpec_taxes_options['wpec_billing_shipping_preference'] == 'shipping_address' ) {
							echo 'checked="checked"';
						} ?> type="radio" value='shipping_address' id='wpec_shipping_preference' name='wpsc_options[wpec_billing_shipping_preference]' />
	<?php _e( 'Apply tax to Shipping Address', 'wpsc' ); ?>
						</label>
		            </p>
				</div>
				</p>
				<p>
					<label for='wpec_taxes_logic_2'>
						<input <?php if ( $wpec_taxes_options['wpec_taxes_logic'] == 'billing' ) {
							echo 'checked="checked"';
						} ?> type="radio" value='billing' id='wpec_taxes_logic_2' name='wpsc_options[wpec_taxes_logic]' />
						<?php _e( 'Apply tax when Billing Country is the same as Tax Rate', 'wpsc' ); ?>
				</label>
			</p>
			<p>
				<label for='wpec_taxes_logic_3'>
					<input <?php if ( $wpec_taxes_options['wpec_taxes_logic'] == 'shipping' ) {
							echo 'checked="checked"';
						} ?> type="radio" value='shipping' id='wpec_taxes_logic_3' name='wpsc_options[wpec_taxes_logic]' />
						<?php _e( 'Apply tax when Shipping Country is the same as Tax Rate', 'wpsc' ); ?>
				</label>
			</p>
			<div id='metabox-holder' class="metabox-holder">
				<div id='wpec-taxes-rates-container' class='postbox'>
					<h3 class='hndle' style='cursor: default'><?php _e( 'Tax Rates', 'wpsc' ); ?></h3>
					<div id='wpec-taxes-rates' class='inside'>
						<!--Start Taxes Output-->
	<?php
						/**
						 * Add New Tax Rate - should add another paragraph with the
						  another key specified for the input array
						 * Delete - Should remove the given paragraph from the page
						  and either ajax delete it from the DB or mark it for
						  deletion and process it after the changes are made.
						 * Selecting a Country - should automatically populate the
						  regions select box. Selecting a different country should
						  remove the region select box. If the user selects a
						  different country with regions it shouldn't matter because
						  the code should automatically add the region select in.
						 *  - Allow users to define tax for entire country even if regions exist.
						 * Shipping Tax - needs to be per region or per tax rate.
						  Remove the setting from the main Tax Settings area.
						 * Constraints -
						  1. Should not allow a user to add more than one
						  tax rate for the same area.
						  2. If a country tax rate is specified and then a region tax
						  rate, the region tax rate takes precedence.
						 * */

	                /**
	                 * Removed Shipping Restriction on Included tax - 01-20-2011
						//if tax is included warn about shipping
						if ( $wpec_taxes_controller->wpec_taxes_isincluded() ) {
							echo '<p>' . __( 'Note: Tax is not applied to shipping when product prices are tax inclusive.' ) . '</p>';
						}// if
	               **/

						//get current tax rates
						$tax_rates = $wpec_taxes_controller->wpec_taxes->wpec_taxes_get_rates();
						$tax_rate_count = 0;
						if ( !empty( $tax_rates ) ) {
							foreach ( $tax_rates as $tax_rate ) {
								echo $wpec_taxes_controller->wpec_taxes_build_form( $tax_rate_count, $tax_rate );
								$tax_rate_count++;
							}// foreach
						}// if
	?>
						<!--End Taxes Output-->
						<p>
							<a id="add_taxes_rate" href="#"><?php _e( 'Add New Tax Rate', 'wpsc' ); ?></a>
						</p>
					</div>
				</div>
				<div id='wpec-taxes-bands-container' class='postbox'>
					<h3 class='hndle' style='cursor: default'><?php _e( 'Tax Bands', 'wpsc' ); ?></h3>
					<div id='wpec-taxes-bands' class='inside'>

	<?php
						echo '<p>' . __( 'Note: Tax Bands are special tax rules you can create and apply on a per-product basis. <br /> Please visit the product page to apply your Tax Band.', 'wpsc' ) . '</p>';

						//echo message regarding inclusive tax
						if ( !$wpec_taxes_controller->wpec_taxes_isincluded() ) {
							echo '<p>' . __( 'Note: Tax Bands do not take affect when product prices are tax exclusive.', 'wpsc' ) . '</p>';
						}// if

						$tax_bands = $wpec_taxes_controller->wpec_taxes->wpec_taxes_get_bands();
						$tax_band_count = 0;
						if ( !empty( $tax_bands ) ) {
							foreach ( $tax_bands as $tax_band ) {
								echo $wpec_taxes_controller->wpec_taxes_build_form( $tax_band_count, $tax_band, 'bands' );
								$tax_band_count++;
							}// foreach
						}// if
	?>
						<p>
							<a id="add_taxes_band" href="#"><?php _e( 'Add New Tax Band', 'wpsc' ); ?></a>
									</p>
								</div>
							</div><!--wpec-taxes-bands-container-->
						</div><!--metabox-holder-->
						<?php do_action('wpsc_taxes_settings_page'); ?>
						<div class="submit">
							<input type='hidden' name='wpec_admin_action' value='submit_taxes_options' />
							<?php wp_nonce_field( 'update-options', 'wpsc-update-options' ); ?>
							<input type="submit" class='button-primary' value="Save Changes" name="submit_taxes" />
						</div>
		<?php
	}
}