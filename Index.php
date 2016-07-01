<?php
/**
 * File: SafetyPayTest.php
 * Author: SafetyPay Inc.
 * Description: Get Token URL to start the purchase process with SafetyPay.
 * @version 1.0
 * @package default
 * @license Open Software License (OSL 3.0)
 * Copyright 2012-2016 SafetyPay Inc. All rights reserved.
*******************************************************************************/
error_reporting(E_ALL & ~(E_WARNING|E_NOTICE));
require_once 'class/SafetyPayProxy.php';

$proxy = new SafetyPayProxy();

$ApiKey = (isset($_REQUEST['ApiKey'])?$_REQUEST['ApiKey']:$proxy->conf['ApiKey']);
$SignatureKey = (isset($_REQUEST['SignatureKey'])?$_REQUEST['SignatureKey']:$proxy->conf['SignatureKey']);
$Environment = (isset($_REQUEST['Environment'])?$_REQUEST['Environment']:$proxy->conf['Environment']);
$Protocol = (isset($_REQUEST['Protocol'])?$_REQUEST['Protocol']:$proxy->conf['Protocol']);
$RequestDateTime = (isset($_REQUEST['RequestDateTime'])?$_REQUEST['RequestDateTime']:$proxy->conf['RequestDateTime']);
$CurrencyCode = (isset($_REQUEST['CurrencyCode'])?$_REQUEST['CurrencyCode']:$proxy->conf['CurrencyCode']);
$Amount = round((float)(isset($_REQUEST['Amount'])?$_REQUEST['Amount']:$proxy->conf['Amount']), 2);
$MerchantSalesID = (isset($_REQUEST['MerchantSalesID'])?$_REQUEST['MerchantSalesID']:'ORDER_NO-12345');
$Language = (isset($_REQUEST['Language'])?$_REQUEST['Language']:$proxy->conf['Language']);
$TrackingCode = (isset($_REQUEST['TrackingCode'])?$_REQUEST['TrackingCode']:$proxy->conf['TrackingCode']);
$ExpirationTime = (isset($_REQUEST['ExpirationTime'])?$_REQUEST['ExpirationTime']:$proxy->conf['ExpirationTime']);
$FilterBy = (isset($_REQUEST['FilterBy'])?$_REQUEST['FilterBy']:$proxy->conf['FilterBy']);
$TransactionOkURL = (isset($_REQUEST['TransactionOkURL'])?$_REQUEST['TransactionOkURL']:$proxy->conf['TransactionOkURL']);
$TransactionErrorURL = (isset($_REQUEST['TransactionErrorURL'])?$_REQUEST['TransactionErrorURL']:$proxy->conf['TransactionErrorURL']);
$TransactionExpirationTime = (isset($_REQUEST['TransactionExpirationTime'])?$_REQUEST['TransactionExpirationTime']:$proxy->conf['TransactionExpirationTime']);
$CustomMerchantName = (isset($_REQUEST['CustomMerchantName'])?$_REQUEST['CustomMerchantName']:$proxy->conf['CustomMerchantName']);
$ShopperEmail = (isset($_REQUEST['ShopperEmail'])?$_REQUEST['ShopperEmail']:$proxy->conf['ShopperEmail']);
$ShopperInformation_FirstName = (isset($_REQUEST['ShopperInformation_FirstName'])?$_REQUEST['ShopperInformation_FirstName']:$proxy->conf['ShopperInformation_FirstName']);
$ShopperInformation_LastName = (isset($_REQUEST['ShopperInformation_LastName'])?$_REQUEST['ShopperInformation_LastName']:$proxy->conf['ShopperInformation_LastName']);
$ShopperInformation_Email = (isset($_REQUEST['ShopperInformation_Email'])?$_REQUEST['ShopperInformation_Email']:$proxy->conf['ShopperInformation_Email']);
$ShopperInformation_CountryCode = (isset($_REQUEST['ShopperInformation_CountryCode'])?$_REQUEST['ShopperInformation_CountryCode']:$proxy->conf['ShopperInformation_CountryCode']);
$ShopperInformation_Mobile = (isset($_REQUEST['ShopperInformation_Mobile'])?$_REQUEST['ShopperInformation_Mobile']:$proxy->conf['ShopperInformation_Mobile']);
$ShopperInformation_NotifyExpiration = (isset($_REQUEST['ShopperInformation_NotifyExpiration'])?$_REQUEST['ShopperInformation_NotifyExpiration']:$proxy->conf['ShopperInformation_NotifyExpiration']);
$ShopperInformation_RecoveryMessage = (isset($_REQUEST['ShopperInformation_RecoveryMessage'])?$_REQUEST['ShopperInformation_RecoveryMessage']:$proxy->conf['ShopperInformation_RecoveryMessage']);

// Values Setting: Optional
$proxy->conf['ApiKey'] = $ApiKey;
$proxy->conf['SignatureKey'] = $SignatureKey;
$proxy->conf['Environment'] = $Environment;
$proxy->conf['Protocol'] = $Protocol;
$proxy->conf['TransactionOkURL'] = $TransactionOkURL;
$proxy->conf['TransactionErrorURL'] = $TransactionErrorURL;

// Values Setting: Required
$proxy->conf['CurrencyCode'] = $CurrencyCode;
$proxy->conf['Amount'] = $Amount;
$proxy->conf['MerchantSalesID'] = $MerchantSalesID;
$proxy->conf['Language'] = $Language;
$proxy->conf['ExpirationTime'] = $ExpirationTime;
$proxy->conf['FilterBy'] = $FilterBy;
$proxy->conf['ProductID'] = $ProductID;
$proxy->conf['TransactionExpirationTime'] = $TransactionExpirationTime;
$proxy->conf['CustomMerchantName'] = $CustomMerchantName;
$proxy->conf['ShopperEmail'] = $ShopperEmail;
$proxy->conf['ShopperInformation']['first_name'] = $ShopperInformation_FirstName;
$proxy->conf['ShopperInformation']['last_name'] = $ShopperInformation_LastName;
$proxy->conf['ShopperInformation']['email'] = $ShopperInformation_Email;
$proxy->conf['ShopperInformation']['country_code'] = $ShopperInformation_CountryCode;
$proxy->conf['ShopperInformation']['mobile'] = $ShopperInformation_Mobile;
$proxy->conf['ShopperInformation']['notify_expiration'] = $ShopperInformation_NotifyExpiration;
$proxy->conf['ShopperInformation']['recovery_message'] = $ShopperInformation_RecoveryMessage;

// Get Token URL
$Result = $proxy->CreateExpressToken();
if ( $Result['ErrorManager']['ErrorNumber']['@content'] == '0' )
{
    $tokenUrl = $Result['ShopperRedirectURL'];
    $tokenUrl = '<a href="' . $tokenUrl . '" target="_new">' . $tokenUrl . '</a>';
}
if ($Result['ErrorManager']['ErrorNumber']['@content'] == '0')
    $errorNo = '<span style="color:black;">'
                . current(@@$Result['ErrorManager']['ErrorNumber']) . ', '
                . current(@@$Result['ErrorManager']['Description']) . '</span>';
else
    if (is_array($Result['ErrorManager']['ErrorNumber']))
        $errorNo = '<span style="color:red;">'
                . current(@@$Result['ErrorManager']['ErrorNumber']) . ', '
                . current(@@$Result['ErrorManager']['Description']) . '</span>';
    else
        $errorNo = '<span style="color:red;">'
                . @@$Result['ErrorManager']['ErrorNumber'] . ', '
                . @@$Result['ErrorManager']['Description'] . '</span>';

$signature = $proxy->conf['Signature'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SafetyPay Express</title>
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
<form method="POST" name="frmCheckOut" id="frmCheckOut">
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
        <td class="subtitle" colspan="4">Resources</td>
    </tr>
    <tr><td>&nbsp;</td>
        <td colspan="3"><a href="SafetyPayCheck.php"
                               target="_blank">Requirements and Server Configuration</a></td>
    </tr>
    <tr><td>&nbsp;</td>
        <td><a href="https://secure.safetypay.com/sandbox/Merchants"
                        target="_blank">MMS Sandbox</a>
        </td>
        <td colspan="2" style="text-align:center;"><a href="http://demobank.safetypay.com"
               target="_blank">SafetyPay Default Bank</a> (only for Sandbox)</td>
    </tr>
    <tr><td>&nbsp;</td>
        <td colspan="3"><a href="https://secure.safetypay.com/Merchants"
                      target="_blank">MMS Production</a>
        </td>
    </tr>
    <tr>
        <td colspan="4">&nbsp;</td>
    </tr>
    <tr>
        <th colspan="4" class="subtitle">Merchant Configuration</th>
    </tr>
    <tr>
        <td width="2%">&nbsp;</td>
        <td width="32%">Environment</td>
        <td width="32%">Communication Protocol</td>
        <td width="32%">Language</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td><select name="Environment">
                <option value="1"<?php if ($Environment == '1') { ?> selected="selected"<?php } ?>>Sandbox</option>
                <option value="0"<?php if ($Environment == '0') { ?> selected="selected"<?php } ?>>Production</option>
            </select>
        </td>
        <td><select name="Protocol">
                <option value="https"<?php if ($Protocol == 'https') { ?> selected="selected"<?php } ?>>HTTPS</option>
				<option value="http"<?php if ($Protocol == 'http') { ?> selected="selected"<?php } ?>>HTTP</option>
            </select>
        </td>
        <td><select name="Language">
                <option value="ES"<?php if ($Language == 'ES') { ?> selected="selected"<?php } ?>>Spanish</option>
                <option value="EN"<?php if ($Language == 'EN') { ?> selected="selected"<?php } ?>>English</option>
                <option value="DE"<?php if ($Language == 'DE') { ?> selected="selected"<?php } ?>>Deutsch</option>
                <option value="PT"<?php if ($Language == 'PT') { ?> selected="selected"<?php } ?>>Portuguese</option>
            </select>
        </td>
    </tr>
    <tr>
        <td width="2%">&nbsp;</td>
        <td width="32%">&nbsp;</td>
        <td width="32%">&nbsp;</td>
        <td width="32%">&nbsp;</td>
    </tr>
    <tr>
        <td width="2%">&nbsp;</td>
        <td width="32%">&nbsp;</td>
        <td width="32%"><strong>Currency Code</strong></td>
        <td width="32%">&nbsp;</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><select name="CurrencyCode">
				<option value="BRL"<?php if ($CurrencyCode == 'BRL') { ?> selected="selected"<?php } ?>>BRL, Brazil Real</option>
				<option value="CAD"<?php if ($CurrencyCode == 'CAD') { ?> selected="selected"<?php } ?>>CAD, Canadian Dollar</option>
				<option value="COP"<?php if ($CurrencyCode == 'COP') { ?> selected="selected"<?php } ?>>COP, Colombian Pesos</option>
				<option value="CRC"<?php if ($CurrencyCode == 'CRC') { ?> selected="selected"<?php } ?>>CRC, Costa Rica Colon</option>
				<option value="EUR"<?php if ($CurrencyCode == 'EUR') { ?> selected="selected"<?php } ?>>EUR, Euro</option>
				<option value="HNL"<?php if ($CurrencyCode == 'HNL') { ?> selected="selected"<?php } ?>>HNL, Lempiras</option>
				<option value="MXN"<?php if ($CurrencyCode == 'MXN') { ?> selected="selected"<?php } ?>>MXN, Mexico Pesos</option>
				<option value="NIO"<?php if ($CurrencyCode == 'NIO') { ?> selected="selected"<?php } ?>>NIO, Nicaragua Cordoba</option>
				<option value="PAB"<?php if ($CurrencyCode == 'PAB') { ?> selected="selected"<?php } ?>>PAB, Panama Balboa</option>
				<option value="PEN"<?php if ($CurrencyCode == 'PEN') { ?> selected="selected"<?php } ?>>PEN, Nuevos Soles</option>
				<option value="USD"<?php if ($CurrencyCode == 'USD') { ?> selected="selected"<?php } ?>>USD, US Dollar</option>
            </select>
        </td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>API Key</td>
        <td colspan="2"><input name="ApiKey" type="text"
                               value="<?php echo $ApiKey; ?>"
                               size="35" /></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>Signature Key</td>
        <td colspan="2"><input name="SignatureKey" type="text"
                               value="<?php echo $SignatureKey; ?>"
                               size="35" /></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>Filter By</td>
        <td colspan="2"><input name="FilterBy" type="text"
                               value="<?php echo $FilterBy; ?>"
                               size="35" /></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>Product ID</td>
        <td colspan="2"><input name="ProductID" type="text"
                                value="<?php echo $ProductID; ?>"
                                size="2" /></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>Token Expiration Time</td>
        <td colspan="2"><input name="ExpirationTime" type="text"
                                value="<?php echo $ExpirationTime; ?>"
                                size="5" /></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>Transaction Expiration Time</td>
        <td colspan="2"><input name="TransactionExpirationTime" type="text"
                                value="<?php echo $TransactionExpirationTime; ?>"
                                size="5" /></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>Custom Merchant Name</td>
        <td colspan="2"><input name="CustomMerchantName" type="text"
                                value="<?php echo $CustomMerchantName; ?>"
                                size="40" /></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>Shopper Email</td>
        <td colspan="2"><input name="ShopperEmail" type="text"
                                value="<?php echo $ShopperEmail; ?>"
                                size="40" /></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>Transaction OK URL</td>
        <td colspan="2"><input name="TransactionOkURL" type="text"
                               value="<?php echo $TransactionOkURL; ?>"
                               size="50" /></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>Transaction Error URL</td>
        <td colspan="2"><input name="TransactionErrorURL" type="text"
                               value="<?php echo $TransactionErrorURL; ?>"
                               size="50" /></td>
    </tr>
    <tr>
        <td colspan="4">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="4">&nbsp;&nbsp;&nbsp;<strong>Shopper Information</strong></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>First Name</td>
        <td colspan="2"><input name="ShopperInformation_FirstName" type="text"
                               value="<?php echo $ShopperInformation_FirstName; ?>"
                               size="40" /></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>Last Name</td>
        <td colspan="2"><input name="ShopperInformation_LastName" type="text"
                               value="<?php echo $ShopperInformation_LastName; ?>"
                               size="40" /></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>Email</td>
        <td colspan="2"><input name="ShopperInformation_Email" type="text"
                               value="<?php echo $ShopperInformation_Email; ?>"
                               size="40" /></td>
    </tr>
	<tr>
        <td>&nbsp;</td>
        <td>Country Code</td>
        <td colspan="2"><input name="ShopperInformation_CountryCode" type="text"
                               value="<?php echo $ShopperInformation_CountryCode; ?>"
                               size="10" /></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>Mobile</td>
        <td colspan="2"><input name="ShopperInformation_Mobile" type="text"
                               value="<?php echo $ShopperInformation_Mobile; ?>"
                               size="40" /></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>Notify Expiration</td>
        <td colspan="2"><input name="ShopperInformation_NotifyExpiration" type="text"
                               value="<?php echo $ShopperInformation_NotifyExpiration; ?>"
                               size="40" /></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>Recovery Message</td>
        <td colspan="2"><input name="ShopperInformation_RecoveryMessage" type="text"
                               value="<?php echo $ShopperInformation_RecoveryMessage; ?>"
                               size="40" /></td>
    </tr>
    <tr>
        <td colspan="4">&nbsp;</td>
    </tr>
    <tr>
        <th colspan="4" class="subtitle">Check Process</th>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>Merchant Sales ID</td>
        <td colspan="2"><input name="MerchantSalesID" type="text"
                               value="<?php echo $MerchantSalesID; ?>"
                               size="45" /></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>Total Amount</td>
        <td colspan="2"><input name="Amount" type="text"
                               value="<?php echo $Amount; ?>" size="20" />
                        &nbsp;<input type="submit" name="Submit" value="Generate" />
        </td>
    </tr>
    <tr>
        <td colspan="4">&nbsp;</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td><strong>Response</strong></td>
        <td colspan="2">&nbsp;
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td colspan="3">
            <div class="subcontain">
                <strong>Token URL:</strong> <?php echo $tokenUrl; ?><br />
                <strong>Error:</strong> <?php echo $errorNo; ?>
            </div>
        </td>
    </tr>
    <tr>
        <td colspan="4">&nbsp;</td>
    </tr>
    <tr>
        <th colspan="4" class="subtitle">Notification Process</th>
    </tr>
	<tr>
        <td>&nbsp;</td>
        <td colspan="3">Example - POST URL:
            <a href="SafetyPayPOSTExpress.php"
               target="_blank">http://www.mydomain.com/SafetyPayPOSTExpress.php
            </a>
        </td>
    </tr>
    <tr>
        <td colspan="4">&nbsp;</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td colspan="3">Please, follow the instructions:
            <ol>
                <li>Register the POST URL in the MMS<br />
                    Location: <strong>Profile > Notification > Set value
                    of POST URL</strong> (include protocol and domain), check
                    "Notifiy by Post" and then click on "Update" link.
                </li>
                <li>Make the necessary changes on the file and put your code
                    to make changes, like:<br />
                    - Report to SafetyPay the correct Order ID.<br />
                    - Change order status to complete or payment.<br />
                    - Send payment confirmation email to your client.
                </li>
            </ol>
        </td>
    </tr>
	</table>
</form>
</div>
</body>
</html>