<?php
class whmcsHookManager
{
	private static $hooks;
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
	public static function runHook( $hook )
	{
		$args = func_get_args();
		$args = empty( $args[1] ) ? array( ) : $args[1];
		if ( !isset( self::$hooks[$hook] ) ) {
			return false;
		}
		foreach ( self::$hooks[$hook] as $hookPriority => $hookList ) {
			for ( $p = 0; $p < count( self::$hooks[$hook][$hookPriority] ); $p++ ) {
				if ( is_array( self::$hooks[$hook][$hookPriority][$p] ) ) {
					call_user_func_array( self::$hooks[$hook][$hookPriority][$p], $args );
				} else {
					$func = self::$hooks[$hook][$hookPriority][$p];
					$func( &$args );
				}
			}
		}
		return $args;
	}
}
$whmcsHookList = "AdminLogin,AdminLogout,AnnouncementAdd,AnnouncementEdit,NetworkIssueAdd,NetworkIssueEdit,NetworkIssueClose,NetworkIssueReopen,NetworkIssueDelete,ProductEdit,ProductDelete,ServerAdd,ServerEdit,ServerDelete,EmailPreSend,DailyCronJob,ClientAreaHomepage,ClientAreaPage,ClientAreaHeadOutput,ClientAreaHeaderOutput,ClientAreaFooterOutput,AdminAreaPage,AdminAreaHeadOutput,AdminAreaHeaderOutput,AdminAreaFooterOutput,AdminHomepage,AdminAreaClientSummaryPage,ViewOrderDetailsPage,TicketOpen,TicketAdminReply,TicketUserReply,TicketOpenAdmin,TicketAddNote,SubmitTicketAnswerSuggestions,InvoiceCreated,InvoiceCreationPreEmail,InvoiceCreationAdminArea,UpdateInvoiceTotal,AddInvoicePayment,InvoicePaid,InvoicePaidPreEmail,InvoiceUnpaid,InvoiceCancelled,InvoiceRefunded,ManualRefund,AddTransaction,LogTransaction,AddInvoiceLateFee,InvoicePaymentReminder,InvoiceChangeGateway,ShoppingCartValidateProductUpdate,ShoppingCartValidateCheckout,PreCalculateCartTotals,PreShoppingCartCheckout,AfterShoppingCartCheckout,ShoppingCartCheckoutCompletePage,AcceptOrder,CancelOrder,FraudOrder,PendingOrder,DeleteOrder,PreDomainRegister,AfterRegistrarRegistration,AfterRegistrarRegistrationFailed,AfterRegistrarTransfer,AfterRegistrarTransferFailed,AfterRegistrarRenewal,AfterRegistrarRenewalFailed,AddonActivation,AddonAdd,AddonEdit,AddonActivated,AddonSuspended,AddonTerminated,AddonCancelled,AddonFraud,AddonDeleted,AfterModuleCreate,PreModuleCreate,AfterModuleSuspend,PreModuleSuspend,AfterModuleUnsuspend,PreModuleUnsuspend,AfterModuleTerminate,PreModuleTerminate,AfterModuleRenew,PreModuleRenew,AfterModuleChangePassword,AfterModuleChangePackage,AdminServiceEdit,CancellationRequest,AfterProductUpgrade,AfterConfigOptionsUpgrade,ContactAdd,ContactEdit,ContactDelete,ClientAdd,ClientAreaRegister,ClientEdit,ClientLogin,ClientLogout,ClientChangePassword,ClientDetailsValidation,ClientClose,ClientDelete,PreDeleteClient";
$whmcsHookList = explode(",",$whmcsHookList);
foreach($whmcsHookList as $whmcsHook)
{
	$func = "whmcsHookManager_Hook_".$whmcsHook;
	if(!function_exists($func))
	{
		eval("function $func()".'
		{
			$args = func_get_args();
			$result = whmcsHookManager::runHook($whmcsHook,&$args);
			return (empty($result) ? array() : $result[0]);
		}');
		add_hook($whmcsHook,1,$func);
	}
}
?>
