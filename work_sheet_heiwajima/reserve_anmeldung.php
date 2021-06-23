<?php
	/* 各データの受け取り */
	$year = $_POST['year'];
	$mon = $_POST['mon'];
	$day = $_POST['day'];
	/* $day_file = $_POST['day_file']; 毎回生成する */
	$lift = $_POST['lift'];
	$time_s = $_POST['time_s'];
	$kariyoyaku = $_POST['kariyoyaku'];
	$name = $_POST['name'];
	$name_f = $_POST['name_f'];
	$tel = $_POST['tel'];
	$p_code = $_POST['p_code'];
	$address = $_POST['address'];
	$mail = $_POST['mail'];
	$car = $_POST['car'];
	$model = $_POST['model'];
	$engine = $_POST['engine'];
	$car_old = $_POST['car_old'];
	$drive = $_POST['drive'];
	$time_e = $_POST['time_e'];
	$hanbai = $_POST['hanbai'];
	$tantou = $_POST['tantou'];
	$sagyou = $_POST['sagyou'];
	$memo = $_POST['memo'];
	$icon = $_POST['icon'];

	$mon0 = $mon;
	if($mon0 < 10){$mon0 = "0$mon";}
	$day0 = $day;
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
	elseif($lift == 13){$lift_f = "サブ";} /* リフトを使用しない作業 */

	/* リフト空き状況との照合 */
	/* 日別作業データの取得 */
	setlocale(LC_ALL,'ja_JP.UTF-8');
	$fp_data = fopen("log/$day_file", "r");
	/* $fp_data = fopen("sample_ymd.dat", "r"); */
	$sheet_para = array();
	if($fp_data == "FALSE" or $fp_data == ""){ /* ファイルが存在しない場合はファイル作成 */
		$i = 0;
		$comm_f = 0;
		while($i < 16){
			for($col = 0; $col <= 21; $col++){
				if($i == 0){
					$sheet_para[0][0] = "連絡事項があればここに入力して下さい。"; /* 初期値として代入 */
					$sheet_para[0][1] = 0; /* 個別作業表用連番（後でプラスするので0スタート） */
					break;
				}
				elseif($i == 1){$sheet_para[$i][$col] = "open";}
				elseif($i == 4){$sheet_para[$i][$col] = "open";}
				elseif($i == 7){$sheet_para[$i][$col] = "open";}
				elseif($i == 10){$sheet_para[$i][$col] = "open";}
				elseif($i == 13){$sheet_para[$i][$col] = "open";}
				else{$sheet_para[$i][$col] = "";}
			}
			$i++;
		}
	}
	else{
		$i = 0;
		$comm_f = 1;
		while($ret_csv = fgetcsv($fp_data)){
			for($col = 0; $col < count($ret_csv); $col++){
				$sheet_para[$i][$col] = $ret_csv[$col];
			}
			$i++;
		}
	}
	fclose($fp_data);
	/* リフト・開始時刻の照合 */
	$sheet_check = 1;
	for($time_c = $time_s; $time_c < $time_e; $time_c++){
		if($sheet_para[$lift][$time_c] != "open" and $sheet_para[$lift][$time_c] != ""){$sheet_check = 0;}
	}
	/* 時刻照合ここまで */

	/* 最終更新日時DATファイル呼び出し */
	$fp_ymd = fopen("ymd.dat","r");
	$ymd = fgets($fp_ymd);
	fclose($fp_ymd);
?>

<?php if($sheet_check == 0):?>
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
		<div class="title_ymd"><?php print "$year"; ?>年<?php print "$mon"; ?>月<?php print "$day"; ?>日（<?php print "$wday"; ?>）</div>
		<div class="input_comm2">ご指定の時間帯は他の作業と重複しています。時間帯を指定し直して下さい。</div>
		<div class="sub_but">
			<form method="post" action="reserve_check.php">
				<input type="hidden" name="year" value="<?php print "$year"; ?>">
				<input type="hidden" name="mon" value="<?php print "$mon"; ?>">
				<input type="hidden" name="day" value="<?php print "$day"; ?>">
				<input type="hidden" name="lift" value="<?php print "$lift"; ?>">
				<input type="hidden" name="kariyoyaku" value="<?php print "$kariyoyaku"; ?>">
				<input type="hidden" name="name" value="<?php print "$name"; ?>">
				<input type="hidden" name="name_f" value="<?php print "$name_f"; ?>">
				<input type="hidden" name="tel" value="<?php print "$tel"; ?>">
				<input type="hidden" name="p_code" value="<?php print "$p_code"; ?>">
				<input type="hidden" name="address" value="<?php print "$address"; ?>">
				<input type="hidden" name="mail" value="<?php print "$mail"; ?>">
				<input type="hidden" name="car" value="<?php print "$car"; ?>">
				<input type="hidden" name="model" value="<?php print "$model"; ?>">
				<input type="hidden" name="engine" value="<?php print "$engine"; ?>">
				<input type="hidden" name="car_old" value="<?php print "$car_old"; ?>">
				<input type="hidden" name="drive" value="<?php print "$drive"; ?>">
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
		<div class="footer">Copyright (C) Kind Techno Structure Corporation. All Rights Reserved.</div>
	</body>
</html>
<?php endif;?>
<?php if($sheet_check == 0){exit();} ?>


<?php
	/* 現在時刻の取得 */
	date_default_timezone_set('Asia/Tokyo');
	$date = date("Y年n月j日G時i分");
?>

<?php if($sheet_check == 1):?>
<?php /* 予約重複なし */ ?>
<?php /* 登録データの整理 */
	/* 作業ファイル（実質新規作成） */
	$sheet_para[0][1]++;
	$file_no = $sheet_para[0][1];
	$mon0 = $mon;
	if($mon0 < 10){$mon0 = "0$mon";}
	$day0 =	$day;
	if($day0 < 10){$day0 = "0$day";}
	$work_file = "$year" . "$mon0" . "$day0" . "_" . "$file_no" . ".dat";
	$in_ind = "0,$name,$name_f,$tel,$p_code,$address,$mail,$car,$model,$engine,$car_old,$drive,$date" . "\n"; /* お客様情報 */
	$in_work = "1,$time_s,$time_e,$lift,$hanbai,$tantou,$sagyou,$memo,$icon,$date" . "\n"; /* 作業内容 */
	$in_data = "$in_ind" . "$in_work";
	file_put_contents("log/$work_file","$in_data"); /* 配列としてではなくカンマ・改行付きの文字列として保存（CSV形式なので問題無し） */
	/* 作業一覧ファイル */
	$time_w = $time_e - $time_s;
	$lift_file = $lift + 1;
	$lift_icon = $lift + 2;
	for($time_c = $time_s; $time_c < $time_e; $time_c++){
		if($time_c == $time_s){
			$sheet_para[$lift][$time_c] = $time_w;
			$sheet_para[$lift_file][$time_c] = "$work_file";
			$sheet_para[$lift_icon][$time_c] = $icon;
		}
		else{$sheet_para[$lift][$time_c] = "reserve";}
	}
	/* CSV形式に変換して上書き保存 */
	$order_put = $sheet_para;
	$fp_put = fopen("log/$day_file", "w");
	foreach($order_put as $fields) fputcsv($fp_put, $fields);
	fclose($fp_put);

	/* 最終更新日時の更新 */
	file_put_contents("ymd.dat","$date");

	/* 最終更新日時DATファイル呼び出し */
	$fp_ymd = fopen("ymd.dat","r");
	$ymd = fgets($fp_ymd);
	fclose($fp_ymd);

	/* 使用ログの保存 */
	$u_id = $_SERVER['REMOTE_USER']; /* ユーザーIDを取得 */
	$u_agent = $_SERVER['HTTP_USER_AGENT']; /* 使用環境を取得 */
	$u_referer = $_SERVER['HTTP_REFERER']; /* リファラー情報を取得 */
	$u_date = date("Y/m/d(D) H:i:s"); /* 日時を取得 */
	$u_todo = "作業情報を新規に追加"; /* 変更内容 */
	$u_log = "$u_date,$u_id,$day_file,$work_file,$u_todo,$u_agent,$u_referer" . "\n"; /* 保存ログデータ */
	$fp_log = fopen("log/work/work_access.log", "a");
	fwrite($fp_log, "$u_log");
	fclose($fp_log);
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
	<body bgcolor="#FFFFFF" text="#000000">
		<table class="title">
			<tr>
				<td>KTS作業予約管理システム<span class="shop_title">（KTS平和島店）</span></td>
				<td><div class="last_ymd">最終更新日時：<?php print "$ymd"; ?></div></td>
			</tr>
		</table>
		<div class="title_ymd"><?php print "$year"; ?>年<?php print "$mon"; ?>月<?php print "$day"; ?>日（<?php print "$wday"; ?>）</div>
		<div class="space10px"></div>
		<div class="input_comm2">入力内容を保存しました。</div>
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
		<div class="space10px"></div>
	</body>
</html>
<?php endif;?>
