<?php
function encrypt($sData){
    $id=(double)$sData*18293823.45;
    return base64_encode($id);
}
function decrypt($sData){
    $url_id=base64_decode($sData);
    $id=(double)$url_id/18293823.45;
    return $id;
}
 
function my_simple_crypt( $string, $action = 'e' )
{
        // you may change these values to your own
        $secret_key = 'hmytechnologies@2017_yahya_mam';
        $secret_iv = 'hmytechnologies@2017_yahya_hamida';
        
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $key = hash( 'sha256', $secret_key );
        $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );
        
        if( $action == 'e' ) {
            $output = base64_encode(openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
        }
        else if( $action == 'd' ){
            $output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
        }
        
        return $output;
}
 
echo my_simple_crypt("IPA/004/2017",'e');
echo "<br><br>";
echo my_simple_crypt("WkZ0OEpKWU5rOGl1NndDY1RKM2N6Zz09",'d');
?>