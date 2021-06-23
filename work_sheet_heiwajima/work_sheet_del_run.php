<?php
	/* 各データの受け取り */
	$year = $_POST['year'];
	$mon = $_POST['mon'];
	$day = $_POST['day'];
	$sheet_file = $_POST['sheet_file'];
	$modi_no = $_POST['modi_no'];
	$del_check = $_POST['del_check'];

	$mon0 = $mon;
	$day0 = $day;
	if($mon0 < 10){$mon0 = "0$mon";}
	if($day0 < 10){$day0 = "0$day";}
	$day_file = "$year" . "$mon0" . "$day0" . ".dat";

	/* 該当データの呼び出し */
	setlocale(LC_ALL,'ja_JP.UTF-8');
	$fp_sheet = fopen("log/$sheet_file", "r");
	$sheet_para = array();
	$sheet_i = 0;
	while($ret_csv = fgetcsv($fp_sheet)){
		for($col = 0; $col < count($ret_csv); $col++){
			$sheet_para[$sheet_i][$col] = $ret_csv[$col];
		}
		$sheet_i++;
	}

	/* 曜日の取得 */
	date_default_timezone_set('Asia/Tokyo');
	$wday = date("w", mktime(0, 0, 0, $mon, $day, $year));
	if($wday == 0){$wday = "日";}
	elseif($wday == 1){$wday = "月";}
	elseif($wday == 2){$wday = "火";}
	elseif($wday == 3){$wday = "水";}
	elseif($wday == 4){$wday = "木";}
	elseif($wday == 5){$wday = "金";}
	elseif($wday == 6){$wday = "土";}

	/* サーバー時刻取得 */
	date_default_timezone_set('Asia/Tokyo');
	$date = date("Y年n月j日G時i分");

	/* 作業一覧ファイルの修正 */
	/* 作業一覧データの取得 */
	setlocale(LC_ALL,'ja_JP.UTF-8');
	$fp_day = fopen("log/$day_file", "r");
	$day_para = array();
	$day_i = 0;
	while($day_csv = fgetcsv($fp_day)){
		for($col = 0; $col < count($day_csv); $col++){
			$day_para[$day_i][$col] = $day_csv[$col];
		}
		$day_i++;
	}
	fclose($fp_day);
	/* 全データ削除の場合 */
	$del_cnt = 1;
	if($modi_no == 0 and $del_check == "checked"){
		while($del_cnt < $sheet_i){
			$lift_check = $sheet_para[$del_cnt][3];
			$file_check = $lift_check + 1;
			$icon_check = $lift_check + 2;
			for($cnt_i = $sheet_para[$del_cnt][1]; $cnt_i < $sheet_para[$del_cnt][2]; $cnt_i++){
				$day_para[$lift_check][$cnt_i] = "open";
				$day_para[$file_check][$cnt_i] = "";
				$day_para[$icon_check][$cnt_i] = "";
			}
			$del_cnt++;
		}
		/* 作業内容データのファイル名を変更 */
		rename("log/$sheet_file","log/del_$sheet_file");
		/* 作業一覧データを上書き保存 */
		$fp_put_d = fopen("log/$day_file", "w");
		foreach($day_para as $fields) fputcsv($fp_put_d, $fields);
		fclose($fp_put_d);
		/* 最終更新日時の更新 */
		file_put_contents("ymd.dat","$date");
		/* 使用ログの保存 */
		$u_id = $_SERVER['REMOTE_USER']; /* ユーザーIDを取得 */
		$u_agent = $_SERVER['HTTP_USER_AGENT']; /* 使用環境を取得 */
		$u_referer = $_SERVER['HTTP_REFERER']; /* リファラー情報を取得 */
		$u_date = date("Y/m/d(D) H:i:s"); /* 日時を取得 */
		$u_todo = "対象作業情報を全削除"; /* 変更内容 */
		$u_log = "$u_date,$u_id,$day_file,$sheet_file,$u_todo,$u_agent,$u_referer" . "\n"; /* 保存ログデータ */
		$fp_log = fopen("log/work/work_access.log", "a");
		fwrite($fp_log, "$u_log");
		fclose($fp_log);
	}
	/* 作業単位で削除の場合 */
	if($modi_no != 0 and $del_check == "checked"){
		$lift_check = $sheet_para[$modi_no][3];
		$file_check = $lift_check + 1;
		$icon_check = $lift_check + 2;
		for($cnt_i = $sheet_para[$modi_no][1]; $cnt_i < $sheet_para[$modi_no][2]; $cnt_i++){
			$day_para[$lift_check][$cnt_i] = "open";
			$day_para[$file_check][$cnt_i] = "";
			$day_para[$icon_check][$cnt_i] = "";
		}
		/* 作業内容を上書き保存 */
		$sheet_para[$modi_no][0] = "del";
		$sheet_para[$modi_no][9] = $date;
		$fp_put_s = fopen("log/$sheet_file", "w");
		foreach($sheet_para as $fields) fputcsv($fp_put_s, $fields);
		fclose($fp_put_s);
		/* 作業一覧データを上書き保存 */
		$fp_put_d = fopen("log/$day_file", "w");
		foreach($day_para as $fields) fputcsv($fp_put_d, $fields);
		fclose($fp_put_d);
		/* 最終更新日時の更新 */
		file_put_contents("ymd.dat","$date");
		/* 使用ログの保存 */
		$u_id = $_SERVER['REMOTE_USER']; /* ユーザーIDを取得 */
		$u_agent = $_SERVER['HTTP_USER_AGENT']; /* 使用環境を取得 */
		$u_referer = $_SERVER['HTTP_REFERER']; /* リファラー情報を取得 */
		$u_date = date("Y/m/d(D) H:i:s"); /* 日時を取得 */
		$u_todo = "対象作業情報の一部を削除"; /* 変更内容 */
		$u_log = "$u_date,$u_id,$day_file,$sheet_file,$u_todo,$u_agent,$u_referer" . "\n"; /* 保存ログデータ */
		$fp_log = fopen("log/work/work_access.log", "a");
		fwrite($fp_log, "$u_log");
		fclose($fp_log);
	}
?>
<html>
<?php /* キャッシュの無効化 */
	header( 'Expires: Sat, 26 Jul 1997 05:00:00 GMT' );
	header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
	// HTTP/1.1
	header( 'Cache-Control: nostore, no-cache, must-revalidate' );
	header( 'Cache-Control: post-check=0, pre-check=0', FALSE );
	// HTTP/1.0
	header( 'Pragma: no-chache' );
?>
	<head>
		<title>作業予約管理システム</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="Pragma" content="no-cache">
		<meta http-equiv="cache-control" content="no-cache">
		<meta http-equiv="expires" content="0">
		<link rel="stylesheet" href="https://www.kts-web.com/work_sheet_heiwajima/work_sheet.css" type="text/css">
	</head>
	<body bgcolor="#FFFFFF" text="#000000">
		<table class="title">
			<tr>
				<td>KTS作業予約管理システム<span class="shop_title">（KTS平和島店）</span></td>
			</tr>
		</table>
		<table class="day_work_comm">
			<tr>
				<td class="day_work_date" width="50%"><?php print "$year"; ?>年<?php print "$mon"; ?>月<?php print "$day"; ?>日（<?php print "$wday"; ?>）</td>
				<td>
					<form method="post" action="day_work.php">
						<input type="hidden" name="year" value="<?php print "$year"; ?>">
						<input type="hidden" name="mon" value="<?php print "$mon"; ?>">
						<input type="hidden" name="day" value="<?php print "$day"; ?>">
						<input type="submit" value="作業一覧ページへ戻る">
					</form>
				</td>
				<td><div><a href="index.php">月間表示画面へ戻る</a></div></td>
			</tr>
		</table>
		<div class="space10px"></div>
<?php if($del_check != "checked"):?>
		<div class="user_check">確認用チェックBOXがチェックされていません。</div>
		<div class="space10px"></div>
		<table class="modi">
			<tr>
				<td>
					<form method="post" action="work_sheet_del.php">
						<input type="hidden" name="year" value="<?php print "$year"; ?>">
						<input type="hidden" name="mon" value="<?php print "$mon"; ?>">
						<input type="hidden" name="day" value="<?php print "$day"; ?>">
						<input type="hidden" name="sheet_file" value="<?php print "$sheet_file"; ?>">
						<input type="hidden" name="modi_no" value="<?php print "$modi_no"; ?>">
						<input type="submit" value="前のページに戻る">
					</form>
				</td>
			</tr>
		</table>
		<div class="space10px"></div>
		<div class="footer">Copyright (C) Kind Techno Structure Corporation. All Rights Reserved.</div>
	</body>
</html>
<?php endif;?>
<?php if($del_check != "checked"){exit();} ?>						
<?php if($del_check == "checked"):?>
		<div class="user_check">データを削除しました。</div>
		<table class="modi">
			<tr>
				<td>
					<?php if($modi_no == 0){print "<form method=post action=day_work.php>";} ?>
					<?php if($modi_no != 0){print "<form method=post action=work_sheet.php>";} ?>
						<input type="hidden" name="year" value="<?php print "$year"; ?>">
						<input type="hidden" name="mon" value="<?php print "$mon"; ?>">
						<input type="hidden" name="day" value="<?php print "$day"; ?>">
						<?php if($modi_no != 0){print "<input type=hidden name=sheet_file value=$sheet_file>";} ?>
						<input type="hidden" name="modi_no" value="<?php print "$modi_no"; ?>">
						<?php if($modi_no == 0){print "<input type=submit value=作業一覧ページに戻る>";} ?>
						<?php if($modi_no != 0){print "<input type=submit value=作業内容ページに戻る>";} ?>
					</form>
				</td>
			</tr>
		</table>
		<div class="space10px"></div>
		<div class="footer">Copyright (C) Kind Techno Structure Corporation. All Rights Reserved.</div>
	</body>
</html>
<?php endif;?>
<?php if($del_check == "checked"){exit();} ?>
