<?php
if(!class_exists("whmcsHookManager"))
{
class whmcsHookManager
{
	static $hooks;
	public static function addHook( $hook, $priority, $func )
	{
		if ( !isset( self::$hooks[$hook] ) ) {
			self::$hooks[$hook] = array( );
		}
		if ( !isset( self::$hooks[$hook][$priority] ) ) {
			self::$hooks[$hook][$priority] = array( );
		}
		self::$hooks[$hook][$priority][ ] = $func;
	}
	public static function removeHook( $hook, $func )
	{
		for ( $i = 0; $i < count( self::$hooks[$hook][$priority] ); $i++ ) {
			if ( self::$hooks[$hook][$priority][$i] == $func ) {
				unset( self::$hooks[$hook][$priority][$i] );
			}
		}
	}
	public static function runHook( $hook, $args )
	{
		if ( !isset( self::$hooks[$hook] ) ) {
			return false;
		}
		foreach ( self::$hooks[$hook] as $hookPriority => $hookList ) {
			for ( $p = 0; $p < count( self::$hooks[$hook][$hookPriority] ); $p++ ) {
				if ( is_array( self::$hooks[$hook][$hookPriority][$p] ) ) {
					if( !is_array( $args ) )
					{
						$args = call_user_func_array( self::$hooks[$hook][$hookPriority][$p], array( $args ) );
					}
					else
					{
						$args = call_user_func_array( self::$hooks[$hook][$hookPriority][$p], $args );
					}
				} else {
					$func = self::$hooks[$hook][$hookPriority][$p];
					$result = $func( $args );
				}
				if(!empty($result))
				{
					$args = $result;
				}
			}
		}
		return $args;
	}
}
$whmcsHookList = "AdminLogin:none
AdminLogout:none
AnnouncementAdd:none
AnnouncementEdit:none
NetworkIssueAdd:none
NetworkIssueEdit:none
NetworkIssueClose:none
NetworkIssueReopen:none
NetworkIssueDelete:none
ProductEdit:none
ProductDelete:none
ServerAdd:none
ServerEdit:none
ServerDelete:none
EmailPreSend:array
DailyCronJob:none
ClientAreaHomepage:array
ClientAreaPage:array
ClientAreaHeadOutput:string
ClientAreaHeaderOutput:string
ClientAreaFooterOutput:array
AdminAreaPage:array
AdminAreaHeadOutput:string
AdminAreaHeaderOutput:string
AdminAreaFooterOutput:string
AdminHomepage:string
AdminAreaClientSummaryPage:string
ViewOrderDetailsPage:string
TicketOpen:none
TicketAdminReply:none
TicketUserReply:none
TicketOpenAdmin:none
TicketAddNote:none
SubmitTicketAnswerSuggestions:none
InvoiceCreated:none
InvoiceCreationPreEmail:none
InvoiceCreationAdminArea:none
UpdateInvoiceTotal:none
AddInvoicePayment:none
InvoicePaid:none
InvoicePaidPreEmail:none
InvoiceUnpaid:none
InvoiceCancelled:none
InvoiceRefunded:none
ManualRefund:none
AddTransaction:none
LogTransaction:none
AddInvoiceLateFee:none
InvoicePaymentReminder:none
InvoiceChangeGateway:none
ShoppingCartValidateProductUpdate:none
ShoppingCartValidateCheckout:none
PreCalculateCartTotals:none
PreShoppingCartCheckout:none
AfterShoppingCartCheckout:none
ShoppingCartCheckoutCompletePage:none
AcceptOrder:none
CancelOrder:none
FraudOrder:none
PendingOrder:none
DeleteOrder:none
PreDomainRegister:none
AfterRegistrarRegistration:none
AfterRegistrarRegistrationFailed:none
AfterRegistrarTransfer:none
AfterRegistrarTransferFailed:none
AfterRegistrarRenewal:none
AfterRegistrarRenewalFailed:none
AddonActivation:none
AddonAdd:none
AddonEdit:none
AddonActivated:none
AddonSuspended:none
AddonTerminated:none
AddonCancelled:none
AddonFraud:none
AddonDeleted:none
AfterModuleCreate:none
PreModuleCreate:none
AfterModuleSuspend:none
PreModuleSuspend:none
AfterModuleUnsuspend:none
PreModuleUnsuspend:none
AfterModuleTerminate:none
PreModuleTerminate:none
AfterModuleRenew:none
PreModuleRenew:none
AfterModuleChangePassword:none
AfterModuleChangePackage:none
AdminServiceEdit:none
CancellationRequest:none
AfterProductUpgrade:none
AfterConfigOptionsUpgrade:none
ContactAdd:none
ContactEdit:none
ContactDelete:none
ClientAdd:none
ClientAreaRegister:none
ClientEdit:none
ClientLogin:none
ClientLogout:none
ClientChangePassword:none
ClientDetailsValidation:none
ClientClose:none
ClientDelete:none
PreDeleteClient:none";
$whmcsHookList = explode("\n",$whmcsHookList);
foreach($whmcsHookList as $whmcsHook)
{
	$parts = explode(":",$whmcsHook);
	$func = "whmcsHookManager_Hook_".$parts[0];
	if(!function_exists($func))
	{
		eval("function $func()".'
		{
			$args = func_get_args();
			$args = whmcsHookManager::runHook("'.$parts[0].'",$args);
			if(!empty( $args ) )
			{
				switch("'.$parts[1].'")
				{
					case "string":
						return (string) $args[0];
					break;
					case "array":
						return (array) $args[0];
					break;
					case "none":
					break;
				}
			}
		}');
		add_hook($parts[0],1,$func);
	}
}
}
?>
