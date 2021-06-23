<?php
	/* 各データの受け取り */
	$year = $_POST['year'];
	$mon = $_POST['mon'];
	$day = $_POST['day'];
	$sheet_file = $_POST['sheet_file'];
	$modi_no = $_POST['modi_no'];
	if($modi_no == 0){
		$name = $_POST['name'];
		$name_f = $_POST['name_f'];
		$tel = $_POST['tel'];
		$p_code = $_POST['p_code'];
		$address = $_POST['address'];
		$mail = $_POST['mail'];
		$car = $_POST['car'];
		$car_model = $_POST['car_model'];
		$engine = $_POST['engine'];
		$car_old = $_POST['car_old'];
		$drive = $_POST['drive'];
	}
	elseif($modi_no != 0){
		$time_s = $_POST['time_s'];
		$time_e = $_POST['time_e'];
		$lift = $_POST['lift'];
		$hanbai = $_POST['hanbai'];
		$tantou = $_POST['tantou'];
		$sagyou = $_POST['sagyou'];
		$memo = $_POST['memo'];
		$icon = $_POST['icon'];
		$time_check = $_POST['time_check'];
		$icon_check = $_POST['icon_check'];
		$lift_change = $_POST['lift_change'];
	}

	/* 作業データの呼び出し */
	setlocale(LC_ALL,'ja_JP.UTF-8');
	$fp_data = fopen("log/$sheet_file", "r");
	$sheet_para = array();
	$i = 0;
	while($ret_csv = fgetcsv($fp_data)){
		for($col = 0; $col < count($ret_csv); $col++){
			$sheet_para[$i][$col] = $ret_csv[$col];
		}
		$i++;
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
	$date = date("Y年n月j日G時i分");

	/* お客様データの修正 */
	if($modi_no == 0){
		$sheet_para[0][1] = $name; /* 名前 */
		$sheet_para[0][2] = $name_f; /* 名前（フリガナ） */
		$sheet_para[0][3] = $tel; /* 電話番号 */
		$sheet_para[0][4] = $p_code; /* 郵便番号 */
		$sheet_para[0][5] = $address; /* 住所 */
		$sheet_para[0][6] = $mail; /* メールアドレス */
		$sheet_para[0][7] = $car; /* 車種 */
		$sheet_para[0][8] = $car_model; /* 車輌型式 */
		$sheet_para[0][9] = $engine; /* エンジン型式 */
		$sheet_para[0][10] = $car_old; /* 年式 */
		$sheet_para[0][11] = $drive; /* 駆動 */
		$sheet_para[0][12] = $date; /* 最終更新日時 */
		/* CSV形式に変換して上書き保存 */
		$order_put = $sheet_para;
		$fp_put = fopen("log/$sheet_file", "w");
		foreach($order_put as $fields) fputcsv($fp_put, $fields);
		fclose($fp_put);
		/* 最終更新日時の更新 */
		file_put_contents("ymd.dat","$date");
		/* 使用ログの保存 */
		$mon0 = $mon;
		$day0 = $day;
		if($mon0 < 10){$mon0 = "0$mon";}
		if($day0 < 10){$day0 = "0$day";}
		$day_file = "$year" . "$mon0" . "$day0" . ".dat";
		$u_id = $_SERVER['REMOTE_USER']; /* ユーザーIDを取得 */
		$u_agent = $_SERVER['HTTP_USER_AGENT']; /* 使用環境を取得 */
		$u_referer = $_SERVER['HTTP_REFERER']; /* リファラー情報を取得 */
		$u_date = date("Y/m/d(D) H:i:s"); /* 日時を取得 */
		$u_todo = "お客様情報を修正"; /* 変更内容 */
		$u_log = "$u_date,$u_id,$day_file,$sheet_file,$u_todo,$u_agent,$u_referer" . "\n"; /* 保存ログデータ */
		$fp_log = fopen("log/work/work_access.log", "a");
		fwrite($fp_log, "$u_log");
		fclose($fp_log);
	}
?>
<?php
	/* キャッシュの無効化 */
	header( 'Expires: Sat, 26 Jul 1997 05:00:00 GMT' );
	header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
	// HTTP/1.1
	header( 'Cache-Control: nostore, no-cache, must-revalidate' );
	header( 'Cache-Control: post-check=0, pre-check=0', FALSE );
	// HTTP/1.0
	header( 'Pragma: no-chache' );
?>
<html>
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
					<form method="post" action="work_sheet_modi.php">
						<input type="hidden" name="year" value="<?php print "$year"; ?>">
						<input type="hidden" name="mon" value="<?php print "$mon"; ?>">
						<input type="hidden" name="day" value="<?php print "$day"; ?>">
						<input type="hidden" name="sheet_file" value="<?php print "$sheet_file"; ?>">
						<input type="hidden" name="modi_no" value="<?php print "$modi_no"; ?>">
						<input type="submit" value="前のページに戻る">
					</form>
				</td>
				<td><div><a href="index.php">月間表示画面へ戻る</a></div></td>
			</tr>
		</table>
		<div class="space10px"></div>
<?php /* お客様情報部分 */ ?>
<?php if($modi_no == 0):?>
		<div class="space10px"></div>
		<div class="user_check">修正内容を保存しました。</div>
		<div class="space10px"></div>
		<div class="del_all">
			<form method="post" action="work_sheet.php">
				<input type="hidden" name="year" value="<?php print "$year"; ?>">
				<input type="hidden" name="mon" value="<?php print "$mon"; ?>">
				<input type="hidden" name="day" value="<?php print "$day"; ?>">
				<input type="hidden" name="sheet_file" value="<?php print "$sheet_file"; ?>">
				<input class="del_all_cau" type="submit" value="修正元のページヘ戻る">
			</form>
		</div>
		<div class="space10px"></div>
		<div class="footer">Copyright (C) Kind Techno Structure Corporation. All Rights Reserved.</div>
	</body>
</html>
<?php endif;?>
<?php if($modi_no == 0){exit();} ?>
<?php 
	/* 作業内容の修正 */
	/* 作業時間の照合 */
	$check_i = 0;
	$sheet_check = 1;
	if($time_check == 1 or $lift_change == 1 or $icon_check == 1){
		$mon0 = $mon;
		$day0 = $day;
		if($mon0 < 10){$mon0 = "0$mon";}
		if($day0 < 10){$day0 = "0$day";}
		$day_file = "$year" . "$mon0" . "$day0" . ".dat";
		$time_s_check = intval($sheet_para[$modi_no][1]); /* 修正前の開始時間を取得 */
		$time_e_check = intval($sheet_para[$modi_no][2]); /* 修正前の終了時間を取得 */
		$lift_check = intval($sheet_para[$modi_no][3]); /* 修正前のリフトIDを取得 */
		$d_point = $lift + 1; /* 一時代入 */
		$lift_file = intval($d_point); /* 修正後 */
		$d_point= $lift + 2; /* 一時代入 */
		$lift_icon = intval($d_point); /* 修正後 */
		$d_point = $lift_check + 1; /* 一時代入 */
		$file_check = intval($d_point); /* 修正後 */
		$d_point = $lift_check + 2; /* 一時代入 */
		$icon_check = intval($d_point); /* 修正後 */
		/* 作業一覧データを呼び出し */
		setlocale(LC_ALL,'ja_JP.UTF-8');
		$fp_day = fopen("log/$day_file", "r");
		$day_para = array();
		$i = 0;
		while($day_csv = fgetcsv($fp_day)){
			for($col = 0; $col < count($day_csv); $col++){
				$day_para[$i][$col] = $day_csv[$col];
			}
			$i++;
		}
		fclose($fp_day);
		/* 作業時間を一時初期化 */
		for($cnt_i = $time_s_check; $cnt_i < $time_e_check; $cnt_i++){
			$day_para[$lift_check][$cnt_i] = "open";
			$day_para[$file_check][$cnt_i] = "";
			$day_para[$icon_check][$cnt_i] = "";
		}
		/* 日別作業データの取得 */
		setlocale(LC_ALL,'ja_JP.UTF-8');
		$fp_data_tc = fopen("log/$sheet_file", "r");
		$sheet_para_tc = array();
		$i_tc = 0;
		while($ret_csv_tc = fgetcsv($fp_data_tc)){
			for($col = 0; $col < count($ret_csv_tc); $col++){
				$sheet_para_tc[$i_tc][$col] = $ret_csv_tc[$col];
			}
			if($i_tc > 0){
				/* 開始時間との重複確認 */
				for($same_i = $sheet_para_tc[$i_tc][1]; $same_i < $sheet_para_tc[$i_tc][2]; $same_i++){
					if($time_s == $same_i and $sheet_para_tc[$i_tc][0] != "del" and $sheet_para_tc[$i_tc][0] != $modi_no){$sheet_check = 0;}
				}
				/* 終了時間との重複確認 */
				$same_i_plus = $sheet_para_tc[$i_tc][1] + 1;
				for($same_i = $same_i_plus; $same_i <= $sheet_para_tc[$i_tc][2]; $same_i++){
					if($time_e == $same_i and $sheet_para_tc[$i_tc][0] != "del" and $sheet_para_tc[$i_tc][0] != $modi_no){$sheet_check = 0;}
				}
			}
			$i_tc++;
		}
		fclose($fp_data_tc);
		/* 作業時間重複確認 */
		for($time_c = $time_s; $time_c < $time_e; $time_c++){
			if($day_para[$lift][$time_c] != "open" and $day_para[$lift][$time_c] != ""){$sheet_check = 0;}
		}
		/* 重複確認OK、作業一覧を上書き */
		if($sheet_check == 1){
			for($time_c = $time_s; $time_c < $time_e; $time_c++){
				if($time_c == $time_s){
					$day_para[$lift][$time_c] = $time_e - $time_s;
					$day_para[$lift_file][$time_c] = $sheet_file;
					$day_para[$lift_icon][$time_c] = $icon;
				}
				else{$day_para[$lift][$time_c] = "reserve";}
			}
			$fp_put_d = fopen("log/$day_file", "w");
			foreach($day_para as $fields) fputcsv($fp_put_d, $fields);
			fclose($fp_put_d);
		}
	}
	/* 作業内容の上書き */
	if($sheet_check == 1){
		$sheet_para[$modi_no][1] = $time_s; /* 開始時間 */
		$sheet_para[$modi_no][2] = $time_e; /* 終了時間 */
		$sheet_para[$modi_no][3] = $lift; /* リフト */
		$sheet_para[$modi_no][4] = $hanbai; /* 販売区分 */
		$sheet_para[$modi_no][5] = $tantou; /* 担当者 */
		$sheet_para[$modi_no][6] = $sagyou; /* 作業内容 */
		$sheet_para[$modi_no][7] = $memo; /* メモ */
		$sheet_para[$modi_no][8] = $icon; /* アイコン */
		$sheet_para[$modi_no][9] = $date; /* 最終更新日時 */
		$sheet_put = $sheet_para;
		$fp_put_s = fopen("log/$sheet_file", "w");
		foreach($sheet_put as $fields) fputcsv($fp_put_s, $fields);
		fclose($fp_put_s);
		/* 最終更新日時の更新 */
		file_put_contents("ymd.dat","$date");
		/* 使用ログの保存 */
		$mon0 = $mon;
		$day0 = $day;
		if($mon0 < 10){$mon0 = "0$mon";}
		if($day0 < 10){$day0 = "0$day";}
		$day_file = "$year" . "$mon0" . "$day0" . ".dat";
		$u_id = $_SERVER['REMOTE_USER']; /* ユーザーIDを取得 */
		$u_agent = $_SERVER['HTTP_USER_AGENT']; /* 使用環境を取得 */
		$u_referer = $_SERVER['HTTP_REFERER']; /* リファラー情報を取得 */
		$u_date = date("Y/m/d(D) H:i:s"); /* 日時を取得 */
		$u_todo = "作業情報を修正"; /* 変更内容 */
		$u_log = "$u_date,$u_id,$day_file,$sheet_file,$u_todo,$u_agent,$u_referer" . "\n"; /* 保存ログデータ */
		$fp_log = fopen("log/work/work_access.log", "a");
		fwrite($fp_log, "$u_log");
		fclose($fp_log);
	}
?>
<?php if($modi_no != 0 and $sheet_check == 0):?>
		<div class="space10px"></div>
		<div class="user_check">その時間帯は別作業で埋まっています</div>
		<div class="space10px"></div>
		<div class="del_all">
			<form method="post" action="work_sheet.php">
				<input type="hidden" name="year" value="<?php print "$year"; ?>">
				<input type="hidden" name="mon" value="<?php print "$mon"; ?>">
				<input type="hidden" name="day" value="<?php print "$day"; ?>">
				<input type="hidden" name="sheet_file" value="<?php print "$sheet_file"; ?>">
				<input class="del_all_cau" type="submit" value="修正元のページヘ戻る">
			</form>
		</div>
		<div class="space10px"></div>
		<div class="footer">Copyright (C) Kind Techno Structure Corporation. All Rights Reserved.</div>
	</body>
</html>
<?php endif;?>
<?php if($modi_no != 0 and $sheet_check == 0){exit();} ?>
<?php if($modi_no != 0 and $sheet_check == 1):?>
		<div class="space10px"></div>
		<div class="user_check">修正内容を保存しました。</div>
		<div class="space10px"></div>
		<div class="del_all">
			<form method="post" action="work_sheet.php">
				<input type="hidden" name="year" value="<?php print "$year"; ?>">
				<input type="hidden" name="mon" value="<?php print "$mon"; ?>">
				<input type="hidden" name="day" value="<?php print "$day"; ?>">
				<input type="hidden" name="sheet_file" value="<?php print "$sheet_file"; ?>">
				<input class="del_all_cau" type="submit" value="修正元のページヘ戻る">
			</form>
		</div>
		<div class="space10px"></div>
		<div class="footer">Copyright (C) Kind Techno Structure Corporation. All Rights Reserved.</div>
	</body>
</html>
<?php endif;?>
<?php if($modi_no != 0 and $sheet_check == 1){exit();} ?>
