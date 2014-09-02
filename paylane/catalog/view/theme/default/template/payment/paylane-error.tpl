<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>


    <p style="text-align:center;">
    <div style="border-radius: 5px; margin:auto; 5px;margin-top:40px;width:550px;color:#bb3333;font-size:14px;font-weight:bold;border:1px solid #bb3333;">
        <div style="padding:10px;">
            Order <?php echo $order_id; ?>: Payment failure - <?php echo $error_text; ?> (<?php echo $error_code; ?>)
        </div>
    </div>
    </p>
<p style="text-align: right"><a href="<?php echo $back_link; ?>">Return back to checkout</a></p>
<?php echo $content_bottom; ?></div>
<?php echo $footer; ?>