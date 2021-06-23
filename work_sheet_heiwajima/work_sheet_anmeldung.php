<?php
	/* 各データの受け取り */
	$year = $_POST['year'];
	$mon = $_POST['mon'];
	$day = $_POST['day'];
	$sheet_file = $_POST['sheet_file'];
	$modi_no = $_POST['modi_no'];
	$lift = $_POST['lift'];
	$kariyoyaku = $_POST['kariyoyaku'];
	$time_s = $_POST['time_s'];
	$time_e = $_POST['time_e'];
	$hanbai = $_POST['hanbai'];
	$tantou = $_POST['tantou'];
	$sagyou = $_POST['sagyou'];
	$memo = $_POST['memo'];
	$icon = $_POST['icon'];

	$mon0 = $mon;
	$day0 = $day;
	if($mon0 < 10){$mon0 = "0$mon";}
	if($day0 < 10){$day0 = "0$day";}
	$day_file = "$year" . "$mon0" . "$day0" . ".dat";

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
	
	/* 開始時間パラメーターの変換 */
	if($time_s == 0){$time_sf = "9時00分";}
	elseif($time_s == 1){$time_sf = "9時30分";}
	elseif($time_s == 2){$time_sf = "10時00分";}
	elseif($time_s == 3){$time_sf = "10時30分";}
	elseif($time_s == 4){$time_sf = "11時00分";}
	elseif($time_s == 5){$time_sf = "11時30分";}
	elseif($time_s == 6){$time_sf = "12時00分";}
	elseif($time_s == 7){$time_sf = "12時30分";}
	elseif($time_s == 8){$time_sf = "13時00分";}
	elseif($time_s == 9){$time_sf = "13時30分";}
	elseif($time_s == 10){$time_sf = "14時00分";}
	elseif($time_s == 11){$time_sf = "14時30分";}
	elseif($time_s == 12){$time_sf = "15時00分";}
	elseif($time_s == 13){$time_sf = "15時30分";}
	elseif($time_s == 14){$time_sf = "16時00分";}
	elseif($time_s == 15){$time_sf = "16時30分";}
	elseif($time_s == 16){$time_sf = "17時00分";}
	elseif($time_s == 17){$time_sf = "17時30分";}
	elseif($time_s == 18){$time_sf = "18時00分";}
	elseif($time_s == 19){$time_sf = "18時30分";}
	elseif($time_s == 20){$time_sf = "19時00分";}
	elseif($time_s == 21){$time_sf = "19時30分";}

	/* 終了時間パラメーターの変換 */
	if($time_e == 1){$time_ef = "9時30分";}
	elseif($time_e == 2){$time_ef = "10時00分";}
	elseif($time_e == 3){$time_ef = "10時30分";}
	elseif($time_e == 4){$time_ef = "11時00分";}
	elseif($time_e == 5){$time_ef = "11時30分";}
	elseif($time_e == 6){$time_ef = "12時00分";}
	elseif($time_e == 7){$time_ef = "12時30分";}
	elseif($time_e == 8){$time_ef = "13時00分";}
	elseif($time_e == 9){$time_ef = "13時30分";}
	elseif($time_e == 10){$time_ef = "14時00分";}
	elseif($time_e == 11){$time_ef = "14時30分";}
	elseif($time_e == 12){$time_ef = "15時00分";}
	elseif($time_e == 13){$time_ef = "15時30分";}
	elseif($time_e == 14){$time_ef = "16時00分";}
	elseif($time_e == 15){$time_ef = "16時30分";}
	elseif($time_e == 16){$time_ef = "17時00分";}
	elseif($time_e == 17){$time_ef = "17時30分";}
	elseif($time_e == 18){$time_ef = "18時00分";}
	elseif($time_e == 19){$time_ef = "18時30分";}
	elseif($time_e == 20){$time_ef = "19時00分";}
	elseif($time_e == 21){$time_ef = "19時30分";}
	elseif($time_e == 22){$time_ef = "20時00分";}

	/* リフトIDの名称変換 */
	if($lift == 1){$lift_f = "アライメントA";} /* アライメント */
	elseif($lift == 4){$lift_f = "アライメントB";} /* アライメント */
	elseif($lift == 7){$lift_f = "2FメインリフトA";}
	elseif($lift == 10){$lift_f = "2FメインリフトB";}
	elseif($lift == 13){$lift_f = "サブ";}/* リフト以外作業 */

	/* 販売区分の名称変換 */
	if($hanbai == 1){$hanbai_f = "アライメント";}
	elseif($hanbai == 2){$hanbai_f = "店舗客注";}
	elseif($hanbai == 3){$hanbai_f = "持ち込み";}
	elseif($hanbai == 4){$hanbai_f = "WEB取り付け";}
	elseif($hanbai == 5){$hanbai_f = "在庫KEEP";}
	elseif($hanbai == 0){$hanbai_f = "仮予約";}

	/* リフト空き状況との照合 */
	$same_check = 1;
	/* 日別作業データの取得 */
	setlocale(LC_ALL,'ja_JP.UTF-8');
	$fp_data = fopen("log/$sheet_file", "r");
	$sheet_para = array();
	$i = 0;
	while($ret_csv = fgetcsv($fp_data)){
		for($col = 0; $col < count($ret_csv); $col++){
			$sheet_para[$i][$col] = $ret_csv[$col];
		}
		if($i > 0){
			/* 開始時間との重複確認 */
			for($same_i = $sheet_para[$i][1]; $same_i < $sheet_para[$i][2]; $same_i++){
				if($time_s == $same_i and $sheet_para[$i][0] != "del"){$same_check = 0;}
			}
			/* 終了時間との重複確認 */
			$same_i_plus = $sheet_para[$i][1] + 1;
			for($same_i = $same_i_plus; $same_i <= $sheet_para[$i][2]; $same_i++){
				if($time_e == $same_i and $sheet_para[$i][0] != "del"){$same_check = 0;}
			}
		}
		$i++;
	}
	fclose($fp_data);
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
	/* 同時刻処理の確認 */
	/* リフト・開始時刻の照合 */
	$sheet_check = 1;
	for($time_c = $time_s; $time_c < $time_e; $time_c++){
		if($day_para[$lift][$time_c] != "open" and $day_para[$lift][$time_c] != ""){$sheet_check = 0;}
	}
	/* 時刻照合ここまで */
?>
<?php /* 作業時間重複時 */ ?>
<?php if($sheet_check == 0 or $same_check == 0):?>
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
		<meta name="robots" content="noindex,nofollow">
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
		<div class="user_check">ご指定の時間帯は他の作業と重複しています。時間帯を指定し直して下さい。</div>
		<div class="space10px"></div>
		<div class="sub_but">
			<form method="post" action="work_sheet_push_check.php">
				<input type="hidden" name="year" value="<?php print "$year"; ?>">
				<input type="hidden" name="mon" value="<?php print "$mon"; ?>">
				<input type="hidden" name="day" value="<?php print "$day"; ?>">
				<input type="hidden" name="sheet_file" value="<?php print "$sheet_file"; ?>">
				<input type="hidden" name="modi_no" value="<?php print "$modi_no"; ?>">
				<input type="hidden" name="lift" value="<?php print "$lift"; ?>">
				<input type="hidden" name="kariyoyaku" value="<?php print "$kariyoyaku"; ?>">
				<input type="hidden" name="time_s" value="none">
				<input type="hidden" name="time_e" value="none">
				<input type="hidden" name="hanbai" value="<?php print "$hanbai"; ?>">
				<input type="hidden" name="tantou" value="<?php print "$tantou"; ?>">
				<input type="hidden" name="sagyou" value="<?php print "$sagyou"; ?>">
				<input type="hidden" name="memo" value="<?php print "$memo"; ?>">
				<input type="hidden" name="icon" value="<?php print "$icon"; ?>">
				<input type="submit" value="時間帯を修正する">
			</form>
		</div>
		<div class="space10px"></div>
		<div class="footer">Copyright (C) Kind Techno Structure Corporation. All Rights Reserved.</div>
	</body>
</html>
<?php endif;?>
<?php if($sheet_check == 0 or $same_check == 0){exit();} ?>
<?php
	/* 現在時刻の取得 */
	date_default_timezone_set('Asia/Tokyo');
	$date = date("Y年n月j日G時i分");
?>
<?php if($sheet_check == 1 and $same_check == 1):?>
<?php /* 予約重複なし */ ?>
<?php /* 作業ファイル 追加データの保存 */
	$in_work = "$modi_no,$time_s,$time_e,$lift,$hanbai,$tantou,$sagyou,$memo,$icon,$date" . "\n"; /* 追加作業内容 */
	file_put_contents("log/$sheet_file", "$in_work", FILE_APPEND | LOCK_EX); /* 既存ファイルへの一行追加書き込み */
	/* 作業一覧ファイルの修正 */
	$time_w = $time_e - $time_s;
	$lift_file = $lift + 1;
	$lift_icon = $lift + 2;
	for($time_c = $time_s; $time_c < $time_e; $time_c++){
		if($time_c == $time_s){
			$day_para[$lift][$time_c] = $time_w;
			$day_para[$lift_file][$time_c] = "$sheet_file";
			$day_para[$lift_icon][$time_c] = $icon;
		}
		else{$day_para[$lift][$time_c] = "reserve";}
	}
	/* CSV形式に変換して上書き保存 */
	$order_put = $day_para;
	$fp_put = fopen("log/$day_file", "w");
	foreach($order_put as $fields) fputcsv($fp_put, $fields);
	fclose($fp_put);

	/* 最終更新日時の更新 */
	file_put_contents("ymd.dat","$date");

	/* 使用ログの保存 */
	$u_id = $_SERVER['REMOTE_USER']; /* ユーザーIDを取得 */
	$u_agent = $_SERVER['HTTP_USER_AGENT']; /* 使用環境を取得 */
	$u_referer = $_SERVER['HTTP_REFERER']; /* リファラー情報を取得 */
	$u_date = date("Y/m/d(D) H:i:s"); /* 日時を取得 */
	$u_todo = "既存作業情報に新しく作業情報を追加"; /* 変更内容 */
	$u_log = "$u_date,$u_id,$day_file,$sheet_file,$u_todo,$u_agent,$u_referer" . "\n"; /* 保存ログデータ */
	$fp_log = fopen("log/work/work_access.log", "a");
	fwrite($fp_log, "$u_log");
	fclose($fp_log);

	/* 最終更新日時DATファイル呼び出し */
	$fp_ymd = fopen("ymd.dat","r");
	$ymd = fgets($fp_ymd);
	fclose($fp_ymd);
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
		<meta name="robots" content="noindex,nofollow">
		<meta http-equiv="Pragma" content="no-cache">
		<meta http-equiv="cache-control" content="no-cache">
		<meta http-equiv="expires" content="0">
		<link rel="stylesheet" href="https://www.kts-web.com/work_sheet_heiwajima/work_sheet.css" type="text/css">
	</head>
	<body bgcolor="#FFFFFF" text="#000000">
		<table class="title">
			<tr>
				<td>KTS作業予約管理システム<span class="shop_title">（KTS平和島店）</span></td>
				<td><div class="last_ymd">最終更新日時：<?php print "$ymd"; ?></div></td>
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
		<div class="user_check">入力内容を保存しました。</div>
		<div class="space10px"></div>
		<div class="sub_but">
			<form method="post" action="day_work.php">
				<input type="hidden" name="year" value="<?php print "$year"; ?>">
				<input type="hidden" name="mon" value="<?php print "$mon"; ?>">
				<input type="hidden" name="day" value="<?php print "$day"; ?>">
				<input type="submit" value="作業一覧ページへ戻る">
			</form>
		</div>
		<div class="space10px"></div>
		<div class="footer">Copyright (C) Kind Techno Structure Corporation. All Rights Reserved.</div>
	</body>
</html>
<?php endif;?>
