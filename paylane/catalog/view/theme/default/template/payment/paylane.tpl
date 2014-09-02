<form action="<?php echo $action ?>" method="post" id="paylane_form" class="hidden">
    <input type="hidden" name="merchant_id" value="<?php echo $merchant_id ?>" />
    <input type="hidden" name="merchant_transaction_id" value="<?php echo $merchant_transaction_id ?>" />
    <input type="hidden" name="amount" value="<?php echo $amount ?>" />
    <input type="hidden" name="currency_code" value="<?php echo $currency_code ?>" />
    <input type="hidden" name="transaction_type" value="S" />
    <input type="hidden" name="back_url" value="<?php echo $back_url ?>" />
    <input type="hidden" name="transaction_description" value="<?php echo $transaction_description ?>" />
    <input type="hidden" name="language" value="<?php echo $language ?>" />
<?php if ( !empty($hash)) : ?>
    <input type="hidden" name="hash" value="<?php echo $hash ?>" />
<?php endif; ?>
    <input type="hidden" name="customer_name" value="<?php echo $customer_name ?>" />
    <input type="hidden" name="customer_email" value="<?php echo $customer_email ?>" />
    <input type="hidden" name="customer_address" value="<?php echo $customer_address ?>" />
    <input type="hidden" name="customer_zip" value="<?php echo $customer_zip ?>" />
    <input type="hidden" name="customer_city" value="<?php echo $customer_city ?>" />
    <input type="hidden" name="customer_country" value="<?php echo $customer_country ?>" />
    <div class="buttons">
        <div class="right">
            <input type="submit" value="<?php echo $button_confirm; ?>" class="button" />
        </div>
    </div>
</form>
