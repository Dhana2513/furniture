<?php

 
// Miscellaneous validation functions
// Author:  Ray Gumme, April 2009

//  You might care to add others!

function isDigit($aChar)
 {
   if ( ord($aChar) >= ord('0') && ord($aChar) <= ord('9') )
     return true;
   else
     return false;
 }

 function isDigits($aString)
{
  // Returns true if string consists entirely of decimal digits, false otherwise
  $i=0;
  $length = strlen($aString);
  if ($length == 0) return false;
  while ( $i < $length && isDigit($aString[$i]) ) $i++;
  return $i == $length;
}

function isEmpty($aString)
 {
   if  ( trim($aString) == "" )
     return true;
   else
     return false;
 }

 function isInteger($aString)
 {
   $str = trim($aString);
   if ( $str == "" ) return false;

   $firstChar = $str[0];
   if ( $firstChar == '+' || $firstChar == '-' ) $str = substr($str, 1);

   return isDigits($str);
 }

 function isNumeric($aString)
 {
   return is_numeric($aString);
 }

 function asCurrency($pence)
 {
   $pounds = $pence / 100;
   return sprintf("%1.2f", $pounds);
 }
 
  function isEMail($aString)
  {
    $regex = "/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i";
    return preg_match($regex, $aString);
  }

function isCardNo($aString)
{

  // Returns true if aString is a valid credit/debit card no
  // Works ok for Visa, Mastercard, Amex, Maestro, etc
  if ( ! isDigits($aString) ) return false;
  
  $checksum = 0;
  $last = strlen($aString) - 1;

  for ( $i=$last-1; $i >= 0; $i -= 2 )
  {
    $value = (ord($aString[$i]) - ord('0')) * 2;
    if ( $value > 9 ) $value -= 9;
    $checksum += $value;
  }
  
  for ( $i=$last; $i >= 0; $i -= 2 )
  {    
    $checksum += ( ord($aString[$i]) - ord('0') );
  }
  return ($checksum % 10) == 0;
}
