<?php

// Data functions (insert, update, delete, form) for table assignments

// This script and data application were generated by AppGini 5.62
// Download AppGini for free from https://bigprof.com/appgini/download/

function assignments_insert(){
	global $Translation;

	// mm: can member insert record?
	$arrPerm=getTablePermissions('assignments');
	if(!$arrPerm[1]){
		return false;
	}

	$data['ProjectId'] = makeSafe($_REQUEST['ProjectId']);
		if($data['ProjectId'] == empty_lookup_value){ $data['ProjectId'] = ''; }
	$data['ProjectDuration'] = makeSafe($_REQUEST['ProjectId']);
		if($data['ProjectDuration'] == empty_lookup_value){ $data['ProjectDuration'] = ''; }
	$data['ResourceId'] = makeSafe($_REQUEST['ResourceId']);
		if($data['ResourceId'] == empty_lookup_value){ $data['ResourceId'] = ''; }
	$data['Commitment'] = makeSafe($_REQUEST['Commitment']);
		if($data['Commitment'] == empty_lookup_value){ $data['Commitment'] = ''; }
	$data['StartDate'] = intval($_REQUEST['StartDateYear']) . '-' . intval($_REQUEST['StartDateMonth']) . '-' . intval($_REQUEST['StartDateDay']);
	$data['StartDate'] = parseMySQLDate($data['StartDate'], '');
	$data['EndDate'] = intval($_REQUEST['EndDateYear']) . '-' . intval($_REQUEST['EndDateMonth']) . '-' . intval($_REQUEST['EndDateDay']);
	$data['EndDate'] = parseMySQLDate($data['EndDate'], '');
	if($data['Commitment'] == '') $data['Commitment'] = "1.00";
	if($data['Commitment']== ''){
		echo StyleSheet() . "\n\n<div class=\"alert alert-danger\">" . $Translation['error:'] . " 'Commitment': " . $Translation['field not null'] . '<br><br>';
		echo '<a href="" onclick="history.go(-1); return false;">'.$Translation['< back'].'</a></div>';
		exit;
	}

	// hook: assignments_before_insert
	if(function_exists('assignments_before_insert')){
		$args=array();
		if(!assignments_before_insert($data, getMemberInfo(), $args)){ return false; }
	}

	$o = array('silentErrors' => true);
	sql('insert into `assignments` set       `ProjectId`=' . (($data['ProjectId'] !== '' && $data['ProjectId'] !== NULL) ? "'{$data['ProjectId']}'" : 'NULL') . ', `ProjectDuration`=' . (($data['ProjectDuration'] !== '' && $data['ProjectDuration'] !== NULL) ? "'{$data['ProjectDuration']}'" : 'NULL') . ', `ResourceId`=' . (($data['ResourceId'] !== '' && $data['ResourceId'] !== NULL) ? "'{$data['ResourceId']}'" : 'NULL') . ', `Commitment`=' . (($data['Commitment'] !== '' && $data['Commitment'] !== NULL) ? "'{$data['Commitment']}'" : 'NULL') . ', `StartDate`=' . (($data['StartDate'] !== '' && $data['StartDate'] !== NULL) ? "'{$data['StartDate']}'" : 'NULL') . ', `EndDate`=' . (($data['EndDate'] !== '' && $data['EndDate'] !== NULL) ? "'{$data['EndDate']}'" : 'NULL'), $o);
	if($o['error']!=''){
		echo $o['error'];
		echo "<a href=\"assignments_view.php?addNew_x=1\">{$Translation['< back']}</a>";
		exit;
	}

	$recID = db_insert_id(db_link());

	// hook: assignments_after_insert
	if(function_exists('assignments_after_insert')){
		$res = sql("select * from `assignments` where `Id`='" . makeSafe($recID, false) . "' limit 1", $eo);
		if($row = db_fetch_assoc($res)){
			$data = array_map('makeSafe', $row);
		}
		$data['selectedID'] = makeSafe($recID, false);
		$args=array();
		if(!assignments_after_insert($data, getMemberInfo(), $args)){ return $recID; }
	}

	// mm: save ownership data
	sql("insert ignore into membership_userrecords set tableName='assignments', pkValue='" . makeSafe($recID, false) . "', memberID='" . makeSafe(getLoggedMemberID(), false) . "', dateAdded='" . time() . "', dateUpdated='" . time() . "', groupID='" . getLoggedGroupID() . "'", $eo);

	return $recID;
}

function assignments_delete($selected_id, $AllowDeleteOfParents=false, $skipChecks=false){
	// insure referential integrity ...
	global $Translation;
	$selected_id=makeSafe($selected_id);

	// mm: can member delete record?
	$arrPerm=getTablePermissions('assignments');
	$ownerGroupID=sqlValue("select groupID from membership_userrecords where tableName='assignments' and pkValue='$selected_id'");
	$ownerMemberID=sqlValue("select lcase(memberID) from membership_userrecords where tableName='assignments' and pkValue='$selected_id'");
	if(($arrPerm[4]==1 && $ownerMemberID==getLoggedMemberID()) || ($arrPerm[4]==2 && $ownerGroupID==getLoggedGroupID()) || $arrPerm[4]==3){ // allow delete?
		// delete allowed, so continue ...
	}else{
		return $Translation['You don\'t have enough permissions to delete this record'];
	}

	// hook: assignments_before_delete
	if(function_exists('assignments_before_delete')){
		$args=array();
		if(!assignments_before_delete($selected_id, $skipChecks, getMemberInfo(), $args))
			return $Translation['Couldn\'t delete this record'];
	}

	sql("delete from `assignments` where `Id`='$selected_id'", $eo);

	// hook: assignments_after_delete
	if(function_exists('assignments_after_delete')){
		$args=array();
		assignments_after_delete($selected_id, getMemberInfo(), $args);
	}

	// mm: delete ownership data
	sql("delete from membership_userrecords where tableName='assignments' and pkValue='$selected_id'", $eo);
}

function assignments_update($selected_id){
	global $Translation;

	// mm: can member edit record?
	$arrPerm=getTablePermissions('assignments');
	$ownerGroupID=sqlValue("select groupID from membership_userrecords where tableName='assignments' and pkValue='".makeSafe($selected_id)."'");
	$ownerMemberID=sqlValue("select lcase(memberID) from membership_userrecords where tableName='assignments' and pkValue='".makeSafe($selected_id)."'");
	if(($arrPerm[3]==1 && $ownerMemberID==getLoggedMemberID()) || ($arrPerm[3]==2 && $ownerGroupID==getLoggedGroupID()) || $arrPerm[3]==3){ // allow update?
		// update allowed, so continue ...
	}else{
		return false;
	}

	$data['ProjectId'] = makeSafe($_REQUEST['ProjectId']);
		if($data['ProjectId'] == empty_lookup_value){ $data['ProjectId'] = ''; }
	$data['ProjectDuration'] = makeSafe($_REQUEST['ProjectId']);
		if($data['ProjectDuration'] == empty_lookup_value){ $data['ProjectDuration'] = ''; }
	$data['ResourceId'] = makeSafe($_REQUEST['ResourceId']);
		if($data['ResourceId'] == empty_lookup_value){ $data['ResourceId'] = ''; }
	$data['Commitment'] = makeSafe($_REQUEST['Commitment']);
		if($data['Commitment'] == empty_lookup_value){ $data['Commitment'] = ''; }
	if($data['Commitment']==''){
		echo StyleSheet() . "\n\n<div class=\"alert alert-danger\">{$Translation['error:']} 'Commitment': {$Translation['field not null']}<br><br>";
		echo '<a href="" onclick="history.go(-1); return false;">'.$Translation['< back'].'</a></div>';
		exit;
	}
	$data['StartDate'] = intval($_REQUEST['StartDateYear']) . '-' . intval($_REQUEST['StartDateMonth']) . '-' . intval($_REQUEST['StartDateDay']);
	$data['StartDate'] = parseMySQLDate($data['StartDate'], '');
	$data['EndDate'] = intval($_REQUEST['EndDateYear']) . '-' . intval($_REQUEST['EndDateMonth']) . '-' . intval($_REQUEST['EndDateDay']);
	$data['EndDate'] = parseMySQLDate($data['EndDate'], '');
	$data['selectedID']=makeSafe($selected_id);

	// hook: assignments_before_update
	if(function_exists('assignments_before_update')){
		$args=array();
		if(!assignments_before_update($data, getMemberInfo(), $args)){ return false; }
	}

	$o=array('silentErrors' => true);
	sql('update `assignments` set       `ProjectId`=' . (($data['ProjectId'] !== '' && $data['ProjectId'] !== NULL) ? "'{$data['ProjectId']}'" : 'NULL') . ', `ProjectDuration`=' . (($data['ProjectDuration'] !== '' && $data['ProjectDuration'] !== NULL) ? "'{$data['ProjectDuration']}'" : 'NULL') . ', `ResourceId`=' . (($data['ResourceId'] !== '' && $data['ResourceId'] !== NULL) ? "'{$data['ResourceId']}'" : 'NULL') . ', `Commitment`=' . (($data['Commitment'] !== '' && $data['Commitment'] !== NULL) ? "'{$data['Commitment']}'" : 'NULL') . ', `StartDate`=' . (($data['StartDate'] !== '' && $data['StartDate'] !== NULL) ? "'{$data['StartDate']}'" : 'NULL') . ', `EndDate`=' . (($data['EndDate'] !== '' && $data['EndDate'] !== NULL) ? "'{$data['EndDate']}'" : 'NULL') . " where `Id`='".makeSafe($selected_id)."'", $o);
	if($o['error']!=''){
		echo $o['error'];
		echo '<a href="assignments_view.php?SelectedID='.urlencode($selected_id)."\">{$Translation['< back']}</a>";
		exit;
	}


	// hook: assignments_after_update
	if(function_exists('assignments_after_update')){
		$res = sql("SELECT * FROM `assignments` WHERE `Id`='{$data['selectedID']}' LIMIT 1", $eo);
		if($row = db_fetch_assoc($res)){
			$data = array_map('makeSafe', $row);
		}
		$data['selectedID'] = $data['Id'];
		$args = array();
		if(!assignments_after_update($data, getMemberInfo(), $args)){ return; }
	}

	// mm: update ownership data
	sql("update membership_userrecords set dateUpdated='".time()."' where tableName='assignments' and pkValue='".makeSafe($selected_id)."'", $eo);

}

function assignments_form($selected_id = '', $AllowUpdate = 1, $AllowInsert = 1, $AllowDelete = 1, $ShowCancel = 0, $TemplateDV = '', $TemplateDVP = ''){
	// function to return an editable form for a table records
	// and fill it with data of record whose ID is $selected_id. If $selected_id
	// is empty, an empty form is shown, with only an 'Add New'
	// button displayed.

	global $Translation;

	// mm: get table permissions
	$arrPerm=getTablePermissions('assignments');
	if(!$arrPerm[1] && $selected_id==''){ return ''; }
	$AllowInsert = ($arrPerm[1] ? true : false);
	// print preview?
	$dvprint = false;
	if($selected_id && $_REQUEST['dvprint_x'] != ''){
		$dvprint = true;
	}

	$filterer_ProjectId = thisOr(undo_magic_quotes($_REQUEST['filterer_ProjectId']), '');
	$filterer_ResourceId = thisOr(undo_magic_quotes($_REQUEST['filterer_ResourceId']), '');

	// populate filterers, starting from children to grand-parents

	// unique random identifier
	$rnd1 = ($dvprint ? rand(1000000, 9999999) : '');
	// combobox: ProjectId
	$combo_ProjectId = new DataCombo;
	// combobox: ResourceId
	$combo_ResourceId = new DataCombo;
	// combobox: Commitment
	$combo_Commitment = new Combo;
	$combo_Commitment->ListType = 0;
	$combo_Commitment->MultipleSeparator = ', ';
	$combo_Commitment->ListBoxHeight = 10;
	$combo_Commitment->RadiosPerLine = 1;
	if(is_file(dirname(__FILE__).'/hooks/assignments.Commitment.csv')){
		$Commitment_data = addslashes(implode('', @file(dirname(__FILE__).'/hooks/assignments.Commitment.csv')));
		$combo_Commitment->ListItem = explode('||', entitiesToUTF8(convertLegacyOptions($Commitment_data)));
		$combo_Commitment->ListData = $combo_Commitment->ListItem;
	}else{
		$combo_Commitment->ListItem = explode('||', entitiesToUTF8(convertLegacyOptions("0.25;;0.50;;0.75;;1.00")));
		$combo_Commitment->ListData = $combo_Commitment->ListItem;
	}
	$combo_Commitment->SelectName = 'Commitment';
	$combo_Commitment->AllowNull = false;
	// combobox: StartDate
	$combo_StartDate = new DateCombo;
	$combo_StartDate->DateFormat = "dmy";
	$combo_StartDate->MinYear = 1900;
	$combo_StartDate->MaxYear = 2100;
	$combo_StartDate->DefaultDate = parseMySQLDate('', '');
	$combo_StartDate->MonthNames = $Translation['month names'];
	$combo_StartDate->NamePrefix = 'StartDate';
	// combobox: EndDate
	$combo_EndDate = new DateCombo;
	$combo_EndDate->DateFormat = "dmy";
	$combo_EndDate->MinYear = 1900;
	$combo_EndDate->MaxYear = 2100;
	$combo_EndDate->DefaultDate = parseMySQLDate('', '');
	$combo_EndDate->MonthNames = $Translation['month names'];
	$combo_EndDate->NamePrefix = 'EndDate';

	if($selected_id){
		// mm: check member permissions
		if(!$arrPerm[2]){
			return "";
		}
		// mm: who is the owner?
		$ownerGroupID=sqlValue("select groupID from membership_userrecords where tableName='assignments' and pkValue='".makeSafe($selected_id)."'");
		$ownerMemberID=sqlValue("select lcase(memberID) from membership_userrecords where tableName='assignments' and pkValue='".makeSafe($selected_id)."'");
		if($arrPerm[2]==1 && getLoggedMemberID()!=$ownerMemberID){
			return "";
		}
		if($arrPerm[2]==2 && getLoggedGroupID()!=$ownerGroupID){
			return "";
		}

		// can edit?
		if(($arrPerm[3]==1 && $ownerMemberID==getLoggedMemberID()) || ($arrPerm[3]==2 && $ownerGroupID==getLoggedGroupID()) || $arrPerm[3]==3){
			$AllowUpdate=1;
		}else{
			$AllowUpdate=0;
		}

		$res = sql("select * from `assignments` where `Id`='".makeSafe($selected_id)."'", $eo);
		if(!($row = db_fetch_array($res))){
			return error_message($Translation['No records found'], 'assignments_view.php', false);
		}
		$urow = $row; /* unsanitized data */
		$hc = new CI_Input();
		$row = $hc->xss_clean($row); /* sanitize data */
		$combo_ProjectId->SelectedData = $row['ProjectId'];
		$combo_ResourceId->SelectedData = $row['ResourceId'];
		$combo_Commitment->SelectedData = $row['Commitment'];
		$combo_StartDate->DefaultDate = $row['StartDate'];
		$combo_EndDate->DefaultDate = $row['EndDate'];
	}else{
		$combo_ProjectId->SelectedData = $filterer_ProjectId;
		$combo_ResourceId->SelectedData = $filterer_ResourceId;
		$combo_Commitment->SelectedText = ( $_REQUEST['FilterField'][1]=='5' && $_REQUEST['FilterOperator'][1]=='<=>' ? (get_magic_quotes_gpc() ? stripslashes($_REQUEST['FilterValue'][1]) : $_REQUEST['FilterValue'][1]) : "1.00");
	}
	$combo_ProjectId->HTML = '<span id="ProjectId-container' . $rnd1 . '"></span><input type="hidden" name="ProjectId" id="ProjectId' . $rnd1 . '" value="' . html_attr($combo_ProjectId->SelectedData) . '">';
	$combo_ProjectId->MatchText = '<span id="ProjectId-container-readonly' . $rnd1 . '"></span><input type="hidden" name="ProjectId" id="ProjectId' . $rnd1 . '" value="' . html_attr($combo_ProjectId->SelectedData) . '">';
	$combo_ResourceId->HTML = '<span id="ResourceId-container' . $rnd1 . '"></span><input type="hidden" name="ResourceId" id="ResourceId' . $rnd1 . '" value="' . html_attr($combo_ResourceId->SelectedData) . '">';
	$combo_ResourceId->MatchText = '<span id="ResourceId-container-readonly' . $rnd1 . '"></span><input type="hidden" name="ResourceId" id="ResourceId' . $rnd1 . '" value="' . html_attr($combo_ResourceId->SelectedData) . '">';
	$combo_Commitment->Render();

	ob_start();
	?>

	<script>
		// initial lookup values
		AppGini.current_ProjectId__RAND__ = { text: "", value: "<?php echo addslashes($selected_id ? $urow['ProjectId'] : $filterer_ProjectId); ?>"};
		AppGini.current_ResourceId__RAND__ = { text: "", value: "<?php echo addslashes($selected_id ? $urow['ResourceId'] : $filterer_ResourceId); ?>"};

		jQuery(function() {
			setTimeout(function(){
				if(typeof(ProjectId_reload__RAND__) == 'function') ProjectId_reload__RAND__();
				if(typeof(ResourceId_reload__RAND__) == 'function') ResourceId_reload__RAND__();
			}, 10); /* we need to slightly delay client-side execution of the above code to allow AppGini.ajaxCache to work */
		});
		function ProjectId_reload__RAND__(){
		<?php if(($AllowUpdate || $AllowInsert) && !$dvprint){ ?>

			$j("#ProjectId-container__RAND__").select2({
				/* initial default value */
				initSelection: function(e, c){
					$j.ajax({
						url: 'ajax_combo.php',
						dataType: 'json',
						data: { id: AppGini.current_ProjectId__RAND__.value, t: 'assignments', f: 'ProjectId' },
						success: function(resp){
							c({
								id: resp.results[0].id,
								text: resp.results[0].text
							});
							$j('[name="ProjectId"]').val(resp.results[0].id);
							$j('[id=ProjectId-container-readonly__RAND__]').html('<span id="ProjectId-match-text">' + resp.results[0].text + '</span>');
							if(resp.results[0].id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=projects_view_parent]').hide(); }else{ $j('.btn[id=projects_view_parent]').show(); }


							if(typeof(ProjectId_update_autofills__RAND__) == 'function') ProjectId_update_autofills__RAND__();
						}
					});
				},
				width: ($j('fieldset .col-xs-11').width() - select2_max_width_decrement()) + 'px',
				formatNoMatches: function(term){ return '<?php echo addslashes($Translation['No matches found!']); ?>'; },
				minimumResultsForSearch: 10,
				loadMorePadding: 200,
				ajax: {
					url: 'ajax_combo.php',
					dataType: 'json',
					cache: true,
					data: function(term, page){ return { s: term, p: page, t: 'assignments', f: 'ProjectId' }; },
					results: function(resp, page){ return resp; }
				},
				escapeMarkup: function(str){ return str; }
			}).on('change', function(e){
				AppGini.current_ProjectId__RAND__.value = e.added.id;
				AppGini.current_ProjectId__RAND__.text = e.added.text;
				$j('[name="ProjectId"]').val(e.added.id);
				if(e.added.id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=projects_view_parent]').hide(); }else{ $j('.btn[id=projects_view_parent]').show(); }


				if(typeof(ProjectId_update_autofills__RAND__) == 'function') ProjectId_update_autofills__RAND__();
			});

			if(!$j("#ProjectId-container__RAND__").length){
				$j.ajax({
					url: 'ajax_combo.php',
					dataType: 'json',
					data: { id: AppGini.current_ProjectId__RAND__.value, t: 'assignments', f: 'ProjectId' },
					success: function(resp){
						$j('[name="ProjectId"]').val(resp.results[0].id);
						$j('[id=ProjectId-container-readonly__RAND__]').html('<span id="ProjectId-match-text">' + resp.results[0].text + '</span>');
						if(resp.results[0].id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=projects_view_parent]').hide(); }else{ $j('.btn[id=projects_view_parent]').show(); }

						if(typeof(ProjectId_update_autofills__RAND__) == 'function') ProjectId_update_autofills__RAND__();
					}
				});
			}

		<?php }else{ ?>

			$j.ajax({
				url: 'ajax_combo.php',
				dataType: 'json',
				data: { id: AppGini.current_ProjectId__RAND__.value, t: 'assignments', f: 'ProjectId' },
				success: function(resp){
					$j('[id=ProjectId-container__RAND__], [id=ProjectId-container-readonly__RAND__]').html('<span id="ProjectId-match-text">' + resp.results[0].text + '</span>');
					if(resp.results[0].id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=projects_view_parent]').hide(); }else{ $j('.btn[id=projects_view_parent]').show(); }

					if(typeof(ProjectId_update_autofills__RAND__) == 'function') ProjectId_update_autofills__RAND__();
				}
			});
		<?php } ?>

		}
		function ResourceId_reload__RAND__(){
		<?php if(($AllowUpdate || $AllowInsert) && !$dvprint){ ?>

			$j("#ResourceId-container__RAND__").select2({
				/* initial default value */
				initSelection: function(e, c){
					$j.ajax({
						url: 'ajax_combo.php',
						dataType: 'json',
						data: { id: AppGini.current_ResourceId__RAND__.value, t: 'assignments', f: 'ResourceId' },
						success: function(resp){
							c({
								id: resp.results[0].id,
								text: resp.results[0].text
							});
							$j('[name="ResourceId"]').val(resp.results[0].id);
							$j('[id=ResourceId-container-readonly__RAND__]').html('<span id="ResourceId-match-text">' + resp.results[0].text + '</span>');
							if(resp.results[0].id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=resources_view_parent]').hide(); }else{ $j('.btn[id=resources_view_parent]').show(); }


							if(typeof(ResourceId_update_autofills__RAND__) == 'function') ResourceId_update_autofills__RAND__();
						}
					});
				},
				width: ($j('fieldset .col-xs-11').width() - select2_max_width_decrement()) + 'px',
				formatNoMatches: function(term){ return '<?php echo addslashes($Translation['No matches found!']); ?>'; },
				minimumResultsForSearch: 10,
				loadMorePadding: 200,
				ajax: {
					url: 'ajax_combo.php',
					dataType: 'json',
					cache: true,
					data: function(term, page){ return { s: term, p: page, t: 'assignments', f: 'ResourceId' }; },
					results: function(resp, page){ return resp; }
				},
				escapeMarkup: function(str){ return str; }
			}).on('change', function(e){
				AppGini.current_ResourceId__RAND__.value = e.added.id;
				AppGini.current_ResourceId__RAND__.text = e.added.text;
				$j('[name="ResourceId"]').val(e.added.id);
				if(e.added.id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=resources_view_parent]').hide(); }else{ $j('.btn[id=resources_view_parent]').show(); }


				if(typeof(ResourceId_update_autofills__RAND__) == 'function') ResourceId_update_autofills__RAND__();
			});

			if(!$j("#ResourceId-container__RAND__").length){
				$j.ajax({
					url: 'ajax_combo.php',
					dataType: 'json',
					data: { id: AppGini.current_ResourceId__RAND__.value, t: 'assignments', f: 'ResourceId' },
					success: function(resp){
						$j('[name="ResourceId"]').val(resp.results[0].id);
						$j('[id=ResourceId-container-readonly__RAND__]').html('<span id="ResourceId-match-text">' + resp.results[0].text + '</span>');
						if(resp.results[0].id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=resources_view_parent]').hide(); }else{ $j('.btn[id=resources_view_parent]').show(); }

						if(typeof(ResourceId_update_autofills__RAND__) == 'function') ResourceId_update_autofills__RAND__();
					}
				});
			}

		<?php }else{ ?>

			$j.ajax({
				url: 'ajax_combo.php',
				dataType: 'json',
				data: { id: AppGini.current_ResourceId__RAND__.value, t: 'assignments', f: 'ResourceId' },
				success: function(resp){
					$j('[id=ResourceId-container__RAND__], [id=ResourceId-container-readonly__RAND__]').html('<span id="ResourceId-match-text">' + resp.results[0].text + '</span>');
					if(resp.results[0].id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=resources_view_parent]').hide(); }else{ $j('.btn[id=resources_view_parent]').show(); }

					if(typeof(ResourceId_update_autofills__RAND__) == 'function') ResourceId_update_autofills__RAND__();
				}
			});
		<?php } ?>

		}
	</script>
	<?php

	$lookups = str_replace('__RAND__', $rnd1, ob_get_contents());
	ob_end_clean();


	// code for template based detail view forms

	// open the detail view template
	if($dvprint){
		$template_file = is_file("./{$TemplateDVP}") ? "./{$TemplateDVP}" : './templates/assignments_templateDVP.html';
		$templateCode = @file_get_contents($template_file);
	}else{
		$template_file = is_file("./{$TemplateDV}") ? "./{$TemplateDV}" : './templates/assignments_templateDV.html';
		$templateCode = @file_get_contents($template_file);
	}

	// process form title
	$templateCode = str_replace('<%%DETAIL_VIEW_TITLE%%>', 'Detail View', $templateCode);
	$templateCode = str_replace('<%%RND1%%>', $rnd1, $templateCode);
	$templateCode = str_replace('<%%EMBEDDED%%>', ($_REQUEST['Embedded'] ? 'Embedded=1' : ''), $templateCode);
	// process buttons
	if($AllowInsert){
		if(!$selected_id) $templateCode=str_replace('<%%INSERT_BUTTON%%>', '<button type="submit" class="btn btn-success" id="insert" name="insert_x" value="1" onclick="return assignments_validateData();"><i class="glyphicon glyphicon-plus-sign"></i> ' . $Translation['Save New'] . '</button>', $templateCode);
		$templateCode=str_replace('<%%INSERT_BUTTON%%>', '<button type="submit" class="btn btn-default" id="insert" name="insert_x" value="1" onclick="return assignments_validateData();"><i class="glyphicon glyphicon-plus-sign"></i> ' . $Translation['Save As Copy'] . '</button>', $templateCode);
	}else{
		$templateCode=str_replace('<%%INSERT_BUTTON%%>', '', $templateCode);
	}

	// 'Back' button action
	if($_REQUEST['Embedded']){
		$backAction = 'window.parent.jQuery(\'.modal\').modal(\'hide\'); return false;';
	}else{
		$backAction = '$$(\'form\')[0].writeAttribute(\'novalidate\', \'novalidate\'); document.myform.reset(); return true;';
	}

	if($selected_id){
		if(!$_REQUEST['Embedded']) $templateCode=str_replace('<%%DVPRINT_BUTTON%%>', '<button type="submit" class="btn btn-default" id="dvprint" name="dvprint_x" value="1" onclick="$$(\'form\')[0].writeAttribute(\'novalidate\', \'novalidate\'); document.myform.reset(); return true;" title="' . html_attr($Translation['Print Preview']) . '"><i class="glyphicon glyphicon-print"></i> ' . $Translation['Print Preview'] . '</button>', $templateCode);
		if($AllowUpdate){
			$templateCode=str_replace('<%%UPDATE_BUTTON%%>', '<button type="submit" class="btn btn-success btn-lg" id="update" name="update_x" value="1" onclick="return assignments_validateData();" title="' . html_attr($Translation['Save Changes']) . '"><i class="glyphicon glyphicon-ok"></i> ' . $Translation['Save Changes'] . '</button>', $templateCode);
		}else{
			$templateCode=str_replace('<%%UPDATE_BUTTON%%>', '', $templateCode);
		}
		if(($arrPerm[4]==1 && $ownerMemberID==getLoggedMemberID()) || ($arrPerm[4]==2 && $ownerGroupID==getLoggedGroupID()) || $arrPerm[4]==3){ // allow delete?
			$templateCode=str_replace('<%%DELETE_BUTTON%%>', '<button type="submit" class="btn btn-danger" id="delete" name="delete_x" value="1" onclick="return confirm(\'' . $Translation['are you sure?'] . '\');" title="' . html_attr($Translation['Delete']) . '"><i class="glyphicon glyphicon-trash"></i> ' . $Translation['Delete'] . '</button>', $templateCode);
		}else{
			$templateCode=str_replace('<%%DELETE_BUTTON%%>', '', $templateCode);
		}
		$templateCode=str_replace('<%%DESELECT_BUTTON%%>', '<button type="submit" class="btn btn-default" id="deselect" name="deselect_x" value="1" onclick="' . $backAction . '" title="' . html_attr($Translation['Back']) . '"><i class="glyphicon glyphicon-chevron-left"></i> ' . $Translation['Back'] . '</button>', $templateCode);
	}else{
		$templateCode=str_replace('<%%UPDATE_BUTTON%%>', '', $templateCode);
		$templateCode=str_replace('<%%DELETE_BUTTON%%>', '', $templateCode);
		$templateCode=str_replace('<%%DESELECT_BUTTON%%>', ($ShowCancel ? '<button type="submit" class="btn btn-default" id="deselect" name="deselect_x" value="1" onclick="' . $backAction . '" title="' . html_attr($Translation['Back']) . '"><i class="glyphicon glyphicon-chevron-left"></i> ' . $Translation['Back'] . '</button>' : ''), $templateCode);
	}

	// set records to read only if user can't insert new records and can't edit current record
	if(($selected_id && !$AllowUpdate && !$AllowInsert) || (!$selected_id && !$AllowInsert)){
		$jsReadOnly .= "\tjQuery('#ProjectId').prop('disabled', true).css({ color: '#555', backgroundColor: '#fff' });\n";
		$jsReadOnly .= "\tjQuery('#ProjectId_caption').prop('disabled', true).css({ color: '#555', backgroundColor: 'white' });\n";
		$jsReadOnly .= "\tjQuery('#ResourceId').prop('disabled', true).css({ color: '#555', backgroundColor: '#fff' });\n";
		$jsReadOnly .= "\tjQuery('#ResourceId_caption').prop('disabled', true).css({ color: '#555', backgroundColor: 'white' });\n";
		$jsReadOnly .= "\tjQuery('#Commitment').replaceWith('<div class=\"form-control-static\" id=\"Commitment\">' + (jQuery('#Commitment').val() || '') + '</div>'); jQuery('#Commitment-multi-selection-help').hide();\n";
		$jsReadOnly .= "\tjQuery('#StartDate').prop('readonly', true);\n";
		$jsReadOnly .= "\tjQuery('#StartDateDay, #StartDateMonth, #StartDateYear').prop('disabled', true).css({ color: '#555', backgroundColor: '#fff' });\n";
		$jsReadOnly .= "\tjQuery('#EndDate').prop('readonly', true);\n";
		$jsReadOnly .= "\tjQuery('#EndDateDay, #EndDateMonth, #EndDateYear').prop('disabled', true).css({ color: '#555', backgroundColor: '#fff' });\n";
		$jsReadOnly .= "\tjQuery('.select2-container').hide();\n";

		$noUploads = true;
	}elseif($AllowInsert){
		$jsEditable .= "\tjQuery('form').eq(0).data('already_changed', true);"; // temporarily disable form change handler
			$jsEditable .= "\tjQuery('form').eq(0).data('already_changed', false);"; // re-enable form change handler
	}

	// process combos
	$templateCode=str_replace('<%%COMBO(ProjectId)%%>', $combo_ProjectId->HTML, $templateCode);
	$templateCode=str_replace('<%%COMBOTEXT(ProjectId)%%>', $combo_ProjectId->MatchText, $templateCode);
	$templateCode=str_replace('<%%URLCOMBOTEXT(ProjectId)%%>', urlencode($combo_ProjectId->MatchText), $templateCode);
	$templateCode=str_replace('<%%COMBO(ResourceId)%%>', $combo_ResourceId->HTML, $templateCode);
	$templateCode=str_replace('<%%COMBOTEXT(ResourceId)%%>', $combo_ResourceId->MatchText, $templateCode);
	$templateCode=str_replace('<%%URLCOMBOTEXT(ResourceId)%%>', urlencode($combo_ResourceId->MatchText), $templateCode);
	$templateCode=str_replace('<%%COMBO(Commitment)%%>', $combo_Commitment->HTML, $templateCode);
	$templateCode=str_replace('<%%COMBOTEXT(Commitment)%%>', $combo_Commitment->SelectedData, $templateCode);
	$templateCode=str_replace('<%%COMBO(StartDate)%%>', ($selected_id && !$arrPerm[3] ? '<div class="form-control-static">' . $combo_StartDate->GetHTML(true) . '</div>' : $combo_StartDate->GetHTML()), $templateCode);
	$templateCode=str_replace('<%%COMBOTEXT(StartDate)%%>', $combo_StartDate->GetHTML(true), $templateCode);
	$templateCode=str_replace('<%%COMBO(EndDate)%%>', ($selected_id && !$arrPerm[3] ? '<div class="form-control-static">' . $combo_EndDate->GetHTML(true) . '</div>' : $combo_EndDate->GetHTML()), $templateCode);
	$templateCode=str_replace('<%%COMBOTEXT(EndDate)%%>', $combo_EndDate->GetHTML(true), $templateCode);

	/* lookup fields array: 'lookup field name' => array('parent table name', 'lookup field caption') */
	$lookup_fields = array(  'ProjectId' => array('projects', 'Project'), 'ResourceId' => array('resources', 'Resource'));
	foreach($lookup_fields as $luf => $ptfc){
		$pt_perm = getTablePermissions($ptfc[0]);

		// process foreign key links
		if($pt_perm['view'] || $pt_perm['edit']){
			$templateCode = str_replace("<%%PLINK({$luf})%%>", '<button type="button" class="btn btn-default view_parent hspacer-md" id="' . $ptfc[0] . '_view_parent" title="' . html_attr($Translation['View'] . ' ' . $ptfc[1]) . '"><i class="glyphicon glyphicon-eye-open"></i></button>', $templateCode);
		}

		// if user has insert permission to parent table of a lookup field, put an add new button
		if($pt_perm['insert'] && !$_REQUEST['Embedded']){
			$templateCode = str_replace("<%%ADDNEW({$ptfc[0]})%%>", '<button type="button" class="btn btn-success add_new_parent hspacer-md" id="' . $ptfc[0] . '_add_new" title="' . html_attr($Translation['Add New'] . ' ' . $ptfc[1]) . '"><i class="glyphicon glyphicon-plus-sign"></i></button>', $templateCode);
		}
	}

	// process images
	$templateCode=str_replace('<%%UPLOADFILE(Id)%%>', '', $templateCode);
	$templateCode=str_replace('<%%UPLOADFILE(ProjectId)%%>', '', $templateCode);
	$templateCode=str_replace('<%%UPLOADFILE(ResourceId)%%>', '', $templateCode);
	$templateCode=str_replace('<%%UPLOADFILE(Commitment)%%>', '', $templateCode);
	$templateCode=str_replace('<%%UPLOADFILE(StartDate)%%>', '', $templateCode);
	$templateCode=str_replace('<%%UPLOADFILE(EndDate)%%>', '', $templateCode);

	// process values
	if($selected_id){
		$templateCode=str_replace('<%%VALUE(Id)%%>', html_attr($row['Id']), $templateCode);
		$templateCode=str_replace('<%%URLVALUE(Id)%%>', urlencode($urow['Id']), $templateCode);
		$templateCode=str_replace('<%%VALUE(ProjectId)%%>', html_attr($row['ProjectId']), $templateCode);
		$templateCode=str_replace('<%%URLVALUE(ProjectId)%%>', urlencode($urow['ProjectId']), $templateCode);
		$templateCode=str_replace('<%%VALUE(ResourceId)%%>', html_attr($row['ResourceId']), $templateCode);
		$templateCode=str_replace('<%%URLVALUE(ResourceId)%%>', urlencode($urow['ResourceId']), $templateCode);
		$templateCode=str_replace('<%%VALUE(Commitment)%%>', html_attr($row['Commitment']), $templateCode);
		$templateCode=str_replace('<%%URLVALUE(Commitment)%%>', urlencode($urow['Commitment']), $templateCode);
		$templateCode=str_replace('<%%VALUE(StartDate)%%>', @date('d/m/Y', @strtotime(html_attr($row['StartDate']))), $templateCode);
		$templateCode=str_replace('<%%URLVALUE(StartDate)%%>', urlencode(@date('d/m/Y', @strtotime(html_attr($urow['StartDate'])))), $templateCode);
		$templateCode=str_replace('<%%VALUE(EndDate)%%>', @date('d/m/Y', @strtotime(html_attr($row['EndDate']))), $templateCode);
		$templateCode=str_replace('<%%URLVALUE(EndDate)%%>', urlencode(@date('d/m/Y', @strtotime(html_attr($urow['EndDate'])))), $templateCode);
	}else{
		$templateCode=str_replace('<%%VALUE(Id)%%>', '', $templateCode);
		$templateCode=str_replace('<%%URLVALUE(Id)%%>', urlencode(''), $templateCode);
		$templateCode=str_replace('<%%VALUE(ProjectId)%%>', '', $templateCode);
		$templateCode=str_replace('<%%URLVALUE(ProjectId)%%>', urlencode(''), $templateCode);
		$templateCode=str_replace('<%%VALUE(ResourceId)%%>', '', $templateCode);
		$templateCode=str_replace('<%%URLVALUE(ResourceId)%%>', urlencode(''), $templateCode);
		$templateCode=str_replace('<%%VALUE(Commitment)%%>', '1.00', $templateCode);
		$templateCode=str_replace('<%%URLVALUE(Commitment)%%>', urlencode('1.00'), $templateCode);
		$templateCode=str_replace('<%%VALUE(StartDate)%%>', '', $templateCode);
		$templateCode=str_replace('<%%URLVALUE(StartDate)%%>', urlencode(''), $templateCode);
		$templateCode=str_replace('<%%VALUE(EndDate)%%>', '', $templateCode);
		$templateCode=str_replace('<%%URLVALUE(EndDate)%%>', urlencode(''), $templateCode);
	}

	// process translations
	foreach($Translation as $symbol=>$trans){
		$templateCode=str_replace("<%%TRANSLATION($symbol)%%>", $trans, $templateCode);
	}

	// clear scrap
	$templateCode=str_replace('<%%', '<!-- ', $templateCode);
	$templateCode=str_replace('%%>', ' -->', $templateCode);

	// hide links to inaccessible tables
	if($_REQUEST['dvprint_x'] == ''){
		$templateCode .= "\n\n<script>\$j(function(){\n";
		$arrTables = getTableList();
		foreach($arrTables as $name => $caption){
			$templateCode .= "\t\$j('#{$name}_link').removeClass('hidden');\n";
			$templateCode .= "\t\$j('#xs_{$name}_link').removeClass('hidden');\n";
		}

		$templateCode .= $jsReadOnly;
		$templateCode .= $jsEditable;

		if(!$selected_id){
		}

		$templateCode.="\n});</script>\n";
	}

	// ajaxed auto-fill fields
	$templateCode .= '<script>';
	$templateCode .= '$j(function() {';

	$templateCode .= "\tProjectId_update_autofills$rnd1 = function(){\n";
	$templateCode .= "\t\t\$j.ajax({\n";
	if($dvprint){
		$templateCode .= "\t\t\turl: 'assignments_autofill.php?rnd1=$rnd1&mfk=ProjectId&id=' + encodeURIComponent('".addslashes($row['ProjectId'])."'),\n";
		$templateCode .= "\t\t\tcontentType: 'application/x-www-form-urlencoded; charset=" . datalist_db_encoding . "', type: 'GET'\n";
	}else{
		$templateCode .= "\t\t\turl: 'assignments_autofill.php?rnd1=$rnd1&mfk=ProjectId&id=' + encodeURIComponent(AppGini.current_ProjectId{$rnd1}.value),\n";
		$templateCode .= "\t\t\tcontentType: 'application/x-www-form-urlencoded; charset=" . datalist_db_encoding . "', type: 'GET', beforeSend: function(){ \$j('#ProjectId$rnd1').prop('disabled', true); \$j('#ProjectIdLoading').html('<img src=loading.gif align=top>'); }, complete: function(){".(($arrPerm[1] || (($arrPerm[3] == 1 && $ownerMemberID == getLoggedMemberID()) || ($arrPerm[3] == 2 && $ownerGroupID == getLoggedGroupID()) || $arrPerm[3] == 3)) ? "\$j('#ProjectId$rnd1').prop('disabled', false); " : "\$j('#ProjectId$rnd1').prop('disabled', true); ")."\$j('#ProjectIdLoading').html('');}\n";
	}
	$templateCode.="\t\t});\n";
	$templateCode.="\t};\n";
	if(!$dvprint) $templateCode.="\tif(\$j('#ProjectId_caption').length) \$j('#ProjectId_caption').click(function(){ ProjectId_update_autofills$rnd1(); });\n";


	$templateCode.="});";
	$templateCode.="</script>";
	$templateCode .= $lookups;

	// handle enforced parent values for read-only lookup fields

	// don't include blank images in lightbox gallery
	$templateCode = preg_replace('/blank.gif" data-lightbox=".*?"/', 'blank.gif"', $templateCode);

	// don't display empty email links
	$templateCode=preg_replace('/<a .*?href="mailto:".*?<\/a>/', '', $templateCode);

	/* default field values */
	$rdata = $jdata = get_defaults('assignments');
	if($selected_id){
		$jdata = get_joined_record('assignments', $selected_id);
		$rdata = $row;
	}
	$cache_data = array(
		'rdata' => array_map('nl2br', array_map('addslashes', $rdata)),
		'jdata' => array_map('nl2br', array_map('addslashes', $jdata)),
	);
	$templateCode .= loadView('assignments-ajax-cache', $cache_data);

	// hook: assignments_dv
	if(function_exists('assignments_dv')){
		$args=array();
		assignments_dv(($selected_id ? $selected_id : FALSE), getMemberInfo(), $templateCode, $args);
	}

	return $templateCode;
}
?>