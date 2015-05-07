<?php

/**
* Paypal Direct Payment API Component class file.
*/

App :: import('Vendor', 'NtkFdf', array('file' => 'ntk.fdf.php'));
//App::uses('Ntkfdf', 'Vendor');
class NtkfdfComponent extends Component {

	var $fdf;

	function init(){
		$this->fdf = new NtkFdf();
	}  //fn

	function set_value( $var='', $val =''){

		$this->fdf->ntk_fdf_set_value( $var, $val );
	}

	function set_file( $fname=''){

		return $this->fdf->ntk_fdf_set_file(  $fname );
	}

	function get_fdf(){
		return $this->fdf->ntk_get_fdf();
	}
}
?>