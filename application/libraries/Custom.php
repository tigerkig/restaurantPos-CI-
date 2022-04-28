<?php 
class Custom{
	function encrypt_decrypt($key, $type){	
			# type = encrypt/decrypt
			if( !$key ){ return false; }
			if($type=='decrypt'){
				return $key;
				
			}elseif($type=='encrypt'){
				return $key;
			}
			return FALSE;	# if function is not used properly
		}
	}


?>