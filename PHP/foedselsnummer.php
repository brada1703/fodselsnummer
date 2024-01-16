<?php
// Downloaded from https://repo.progsbase.com - Code Developed Using progsbase.


function unichr($unicode){
    return mb_convert_encoding("&#{$unicode};", 'UTF-8', 'HTML-ENTITIES');
}
function uniord($s) {
    return unpack('V', iconv('UTF-8', 'UCS-4LE', $s))[1];
}

function IsValidNorwegianPersonalIdentificationNumber(&$fnummer, $message){

  if(count($fnummer) == 11.0){
    $gyldig = true;
    for($i = 0.0; $i < count($fnummer); $i = $i + 1.0){
      if(charIsNumber($fnummer[$i])){
      }else{
        $gyldig = false;
      }
    }

    if($gyldig){
      $d1 = charCharacterToDecimalDigit($fnummer[0.0]);
      $d2 = charCharacterToDecimalDigit($fnummer[1.0]);
      $d3 = charCharacterToDecimalDigit($fnummer[2.0]);
      $d4 = charCharacterToDecimalDigit($fnummer[3.0]);
      $d5 = charCharacterToDecimalDigit($fnummer[4.0]);
      $d6 = charCharacterToDecimalDigit($fnummer[5.0]);
      $d7 = charCharacterToDecimalDigit($fnummer[6.0]);
      $d8 = charCharacterToDecimalDigit($fnummer[7.0]);
      $d9 = charCharacterToDecimalDigit($fnummer[8.0]);
      $d10 = charCharacterToDecimalDigit($fnummer[9.0]);
      $d11 = charCharacterToDecimalDigit($fnummer[10.0]);

      $dateRef = new stdClass();
      $gyldig = GetDateFromNorwegianPersonalIdentificationNumber($fnummer, $dateRef, $message);

      if($gyldig){
        if(IsValidDate($dateRef->date, $message)){
          $k1 = $d1*3.0 + $d2*7.0 + $d3*6.0 + $d4*1.0 + $d5*8.0 + $d6*9.0 + $d7*4.0 + $d8*5.0 + $d9*2.0;
          $k1 = $k1%11.0;
          if($k1 != 0.0){
            $k1 = 11.0 - $k1;
          }
          if($k1 == 10.0){
            $gyldig = false;
            $message->string = str_split("Control digit 1 is 10, which is invalid.");
          }

          if($gyldig){
            $k2 = $d1*5.0 + $d2*4.0 + $d3*3.0 + $d4*2.0 + $d5*7.0 + $d6*6.0 + $d7*5.0 + $d8*4.0 + $d9*3.0 + $k1*2.0;
            $k2 = $k2%11.0;
            if($k2 != 0.0){
              $k2 = 11.0 - $k2;
            }
            if($k2 == 10.0){
              $gyldig = false;
              $message->string = str_split("Control digit 2 is 10, which is invalid.");
            }

            if($gyldig){
              if($k1 == $d10){
                if($k2 == $d11){
                  $gyldig = true;
                }else{
                  $gyldig = false;
                  $message->string = str_split("Check of control digit 2 failed.");
                }
              }else{
                $gyldig = false;
                $message->string = str_split("Check of control digit 1 failed.");
              }
            }
          }
        }else{
          $gyldig = false;
          $message->string = str_split("The date is not a valid date.");
        }
      }
    }else{
      $gyldig = false;
      $message->string = str_split("Each character must be a decimal digit.");
    }
  }else{
    $gyldig = false;
    $message->string = str_split("Must be exactly 11 digits long.");
  }

  return $gyldig;
}
function GetDateFromNorwegianPersonalIdentificationNumber(&$fnummer, $dateRef, $message){

  $success = true;
  $dateRef->date = new stdClass();

  if(count($fnummer) == 11.0){
    for($i = 0.0; $i < count($fnummer); $i = $i + 1.0){
      if(charIsNumber($fnummer[$i])){
      }else{
        $success = false;
      }
    }

    if($success){
      $d1 = charCharacterToDecimalDigit($fnummer[0.0]);
      $d2 = charCharacterToDecimalDigit($fnummer[1.0]);
      $d3 = charCharacterToDecimalDigit($fnummer[2.0]);
      $d4 = charCharacterToDecimalDigit($fnummer[3.0]);
      $d5 = charCharacterToDecimalDigit($fnummer[4.0]);
      $d6 = charCharacterToDecimalDigit($fnummer[5.0]);
      $d7 = charCharacterToDecimalDigit($fnummer[6.0]);
      $d8 = charCharacterToDecimalDigit($fnummer[7.0]);
      $d9 = charCharacterToDecimalDigit($fnummer[8.0]);

      /* Individnummer */
      $individnummer = $d7*100.0 + $d8*10.0 + $d9;

      /* Make date */
      $day = $d1*10.0 + $d2;
      $month = $d3*10.0 + $d4;
      $year = $d5*10.0 + $d6;

      if($individnummer >= 0.0 && $individnummer <= 499.0){
        $year = $year + 1900.0;
      }else if($individnummer >= 500.0 && $individnummer <= 749.0 && $year >= 54.0 && $year <= 99.0){
        $year = $year + 1800.0;
      }else if($individnummer >= 900.0 && $individnummer <= 999.0 && $year >= 40.0 && $year <= 99.0){
        $year = $year + 1900.0;
      }else if($individnummer >= 500.0 && $individnummer <= 999.0 && $year >= 0.0 && $year <= 39.0){
        $year = $year + 2000.0;
      }else{
        $success = false;
        $message->string = str_split("Invalid combination of individnummer and year.");
      }

      if($success){
        $dateRef->date->year = $year;
        $dateRef->date->month = $month;
        $dateRef->date->day = $day;
      }
    }else{
      $message->string = str_split("Each character must be a decimal digit.");
    }
  }else{
    $message->string = str_split("Must be exactly 11 digits long.");
  }

  return $success;
}
function Test1($failures){

  $message = new stdClass();

  $success = IsValidNorwegianPersonalIdentificationNumber($literal = str_split("10061270707"), $message);
  AssertTrue($success, $failures);

  $dateRef = new stdClass();

  $success = GetDateFromNorwegianPersonalIdentificationNumber($literal = str_split("11028559912"), $dateRef, $message);
  AssertTrue($success, $failures);
  AssertEquals($dateRef->date->year, 1885.0, $failures);

  $success = GetDateFromNorwegianPersonalIdentificationNumber($literal = str_split("01019949768"), $dateRef, $message);
  AssertTrue($success, $failures);
  AssertEquals($dateRef->date->year, 1999.0, $failures);

  $success = GetDateFromNorwegianPersonalIdentificationNumber($literal = str_split("01010099931"), $dateRef, $message);
  AssertTrue($success, $failures);
  AssertEquals($dateRef->date->year, 2000.0, $failures);
}
function test(){

  $failures = CreateNumberReference(0.0);

  Test1($failures);

  return $failures->numberValue;
}
function CreateBooleanReference($value){

  $ref = new stdClass();
  $ref->booleanValue = $value;

  return $ref;
}
function CreateBooleanArrayReference(&$value){

  $ref = new stdClass();
  $ref->booleanArray = $value;

  return $ref;
}
function CreateBooleanArrayReferenceLengthValue($length, $value){

  $ref = new stdClass();
  $ref->booleanArray = array_fill(0, $length, 0);

  for($i = 0.0; $i < $length; $i = $i + 1.0){
    $ref->booleanArray[$i] = $value;
  }

  return $ref;
}
function FreeBooleanArrayReference($booleanArrayReference){
  unset($booleanArrayReference->booleanArray);
  unset($booleanArrayReference);
}
function CreateCharacterReference($value){

  $ref = new stdClass();
  $ref->characterValue = $value;

  return $ref;
}
function CreateNumberReference($value){

  $ref = new stdClass();
  $ref->numberValue = $value;

  return $ref;
}
function CreateNumberArrayReference(&$value){

  $ref = new stdClass();
  $ref->numberArray = $value;

  return $ref;
}
function CreateNumberArrayReferenceLengthValue($length, $value){

  $ref = new stdClass();
  $ref->numberArray = array_fill(0, $length, 0);

  for($i = 0.0; $i < $length; $i = $i + 1.0){
    $ref->numberArray[$i] = $value;
  }

  return $ref;
}
function FreeNumberArrayReference($numberArrayReference){
  unset($numberArrayReference->numberArray);
  unset($numberArrayReference);
}
function CreateStringReference(&$value){

  $ref = new stdClass();
  $ref->string = $value;

  return $ref;
}
function CreateStringReferenceLengthValue($length, $value){

  $ref = new stdClass();
  $ref->string = array_fill(0, $length, 0);

  for($i = 0.0; $i < $length; $i = $i + 1.0){
    $ref->string[$i] = $value;
  }

  return $ref;
}
function FreeStringReference($stringReference){
  unset($stringReference->string);
  unset($stringReference);
}
function CreateStringArrayReference(&$strings){

  $ref = new stdClass();
  $ref->stringArray = $strings;

  return $ref;
}
function CreateStringArrayReferenceLengthValue($length, &$value){

  $ref = new stdClass();
  $ref->stringArray = array_fill(0, $length, 0);

  for($i = 0.0; $i < $length; $i = $i + 1.0){
    $ref->stringArray[$i] = CreateStringReference($value);
  }

  return $ref;
}
function FreeStringArrayReference($stringArrayReference){

  for($i = 0.0; $i < count($stringArrayReference->stringArray); $i = $i + 1.0){
    unset($stringArrayReference->stringArray[$i]);
  }
  unset($stringArrayReference->stringArray);
  unset($stringArrayReference);
}
function CreateDate($year, $month, $day){

  $date = new stdClass();

  $date->year = $year;
  $date->month = $month;
  $date->day = $day;

  return $date;
}
function IsLeapYearWithCheck($year, $isLeapYearReference, $message){

  if($year >= 1752.0){
    $success = true;
    $itIsLeapYear = IsLeapYear($year);
  }else{
    $success = false;
    $itIsLeapYear = false;
    $message->string = str_split("Gregorian calendar was not in general use.");
  }

  $isLeapYearReference->booleanValue = $itIsLeapYear;
  return $success;
}
function IsLeapYear($year){

  if(DivisibleBy($year, 4.0)){
    if(DivisibleBy($year, 100.0)){
      if(DivisibleBy($year, 400.0)){
        $itIsLeapYear = true;
      }else{
        $itIsLeapYear = false;
      }
    }else{
      $itIsLeapYear = true;
    }
  }else{
    $itIsLeapYear = false;
  }

  return $itIsLeapYear;
}
function DayToDateWithCheck($dayNr, $dateReference, $message){

  if($dayNr >= -79623.0){
    $date = new stdClass();
    $remainder = new stdClass();
    $remainder->numberValue = $dayNr + 79623.0;
    /* Days since 1752-01-01. Day 0: Thursday, 1970-01-01 */
    /* Find year. */
    $date->year = GetYearFromDayNr($remainder->numberValue, $remainder);

    /* Find month. */
    $date->month = GetMonthFromDayNr($remainder->numberValue, $date->year, $remainder);

    /* Find day. */
    $date->day = 1.0 + $remainder->numberValue;

    $dateReference->date = $date;
    $success = true;
  }else{
    $success = false;
    $message->string = str_split("Gregorian calendar was not in general use before 1752.");
  }

  return $success;
}
function DayToDate($dayNr){

  $dateRef = new stdClass();
  $message = new stdClass();

  $success = DayToDateWithCheck($dayNr, $dateRef, $message);
  if($success){
    $date = $dateRef->date;
    unset($dateRef);
    FreeStringReference($message);
  }else{
    $date = CreateDate(1970.0, 1.0, 1.0);
  }

  return $date;
}
function GetMonthFromDayNrWithCheck($dayNr, $year, $monthReference, $remainderReference, $message){

  if($dayNr >= -79623.0){
    $month = GetMonthFromDayNr($dayNr, $year, $remainderReference);
    $monthReference->numberValue = $month;
    $success = true;
  }else{
    $success = false;
    $message->string = str_split("Gregorian calendar not in general use before 1752.");
  }

  return $success;
}
function GetMonthFromDayNr($dayNr, $year, $remainderReference){

  $daysInMonth = GetDaysInMonth($year);
  $done = false;
  $month = 1.0;

  for(;  !$done ; ){
    if($dayNr >= $daysInMonth[$month]){
      $dayNr = $dayNr - $daysInMonth[$month];
      $month = $month + 1.0;
    }else{
      $done = true;
    }
  }
  $remainderReference->numberValue = $dayNr;

  return $month;
}
function GetYearFromDayNrWithCheck($dayNr, $yearReference, $remainder, $message){

  if($dayNr >= 0.0){
    $success = true;
    $year = GetYearFromDayNr($dayNr, $remainder);
    $yearReference->numberValue = $year;
  }else{
    $success = false;
    $message->string = str_split("Day number must be 0 or higher. 0 is 1752-01-01.");
  }

  return $success;
}
function GetYearFromDayNr($dayNr, $remainder){

  $done = false;
  $year = 1752.0;

  for(;  !$done ; ){
    if(IsLeapYear($year)){
      $nrOfDays = 366.0;
    }else{
      $nrOfDays = 365.0;
    }

    if($dayNr >= $nrOfDays){
      /* First day is 0. */
      $dayNr = $dayNr - $nrOfDays;
      $year = $year + 1.0;
    }else{
      $done = true;
    }
  }
  $remainder->numberValue = $dayNr;

  return $year;
}
function DaysBetweenDates($A, $B){

  $daysA = DateToDays($A);
  $daysB = DateToDays($B);

  $daysBetween = $daysB - $daysA;

  return $daysBetween;
}
function GetDaysInMonthWithCheck($year, $daysInMonthReference, $message){

  $date = CreateDate($year, 1.0, 1.0);

  $success = IsValidDate($date, $message);
  if($success){
    $daysInMonth = GetDaysInMonth($year);

    $daysInMonthReference->numberArray = $daysInMonth;
  }

  return $success;
}
function &GetDaysInMonth($year){

  $daysInMonth = array_fill(0, 1.0 + 12.0, 0);

  $daysInMonth[0.0] = 0.0;
  $daysInMonth[1.0] = 31.0;

  if(IsLeapYear($year)){
    $daysInMonth[2.0] = 29.0;
  }else{
    $daysInMonth[2.0] = 28.0;
  }
  $daysInMonth[3.0] = 31.0;
  $daysInMonth[4.0] = 30.0;
  $daysInMonth[5.0] = 31.0;
  $daysInMonth[6.0] = 30.0;
  $daysInMonth[7.0] = 31.0;
  $daysInMonth[8.0] = 31.0;
  $daysInMonth[9.0] = 30.0;
  $daysInMonth[10.0] = 31.0;
  $daysInMonth[11.0] = 30.0;
  $daysInMonth[12.0] = 31.0;

  return $daysInMonth;
}
function DateToDaysWithCheck($date, $dayNumberReferenceReference, $message){

  $success = IsValidDate($date, $message);
  if($success){
    $days = DateToDays($date);
    $dayNumberReferenceReference->numberValue = $days;
  }

  return $success;
}
function DateToDays($date){

  /* Day 1752-01-01 */
  $days = -79623.0;

  $days = $days + DaysInYears($date->year);
  $days = $days + DaysInMonths($date->month, $date->year);
  $days = $days + $date->day - 1.0;

  return $days;
}
function DateToWeekdayNumberWithCheck($date, $weekDayNumberReference, $message){

  $success = IsValidDate($date, $message);
  if($success){
    $weekDay = DateToWeekdayNumber($date);
    $weekDayNumberReference->numberValue = $weekDay;
  }

  return $success;
}
function DateToWeekdayNumber($date){

  $days = DateToDays($date);

  $days = $days + 79623.0;
  $days = $days + 5.0;

  $weekDay = $days%7.0 + 1.0;

  return $weekDay;
}
function DateToWeeknumber($date, $yearRef){

  $week1Start = CopyDate($date);

  $week1Start->day = 1.0;
  $week1Start->month = 1.0;
  $weekday = DateToWeekdayNumber($week1Start);

  /* Set week1Start to the start of the Week 1. */
  /* If monday, week 1 begins on Jan. 1st */
  if($weekday == 1.0){
    $week1Start->day = 1.0;
  }
  /* If tuesday, week 1 begins on Dec. 31st */
  if($weekday == 2.0){
    $week1Start->year = $week1Start->year - 1.0;
    $week1Start->month = 12.0;
    $week1Start->day = 31.0;
  }
  /* If wednesday, week 1 begins on Dec. 30th */
  if($weekday == 3.0){
    $week1Start->year = $week1Start->year - 1.0;
    $week1Start->month = 12.0;
    $week1Start->day = 30.0;
  }
  /* If thursday, week 1 begins on Dec. 29th */
  if($weekday == 4.0){
    $week1Start->year = $week1Start->year - 1.0;
    $week1Start->month = 12.0;
    $week1Start->day = 29.0;
  }
  /* If friday, week 1 begins on Jan. 4th */
  if($weekday == 5.0){
    $week1Start->day = 4.0;
  }
  /* If saturday, week 1 begins on Jan. 3rd */
  if($weekday == 6.0){
    $week1Start->day = 3.0;
  }
  /* If sunday, week 1 begins on Jan. 2nd */
  if($weekday == 7.0){
    $week1Start->day = 2.0;
  }

  $days = DateToDays($date);
  $daysWeek1Start = DateToDays($week1Start);

  if($days >= $daysWeek1Start){
    $weekNumber = 1.0 + floor(($days - $daysWeek1Start)/7.0);

    if($weekNumber >= 1.0 && $weekNumber <= 52.0){
      /* Week is between 1 and 52 in the current year. */
      $yearRef->numberValue = $date->year;
    }else{
      /* Is week nr 53 or 1 next year? */
      $newyears = CopyDate($date);
      $newyears->month = 12.0;
      $newyears->day = 31.0;
      $weekdayNewYears = DateToWeekdayNumber($newyears);
      if($weekdayNewYears == 1.0 || $weekdayNewYears == 2.0 || $weekdayNewYears == 3.0){
        /* Week 1 next year. */
        $weekNumber = 1.0;
        $yearRef->numberValue = $date->year + 1.0;
      }else{
        /* Week 53 */
        $yearRef->numberValue = $date->year;
      }
      unset($newyears);
    }
  }else{
    /* Week is in previous year. Either 52nd or 53rd. */
    $newyears = CopyDate($date);
    $newyears->month = 12.0;
    $newyears->day = 31.0;
    $newyears->year = $date->year - 1.0;
    $weekNumber = DateToWeeknumber($newyears, $yearRef);
    unset($newyears);
  }

  unset($week1Start);

  return $weekNumber;
}
function DaysInMonthsWithCheck($month, $year, $daysInMonthsReference, $message){

  $date = CreateDate($year, $month, 1.0);

  $success = IsValidDate($date, $message);
  if($success){
    $days = DaysInMonths($month, $year);

    $daysInMonthsReference->numberValue = $days;
  }

  return $success;
}
function DaysInMonths($month, $year){

  $daysInMonth = GetDaysInMonth($year);

  $days = 0.0;
  for($i = 1.0; $i < $month; $i = $i + 1.0){
    $days = $days + $daysInMonth[$i];
  }

  return $days;
}
function DaysInYearsWithCheck($years, $daysReference, $message){

  $date = CreateDate($years, 1.0, 1.0);

  $success = IsValidDate($date, $message);
  if($success){
    $days = DaysInYears($years);
    $daysReference->numberValue = $days;
  }

  return $success;
}
function DaysInYears($years){

  $days = 0.0;
  for($i = 1752.0; $i < $years; $i = $i + 1.0){
    if(IsLeapYear($i)){
      $nrOfDays = 366.0;
    }else{
      $nrOfDays = 365.0;
    }
    $days = $days + $nrOfDays;
  }

  return $days;
}
function IsValidDate($date, $message){

  if($date->year >= 1752.0){
    if($date->month >= 1.0 && $date->month <= 12.0){
      $daysInMonth = GetDaysInMonth($date->year);
      $daysInThisMonth = $daysInMonth[$date->month];
      if($date->day >= 1.0 && $date->day <= $daysInThisMonth){
        $valid = true;
      }else{
        $valid = false;
        $message->string = str_split("The month does not have the given day number.");
      }
    }else{
      $valid = false;
      $message->string = str_split("Month must be between 1 and 12, inclusive.");
    }
  }else{
    $valid = false;
    $message->string = str_split("Gregorian calendar was not in general use before 1752.");
  }

  return $valid;
}
function AddDaysToDate($date, $days, $message){

  $daysRef = new stdClass();
  $success = DateToDaysWithCheck($date, $daysRef, $message);

  if($success){
    $n = $daysRef->numberValue;
    $n = $n + $days;

    $dateReference = new stdClass();
    $success = DayToDateWithCheck($n, $dateReference, $message);
    if($success){
      AssignDate($date, $dateReference->date);
    }
  }

  return $success;
}
function AssignDate($a, $b){
  $a->year = $b->year;
  $a->month = $b->month;
  $a->day = $b->day;
}
function AddMonthsToDate($date, $months){

  if($months > 0.0){
    for($i = 0.0; $i < $months; $i = $i + 1.0){
      $date->month = $date->month + 1.0;

      if($date->month == 13.0){
        $date->month = 1.0;
        $date->year = $date->year + 1.0;
      }
    }
  }
  if($months < 0.0){
    for($i = 0.0; $i < -$months; $i = $i + 1.0){
      $date->month = $date->month - 1.0;

      if($date->month == 0.0){
        $date->month = 12.0;
        $date->year = $date->year - 1.0;
      }
    }
  }
}
function DateToStringISO8601WithCheck($date, $datestr, $message){

  $success = IsValidDate($date, $message);

  if($success){
    if($date->year <= 9999.0){
      $datestr->string = DateToStringISO8601($date);
    }else{
      $message->string = str_split("This library works from 1752 to 9999.");
    }
  }

  return $success;
}
function &DateToStringISO8601($date){

  $str = array_fill(0, 10.0, 0);

  $str[0.0] = charDecimalDigitToCharacter(floor($date->year/1000.0));
  $str[1.0] = charDecimalDigitToCharacter(floor(($date->year%1000.0)/100.0));
  $str[2.0] = charDecimalDigitToCharacter(floor(($date->year%100.0)/10.0));
  $str[3.0] = charDecimalDigitToCharacter(floor($date->year%10.0));

  $str[4.0] = "-";

  $str[5.0] = charDecimalDigitToCharacter(floor(($date->month%100.0)/10.0));
  $str[6.0] = charDecimalDigitToCharacter(floor($date->month%10.0));

  $str[7.0] = "-";

  $str[8.0] = charDecimalDigitToCharacter(floor(($date->day%100.0)/10.0));
  $str[9.0] = charDecimalDigitToCharacter(floor($date->day%10.0));

  return $str;
}
function DateFromStringISO8601(&$str){

  $date = new stdClass();

  $n = charCharacterToDecimalDigit($str[0.0])*1000.0;
  $n = $n + charCharacterToDecimalDigit($str[1.0])*100.0;
  $n = $n + charCharacterToDecimalDigit($str[2.0])*10.0;
  $n = $n + charCharacterToDecimalDigit($str[3.0])*1.0;

  $date->year = $n;

  $n = charCharacterToDecimalDigit($str[5.0])*10.0;
  $n = $n + charCharacterToDecimalDigit($str[6.0])*1.0;

  $date->month = $n;

  $n = charCharacterToDecimalDigit($str[8.0])*10.0;
  $n = $n + charCharacterToDecimalDigit($str[9.0])*1.0;

  $date->day = $n;

  return $date;
}
function DateFromStringISO8601WithCheck(&$str, $dateRef, $message){

  $valid = IsValidDateISO8601($str, $message);

  if($valid){
    $dateRef->date = DateFromStringISO8601($str);
  }

  return $valid;
}
function IsValidDateISO8601(&$str, $message){

  if(count($str) == 4.0 + 1.0 + 2.0 + 1.0 + 2.0){

    if(charIsNumber($str[0.0]) && charIsNumber($str[1.0]) && charIsNumber($str[2.0]) && charIsNumber($str[3.0]) && charIsNumber($str[5.0]) && charIsNumber($str[6.0]) && charIsNumber($str[8.0]) && charIsNumber($str[9.0])){
      if($str[4.0] == "-" && $str[7.0] == "-"){
        $valid = true;
      }else{
        $valid = false;
        $message->string = str_split("ISO8601 date must use \'-\' in positions 5 and 8.");
      }
    }else{
      $valid = false;
      $message->string = str_split("ISO8601 date must use decimal digits in positions 1, 2, 3, 4, 6, 7, 9 and 10.");
    }
  }else{
    $valid = false;
    $message->string = str_split("ISO8601 date must be exactly 10 characters long.");
  }

  return $valid;
}
function DateEquals($a, $b){
  return $a->year == $b->year && $a->month == $b->month && $a->day == $b->day;
}
function CopyDate($a){

  $b = CreateDate($a->year, $a->month, $a->day);

  return $b;
}
function GetSecondsFromDate($date){

  $seconds = 0.0;
  $dayNumberReferenceReference = new stdClass();
  $message = new stdClass();

  $success = DateToDaysWithCheck($date, $dayNumberReferenceReference, $message);
  if($success){
    $days = $dayNumberReferenceReference->numberValue;

    $secondsInMinute = 60.0;
    $secondsInHour = 60.0*$secondsInMinute;
    $secondsInDay = 24.0*$secondsInHour;

    $seconds = $seconds + $secondsInDay*$days;
  }

  unset($dayNumberReferenceReference);
  unset($message);

  return $seconds;
}
function DateIsInInterval($interval, $date){

  $from = DateToDays($interval->first);
  $to = DateToDays($interval->last);
  $day = DateToDays($date);

  return $day >= $from && $day <= $to;
}
function DateLessThan($a, $b){

  $aDays = DateToDays($a);
  $bDays = DateToDays($b);

  return $aDays < $bDays;
}
function CreateDateTimeTimezone($year, $month, $day, $hours, $minutes, $seconds, $timezoneOffsetSeconds){

  $dateTimeTimezone = new stdClass();

  $dateTimeTimezone->dateTime = CreateDateTime($year, $month, $day, $hours, $minutes, $seconds);
  $dateTimeTimezone->timezoneOffsetSeconds = $timezoneOffsetSeconds;

  return $dateTimeTimezone;
}
function CreateDateTimeTimezoneInHoursAndMinutes($year, $month, $day, $hours, $minutes, $seconds, $timezoneOffsetHours, $timezoneOffsetMinutes){

  $dateTimeTimezone = new stdClass();

  $dateTimeTimezone->dateTime = CreateDateTime($year, $month, $day, $hours, $minutes, $seconds);
  $dateTimeTimezone->timezoneOffsetSeconds = GetSecondsFromHours($timezoneOffsetHours) + GetSecondsFromMinutes($timezoneOffsetMinutes);

  return $dateTimeTimezone;
}
function GetDateFromDateTimeTimeZone($dateTimeTimezone, $dateTimeReference, $message){

  $dateTime = $dateTimeTimezone->dateTime;

  return AddSecondsToDateTimeWithCheck($dateTime, -$dateTimeTimezone->timezoneOffsetSeconds, $dateTimeReference, $message);
}
function CreateDateTimeTimezoneFromDateTimeAndTimeZoneInSeconds($dateTime, $timezoneOffsetSeconds, $dateTimeTimezoneReference, $message){

  $adjustedDateTimeReference = new stdClass();
  $dateTimeTimezone = new stdClass();

  $success = AddSecondsToDateTime($dateTime, $timezoneOffsetSeconds, $adjustedDateTimeReference, $message);

  if($success){
    $dateTimeTimezone->dateTime = $adjustedDateTimeReference->dateTime;
    $dateTimeTimezone->timezoneOffsetSeconds = $timezoneOffsetSeconds;

    $dateTimeTimezoneReference->dateTimeTimezone = $dateTimeTimezone;
  }

  return $success;
}
function CreateDateTimeTimezoneFromDateTimeAndTimeZoneInHoursAndMinutes($dateTime, $timezoneOffsetHours, $timezoneOffsetMinutes, $dateTimeTimezoneReference, $message){
  return CreateDateTimeTimezoneFromDateTimeAndTimeZoneInSeconds($dateTime, GetSecondsFromHours($timezoneOffsetHours) + GetSecondsFromMinutes($timezoneOffsetMinutes), $dateTimeTimezoneReference, $message);
}
function GetDateTimeTimezoneFromSeconds($dateTimeTzRef, $seconds, $offset, $message){

  $dateTimeRef = new stdClass();
  $success = GetDateTimeFromSeconds($seconds, $dateTimeRef, $message);

  if($success){
    $success = CreateDateTimeTimezoneFromDateTimeAndTimeZoneInSeconds($dateTimeRef->dateTime, $offset, $dateTimeTzRef, $message);
  }

  return $success;
}
function CreateDateTime($year, $month, $day, $hours, $minutes, $seconds){

  $dateTime = new stdClass();

  $dateTime->date = CreateDate($year, $month, $day);
  $dateTime->hours = $hours;
  $dateTime->minutes = $minutes;
  $dateTime->seconds = $seconds;

  return $dateTime;
}
function GetDateTimeFromSeconds($seconds, $dateTimeReference, $message){

  $secondsInMinute = 60.0;
  $secondsInHour = 60.0*$secondsInMinute;
  $secondsInDay = 24.0*$secondsInHour;
  $days = floor($seconds/$secondsInDay);
  $remainder = $seconds - $days*$secondsInDay;
  $dateReference = new stdClass();

  $success = DayToDateWithCheck($days, $dateReference, $message);
  if($success){
    $date = $dateReference->date;

    $dateTime = new stdClass();
    $dateTime->date = $date;
    $dateTime->hours = floor($remainder/$secondsInHour);
    $remainder = $remainder - $dateTime->hours*$secondsInHour;
    $dateTime->minutes = floor($remainder/$secondsInMinute);
    $remainder = $remainder - $dateTime->minutes*$secondsInMinute;
    $dateTime->seconds = $remainder;

    $dateTimeReference->dateTime = $dateTime;
  }

  return $success;
}
function GetSecondsFromDateTime($dateTime){

  $secondsInMinute = 60.0;
  $secondsInHour = 60.0*$secondsInMinute;

  $seconds = GetSecondsFromDate($dateTime->date);
  $seconds = $seconds + $secondsInHour*$dateTime->hours;
  $seconds = $seconds + $secondsInMinute*$dateTime->minutes;
  $seconds = $seconds + $dateTime->seconds;

  return $seconds;
}
function GetSecondsFromMinutes($minutes){
  return $minutes*60.0;
}
function GetSecondsFromHours($hours){
  return GetSecondsFromMinutes($hours*60.0);
}
function GetSecondsFromDays($days){
  return GetSecondsFromHours($days*24.0);
}
function GetSecondsFromWeeks($weeks){
  return GetSecondsFromDays($weeks*7.0);
}
function GetMinutesFromSeconds($seconds){
  return $seconds/60.0;
}
function GetHoursFromSeconds($seconds){
  return GetMinutesFromSeconds($seconds)/60.0;
}
function GetDaysFromSeconds($seconds){
  return GetHoursFromSeconds($seconds)/24.0;
}
function GetWeeksFromSeconds($seconds){
  return GetDaysFromSeconds($seconds)/7.0;
}
function GetDateFromDateTime($dateTime){
  return $dateTime->date;
}
function AddSecondsToDateTimeWithCheck($dateTime, $seconds, $dateTimeReference, $message){

  if(IsValidDateTime($dateTime, $message)){
    $secondsInDateTime = GetSecondsFromDateTime($dateTime);
    $secondsInDateTime = $secondsInDateTime + $seconds;

    $success = GetDateTimeFromSeconds($secondsInDateTime, $dateTimeReference, $message);
  }else{
    $success = false;
  }

  return $success;
}
function AddSecondsToDateTime($dateTime, $seconds, $dateTimeReference, $message){

  $secondsInDateTime = GetSecondsFromDateTime($dateTime);
  $secondsInDateTime = $secondsInDateTime + $seconds;

  return GetDateTimeFromSeconds($secondsInDateTime, $dateTimeReference, $message);
}
function AddMinutesToDateTime($dateTime, $minutes, $dateTimeReference, $message){
  return AddSecondsToDateTime($dateTime, GetSecondsFromMinutes($minutes), $dateTimeReference, $message);
}
function AddHoursToDateTime($dateTime, $hours, $dateTimeReference, $message){
  return AddSecondsToDateTime($dateTime, GetSecondsFromHours($hours), $dateTimeReference, $message);
}
function AddDaysToDateTime($dateTime, $days, $dateTimeReference, $message){
  return AddSecondsToDateTime($dateTime, GetSecondsFromDays($days), $dateTimeReference, $message);
}
function AddWeeksToDateTime($dateTime, $weeks, $dateTimeReference, $message){
  return AddSecondsToDateTime($dateTime, GetSecondsFromWeeks($weeks), $dateTimeReference, $message);
}
function DateTimeToStringISO8601WithCheck($datetime, $dateStr, $message){

  $success = DateToStringISO8601WithCheck($datetime->date, $dateStr, $message);

  if($success){
    unset($dateStr->string);

    $success = IsValidDateTime($datetime, $message);
    if($success){
      $dateStr->string = DateTimeToStringISO8601($datetime);
    }
  }

  return $success;
}
function IsValidDateTime($datetime, $message){

  $success = IsValidDate($datetime->date, $message);

  if($success){
    if($datetime->hours <= 23.0 && $datetime->hours >= 0.0){
      if($datetime->minutes <= 59.0 && $datetime->minutes >= 0.0){
        if($datetime->seconds <= 59.0 && $datetime->seconds >= 0.0){
          $success = true;
        }else{
          $success = false;
          $message->string = str_split("Seconds must be between 0 and 59.");
        }
      }else{
        $success = false;
        $message->string = str_split("Minutes must be between 0 and 59.");
      }
    }else{
      $success = false;
      $message->string = str_split("Hours must be between 0 and 23.");
    }
  }

  return $success;
}
function &DateTimeToStringISO8601($datetime){

  $str = array_fill(0, 19.0, 0);

  $datestr = DateToStringISO8601($datetime->date);
  for($i = 0.0; $i < count($datestr); $i = $i + 1.0){
    $str[$i] = $datestr[$i];
  }

  $str[10.0] = "T";
  $str[11.0] = charDecimalDigitToCharacter(floor(($datetime->hours%100.0)/10.0));
  $str[12.0] = charDecimalDigitToCharacter(floor($datetime->hours%10.0));

  $str[13.0] = ":";

  $str[14.0] = charDecimalDigitToCharacter(floor(($datetime->minutes%100.0)/10.0));
  $str[15.0] = charDecimalDigitToCharacter(floor($datetime->minutes%10.0));

  $str[16.0] = ":";

  $str[17.0] = charDecimalDigitToCharacter(floor(($datetime->seconds%100.0)/10.0));
  $str[18.0] = charDecimalDigitToCharacter(floor($datetime->seconds%10.0));

  return $str;
}
function DateTimeFromStringISO8601(&$str){

  $dateTime = new stdClass();

  $dateTime->date = DateFromStringISO8601($str);

  $n = charCharacterToDecimalDigit($str[11.0])*10.0;
  $n = $n + charCharacterToDecimalDigit($str[12.0])*1.0;

  $dateTime->hours = $n;

  $n = charCharacterToDecimalDigit($str[14.0])*10.0;
  $n = $n + charCharacterToDecimalDigit($str[15.0])*1.0;

  $dateTime->minutes = $n;

  $n = charCharacterToDecimalDigit($str[17.0])*10.0;
  $n = $n + charCharacterToDecimalDigit($str[18.0])*1.0;

  $dateTime->seconds = $n;

  return $dateTime;
}
function DateTimeFromStringISO8601WithCheck(&$str, $dateTimeRef, $message){

  $valid = IsValidDateTimeISO8601($str, $message);

  if($valid){
    $dateTimeRef->dateTime = DateTimeFromStringISO8601($str);
  }

  return $valid;
}
function IsValidDateTimeISO8601(&$str, $message){

  if(count($str) == 4.0 + 1.0 + 2.0 + 1.0 + 2.0 + 1.0 + 2.0 + 1.0 + 2.0 + 1.0 + 2.0){

    if(charIsNumber($str[0.0]) && charIsNumber($str[1.0]) && charIsNumber($str[2.0]) && charIsNumber($str[3.0]) && charIsNumber($str[5.0]) && charIsNumber($str[6.0]) && charIsNumber($str[8.0]) && charIsNumber($str[9.0]) && charIsNumber($str[11.0]) && charIsNumber($str[12.0]) && charIsNumber($str[14.0]) && charIsNumber($str[15.0]) && charIsNumber($str[17.0]) && charIsNumber($str[18.0])){
      if($str[4.0] == "-" && $str[7.0] == "-" && $str[10.0] == "T" && $str[13.0] == ":" && $str[16.0] == ":"){
        $valid = true;
      }else{
        $valid = false;
        $message->string = str_split("ISO8601 date must use \'-\' in positions 5 and 8, \'T\' in position 11 and \':\' in positions 14 and 17.");
      }
    }else{
      $valid = false;
      $message->string = str_split("ISO8601 date must use decimal digits in positions 1, 2, 3, 4, 6, 7, 9, 10, 12, 13, 15, 16, 18 and 19.");
    }
  }else{
    $valid = false;
    $message->string = str_split("ISO8601 date must be exactly 19 characters long.");
  }

  return $valid;
}
function DateTimeEquals($a, $b){
  return DateEquals($a->date, $b->date) && $a->hours == $b->hours && $a->minutes == $b->minutes && $a->seconds == $b->seconds;
}
function FreeDateTime($datetime){
  unset($datetime->date);
  unset($datetime);
}
function charToLowerCase($character){

  $toReturn = $character;
  if($character == "A"){
    $toReturn = "a";
  }else if($character == "B"){
    $toReturn = "b";
  }else if($character == "C"){
    $toReturn = "c";
  }else if($character == "D"){
    $toReturn = "d";
  }else if($character == "E"){
    $toReturn = "e";
  }else if($character == "F"){
    $toReturn = "f";
  }else if($character == "G"){
    $toReturn = "g";
  }else if($character == "H"){
    $toReturn = "h";
  }else if($character == "I"){
    $toReturn = "i";
  }else if($character == "J"){
    $toReturn = "j";
  }else if($character == "K"){
    $toReturn = "k";
  }else if($character == "L"){
    $toReturn = "l";
  }else if($character == "M"){
    $toReturn = "m";
  }else if($character == "N"){
    $toReturn = "n";
  }else if($character == "O"){
    $toReturn = "o";
  }else if($character == "P"){
    $toReturn = "p";
  }else if($character == "Q"){
    $toReturn = "q";
  }else if($character == "R"){
    $toReturn = "r";
  }else if($character == "S"){
    $toReturn = "s";
  }else if($character == "T"){
    $toReturn = "t";
  }else if($character == "U"){
    $toReturn = "u";
  }else if($character == "V"){
    $toReturn = "v";
  }else if($character == "W"){
    $toReturn = "w";
  }else if($character == "X"){
    $toReturn = "x";
  }else if($character == "Y"){
    $toReturn = "y";
  }else if($character == "Z"){
    $toReturn = "z";
  }

  return $toReturn;
}
function charToUpperCase($character){

  $toReturn = $character;
  if($character == "a"){
    $toReturn = "A";
  }else if($character == "b"){
    $toReturn = "B";
  }else if($character == "c"){
    $toReturn = "C";
  }else if($character == "d"){
    $toReturn = "D";
  }else if($character == "e"){
    $toReturn = "E";
  }else if($character == "f"){
    $toReturn = "F";
  }else if($character == "g"){
    $toReturn = "G";
  }else if($character == "h"){
    $toReturn = "H";
  }else if($character == "i"){
    $toReturn = "I";
  }else if($character == "j"){
    $toReturn = "J";
  }else if($character == "k"){
    $toReturn = "K";
  }else if($character == "l"){
    $toReturn = "L";
  }else if($character == "m"){
    $toReturn = "M";
  }else if($character == "n"){
    $toReturn = "N";
  }else if($character == "o"){
    $toReturn = "O";
  }else if($character == "p"){
    $toReturn = "P";
  }else if($character == "q"){
    $toReturn = "Q";
  }else if($character == "r"){
    $toReturn = "R";
  }else if($character == "s"){
    $toReturn = "S";
  }else if($character == "t"){
    $toReturn = "T";
  }else if($character == "u"){
    $toReturn = "U";
  }else if($character == "v"){
    $toReturn = "V";
  }else if($character == "w"){
    $toReturn = "W";
  }else if($character == "x"){
    $toReturn = "X";
  }else if($character == "y"){
    $toReturn = "Y";
  }else if($character == "z"){
    $toReturn = "Z";
  }

  return $toReturn;
}
function charIsUpperCase($character){

  $isUpper = true;
  if($character == "A"){
  }else if($character == "B"){
  }else if($character == "C"){
  }else if($character == "D"){
  }else if($character == "E"){
  }else if($character == "F"){
  }else if($character == "G"){
  }else if($character == "H"){
  }else if($character == "I"){
  }else if($character == "J"){
  }else if($character == "K"){
  }else if($character == "L"){
  }else if($character == "M"){
  }else if($character == "N"){
  }else if($character == "O"){
  }else if($character == "P"){
  }else if($character == "Q"){
  }else if($character == "R"){
  }else if($character == "S"){
  }else if($character == "T"){
  }else if($character == "U"){
  }else if($character == "V"){
  }else if($character == "W"){
  }else if($character == "X"){
  }else if($character == "Y"){
  }else if($character == "Z"){
  }else{
    $isUpper = false;
  }

  return $isUpper;
}
function charIsLowerCase($character){

  $isLower = true;
  if($character == "a"){
  }else if($character == "b"){
  }else if($character == "c"){
  }else if($character == "d"){
  }else if($character == "e"){
  }else if($character == "f"){
  }else if($character == "g"){
  }else if($character == "h"){
  }else if($character == "i"){
  }else if($character == "j"){
  }else if($character == "k"){
  }else if($character == "l"){
  }else if($character == "m"){
  }else if($character == "n"){
  }else if($character == "o"){
  }else if($character == "p"){
  }else if($character == "q"){
  }else if($character == "r"){
  }else if($character == "s"){
  }else if($character == "t"){
  }else if($character == "u"){
  }else if($character == "v"){
  }else if($character == "w"){
  }else if($character == "x"){
  }else if($character == "y"){
  }else if($character == "z"){
  }else{
    $isLower = false;
  }

  return $isLower;
}
function charIsLetter($character){
  return charIsUpperCase($character) || charIsLowerCase($character);
}
function charIsNumber($character){

  $isNumberx = true;
  if($character == "0"){
  }else if($character == "1"){
  }else if($character == "2"){
  }else if($character == "3"){
  }else if($character == "4"){
  }else if($character == "5"){
  }else if($character == "6"){
  }else if($character == "7"){
  }else if($character == "8"){
  }else if($character == "9"){
  }else{
    $isNumberx = false;
  }

  return $isNumberx;
}
function charIsWhiteSpace($character){

  $isWhiteSpacex = true;
  if($character == " "){
  }else if($character == "\t"){
  }else if($character == "\n"){
  }else if($character == "\r"){
  }else{
    $isWhiteSpacex = false;
  }

  return $isWhiteSpacex;
}
function charIsSymbol($character){

  $isSymbolx = true;
  if($character == "!"){
  }else if($character == "\""){
  }else if($character == "#"){
  }else if($character == "$"){
  }else if($character == "%"){
  }else if($character == "&"){
  }else if($character == "\'"){
  }else if($character == "("){
  }else if($character == ")"){
  }else if($character == "*"){
  }else if($character == "+"){
  }else if($character == ","){
  }else if($character == "-"){
  }else if($character == "."){
  }else if($character == "/"){
  }else if($character == ":"){
  }else if($character == ";"){
  }else if($character == "<"){
  }else if($character == "="){
  }else if($character == ">"){
  }else if($character == "?"){
  }else if($character == "@"){
  }else if($character == "["){
  }else if($character == "\\"){
  }else if($character == "]"){
  }else if($character == "^"){
  }else if($character == "_"){
  }else if($character == "`"){
  }else if($character == "{"){
  }else if($character == "|"){
  }else if($character == "}"){
  }else if($character == "~"){
  }else{
    $isSymbolx = false;
  }

  return $isSymbolx;
}
function charCharacterIsBefore($a, $b){

  $ad = uniord($a);
  $bd = uniord($b);

  return $ad < $bd;
}
function charDecimalDigitToCharacter($digit){
  if($digit == 1.0){
    $c = "1";
  }else if($digit == 2.0){
    $c = "2";
  }else if($digit == 3.0){
    $c = "3";
  }else if($digit == 4.0){
    $c = "4";
  }else if($digit == 5.0){
    $c = "5";
  }else if($digit == 6.0){
    $c = "6";
  }else if($digit == 7.0){
    $c = "7";
  }else if($digit == 8.0){
    $c = "8";
  }else if($digit == 9.0){
    $c = "9";
  }else{
    $c = "0";
  }
  return $c;
}
function charCharacterToDecimalDigit($c){

  if($c == "1"){
    $digit = 1.0;
  }else if($c == "2"){
    $digit = 2.0;
  }else if($c == "3"){
    $digit = 3.0;
  }else if($c == "4"){
    $digit = 4.0;
  }else if($c == "5"){
    $digit = 5.0;
  }else if($c == "6"){
    $digit = 6.0;
  }else if($c == "7"){
    $digit = 7.0;
  }else if($c == "8"){
    $digit = 8.0;
  }else if($c == "9"){
    $digit = 9.0;
  }else{
    $digit = 0.0;
  }

  return $digit;
}
function AssertFalse($b, $failures){
  if($b){
    $failures->numberValue = $failures->numberValue + 1.0;
  }
}
function AssertTrue($b, $failures){
  if( !$b ){
    $failures->numberValue = $failures->numberValue + 1.0;
  }
}
function AssertEquals($a, $b, $failures){
  if($a != $b){
    $failures->numberValue = $failures->numberValue + 1.0;
  }
}
function AssertBooleansEqual($a, $b, $failures){
  if($a != $b){
    $failures->numberValue = $failures->numberValue + 1.0;
  }
}
function AssertCharactersEqual($a, $b, $failures){
  if($a != $b){
    $failures->numberValue = $failures->numberValue + 1.0;
  }
}
function AssertStringEquals(&$a, &$b, $failures){
  if( !StringsEqual($a, $b) ){
    $failures->numberValue = $failures->numberValue + 1.0;
  }
}
function AssertNumberArraysEqual(&$a, &$b, $failures){

  if(count($a) == count($b)){
    for($i = 0.0; $i < count($a); $i = $i + 1.0){
      AssertEquals($a[$i], $b[$i], $failures);
    }
  }else{
    $failures->numberValue = $failures->numberValue + 1.0;
  }
}
function AssertBooleanArraysEqual(&$a, &$b, $failures){

  if(count($a) == count($b)){
    for($i = 0.0; $i < count($a); $i = $i + 1.0){
      AssertBooleansEqual($a[$i], $b[$i], $failures);
    }
  }else{
    $failures->numberValue = $failures->numberValue + 1.0;
  }
}
function AssertStringArraysEqual(&$a, &$b, $failures){

  if(count($a) == count($b)){
    for($i = 0.0; $i < count($a); $i = $i + 1.0){
      AssertStringEquals($a[$i]->string, $b[$i]->string, $failures);
    }
  }else{
    $failures->numberValue = $failures->numberValue + 1.0;
  }
}
function Negate($x){
  return -$x;
}
function Positive($x){
  return +$x;
}
function Factorial($x){

  $f = 1.0;

  for($i = 2.0; $i <= $x; $i = $i + 1.0){
    $f = $f*$i;
  }

  return $f;
}
function Roundx($x){
  return floor($x + 0.5);
}
function BankersRound($x){

  if(Absolute($x - Truncate($x)) == 0.5){
    if( !DivisibleBy(Roundx($x), 2.0) ){
      $r = Roundx($x) - 1.0;
    }else{
      $r = Roundx($x);
    }
  }else{
    $r = Roundx($x);
  }

  return $r;
}
function Ceilx($x){
  return ceil($x);
}
function Floorx($x){
  return floor($x);
}
function Truncate($x){

  if($x >= 0.0){
    $t = floor($x);
  }else{
    $t = ceil($x);
  }

  return $t;
}
function Absolute($x){
  return abs($x);
}
function Logarithm($x){
  return log10($x);
}
function NaturalLogarithm($x){
  return log($x);
}
function Sinx($x){
  return sin($x);
}
function Cosx($x){
  return cos($x);
}
function Tanx($x){
  return tan($x);
}
function Asinx($x){
  return asin($x);
}
function Acosx($x){
  return acos($x);
}
function Atanx($x){
  return atan($x);
}
function Atan2x($y, $x){

  /* Atan2 is an invalid operation when x = 0 and y = 0, but this method does not return errors. */
  $a = 0.0;

  if($x > 0.0){
    $a = Atanx($y/$x);
  }else if($x < 0.0 && $y >= 0.0){
    $a = Atanx($y/$x) + M_PI;
  }else if($x < 0.0 && $y < 0.0){
    $a = Atanx($y/$x) - M_PI;
  }else if($x == 0.0 && $y > 0.0){
    $a = M_PI/2.0;
  }else if($x == 0.0 && $y < 0.0){
    $a = -M_PI/2.0;
  }

  return $a;
}
function Squareroot($x){
  return sqrt($x);
}
function Expx($x){
  return exp($x);
}
function DivisibleBy($a, $b){
  return (($a%$b) == 0.0);
}
function Combinations($n, $k){

  $c = 1.0;
  $j = 1.0;
  $i = $n - $k + 1.0;

  for(; $i <= $n; ){
    $c = $c*$i;
    $c = $c/$j;

    $i = $i + 1.0;
    $j = $j + 1.0;
  }

  return $c;
}
function Permutations($n, $k){

  $c = 1.0;

  for($i = $n - $k + 1.0; $i <= $n; $i = $i + 1.0){
    $c = $c*$i;
  }

  return $c;
}
function EpsilonCompare($a, $b, $epsilon){
  return abs($a - $b) < $epsilon;
}
function GreatestCommonDivisor($a, $b){

  for(; $b != 0.0; ){
    $t = $b;
    $b = $a%$b;
    $a = $t;
  }

  return $a;
}
function GCDWithSubtraction($a, $b){

  if($a == 0.0){
    $g = $b;
  }else{
    for(; $b != 0.0; ){
      if($a > $b){
        $a = $a - $b;
      }else{
        $b = $b - $a;
      }
    }

    $g = $a;
  }

  return $g;
}
function IsInteger($a){
  return ($a - floor($a)) == 0.0;
}
function GreatestCommonDivisorWithCheck($a, $b, $gcdReference){

  if(IsInteger($a) && IsInteger($b)){
    $gcd = GreatestCommonDivisor($a, $b);
    $gcdReference->numberValue = $gcd;
    $success = true;
  }else{
    $success = false;
  }

  return $success;
}
function LeastCommonMultiple($a, $b){

  if($a > 0.0 && $b > 0.0){
    $lcm = abs($a*$b)/GreatestCommonDivisor($a, $b);
  }else{
    $lcm = 0.0;
  }

  return $lcm;
}
function Sign($a){

  if($a > 0.0){
    $s = 1.0;
  }else if($a < 0.0){
    $s = -1.0;
  }else{
    $s = 0.0;
  }

  return $s;
}
function Maxx($a, $b){
  return max($a, $b);
}
function Minx($a, $b){
  return min($a, $b);
}
function Power($a, $b){
  return $a**$b;
}
function Gamma($x){
  return LanczosApproximation($x);
}
function LogGamma($x){
  return log(Gamma($x));
}
function LanczosApproximation($z){

  $p = array_fill(0, 8.0, 0);
  $p[0.0] = 676.5203681218851;
  $p[1.0] = -1259.1392167224028;
  $p[2.0] = 771.32342877765313;
  $p[3.0] = -176.61502916214059;
  $p[4.0] = 12.507343278686905;
  $p[5.0] = -0.13857109526572012;
  $p[6.0] = 9.9843695780195716e-6;
  $p[7.0] = 1.5056327351493116e-7;

  if($z < 0.5){
    $y = M_PI/(sin(M_PI*$z)*LanczosApproximation(1.0 - $z));
  }else{
    $z = $z - 1.0;
    $x = 0.99999999999980993;
    for($i = 0.0; $i < count($p); $i = $i + 1.0){
      $x = $x + $p[$i]/($z + $i + 1.0);
    }
    $t = $z + count($p) - 0.5;
    $y = sqrt(2.0*M_PI)*$t**($z + 0.5)*exp(-$t)*$x;
  }

  return $y;
}
function Beta($x, $y){
  return Gamma($x)*Gamma($y)/Gamma($x + $y);
}
function Sinhx($x){
  return (exp($x) - exp(-$x))/2.0;
}
function Coshx($x){
  return (exp($x) + exp(-$x))/2.0;
}
function Tanhx($x){
  return Sinhx($x)/Coshx($x);
}
function Cot($x){
  return 1.0/tan($x);
}
function Sec($x){
  return 1.0/cos($x);
}
function Csc($x){
  return 1.0/sin($x);
}
function Coth($x){
  return Coshx($x)/Sinhx($x);
}
function Sech($x){
  return 1.0/Coshx($x);
}
function Csch($x){
  return 1.0/Sinhx($x);
}
function Error($x){

  if($x == 0.0){
    $y = 0.0;
  }else if($x < 0.0){
    $y = -Error(-$x);
  }else{
    $c1 = -1.26551223;
    $c2 = +1.00002368;
    $c3 = +0.37409196;
    $c4 = +0.09678418;
    $c5 = -0.18628806;
    $c6 = +0.27886807;
    $c7 = -1.13520398;
    $c8 = +1.48851587;
    $c9 = -0.82215223;
    $c10 = +0.17087277;

    $t = 1.0/(1.0 + 0.5*abs($x));

    $tau = $t*exp(-$x**2.0 + $c1 + $t*($c2 + $t*($c3 + $t*($c4 + $t*($c5 + $t*($c6 + $t*($c7 + $t*($c8 + $t*($c9 + $t*$c10)))))))));

    $y = 1.0 - $tau;
  }

  return $y;
}
function ErrorInverse($x){

  $a = (8.0*(M_PI - 3.0))/(3.0*M_PI*(4.0 - M_PI));

  $t = 2.0/(M_PI*$a) + log(1.0 - $x**2.0)/2.0;
  $y = Sign($x)*sqrt(sqrt($t**2.0 - log(1.0 - $x**2.0)/$a) - $t);

  return $y;
}
function FallingFactorial($x, $n){

  $y = 1.0;

  for($k = 0.0; $k <= $n - 1.0; $k = $k + 1.0){
    $y = $y*($x - $k);
  }

  return $y;
}
function RisingFactorial($x, $n){

  $y = 1.0;

  for($k = 0.0; $k <= $n - 1.0; $k = $k + 1.0){
    $y = $y*($x + $k);
  }

  return $y;
}
function Hypergeometric($a, $b, $c, $z, $maxIterations, $precision){

  if(abs($z) >= 0.5){
    $y = (1.0 - $z)**(-$a)*HypergeometricDirect($a, $c - $b, $c, $z/($z - 1.0), $maxIterations, $precision);
  }else{
    $y = HypergeometricDirect($a, $b, $c, $z, $maxIterations, $precision);
  }

  return $y;
}
function HypergeometricDirect($a, $b, $c, $z, $maxIterations, $precision){

  $y = 0.0;
  $done = false;

  for($n = 0.0; $n < $maxIterations &&  !$done ; $n = $n + 1.0){
    $yp = RisingFactorial($a, $n)*RisingFactorial($b, $n)/RisingFactorial($c, $n)*$z**$n/Factorial($n);
    if(abs($yp) < $precision){
      $done = true;
    }
    $y = $y + $yp;
  }

  return $y;
}
function BernouilliNumber($n){
  return AkiyamaTanigawaAlgorithm($n);
}
function AkiyamaTanigawaAlgorithm($n){

  $A = array_fill(0, $n + 1.0, 0);

  for($m = 0.0; $m <= $n; $m = $m + 1.0){
    $A[$m] = 1.0/($m + 1.0);
    for($j = $m; $j >= 1.0; $j = $j - 1.0){
      $A[$j - 1.0] = $j*($A[$j - 1.0] - $A[$j]);
    }
  }

  $B = $A[0.0];

  unset($A);

  return $B;
}
function &StringToNumberArray(&$string){

  $array = array_fill(0, count($string), 0);

  for($i = 0.0; $i < count($string); $i = $i + 1.0){
    $array[$i] = uniord($string[$i]);
  }
  return $array;
}
function &NumberArrayToString(&$array){

  $string = array_fill(0, count($array), 0);

  for($i = 0.0; $i < count($array); $i = $i + 1.0){
    $string[$i] = unichr($array[$i]);
  }
  return $string;
}
function NumberArraysEqual(&$a, &$b){

  $equal = true;
  if(count($a) == count($b)){
    for($i = 0.0; $i < count($a) && $equal; $i = $i + 1.0){
      if($a[$i] != $b[$i]){
        $equal = false;
      }
    }
  }else{
    $equal = false;
  }

  return $equal;
}
function BooleanArraysEqual(&$a, &$b){

  $equal = true;
  if(count($a) == count($b)){
    for($i = 0.0; $i < count($a) && $equal; $i = $i + 1.0){
      if($a[$i] != $b[$i]){
        $equal = false;
      }
    }
  }else{
    $equal = false;
  }

  return $equal;
}
function StringsEqual(&$a, &$b){

  $equal = true;
  if(count($a) == count($b)){
    for($i = 0.0; $i < count($a) && $equal; $i = $i + 1.0){
      if($a[$i] != $b[$i]){
        $equal = false;
      }
    }
  }else{
    $equal = false;
  }

  return $equal;
}
function FillNumberArray(&$a, $value){

  for($i = 0.0; $i < count($a); $i = $i + 1.0){
    $a[$i] = $value;
  }
}
function FillString(&$a, $value){

  for($i = 0.0; $i < count($a); $i = $i + 1.0){
    $a[$i] = $value;
  }
}
function FillBooleanArray(&$a, $value){

  for($i = 0.0; $i < count($a); $i = $i + 1.0){
    $a[$i] = $value;
  }
}
function FillNumberArrayRange(&$a, $value, $from, $to){

  if($from >= 0.0 && $from <= count($a) && $to >= 0.0 && $to <= count($a) && $from <= $to){
    $length = $to - $from;
    for($i = 0.0; $i < $length; $i = $i + 1.0){
      $a[$from + $i] = $value;
    }

    $success = true;
  }else{
    $success = false;
  }

  return $success;
}
function FillBooleanArrayRange(&$a, $value, $from, $to){

  if($from >= 0.0 && $from <= count($a) && $to >= 0.0 && $to <= count($a) && $from <= $to){
    $length = $to - $from;
    for($i = 0.0; $i < $length; $i = $i + 1.0){
      $a[$from + $i] = $value;
    }

    $success = true;
  }else{
    $success = false;
  }

  return $success;
}
function FillStringRange(&$a, $value, $from, $to){

  if($from >= 0.0 && $from <= count($a) && $to >= 0.0 && $to <= count($a) && $from <= $to){
    $length = $to - $from;
    for($i = 0.0; $i < $length; $i = $i + 1.0){
      $a[$from + $i] = $value;
    }

    $success = true;
  }else{
    $success = false;
  }

  return $success;
}
function &CopyNumberArray(&$a){

  $n = array_fill(0, count($a), 0);

  for($i = 0.0; $i < count($a); $i = $i + 1.0){
    $n[$i] = $a[$i];
  }

  return $n;
}
function &CopyBooleanArray(&$a){

  $n = array_fill(0, count($a), 0);

  for($i = 0.0; $i < count($a); $i = $i + 1.0){
    $n[$i] = $a[$i];
  }

  return $n;
}
function &CopyString(&$a){

  $n = array_fill(0, count($a), 0);

  for($i = 0.0; $i < count($a); $i = $i + 1.0){
    $n[$i] = $a[$i];
  }

  return $n;
}
function CopyNumberArrayRange(&$a, $from, $to, $copyReference){

  if($from >= 0.0 && $from <= count($a) && $to >= 0.0 && $to <= count($a) && $from <= $to){
    $length = $to - $from;
    $n = array_fill(0, $length, 0);

    for($i = 0.0; $i < $length; $i = $i + 1.0){
      $n[$i] = $a[$from + $i];
    }

    $copyReference->numberArray = $n;
    $success = true;
  }else{
    $success = false;
  }

  return $success;
}
function CopyBooleanArrayRange(&$a, $from, $to, $copyReference){

  if($from >= 0.0 && $from <= count($a) && $to >= 0.0 && $to <= count($a) && $from <= $to){
    $length = $to - $from;
    $n = array_fill(0, $length, 0);

    for($i = 0.0; $i < $length; $i = $i + 1.0){
      $n[$i] = $a[$from + $i];
    }

    $copyReference->booleanArray = $n;
    $success = true;
  }else{
    $success = false;
  }

  return $success;
}
function CopyStringRange(&$a, $from, $to, $copyReference){

  if($from >= 0.0 && $from <= count($a) && $to >= 0.0 && $to <= count($a) && $from <= $to){
    $length = $to - $from;
    $n = array_fill(0, $length, 0);

    for($i = 0.0; $i < $length; $i = $i + 1.0){
      $n[$i] = $a[$from + $i];
    }

    $copyReference->string = $n;
    $success = true;
  }else{
    $success = false;
  }

  return $success;
}
function IsLastElement($length, $index){
  return $index + 1.0 == $length;
}
function &CreateNumberArray($length, $value){

  $array = array_fill(0, $length, 0);
  FillNumberArray($array, $value);

  return $array;
}
function &CreateBooleanArray($length, $value){

  $array = array_fill(0, $length, 0);
  FillBooleanArray($array, $value);

  return $array;
}
function &CreateString($length, $value){

  $array = array_fill(0, $length, 0);
  FillString($array, $value);

  return $array;
}
function SwapElementsOfNumberArray(&$A, $ai, $bi){

  $tmp = $A[$ai];
  $A[$ai] = $A[$bi];
  $A[$bi] = $tmp;
}
function SwapElementsOfStringArray($A, $ai, $bi){

  $tmp = $A->stringArray[$ai];
  $A->stringArray[$ai] = $A->stringArray[$bi];
  $A->stringArray[$bi] = $tmp;
}
function ReverseNumberArray(&$array){

  for($i = 0.0; $i < count($array)/2.0; $i = $i + 1.0){
    SwapElementsOfNumberArray($array, $i, count($array) - $i - 1.0);
  }
}

