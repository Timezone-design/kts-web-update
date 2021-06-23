<?php
	/* 各データの受け取り */
	$year = $_POST['year'];
	$mon = $_POST['mon'];
	$day = $_POST['day'];
	$sheet_file = $_POST['sheet_file'];

	/* 該当データの呼び出し */
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

	/* お客様情報を変数へ代入 */
	$name = $sheet_para[0][1];
	$name_f = $sheet_para[0][2];
	$tel = $sheet_para[0][3];
	$p_code = $sheet_para[0][4];
	$address = $sheet_para[0][5];
	$mail = $sheet_para[0][6];
	$car = $sheet_para[0][7];
	$model = $sheet_para[0][8];
	$engine = $sheet_para[0][9];
	$car_old = $sheet_para[0][10];
	$drive = $sheet_para[0][11];

	/* 配列内のお客様情報を削除 */
	unset($sheet_para[0][0],$sheet_para[0][1],$sheet_para[0][2],$sheet_para[0][3],$sheet_para[0][4],$sheet_para[0][5],$sheet_para[0][6],$sheet_para[0][7],$sheet_para[0][8],$sheet_para[0][9],$sheet_para[0][10],$sheet_para[0][11],$sheet_para[0][12]);

	/* 配列内をソート */
	foreach($sheet_para as $key => $value){
		$key_id[$key] = $value[1];
	}
	array_multisort($key_id , SORT_NUMERIC , $sheet_para);

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
				<td><div class="print_right">
					<form method="post" action="print_work_sheet.php">
						<input type="hidden" name="year" value="<?php print "$year"; ?>">
						<input type="hidden" name="mon" value="<?php print "$mon"; ?>">
						<input type="hidden" name="day" value="<?php print "$day"; ?>">
						<input type="hidden" name="sheet_file" value="<?php print "$sheet_file"; ?>">
						<input type="hidden" name="print_id" value="input">
						<input type="submit" value="作業書を印刷する">
					</form>
				</div></td>
			</tr>
		</table>
		<table class="day_work_comm">
			<tr>
				<td class="day_work_date" width="50%"><?php print "$year"; ?>年<?php print "$mon"; ?>月<?php print "$day"; ?>日（<?php print "$wday"; ?>）</td>
				<td class="none_print">
					<form method="post" action="day_work.php">
						<input type="hidden" name="year" value="<?php print "$year"; ?>">
						<input type="hidden" name="mon" value="<?php print "$mon"; ?>">
						<input type="hidden" name="day" value="<?php print "$day"; ?>">
						<input type="submit" value="前のページに戻る">
					</form>
				</td>
				<td class="none_print"><a href="index.php">月間表示画面へ戻る</a></td>
			</tr>
		</table>
		<div class="space10px"></div>
		<table class="reserve">
			<tr>
				<td class="td_title">氏名</td>
				<td width="320"><?php print "$name"; ?></td>
				<td class="td_title">氏名（フリガナ）</td>
				<td><?php print "$name_f"; ?></td>
			</tr>
			<tr>
				<td class="td_title">電話番号</td>
				<td><?php print "$tel"; ?><span class="input_comm">※半角英数</span></td>
				<td class="td_title" rowspan="4">作業車両</td>
				<td rowspan="4">
					<div>車種：<?php print "$car"; ?></div>
					<div class="space3px"></div>
					<div>車輌型式：<?php print "$model"; ?></div>
					<div class="space3px"></div>
					<div>エンジン：<?php print "$engine"; ?></div>
					<div class="space3px"></div>
					<div>年式：<?php print "$car_old"; ?></div>
					<div class="space3px"></div>
					<div>駆動：<?php print "$drive"; ?></div>
				</td>
			</tr>
			<tr>
				<td class="td_title">郵便番号</td>
				<td><?php print "$p_code"; ?><span class="input_comm">※半角英数</span></td>
			</tr>
			<tr>
				<td class="td_title">住所</td>
				<td><?php print "$address"; ?></td>
			</tr>
			<tr>
				<td class="td_title">メールアドレス</td>
				<td><?php print "$mail"; ?><span class="input_comm">※半角英数</span></td>
			</tr>
		</table>
		<div class="modi"><form method="post" action="work_sheet_modi.php"><input type="hidden" name="year" value="<?php print "$year"; ?>"><input type="hidden" name="mon" value="<?php print "$mon"; ?>"><input type="hidden" name="day" value="<?php print "$day"; ?>"><input type="hidden" name="sheet_file" value="<?php print "$sheet_file"; ?>"><input type="hidden" name="modi_no" value="0"><input type="submit" value="お客様情報を修正する"></form></div>
<?php /* 作業内容部分 */
	/* ループ回数の設定 */
	$roop_i = $i - 1;
	for($roop = 1; $roop <= $roop_i; $roop++){
		if($sheet_para[$roop][0] == "del"){continue;}
		print "<div class=space10px></div>";
		print "<table class=reserve>";
		print "<tr>";
		print "<td class=td_title2>日時</td>";
		print "<td width=320>";
		/* 開始時間パラメーターの変換 */
		if($sheet_para[$roop][1] == 0){$time_sf = "9時00分";}
		elseif($sheet_para[$roop][1] == 1){$time_sf = "9時30分";}
		elseif($sheet_para[$roop][1] == 2){$time_sf = "10時00分";}
		elseif($sheet_para[$roop][1] == 3){$time_sf = "10時30分";}
		elseif($sheet_para[$roop][1] == 4){$time_sf = "11時00分";}
		elseif($sheet_para[$roop][1] == 5){$time_sf = "11時30分";}
		elseif($sheet_para[$roop][1] == 6){$time_sf = "12時00分";}
		elseif($sheet_para[$roop][1] == 7){$time_sf = "12時30分";}
		elseif($sheet_para[$roop][1] == 8){$time_sf = "13時00分";}
		elseif($sheet_para[$roop][1] == 9){$time_sf = "13時30分";}
		elseif($sheet_para[$roop][1] == 10){$time_sf = "14時00分";}
		elseif($sheet_para[$roop][1] == 11){$time_sf = "14時30分";}
		elseif($sheet_para[$roop][1] == 12){$time_sf = "15時00分";}
		elseif($sheet_para[$roop][1] == 13){$time_sf = "15時30分";}
		elseif($sheet_para[$roop][1] == 14){$time_sf = "16時00分";}
		elseif($sheet_para[$roop][1] == 15){$time_sf = "16時30分";}
		elseif($sheet_para[$roop][1] == 16){$time_sf = "17時00分";}
		elseif($sheet_para[$roop][1] == 17){$time_sf = "17時30分";}
		elseif($sheet_para[$roop][1] == 18){$time_sf = "18時00分";}
		elseif($sheet_para[$roop][1] == 19){$time_sf = "18時30分";}
		elseif($sheet_para[$roop][1] == 20){$time_sf = "19時00分";}
		elseif($sheet_para[$roop][1] == 21){$time_sf = "19時30分";}
		/* 終了時間パラメーターの変換 */
		if($sheet_para[$roop][2] == 1){$time_ef = "9時30分";}
		elseif($sheet_para[$roop][2] == 2){$time_ef = "10時00分";}
		elseif($sheet_para[$roop][2] == 3){$time_ef = "10時30分";}
		elseif($sheet_para[$roop][2] == 4){$time_ef = "11時00分";}
		elseif($sheet_para[$roop][2] == 5){$time_ef = "11時30分";}
		elseif($sheet_para[$roop][2] == 6){$time_ef = "12時00分";}
		elseif($sheet_para[$roop][2] == 7){$time_ef = "12時30分";}
		elseif($sheet_para[$roop][2] == 8){$time_ef = "13時00分";}
		elseif($sheet_para[$roop][2] == 9){$time_ef = "13時30分";}
		elseif($sheet_para[$roop][2] == 10){$time_ef = "14時00分";}
		elseif($sheet_para[$roop][2] == 11){$time_ef = "14時30分";}
		elseif($sheet_para[$roop][2] == 12){$time_ef = "15時00分";}
		elseif($sheet_para[$roop][2] == 13){$time_ef = "15時30分";}
		elseif($sheet_para[$roop][2] == 14){$time_ef = "16時00分";}
		elseif($sheet_para[$roop][2] == 15){$time_ef = "16時30分";}
		elseif($sheet_para[$roop][2] == 16){$time_ef = "17時00分";}
		elseif($sheet_para[$roop][2] == 17){$time_ef = "17時30分";}
		elseif($sheet_para[$roop][2] == 18){$time_ef = "18時00分";}
		elseif($sheet_para[$roop][2] == 19){$time_ef = "18時30分";}
		elseif($sheet_para[$roop][2] == 20){$time_ef = "19時00分";}
		elseif($sheet_para[$roop][2] == 21){$time_ef = "19時30分";}
		elseif($sheet_para[$roop][2] == 22){$time_ef = "20時00分";}
		print "$time_sf" . "～" . "$time_ef</td>";
		print "<td class=td_title2>リフト</td>";
		print "<td>";
		/* リフトIDの名称変換 */
		if($sheet_para[$roop][3] == 1){$lift_f = "アライメントA";} /* アライメント */
		elseif($sheet_para[$roop][3] == 4){$lift_f = "アライメントB";} /* アライメント */
		elseif($sheet_para[$roop][3] == 7){$lift_f = "2FメインリフトA";}
		elseif($sheet_para[$roop][3] == 10){$lift_f = "2FメインリフトB";}
		elseif($sheet_para[$roop][3] == 13){$lift_f = "サブ";} /* リフトを使用しない作業 */
		print "$lift_f</td>";
		print "</tr>";
		print "<tr>";
		print "<td class=td_title2>販売区分</td>";
		print "<td>";
		/* 販売区分の名称変換 */
		if($sheet_para[$roop][4] == 1){$hanbai_f = "アライメント";}
		elseif($sheet_para[$roop][4] == 2){$hanbai_f = "店舗客注";}
		elseif($sheet_para[$roop][4] == 3){$hanbai_f = "持ち込み";}
		elseif($sheet_para[$roop][4] == 4){$hanbai_f = "WEB取り付け";}
		elseif($sheet_para[$roop][4] == 5){$hanbai_f = "在庫KEEP";}
		elseif($sheet_para[$roop][4] == 0){$hanbai_f = "仮予約";}
		print "$hanbai_f</td>";
		print "<td class=td_title2>担当者</td>";
		print "<td>";
		print_r($sheet_para[$roop][5]);
		print "</td>";
		print "</tr>";
		print "<tr><td colspan=4 class=td_title2>作業内容</td></tr>";
		print "<tr><td colspan=4>";
		print_r($sheet_para[$roop][6]);
		print "</td></tr>";
		print "<tr><td colspan=4 class=td_title2>メモ欄・工賃</td></tr>";
		print "<tr><td colspan=4>";
		print_r($sheet_para[$roop][7]);
		print "</td></tr>";
		print "<tr><td colspan=4 class=td_title2>表示アイコン</td></tr>";
		print "<tr><td colspan=4 class=icon_td>";
		if($sheet_para[$roop][8] == 0){print "特になし";}
		elseif($sheet_para[$roop][8] == 1){print "<form method=post action=alignment.php><input type=hidden name=year value=$year><input type=hidden name=mon value=$mon><input type=hidden name=day value=$day><input type=hidden name=sheet_file value=$sheet_file><div><input type=image src=img/mail_none.gif name=image></div></form>";}
		elseif($sheet_para[$roop][8] == 2){print "<img src=img/minyuka.gif width=90 height=20 alt=商品未入荷>";}
		elseif($sheet_para[$roop][8] == 3){print "<img src=img/nouki.gif width=90 height=20 alt=納期要確認>";}
		elseif($sheet_para[$roop][8] == 4){print "<img src=img/minou.gif width=90 height=20 alt=未納あり>";}
		elseif($sheet_para[$roop][8] == 5){print "<img src=img/daisya.gif width=90 height=20 alt=代車使用>";}
		print "</td></tr></table>";
		print "<table class=modi><tr><td>";
		print "<form method=post action=work_sheet_del.php><input type=hidden name=year value=$year><input type=hidden name=mon value=$mon><input type=hidden name=day value=$day><input type=hidden name=sheet_file value=$sheet_file><input type=hidden name=modi_no value=";
		print_r($sheet_para[$roop][0]);
		print "><input type=submit value=作業内容を削除する></form></td>";
		print "<td width=130><form method=post action=work_sheet_modi.php><input type=hidden name=year value=$year><input type=hidden name=mon value=$mon><input type=hidden name=day value=$day><input type=hidden name=sheet_file value=$sheet_file><input type=hidden name=modi_no value=";
		print_r($sheet_para[$roop][0]);
		print "><input type=submit value=作業内容を修正する></form></td></tr></table>";
	}
?>
		<div class="space10px"></div>
		<div class="del_all">
			<form method="post" action="work_sheet_push.php">
				<input type="hidden" name="year" value="<?php print "$year"; ?>">
				<input type="hidden" name="mon" value="<?php print "$mon"; ?>">
				<input type="hidden" name="day" value="<?php print "$day"; ?>">
				<input type="hidden" name="sheet_file" value="<?php print "$sheet_file"; ?>">
				<input type="hidden" name="modi_no" value="<?php print "$roop"; ?>">
				<input class="sagyou_push" type="submit" value="作業情報を追加する">
			</form>
		</div>
		<div class="space10px"></div>
		<div class="del_all">
			<form method="post" action="work_sheet_del.php">
				<input type="hidden" name="year" value="<?php print "$year"; ?>">
				<input type="hidden" name="mon" value="<?php print "$mon"; ?>">
				<input type="hidden" name="day" value="<?php print "$day"; ?>">
				<input type="hidden" name="sheet_file" value="<?php print "$sheet_file"; ?>">
				<input type="hidden" name="modi_no" value="0">
				<input class="del_all_cau" type="submit" value="ページ内データを全て削除する">
			</form>
		</div>
		<div class="space10px"></div>
		<div class="footer">Copyright (C) Kind Techno Structure Corporation. All Rights Reserved.</div>
	</body>
</html>
