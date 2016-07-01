<?php
/**
 * File: SafetyPayCheck.php
 * Author: SafetyPay Inc.
 * Description: Checking Requirements
 * @version 1.0
 * @package default
 * @license Open Software License (OSL 3.0)
 * Copyright 2012-2016 SafetyPay Inc. All rights reserved.
*******************************************************************************/

require_once("class/SafetyPayProxy.php");

$proxy = new SafetyPayProxy();

function testHash($it = 10) {
    global $proxy;

    $it = intval($it);
    if ($it === 0)
        $it = 10;
    set_time_limit(-1);
    $test = array(
        $proxy->conf['RequestDateTime'] . $proxy->conf['SignatureKey'] => '',
        'This is exactly 64 bytes long, not counting the terminating byte' => 
        'ab64eff7e88e2e46165e29f2bce41826bd4c7b3552f6b382a9e7d3af47c245f8'
    );
    
    foreach($test as $str => $hash)
    {
        echo 'Message: ' . var_export($str, true) . '<br />';
        echo 'Start time: ' . date('Y-m-d H:i:s') . '<br />';
        if ( $it > 1 )
        {
            list($s1,$s2) = explode(' ', microtime());
            $o = sha256($str);
            list($e1, $e2) = explode(' ', microtime());
            echo 'Estimated time to perform test: ' 
                    . (($e2 - $s2 + $e1 - $s1) * $it) 
                    . ' seconds for ' . $it . ' iterations.<br />';
        }
        
        $t = 0;
        for( $x = 0; $x < $it; $x++ )
        {
            list($s1,$s2) = explode(' ', microtime());
            $o = sha256($str);
            list($e1,$e2) = explode(' ', microtime());
            $t += $e2 - $s2 + $e1 - $s1;
        }
        
        if ( $hash != '' )
        {
            echo var_export($o, true) . ' == ' . var_export($hash, true) . ' ' 
                . (strcasecmp($o, $hash)==0 ? 
                    '<span class="passed">PASSED</span>' 
                    : '<span class="failed">FAILED</span>') 
                . '<br />';
        }
        else
            echo 'Result: ' . var_export($o,true) . '<br />';
        
        echo '<br />Processing took ' . ($t / $it) . ' seconds.<br /><br />';
    }
}

$version = phpversion();
?>

<html>
<head>
<title>SafetyPay SDK PHP - Checking Requirements</title>
<style type="text/css">
    *           {   font-family: Verdana, Arial, Helvetica, sans-serif; font-size:11px; }
    BODY        {   font-family: Verdana, Arial, Helvetica, sans-serif; font-size:11px; }
    FORM        {   margin:0px; padding:0px; }
    TD          {   padding-bottom:1px; padding-top:1px; }
    A           {   color:#0000FF; }
    A:hover     {   color:#0000FF; }
    A:active    {   color:#0000FF; }
    A:visited   {   color:#0000FF; }
    .error      {   color:#FF0000; }
    .passed	{   color:#0000FF; }
    .failed     {   color:#FF0000; }
    .container  {   float:left; width:580px; vertical-align:top;
                    border:#036 1px solid; margin:0px;                   }
    .subcontain {   float:left; vertical-align:middle;
                    border:#036 1px solid; margin:0px; padding: 5px;     }
    .subtitle   {   background-color:#036; color:#FFFFFF; text-align:left;
                    font-weight:bold; padding-bottom:4px; padding-top:4px; }
</style>
</head>
<body>
<div class="container">
    <table width="100%" border="0" cellpadding="3" cellspacing="3">
    <tr><td colspan="4" nowrap>
            <div style="text-align:right;">
                <div style="float:left;text-align:left;width:50%;">
                    <img src="images/safetypay_logo.png" border="0" />
                </div>
                <br /><?php echo STP_SDK_NAME . ' ' . STP_SDK_VERSION; ?><br />
                        <?php echo STP_SERVICE_NAME . ' ' . STP_SERVICE_VERSION; ?>
            </div>
        </td>
    </tr>
    <tr>
        <td colspan="4">&nbsp;</td>
    </tr>
    <tr>
        <td class="subtitle" colspan="4">Requirements and Server Configuration</td>
    </tr>
    <tr>
        <td width="2%">&nbsp;</td>
        <td colspan="3">


                <table width="100%" border="0" cellpadding="3" cellspacing="3">
                <tr><td><strong>Requeriments:</strong></td>
                    <td><strong>HTTP Server:</strong> Apache 2+ / IIS<br />
                        <strong>PHP:</strong> 4, 5<br />
                        <strong>Register Globals:</strong> Off</td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr><td><strong>Server Current Configuration</strong></td>
                    <td><strong>HTTP Server:</strong> <?php
                        echo ($version <= '4.1.0' ? 
                                    $HTTP_SERVER_VARS['SERVER_SOFTWARE']
                                    :$_SERVER['SERVER_SOFTWARE']) 
                                . ' / ' . @php_uname('m') . ' / ' 
                                . @php_uname();
                        ?><br />
                        <strong>PHP:</strong> <?php
                        echo ($version >= '4.3' ? 
                                    '<span class="passed">PASSED - '
                                    :'<span class="failed">FAILED - ') 
                                . PHP_VERSION . '</span>';
                        echo (function_exists('zend_version') ? 
                                    ' (Zend: ' . @zend_version() . ')'
                                    : '');	?><br />
                        <strong>MySQL:</strong> <?php
                        ob_start();
                        phpinfo(INFO_MODULES);
                        preg_match( '#<td(?:.*?)>Client API version </td><td(?:.*?)>(.*) </td>#', 
                                    ob_get_contents(), 
                                    $matches );
                        ob_end_clean();
                        echo isset($matches[1]) ? $matches[1] : 'Not detected';
                        ?><br />
                        <strong>cURL version:</strong> <?php
                        if (function_exists(curl_version))
                        {
                            $cversion = @@curl_version();
                            if (is_array($cversion)) {
                                echo $cversion['version'] . '; ssl version: ' 
                                        . $cversion["ssl_version"] 
                                        . '; zlib version: ' 
                                        . $cversion['libz_version'] 
                                        . '; supported protocols: ';
                                foreach ($cversion["protocols"] as $protocol)
                                    print $protocol . ' ';
                            }
                            else
                                echo '<span class="passed">' . $cversion 
                                        . '</span>';
                            ?><br /><?php
                        }
                        else
                            echo '<span class="failed">Inactive Support. ' 
                                . 'Update your PHP.INI Configuration File. '
                                . 'Active Extension cURL.</span><br />';
                        ?>
                        <strong>Register Globals:</strong> <?php
                            echo ((int)ini_get('register_globals')? 'On':'Off');
                            ?><br /></td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr><td><strong>Proxy Current Configuration</strong></td>
                    <td><?php
                        foreach($proxy->conf as $key=>$value)
                        {
                            echo '<span>';
                            if ($key == 'HeaderWS')
                                echo "<strong>$key: </strong> " 
                                            . htmlentities($value);
                            else
                                echo "<strong>$key: </strong> " 
                                            . $value;

                            echo '</span><br />';
                        }   ?><br />
                    </td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr><td><strong>Testing:</strong></td>
                    <td><strong>Hash Test:</strong><br /><?php
                        testHash();
                    ?><br /></td>
                </tr>
                </table>
        </td>
    </tr>
    <tr>
        <td colspan="4"></td>
    </tr>
    </table>
</div>
</body>
</html>