<div style='width:100px;'>
 <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="business" value="2YYZM7KAYFFQJ">
<input type="hidden" name="lc" value="US">
<input type="hidden" name="item_name" value="<?php __( $event_info['Event']['event_name'] . ' ' . 'Registration')?>">
<input type="hidden" name="item_number" value="1001">
<input type="hidden" name="amount" value="<?php echo $price ;?>">
<input type="hidden" name="currency_code" value="USD">
<input type="hidden" name="button_subtype" value="services">
<input type="hidden" name="tax_rate" value="0.000">
<input type="hidden" name="shipping" value="0.00">
<input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynowCC_LG.gif:NonHosted">
<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypal.com/es_XC/i/scr/pixel.gif" width="1" height="1">
</form>
</div>