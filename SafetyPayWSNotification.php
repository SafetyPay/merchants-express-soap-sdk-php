<?php
/* SafetyPay Integration Code
 *
 * This script will let your system receive Automatic Notifications from
 * SafetyPay changing an order from "Pending" to "Paid" status and
 * sending an email (must implement this) of Payment Confirmation to
 * shopper AUTOMATICALLY.
 *
 * @copyright   2012-2016 SafetyPay Inc. IT Team - support@safetypay.com
 * @license     http://opensource.org/licenses/osl-3.0.php
 *              Open Software License (OSL 3.0)
 * @package     main
 */

require_once 'class/SafetyPayProxy.php';

$proxy = new SafetyPayProxy();

// 1: Get New Paid Orders
$Result = $proxy->GetNewOperationActivity();
if ( $Result['ErrorManager']['ErrorNumber']['@content'] == '0' )
{
    $txtLogMessage = '';
    if ( is_array($Result['ListOfOperations']['Operation']) )
    {
        if ( isset($Result['ListOfOperations']['Operation']['OperationID']) )
            $oResult = $Result['ListOfOperations'];
        else
            $oResult = $Result['ListOfOperations']['Operation'];

        $nCounter = 0;
        $opStatus = 0;
		
        foreach( $oResult as $k => $v )
        {
			//if ($k === 150)	break;		// If need limit a max number of operations to confirm
            // 2: Set your Order Number in $merchantOrderID var to confirm
            // IMPORTANT!
            // INSERT YOUR CODE HERE
            // You will be receive order paids in the variable $oResult,
            // you can use this to change your orders from PENDING to COMPLETE.
            // In this variable are include your MerchantSalesID (sames as used
            // in the CreateTransaction() call)
            $merchantOrderID = $v['MerchantSalesID'];
            // else
            // You create/recover a Order Number different than the
            // MerchantSalesID your must, then...
            // $merchantOrderID = 'YOUR ORDER NUMBER';

            if (isset($v['OperationActivities']['OperationActivity']))
                $oActivities = $v['OperationActivities']['OperationActivity'];
            else
                $oActivities = $v['OperationActivities'];

            if (isset($oActivities['CreationDateTime']))
            {
                $opStatus = $oActivities['Status']['StatusCode'];
            }
            else
            {
                foreach( $oActivities as $key => $va )
                {
                    $opStatus = $va['Status']['StatusCode'];
                }
            }
			
            $toconfirm['ConfirmOperation'][] = array(
                                'CreationDateTime' => $v['CreationDateTime'],
                                'OperationID' => $v['OperationID'],
                                'MerchantSalesID' => $v['MerchantSalesID'],
                                'MerchantOrderID' => $merchantOrderID,
                                'OperationStatus' => $opStatus
                                                    );
            
            $ConfirmTransactions[] = $v['OperationID']
                                        . ' (' . $v['MerchantSalesID'] . ')';
            $nCounter++;
        }
        $nCounter = count( $toconfirm['ConfirmOperation'] );
        // 3: Confirm to Safetypay the Order Number
        $Result = $proxy->ConfirmNewOperationActivity( $toconfirm );
        if ( $Result['ErrorManager']['ErrorNumber']['@content'] == '0' )
        {
            // 4: Send Email Confirmation (Optional)
            // If needed, enter your own function to send an email to the buyer
            // sendmail_function($emailaddress, $emailsubject, $msg, $headers);
            $txtLogMessage = 'Operation (Merchant Reference No) Confirmed: '
                                . implode(", ", $ConfirmTransactions);
        }
        else
        {
            if (is_array($Result['ErrorManager']['ErrorNumber']['@content']))
                $txtLogMessage = 'Error: '
                . current($Result['ErrorManager']['ErrorNumber']['@content']) . ' - '
                . current($Result['ErrorManager']['Description']['@content']);
            else
                $txtLogMessage = 'Error: '
                . $Result['ErrorManager']['ErrorNumber']['@content'] . ' - '
                . $Result['ErrorManager']['Description']['@content'];
        }
    }
    else
    {
        $txtLogMessage .= 'No New Paid Orders';
    }

    // 5: Show message about result some process or error
    if ( $nCounter == 0 )
        echo 'No registrations processed.';
    elseif ( ($nCounter != 0) && (strrpos($txtLogMessage, 'Error:') > 1) )
        echo (string)$nCounter.' processed not confirmed. <br /><br />'
                    . $txtLogMessage;
    else
        echo (string)$nCounter.' processed confirmed. <br /><br />'
                    . $txtLogMessage;
}
else
{
    echo 'Error in GetNewOperationActivity Method: Invalid Credentials!<br>';
    echo 'Error Number: ' . $Result['ErrorManager']['ErrorNumber']['@content']
            . '<br>Severity: '. $Result['ErrorManager']['Severity']['@content']
            . '<br>Description: '. $Result['ErrorManager']['Description']['@content'];
}
?>