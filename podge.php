<?php
# Podger Options
$MIN_PODGE_LEN = 5;
$DB = "test";
$COLLECTION = "podge";

$mongo = new MongoClient();
$podgeCollection = $mongo->selectDB( $DB )->selectCollection( $COLLECTION );

function qualifyPodge( $podge ) {
   $lengthOk = ( strlen( $podge ) > $MIN_PODGE_LEN );
   $containsLetter = preg_match( "/[a-z]/i", $podge );
   return $lengthOk && $containsLetter;
}

if( isset( $_GET[ "str" ] ) ) {
   $ingressPodge = $_GET[ "str" ];
   if( qualifyPodge( $ingressPodge ) ) {
      $podgeCollection->insert( array( "string" => $ingressPodge,
                                       "date" => date( "Y-m-d H:i:s" ),
                                       "rating" => 0 ) );
   }
}

$agg = $podgeCollection->aggregate( array( '$sample' => array( 'size' => 1 ) ) );
if( $agg[ "ok" ] ) {
   $egressPodge = $agg[ "result" ][ 0 ];
   print( json_encode( $egressPodge ) );
}
?>
