<form class="pmagic-form pm-dbfl pg-mycred-address-form" method="post" action="" id="pg_billing_address_form" name="pg_billing_address_form" onsubmit="return pm_save_billing_address()">
<?php
$this->pg_edit_mycred_address('billing');
?>
<div class="buttonarea pm-full-width-container">
    <div class="pg_billing_errors"></div>
    <input type="submit" class="button" name="pg_saved_billing_address" value="<?php _e( 'Save Address', 'profilegrid-mycred-integration' ); ?>" />
</div>

</form>