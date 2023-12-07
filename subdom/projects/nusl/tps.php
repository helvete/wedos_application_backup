<?php
include (__DIR__. "/xml/XSLT2Processor.php");

$odkdy = time() - (60*60*24*7);

run($odkdy);


function run($odkdy){

  if(file_exists("resumptiontoken.txt")){
    $rtoken = file_get_contents("resumptiontoken.txt");
  }
  if (strlen($rtoken)>0){
    //pridat druhy atribut casu... prazdny
    $data = getdata($rtoken);
  }
  else {
    //zakomentovat a odkomentovat radek pod tim
    $data = getdata("");
    //$data = getdata("", $odkdy);
  }

  if(!accept($data)){
    echo "token nebyl zapsan do souboru!";    
  }
  $file = time() . ".xml";
  if(!write(xsltprocess($data), $file)){
    echo "neprobehl zapis dat do souboru!";
  }
  else {
    echo "vsechno probehlo v poradku";
  }

}

//pridat funkci druhy parametr a pridat podminku na jeho prazdnost .. plus do query pridat from parametr
function getdata($token){
  if(strlen($token)>0){                                                                                     
    $base = file_get_contents("http://invenio.nusl.cz/oai2d?verb=ListRecords&resumptionToken=". $token);    
  }
  else {                                                                                                    
    $base = file_get_contents("http://invenio.nusl.cz/oai2d?verb=ListRecords&metadataPrefix=oai_dc");                           
  }

  if(strlen($base)>0){
   return $base;  
  }
  else {
   echo "nepovedlo se ziskat data!";
   exit;
  }

}


function accept($data){
  
  $xml = new DOMDocument;  
  $xml->loadXML($data);
  
  
  $tokens = $xml->getElementsByTagName('resumptionToken');
  
  foreach ($tokens as $token) {
    $tokenvalue = $token->nodeValue;
  }
  if(strlen($tokenvalue)>0){
    file_put_contents("resumptiontoken.txt", $tokenvalue);
    return true;
  }
  else {
    if(file_exists("resumptiontoken.txt")){
      unlink("resumptiontoken.txt");
    }
    return false;
  }
}


function xsltprocess($xmlnodes){

  $xml = new DOMDocument;
  $xml->loadXML($xmlnodes);
  
  $xsl = new DOMDocument;
  $xsl->load("sablona-xsl2.xsl");

  //$proc = new XSLTProcessor;
  $proc = new XML_XSLT2Processor();
  $proc->importStyleSheet($xsl);
  
  $result = $proc->transformToXML($xml);

  if(strlen($result)>0){
    return $result;
  }
  else {
    echo "neprobehla transformace!";
    exit;
  }
}

function write($rdfxml, $file){
    //opravdu append? neni lepsi kazdy do noveho souboru? co ohranicujici tagy?
   if (file_put_contents($file, $rdfxml)){
    return true;
   }
   else{
    return false;
   }   
}

?>
