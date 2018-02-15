<?php
// This script and data application were generated by AppGini 5.62
// Download AppGini for free from https://bigprof.com/appgini/download/

	$currDir=dirname(__FILE__);
	include("$currDir/defaultLang.php");
	include("$currDir/language.php");
	include("$currDir/lib.php");
	@include("$currDir/hooks/assignments.php");
	include("$currDir/assignments_dml.php");

	// mm: can the current member access this page?
	$perm=getTablePermissions('assignments');
	if(!$perm[0]){
		echo error_message($Translation['tableAccessDenied'], false);
		echo '<script>setTimeout("window.location=\'index.php?signOut=1\'", 2000);</script>';
		exit;
	}

	$x = new DataList;
	$x->TableName = "assignments";

	// Fields that can be displayed in the table view
	$x->QueryFieldsTV = array(   
		"`assignments`.`Id`" => "Id",
		"IF(    CHAR_LENGTH(`projects1`.`Name`), CONCAT_WS('',   `projects1`.`Name`), '') /* Project */" => "ProjectId",
		"IF(    CHAR_LENGTH(if(`projects1`.`StartDate`,date_format(`projects1`.`StartDate`,'%d/%m/%Y'),'')) || CHAR_LENGTH(if(`projects1`.`EndDate`,date_format(`projects1`.`EndDate`,'%d/%m/%Y'),'')), CONCAT_WS('',   if(`projects1`.`StartDate`,date_format(`projects1`.`StartDate`,'%d/%m/%Y'),''), ' <b>to</b> ', if(`projects1`.`EndDate`,date_format(`projects1`.`EndDate`,'%d/%m/%Y'),'')), '') /* Project Duration */" => "ProjectDuration",
		"IF(    CHAR_LENGTH(`resources1`.`Name`), CONCAT_WS('',   `resources1`.`Name`), '') /* Resource */" => "ResourceId",
		"`assignments`.`Commitment`" => "Commitment",
		"if(`assignments`.`StartDate`,date_format(`assignments`.`StartDate`,'%d/%m/%Y'),'')" => "StartDate",
		"if(`assignments`.`EndDate`,date_format(`assignments`.`EndDate`,'%d/%m/%Y'),'')" => "EndDate"
	);
	// mapping incoming sort by requests to actual query fields
	$x->SortFields = array(   
		1 => '`assignments`.`Id`',
		2 => '`projects1`.`Name`',
		3 => 3,
		4 => '`resources1`.`Name`',
		5 => '`assignments`.`Commitment`',
		6 => '`assignments`.`StartDate`',
		7 => '`assignments`.`EndDate`'
	);

	// Fields that can be displayed in the csv file
	$x->QueryFieldsCSV = array(   
		"`assignments`.`Id`" => "Id",
		"IF(    CHAR_LENGTH(`projects1`.`Name`), CONCAT_WS('',   `projects1`.`Name`), '') /* Project */" => "ProjectId",
		"IF(    CHAR_LENGTH(if(`projects1`.`StartDate`,date_format(`projects1`.`StartDate`,'%d/%m/%Y'),'')) || CHAR_LENGTH(if(`projects1`.`EndDate`,date_format(`projects1`.`EndDate`,'%d/%m/%Y'),'')), CONCAT_WS('',   if(`projects1`.`StartDate`,date_format(`projects1`.`StartDate`,'%d/%m/%Y'),''), ' <b>to</b> ', if(`projects1`.`EndDate`,date_format(`projects1`.`EndDate`,'%d/%m/%Y'),'')), '') /* Project Duration */" => "ProjectDuration",
		"IF(    CHAR_LENGTH(`resources1`.`Name`), CONCAT_WS('',   `resources1`.`Name`), '') /* Resource */" => "ResourceId",
		"`assignments`.`Commitment`" => "Commitment",
		"if(`assignments`.`StartDate`,date_format(`assignments`.`StartDate`,'%d/%m/%Y'),'')" => "StartDate",
		"if(`assignments`.`EndDate`,date_format(`assignments`.`EndDate`,'%d/%m/%Y'),'')" => "EndDate"
	);
	// Fields that can be filtered
	$x->QueryFieldsFilters = array(   
		"`assignments`.`Id`" => "ID",
		"IF(    CHAR_LENGTH(`projects1`.`Name`), CONCAT_WS('',   `projects1`.`Name`), '') /* Project */" => "Project",
		"IF(    CHAR_LENGTH(if(`projects1`.`StartDate`,date_format(`projects1`.`StartDate`,'%d/%m/%Y'),'')) || CHAR_LENGTH(if(`projects1`.`EndDate`,date_format(`projects1`.`EndDate`,'%d/%m/%Y'),'')), CONCAT_WS('',   if(`projects1`.`StartDate`,date_format(`projects1`.`StartDate`,'%d/%m/%Y'),''), ' <b>to</b> ', if(`projects1`.`EndDate`,date_format(`projects1`.`EndDate`,'%d/%m/%Y'),'')), '') /* Project Duration */" => "Project Duration",
		"IF(    CHAR_LENGTH(`resources1`.`Name`), CONCAT_WS('',   `resources1`.`Name`), '') /* Resource */" => "Resource",
		"`assignments`.`Commitment`" => "Commitment",
		"`assignments`.`StartDate`" => "Start Date",
		"`assignments`.`EndDate`" => "End Date"
	);

	// Fields that can be quick searched
	$x->QueryFieldsQS = array(   
		"`assignments`.`Id`" => "Id",
		"IF(    CHAR_LENGTH(`projects1`.`Name`), CONCAT_WS('',   `projects1`.`Name`), '') /* Project */" => "ProjectId",
		"IF(    CHAR_LENGTH(if(`projects1`.`StartDate`,date_format(`projects1`.`StartDate`,'%d/%m/%Y'),'')) || CHAR_LENGTH(if(`projects1`.`EndDate`,date_format(`projects1`.`EndDate`,'%d/%m/%Y'),'')), CONCAT_WS('',   if(`projects1`.`StartDate`,date_format(`projects1`.`StartDate`,'%d/%m/%Y'),''), ' <b>to</b> ', if(`projects1`.`EndDate`,date_format(`projects1`.`EndDate`,'%d/%m/%Y'),'')), '') /* Project Duration */" => "ProjectDuration",
		"IF(    CHAR_LENGTH(`resources1`.`Name`), CONCAT_WS('',   `resources1`.`Name`), '') /* Resource */" => "ResourceId",
		"`assignments`.`Commitment`" => "Commitment",
		"if(`assignments`.`StartDate`,date_format(`assignments`.`StartDate`,'%d/%m/%Y'),'')" => "StartDate",
		"if(`assignments`.`EndDate`,date_format(`assignments`.`EndDate`,'%d/%m/%Y'),'')" => "EndDate"
	);

	// Lookup fields that can be used as filterers
	$x->filterers = array(  'ProjectId' => 'Project', 'ResourceId' => 'Resource');

	$x->QueryFrom = "`assignments` LEFT JOIN `projects` as projects1 ON `projects1`.`Id`=`assignments`.`ProjectId` LEFT JOIN `resources` as resources1 ON `resources1`.`Id`=`assignments`.`ResourceId` ";
	$x->QueryWhere = '';
	$x->QueryOrder = '';

	$x->AllowSelection = 1;
	$x->HideTableView = ($perm[2]==0 ? 1 : 0);
	$x->AllowDelete = $perm[4];
	$x->AllowMassDelete = true;
	$x->AllowInsert = $perm[1];
	$x->AllowUpdate = $perm[3];
	$x->SeparateDV = 1;
	$x->AllowDeleteOfParents = 0;
	$x->AllowFilters = 1;
	$x->AllowSavingFilters = 1;
	$x->AllowSorting = 1;
	$x->AllowNavigation = 1;
	$x->AllowPrinting = 1;
	$x->AllowCSV = 1;
	$x->RecordsPerPage = 10;
	$x->QuickSearch = 1;
	$x->QuickSearchText = $Translation["quick search"];
	$x->ScriptFileName = "assignments_view.php";
	$x->RedirectAfterInsert = "assignments_view.php?SelectedID=#ID#";
	$x->TableTitle = "Assignments";
	$x->TableIcon = "resources/table_icons/client_account_template.png";
	$x->PrimaryKey = "`assignments`.`Id`";
	$x->DefaultSortField = '`assignments`.`EndDate`';
	$x->DefaultSortDirection = 'desc';

	$x->ColWidth   = array(  150, 150, 150, 150, 150);
	$x->ColCaption = array("Project", "Resource", "Commitment", "Start Date", "End Date");
	$x->ColFieldName = array('ProjectId', 'ResourceId', 'Commitment', 'StartDate', 'EndDate');
	$x->ColNumber  = array(2, 4, 5, 6, 7);

	// template paths below are based on the app main directory
	$x->Template = 'templates/assignments_templateTV.html';
	$x->SelectedTemplate = 'templates/assignments_templateTVS.html';
	$x->TemplateDV = 'templates/assignments_templateDV.html';
	$x->TemplateDVP = 'templates/assignments_templateDVP.html';

	$x->ShowTableHeader = 1;
	$x->ShowRecordSlots = 0;
	$x->TVClasses = "";
	$x->DVClasses = "";
	$x->HighlightColor = '#FFF0C2';

	// mm: build the query based on current member's permissions
	$DisplayRecords = $_REQUEST['DisplayRecords'];
	if(!in_array($DisplayRecords, array('user', 'group'))){ $DisplayRecords = 'all'; }
	if($perm[2]==1 || ($perm[2]>1 && $DisplayRecords=='user' && !$_REQUEST['NoFilter_x'])){ // view owner only
		$x->QueryFrom.=', membership_userrecords';
		$x->QueryWhere="where `assignments`.`Id`=membership_userrecords.pkValue and membership_userrecords.tableName='assignments' and lcase(membership_userrecords.memberID)='".getLoggedMemberID()."'";
	}elseif($perm[2]==2 || ($perm[2]>2 && $DisplayRecords=='group' && !$_REQUEST['NoFilter_x'])){ // view group only
		$x->QueryFrom.=', membership_userrecords';
		$x->QueryWhere="where `assignments`.`Id`=membership_userrecords.pkValue and membership_userrecords.tableName='assignments' and membership_userrecords.groupID='".getLoggedGroupID()."'";
	}elseif($perm[2]==3){ // view all
		// no further action
	}elseif($perm[2]==0){ // view none
		$x->QueryFields = array("Not enough permissions" => "NEP");
		$x->QueryFrom = '`assignments`';
		$x->QueryWhere = '';
		$x->DefaultSortField = '';
	}
	// hook: assignments_init
	$render=TRUE;
	if(function_exists('assignments_init')){
		$args=array();
		$render=assignments_init($x, getMemberInfo(), $args);
	}

	if($render) $x->Render();

	// hook: assignments_header
	$headerCode='';
	if(function_exists('assignments_header')){
		$args=array();
		$headerCode=assignments_header($x->ContentType, getMemberInfo(), $args);
	}  
	if(!$headerCode){
		include_once("$currDir/header.php"); 
	}else{
		ob_start(); include_once("$currDir/header.php"); $dHeader=ob_get_contents(); ob_end_clean();
		echo str_replace('<%%HEADER%%>', $dHeader, $headerCode);
	}

	echo $x->HTML;
	// hook: assignments_footer
	$footerCode='';
	if(function_exists('assignments_footer')){
		$args=array();
		$footerCode=assignments_footer($x->ContentType, getMemberInfo(), $args);
	}  
	if(!$footerCode){
		include_once("$currDir/footer.php"); 
	}else{
		ob_start(); include_once("$currDir/footer.php"); $dFooter=ob_get_contents(); ob_end_clean();
		echo str_replace('<%%FOOTER%%>', $dFooter, $footerCode);
	}
?>