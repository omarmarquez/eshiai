<?php

/**
* Paypal Direct Payment API Component class file.
*/

App :: import('Vendor', 'Paypal');

class PaypalComponent extends Component {

	function processPayment($paymentInfo, $function, $options = array()) {
		$paypal = new Paypal( $options );
		if ($function == "DoDirectPayment")
			return $paypal->DoDirectPayment($paymentInfo );
		elseif ($function == "SetExpressCheckout") return $paypal->SetExpressCheckout($paymentInfo);
		elseif ($function == "GetExpressCheckoutDetails") return $paypal->GetExpressCheckoutDetails($paymentInfo);
		elseif ($function == "DoExpressCheckoutPayment") return $paypal->DoExpressCheckoutPayment($paymentInfo);
		else
			return "Function Does Not Exist!";
	}
}
?>