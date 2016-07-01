<?php
 /**
 * File: SafetyPayProxy.php
 * Author: SafetyPay Inc.
 * Description: Util funcionts
 * @version 2.0
 * @package class
 * @license Open Software License (OSL 3.0)
 * Copyright 2012-2016 SafetyPay Inc. All rights reserved.
*******************************************************************************/

function do_offset($level)
{
    $offset = ''; // offset for subarry
    for ($i=1; $i<$level ;$i++)
        $offset = $offset . '<td></td>';
    return $offset;
}

function show_array($array, $level, $sub)
{
    if (is_array($array) == 1)
    {   // check if input is an array
        foreach($array as $key_val => $value)
        {
            $offset = '';
            if (is_array($value) == 1)
            {   // array is multidimensional
                echo "\n<tr>";
                $offset = do_offset($level);
                echo $offset . '<td><strong>' . $key_val . '</strong></td>';
                show_array($value, $level+1, 1);
            }
            else
            {   // (sub)array is not multidim
                if ($sub != 1)
                {   // first entry for subarray
                    echo "\n<tr nosub>";
                    $offset = do_offset($level);
                }
                $sub = 0;
                echo $offset . '<td ' . $sub . ' width="120"><strong>' .
                        $key_val . '</strong></td>';
                if (strrpos($value, 'urn:safetypay:') === false)
                    echo '<td width="150">' . $value . '</td>';
                echo '</tr>' . "\n";
           }
       }
    }
    else    // argument $array is not an array
        return;
}

function html_show_array( $array )
{
    echo "<table cellspacing=\"0\" border=\"1\">\n";
    show_array($array, 1, 0);
    echo "</table>\n";
}
?>