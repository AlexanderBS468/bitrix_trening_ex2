<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

function checkUserCount() {

	$timestampFrom = COption::GetOptionString('main','checkUserCounter');
	if (!$timestampFrom)
	{
		$timestampFrom = 0;
	}

	$dateFrom = ConvertTimeStamp($timestampFrom, 'FULL', LANGUAGE_ID);

	$filter = array('DATE_REGISTER_1' => $dateFrom);
	$resUsers = CUser::GetList(($by = 'id'), ($order = 'desc'), $filter);
	$userQuantity = $resUsers->SelectedRowsCount();

	$adminEmails = array();
	$filter = array('GROUPS_ID' => array(1));
	$resUsers = CUser::GetList(($by = 'id'), ($order = 'asc'), $filter);
	while ($user = $resUsers->Fetch())
	{
		$adminEmails[] = $user['EMAIL'];
	}

	$timestamp = getmicrotime();
	COption::SetOptionString('main','checkUserCounter', $timestamp);

	$secondsDiff = $timestamp - $timestampFrom;
	$daysDiff = round($secondsDiff / (3600 * 24));

	if ($adminEmails)
	{
		$fields = array(
			'COUNT' => $userQuantity,
			'DAYS' => $daysDiff,
			'EMAIL_TO' => implode(', ', $adminEmails)
		);
		CEvent::Send('CHECK_USER_COUNT', SITE_ID, $fields, 'Y', CHECK_USER_COUNT_TEMPLATE_FOR_ADMINS_ID);
	}

	return 'checkUserCount();';
}
