// Miscellaneous validation functions
// Author:  Ray Gumme, April 2009

//  You might care to add others!

function trimLeft(aString)
{
  // Trim leading spaces from a string
  // Returns the trimmed string
  
  var length=aString.length;
  if (length == 0) return aString;

  var left=0;
  while ( left < length && aString.charAt(left) == ' ') left++;
  return aString.substring(left);
}

function trimRight(aString)
{
  // Trim trailing spaces from a string
  // Returns the trimmed string
  
  var length=aString.length;
  if (length == 0) return aString;

  var right= aString.length-1;
  while ( right >= 0 && aString.charAt(right) == ' ') right--;
  return aString.substring(0, right+1);
}

function trim(aString)
{
  // Trim leading and trailing spaces from a string
  // Returns the trimmed string
  
  var length=aString.length;
  if (length == 0) return aString;

  return trimLeft(trimRight(aString));
}

function isEmpty(aString)
{
  // Returns true if aString is null or consists entirely of spaces, false otherwise
  return trimLeft(aString) == "";
}

function isDigit(aChar)
{
  // Returns true if aChar is a decimal digit, false otherwise
  
  return aChar >= '0' && aChar <= '9';
}

function isDigits(aString)
{
  // Returns true if string consists entirely of decimal digits, false otherwise
  var i=0;
  var length = aString.length;
  if (length == 0) return false;
  while ( i<length && isDigit(aString.charAt(i)) ) i++;
  return i == length;
}

function isInteger(aString)
{
  // Returns true if aString is a decimal integer, false otherwise
  // Leading or trailing spaces are ignored

  var strTrimmed = trim(aString);
  if (strTrimmed.length == 0) return false;
  
  var firstChar = strTrimmed.charAt(0);
  if ( firstChar == '+' || firstChar == '-' ) strTrimmed = strTrimmed.substring(1);
  return isDigits(strTrimmed);
}

function isCardNo(aString)
{

  // Returns true if aString is a valid credit/debit card no
  // Works ok for Visa, Mastercard, Amex, Maestro, etc
  if ( ! isDigits(aString) ) return false;
  
  var checksum = 0;
  var last = aString.length - 1;

  for (i=last-1; i >= 0; i-=2)
  {
    value = Number(aString.charAt(i)) * 2;
    if ( value > 9 ) value -= 9;
    checksum += value;
  }
  
  for (i=last; i >= 0; i-=2)
  {    
    checksum += Number(aString.charAt(i));
  }
  return (checksum % 10) == 0;
}

function isNumber(aString)
{
  // Returns true if aString is a decimal number, false otherwise
  // See JavaScript documentation for isNaN
  
  var strTrimmed = trim(aString);
  if (strTrimmed.length == 0) return false;
  
  return ! isNaN(strTrimmed);
}


function toInteger(aString)
{
  // Converts aString to an integer
  // See JavaScript documentation for parseInt()
  
  return parseInt(aString);
}

function toFloat(aString)
{
  // Converts aString to a float
  // See JavaScript documentation for parseFloat()
  
  return parseFloat(aString);
}

function isEMail(aString)
{
  var regex = new RegExp("^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$", "i");
  return regex.test(aString);
  
}

