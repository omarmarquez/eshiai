<?php
define('ntk_FDFValue', 0);
define('ntk_FDFStatus', 1);
define('ntk_FDFFile', 2);
define('ntk_FDFID', 3);
define('ntk_FDFFf', 5);
define('ntk_FDFSetFf', 6);
define('ntk_FDFClearFf', 7);
define('ntk_FDFFlags', 8);
define('ntk_FDFSetF', 9);
define('ntk_FDFClrF', 10);
define('ntk_FDFAP', 11);
define('ntk_FDFAS', 12);
define('ntk_FDFAction', 13);
define('ntk_FDFAA', 14);
define('ntk_FDFAPRef', 15);
define('ntk_FDFIF', 16);
define('ntk_FDFEnter', 0);
define('ntk_FDFExit', 1);
define('ntk_FDFDown', 2);
define('ntk_FDFUp', 3);
define('ntk_FDFFormat', 4);
define('ntk_FDFValidate', 5);
define('ntk_FDFKeystroke', 6);
define('ntk_FDFCalculate', 7);
define('ntk_FDFNormalAP', 1);
define('ntk_FDFRolloverAP', 2);
define('ntk_FDFDownAP', 3);


class NtkFdf{
	
  public $fdf;

 public function __construct() {
    $this->fdf = $this->ntk_fdf_create();
  }

function ntk_fdf_create() {
    $fdf['header'] = "%FDF-1.2\n%????\n1 0 obj \n<< /FDF ";
    $fdf['trailer'] = ">>\nendobj\ntrailer\n<<\n/Root 1 0 R \n\n>>\n%%EOF";
	$fdf['values'] = array();
	
    return $fdf;
}

function ntk_fdf_header() {
    header('Content-type: application/vnd.fdf');
}

function ntk_fdf_close(&$fdf) {
    unset($fdf);
}

function ntk_fdf_set_file(  $pdfFile) {
    $this->fdf['file'] = $pdfFile;
}

function ntk_fdf_set_target_frame( $target) {
    $this->fdf['target'] = $target;
}

function ntk_fdf_set_value( $fieldName, $fieldValue) {
    $this->fdf['values'] = array_merge($this->fdf['values'], array($fieldName => $fieldValue));
}

function ntk_fdf_add_doc_javascript( $scriptName, $script) {
    $this->fdf['docscripts'] = array_merge($this->fdf['docscripts'], array($scriptName => $script));
}

function ntk_fdf_set_javascript_action( $fieldName, $trigger, $script) {
    $this->fdf['fieldscripts'] = array_merge($this->fdf['fieldscripts'], array($fieldName => array($script, $trigger)));
}

function ntk_get_fdf() {
    $search = array('\\', '(', ')');
    $replace = array('\\\\', '\(', '\)');

    $fdfStr = $this->fdf['header'];

    $fdfStr.= "<< ";

    if(isset($this->fdf['file'])) {
        $fdfStr.= "/F (".$this->fdf['file'].") ";
    }

    if(isset($this->fdf['target'])) {
        $fdfStr.= "/Target (".$this->fdf['target'].") ";
    }

    if(isset($this->fdf['docscripts'])) {
        $fdfStr.= "/JavaScript << /Doc [\n";

        // populate the doc level javascripts
        foreach($this->fdf['docscripts'] as $key => $value) {
            $fdfStr.= "(".str_replace($search, $replace, $key).")(".str_replace($search, $replace, $value).")";
        }
   
        $fdfStr.= "\n] >>\n";
    }

    if(isset($this->fdf['values']) || isset($this->fdf['fieldscripts'])) {
        // field level
        $fdfStr.= "/Fields [\n";

        if(isset($this->fdf['fieldscripts'])) {
            // populate the field level javascripts
            foreach($this->fdf['fieldscripts'] as $key => $val) {
                $fdfStr .= "<< /A << /S /JavaScript /JS (".str_replace($search, $replace, $val[0]).") >> /T (".str_replace($search, $replace, $key).") >>\n";
            }
        }

        if(isset($this->fdf['values'])) {
            // populate the fields
            foreach($this->fdf['values'] as $key => $value) {
                $fdfStr .= "<< /V (".str_replace($search, $replace, $value).") /T (".str_replace($search, $replace, $key).") >>\n";
            }
        }

        $fdfStr.= "]\n";
    }

    $fdfStr.= ">>";

    $fdfStr.= $this->fdf['trailer'];
    
    return $fdfStr;
	} // function
	 
	function ntk_fdf_save( $fdfFile = null )
	{
    if($fdfFile) {
        $handle = fopen($fdfFile, 'w');
        fwrite($handle, $this->ntk_get_fdf() );
        fclose($handle);
    } 
	} //function
	
} // class

?>
