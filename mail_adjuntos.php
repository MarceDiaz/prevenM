<?php
function form_mail($sPara, $sAsunto, $sTexto, $sDe){
  $bHayFicheros = 0;
  $sCabeceraTexto = "";
  $sAdjuntos = "";

  if ($sDe)$sCabeceras = "From:".$sDe."\n";
  else $sCabeceras = "";
  $sCabeceras .= "MIME-version: 1.0\n";
  foreach ($_POST as $sNombre => $sValor)
  $sTexto = $sTexto."\n".$sNombre." = ".$sValor;

  foreach ($_FILES as $vAdjunto){
     if ($bHayFicheros == 0){
         $bHayFicheros = 1;
         $sCabeceras .= "Content-type: multipart/mixed;";
         $sCabeceras .= "boundary=\"--_Separador-de-mensajes_--\"\n";
         
         $sCabeceraTexto = "----_Separador-de-mensajes_--\n";
         $sCabeceraTexto .= "Content-type: text/plain;charset=iso-8859-1\n";
         $sCabeceraTexto .= "Content-transfer-encoding: 7BIT\n";

         $sTexto = $sCabeceraTexto.$sTexto;
     }
    if (($vAdjunto["size"] > 0) && ($vAdjunto["size"]<3000)){
         $sAdjuntos .= "\n\n----_Separador-de-mensajes_--\n";
         $sAdjuntos .= "Content-type: ".$vAdjunto["type"].";name=\"".$vAdjunto["name"]."\"\n";;
         $sAdjuntos .= "Content-Transfer-Encoding: BASE64\n";
         $sAdjuntos .= "Content-disposition: attachment;filename=\"".$vAdjunto["name"]."\"\n\n";

         $oFichero = fopen($vAdjunto["tmp_name"], 'r');
         $sContenido = fread($oFichero, filesize($vAdjunto["tmp_name"]));
         $sAdjuntos .= chunk_split(base64_encode($sContenido));
         fclose($oFichero);
    }
}

if ($bHayFicheros)
   $sTexto .= $sAdjuntos."\n\n----_Separador-de-mensajes_----\n";
   return(mail($sPara, $sAsunto, $sTexto, $sCabeceras));
}

if (form_mail("contacto@prevenmax.uy","Consulta a PrevenMax",
     "Los datos introducidos en el formulario son:\n\n", "contacto@prevenmax.uy"))
    echo "";
    echo "";
    echo "";
    echo '<h2 align="center" >Su formulario ha sido enviado con exito</h2>';
    echo "";
    echo '<h4 align="center" >Muchas Gracias.-</h4>';
    echo "";
    echo "";
    echo '<br><br><center><a href="http://www.prevenmax.uy">Volver</a></center>';
?>

