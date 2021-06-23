<?php
	/* 各データの受け取り（基礎データ） */
	$year = $_POST['year'];
	$mon = $_POST['mon'];
	$day = $_POST['day'];
	$sheet_file = $_POST['sheet_file'];
	$modi_no = $_POST['modi_no'];
	/* 各データの受け取り（お客様情報） */
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
	/* 各データの受け取り（作業情報） */
	$time_s = $_POST['time_s'];
	$time_e = $_POST['time_e'];
	$lift = $_POST['lift'];
	$hanbai = $_POST['hanbai'];
	$tantou = $_POST['tantou'];
	$sagyou = $_POST['sagyou'];
	$memo = $_POST['memo'];
	$icon = $_POST['icon'];

	/* 修正未入力チェック（お客様情報） */
	$user_check = 0;
	if($name == ""){$user_check = 1;}
	if($name_f == ""){$user_check = 1;}
	if($tel == ""){$user_check = 1;}
	if($p_code == ""){$user_check = 1;}
	if($address == ""){$user_check = 1;}
	if($mail == ""){$user_check = 1;}
	if($car == ""){$user_check = 1;}
	if($car_model == ""){$user_check = 1;}
	if($engine == ""){$user_check = 1;}
	if($car_old == ""){$user_check = 1;}
	if($drive == ""){$user_check = 1;}

	/* 修正未入力チェック（作業情報） */
	$sagyou_check = 0;
	if($time_s == ""){$sagyou_check = 1;}
	if($time_e == ""){$sagyou_check = 1;}
	if($time_e <= $time_s){$sagyou_check = 1;}
	if($lift == ""){$sagyou_check = 1;}
	if($hanbai == ""){$sagyou_check = 1;}
	if($tantou == ""){$sagyou_check = 1;}
	if($sagyou == ""){$sagyou_check = 1;}
	/* if($memo == ""){$sagyou_check = 1;} メモは未入力OKパラメーター */
	if($icon == ""){$sagyou_check = 1;}

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

	/* 作業車両の変更点フラグ */
	$check_car = 0;
	if($car != $sheet_para[0][7]){$check_car = 1;}
	if($car_model != $sheet_para[0][8]){$check_car = 1;}
	if($engine != $sheet_para[0][9]){$check_car = 1;}
	if($car_old != $sheet_para[0][10]){$check_car = 1;}
	if($drive != $sheet_para[0][11]){$check_car = 1;}

	/* 作業時間・リフト・アイコンチェックフラグの初期化 */
	$time_check = 0;
	$lift_change = 0;
	$icon_check = 0;

	/* 最終更新日時DATファイル呼び出し */
	$fp_ymd = fopen("ymd.dat","r");
	$ymd = fgets($fp_ymd);
	fclose($fp_ymd);
?>

<html>
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
<?php /* お客様情報 */ ?>
<?php if($user_check == 1 and $modi_no == 0):?>
		<div class="user_check">修正箇所に入力漏れがあります。確認の上、やり直して下さい。</div>
		<div class="space10px"></div>
		<div class="footer">Copyright (C) Kind Techno Structure Corporation. All Rights Reserved.</div>
	</body>
</html>
<?php endif;?>
<?php if($user_check == 1 and $modi_no == 0){exit();} ?>
<?php if($modi_no == 0):?>
		<table class="reserve">
			<tr>
				<td class="td_title">氏名</td>
				<td width="320"><?php print_r($sheet_para[0][1]); ?></td>
				<td class="td_title">氏名（フリガナ）</td>
				<td><?php print_r($sheet_para[0][2]); ?></td>
			</tr>
			<tr>
				<td class="td_title">電話番号</td>
				<td><?php print_r($sheet_para[0][3]); ?><span class="input_comm">※半角英数</span></td>
				<td class="td_title" rowspan="4">作業車両</td>
				<td rowspan="4">
					<div>車種：<?php print_r($sheet_para[0][7]); ?></div>
					<div class="space3px"></div>
					<div>車輌型式：<?php print_r($sheet_para[0][8]); ?></div>
					<div class="space3px"></div>
					<div>エンジン：<?php print_r($sheet_para[0][9]); ?></div>
					<div class="space3px"></div>
					<div>年式：<?php print_r($sheet_para[0][10]); ?></div>
					<div class="space3px"></div>
					<div>駆動：<?php print_r($sheet_para[0][11]); ?></div>
				</td>
			</tr>
			<tr>
				<td class="td_title">郵便番号</td>
				<td><?php print_r($sheet_para[0][4]); ?><span class="input_comm">※半角英数</span></td>
			</tr>
			<tr>
				<td class="td_title">住所</td>
				<td><?php print_r($sheet_para[0][5]); ?></td>
			</tr>
			<tr>
				<td class="td_title">メールアドレス</td>
				<td><?php print_r($sheet_para[0][6]); ?><span class="input_comm">※半角英数</span></td>
			</tr>
		</table>
		<div class="del_all"><img src="img/yajirushi.gif" width="410" height="130"></div>
		<table class="reserve">
			<tr>
				<td class="<?php if($name == $sheet_para[0][1]){print "td_title";} else{print "td_title2";} ?>">氏名</td>
				<td width="320"><?php print "$name"; ?></td>
				<td class="<?php if($name_f == $sheet_para[0][2]){print "td_title";} else{print "td_title2";} ?>">氏名（フリガナ）</td>
				<td><?php print "$name_f"; ?></td>
			</tr>
			<tr>
				<td class="<?php if($tel == $sheet_para[0][3]){print "td_title";} else{print "td_title2";} ?>">電話番号</td>
				<td><?php print "$tel"; ?><span class="input_comm">※半角英数</span></td>
				<td class="<?php if($check_car == 0){print "td_title";} else{print "td_title2";} ?>" rowspan="4">作業車両</td>
				<td rowspan="4">
					<div>車種：<?php print "$car"; ?></div>
					<div class="space3px"></div>
					<div>車輌型式：<?php print "$car_model"; ?></div>
					<div class="space3px"></div>
					<div>エンジン：<?php print "$engine"; ?></div>
					<div class="space3px"></div>
					<div>年式：<?php print "$car_old"; ?></div>
					<div class="space3px"></div>
					<div>駆動：<?php print "$drive"; ?></div>
				</td>
			</tr>
			<tr>
				<td class="<?php if($p_code == $sheet_para[0][4]){print "td_title";} else{print "td_title2";} ?>">郵便番号</td>
				<td><?php print "$p_code"; ?><span class="input_comm">※半角英数</span></td>
			<tr>
				<td class="<?php if($address == $sheet_para[0][5]){print "td_title";} else{print "td_title2";} ?>">住所</td>
				<td><?php print "$address"; ?></td>
			</tr>
				<td class="<?php if($mail == $sheet_para[0][6]){print "td_title";} else{print "td_title2";} ?>">メールアドレス</td>
				<td><?php print "$mail"; ?><span class="input_comm">※半角英数</span></td>
			</tr>
		</table>
		<form method="post" action="work_sheet_modi_push.php">
		<input type="hidden" name="year" value="<?php print "$year"; ?>">
		<input type="hidden" name="mon" value="<?php print "$mon"; ?>">
		<input type="hidden" name="day" value="<?php print "$day"; ?>">
		<input type="hidden" name="sheet_file" value="<?php print "$sheet_file"; ?>">
		<input type="hidden" name="modi_no" value="0">
		<input type="hidden" name="name" value="<?php print "$name"; ?>">
		<input type="hidden" name="name_f" value="<?php print "$name_f"; ?>">
		<input type="hidden" name="tel" value="<?php print "$tel"; ?>">
		<input type="hidden" name="p_code" value="<?php print "$p_code"; ?>">
		<input type="hidden" name="address" value="<?php print "$address"; ?>">
		<input type="hidden" name="mail" value="<?php print "$mail"; ?>">
		<input type="hidden" name="car" value="<?php print "$car"; ?>">
		<input type="hidden" name="car_model" value="<?php print "$car_model"; ?>">
		<input type="hidden" name="engine" value="<?php print "$engine"; ?>">
		<input type="hidden" name="car_old" value="<?php print "$car_old"; ?>">
		<input type="hidden" name="drive" value="<?php print "$drive"; ?>">
		<div class="space10px"></div>
		<div class="user_check">この内容で宜しければ下のボタンをクリックして下さい</div>
		<div class="space10px"></div>
		<div class="del_all"><input class="del_all_cau" type="submit" value="お客様情報を修正する"></div>
		</form>
		<div class="space10px"></div>
		<div class="footer">Copyright (C) Kind Techno Structure Corporation. All Rights Reserved.</div>
	</body>
</html>
<?php endif;?>
<?php if($modi_no == 0){exit();} ?>
<?php /* 作業内容の修正 */ ?>
<?php if($sagyou_check == 1 and $modi_no != 0):?>
		<div class="user_check">修正箇所に入力漏れ・誤りがあります。確認の上、やり直して下さい。</div>
		<div class="space10px"></div>
		<div class="footer">Copyright (C) Kind Techno Structure Corporation. All Rights Reserved.</div>
	</body>
</html>
<?php endif;?>
<?php if($sagyou_check == 1){exit();} ?>
<?php if($modi_no != 0):?>
		<table class="reserve">
			<tr>
				<td class="td_title2">日時</td>
				<td width="270">
					<?php if($sheet_para[$modi_no][1] == 0){print "9時00分";} ?>
					<?php if($sheet_para[$modi_no][1] == 1){print "9時30分";} ?>
					<?php if($sheet_para[$modi_no][1] == 2){print "10時00分";} ?>
					<?php if($sheet_para[$modi_no][1] == 3){print "10時30分";} ?>
					<?php if($sheet_para[$modi_no][1] == 4){print "11時00分";} ?>
					<?php if($sheet_para[$modi_no][1] == 5){print "11時30分";} ?>
					<?php if($sheet_para[$modi_no][1] == 6){print "12時00分";} ?>
					<?php if($sheet_para[$modi_no][1] == 7){print "12時30分";} ?>
					<?php if($sheet_para[$modi_no][1] == 8){print "13時00分";} ?>
					<?php if($sheet_para[$modi_no][1] == 9){print "13時30分";} ?>
					<?php if($sheet_para[$modi_no][1] == 10){print "14時00分";} ?>
					<?php if($sheet_para[$modi_no][1] == 11){print "14時30分";} ?>
					<?php if($sheet_para[$modi_no][1] == 12){print "15時00分";} ?>
					<?php if($sheet_para[$modi_no][1] == 13){print "15時30分";} ?>
					<?php if($sheet_para[$modi_no][1] == 14){print "16時00分";} ?>
					<?php if($sheet_para[$modi_no][1] == 15){print "16時30分";} ?>
					<?php if($sheet_para[$modi_no][1] == 16){print "17時00分";} ?>
					<?php if($sheet_para[$modi_no][1] == 17){print "17時30分";} ?>
					<?php if($sheet_para[$modi_no][1] == 18){print "18時00分";} ?>
					<?php if($sheet_para[$modi_no][1] == 19){print "18時30分";} ?>
					<?php if($sheet_para[$modi_no][1] == 20){print "19時00分";} ?>
					<?php if($sheet_para[$modi_no][1] == 21){print "19時30分";} ?>
					～
					<?php if($sheet_para[$modi_no][2] == 1){print "9時30分";} ?>
					<?php if($sheet_para[$modi_no][2] == 2){print "10時00分";} ?>
					<?php if($sheet_para[$modi_no][2] == 3){print "10時30分";} ?>
					<?php if($sheet_para[$modi_no][2] == 4){print "11時00分";} ?>
					<?php if($sheet_para[$modi_no][2] == 5){print "11時30分";} ?>
					<?php if($sheet_para[$modi_no][2] == 6){print "12時00分";} ?>
					<?php if($sheet_para[$modi_no][2] == 7){print "12時30分";} ?>
					<?php if($sheet_para[$modi_no][2] == 8){print "13時00分";} ?>
					<?php if($sheet_para[$modi_no][2] == 9){print "13時30分";} ?>
					<?php if($sheet_para[$modi_no][2] == 10){print "14時00分";} ?>
					<?php if($sheet_para[$modi_no][2] == 11){print "14時30分";} ?>
					<?php if($sheet_para[$modi_no][2] == 12){print "15時00分";} ?>
					<?php if($sheet_para[$modi_no][2] == 13){print "15時30分";} ?>
					<?php if($sheet_para[$modi_no][2] == 14){print "16時00分";} ?>
					<?php if($sheet_para[$modi_no][2] == 15){print "16時30分";} ?>
					<?php if($sheet_para[$modi_no][2] == 16){print "17時00分";} ?>
					<?php if($sheet_para[$modi_no][2] == 17){print "17時30分";} ?>
					<?php if($sheet_para[$modi_no][2] == 18){print "18時00分";} ?>
					<?php if($sheet_para[$modi_no][2] == 19){print "18時30分";} ?>
					<?php if($sheet_para[$modi_no][2] == 20){print "19時00分";} ?>
					<?php if($sheet_para[$modi_no][2] == 21){print "19時30分";} ?>
					<?php if($sheet_para[$modi_no][2] == 22){print "20時00分";} ?>
				</td>
				<td class="td_title2">リフト</td>
				<td width="270">
					<?php if($sheet_para[$modi_no][3] == 1){print "アライメントA";} ?>
					<?php if($sheet_para[$modi_no][3] == 4){print "アライメントB";} ?>
					<?php if($sheet_para[$modi_no][3] == 7){print "2FメインリフトA";} ?>
					<?php if($sheet_para[$modi_no][3] == 10){print "2FメインリフトB";} ?>
					<?php if($sheet_para[$modi_no][3] == 13){print "サブ";} ?>
				</td>
			</tr>
			<tr>
				<td class="td_title2">販売区分</td>
				<td>
					<?php if($sheet_para[$modi_no][4] == 1){print "アライメント";} ?>
					<?php if($sheet_para[$modi_no][4] == 2){print "店舗客注";} ?>
					<?php if($sheet_para[$modi_no][4] == 3){print "持ち込み";} ?>
					<?php if($sheet_para[$modi_no][4] == 4){print "WEB取り付け";} ?>
					<?php if($sheet_para[$modi_no][4] == 5){print "在庫KEEP";} ?>
					<?php if($sheet_para[$modi_no][4] == 0){print "仮予約";} ?>
				</td>
				<td class="td_title2">担当者</td>
				<td><?php print_r($sheet_para[$modi_no][5]); ?></td>
			</tr>
			<tr><td colspan="4" class="td_title2">作業内容</td></tr>
			<tr><td colspan="4"><?php print_r($sheet_para[$modi_no][6]); ?></td></tr>
			<tr><td colspan="4" class="td_title2">メモ欄・工賃</td></tr>
			<tr><td colspan="4"><?php print_r($sheet_para[$modi_no][7]); ?></td></tr>
			<tr><td colspan="4" class="td_title2">表示アイコン</td></tr>
			<tr>
				<td colspan="4" class="icon_td">
					<?php if($sheet_para[$modi_no][8] == 0){print "特になし";} ?>
					<?php if($sheet_para[$modi_no][8] == 1){print "<img src=img/mail_none.gif width=90 height=20 alt=メール未送信>";} ?>
					<?php if($sheet_para[$modi_no][8] == 2){print "<img src=img/minyuka.gif width=90 height=20 alt=商品未入荷>";} ?>
					<?php if($sheet_para[$modi_no][8] == 3){print "<img src=img/nouki.gif width=90 height=20 alt=納期要確認>";} ?>
					<?php if($sheet_para[$modi_no][8] == 4){print "<img src=img/minou.gif width=90 height=20 alt=未納あり>";} ?>
					<?php if($sheet_para[$modi_no][8] == 5){print "<img src=img/daisya.gif width=90 height=20 alt=代車使用>";} ?>
				</td>
			</tr>
		</table>
		<div class="del_all"><img src="img/yajirushi.gif" width="410" height="130"></div>
		<table class="reserve">
			<tr>
				<td class="<?php if($time_s != $sheet_para[$modi_no][1] or $time_e != $sheet_para[$modi_no][2]){print "td_title"; $time_check = 1;} else{print "td_title2";} ?>">日時</td>
				<td width="270">
					<?php if($time_s == 0){print "9時00分";} ?>
					<?php if($time_s == 1){print "9時30分";} ?>
					<?php if($time_s == 2){print "10時00分";} ?>
					<?php if($time_s == 3){print "10時30分";} ?>
					<?php if($time_s == 4){print "11時00分";} ?>
					<?php if($time_s == 5){print "11時30分";} ?>
					<?php if($time_s == 6){print "12時00分";} ?>
					<?php if($time_s == 7){print "12時30分";} ?>
					<?php if($time_s == 8){print "13時00分";} ?>
					<?php if($time_s == 9){print "13時30分";} ?>
					<?php if($time_s == 10){print "14時00分";} ?>
					<?php if($time_s == 11){print "14時30分";} ?>
					<?php if($time_s == 12){print "15時00分";} ?>
					<?php if($time_s == 13){print "15時30分";} ?>
					<?php if($time_s == 14){print "16時00分";} ?>
					<?php if($time_s == 15){print "16時30分";} ?>
					<?php if($time_s == 16){print "17時00分";} ?>
					<?php if($time_s == 17){print "17時30分";} ?>
					<?php if($time_s == 18){print "18時00分";} ?>
					<?php if($time_s == 19){print "18時30分";} ?>
					<?php if($time_s == 20){print "19時00分";} ?>
					<?php if($time_s == 21){print "19時30分";} ?>
					～
					<?php if($time_e == 1){print "9時30分";} ?>
					<?php if($time_e == 2){print "10時00分";} ?>
					<?php if($time_e == 3){print "10時30分";} ?>
					<?php if($time_e == 4){print "11時00分";} ?>
					<?php if($time_e == 5){print "11時30分";} ?>
					<?php if($time_e == 6){print "12時00分";} ?>
					<?php if($time_e == 7){print "12時30分";} ?>
					<?php if($time_e == 8){print "13時00分";} ?>
					<?php if($time_e == 9){print "13時30分";} ?>
					<?php if($time_e == 10){print "14時00分";} ?>
					<?php if($time_e == 11){print "14時30分";} ?>
					<?php if($time_e == 12){print "15時00分";} ?>
					<?php if($time_e == 13){print "15時30分";} ?>
					<?php if($time_e == 14){print "16時00分";} ?>
					<?php if($time_e == 15){print "16時30分";} ?>
					<?php if($time_e == 16){print "17時00分";} ?>
					<?php if($time_e == 17){print "17時30分";} ?>
					<?php if($time_e == 18){print "18時00分";} ?>
					<?php if($time_e == 19){print "18時30分";} ?>
					<?php if($time_e == 20){print "19時00分";} ?>
					<?php if($time_e == 21){print "19時30分";} ?>
					<?php if($time_e == 22){print "20時00分";} ?>
				</td>
				<td class="<?php if($lift != $sheet_para[$modi_no][3]){print "td_title"; $lift_change = 1;} else{print "td_title2";} ?>">リフト</td>
				<td width="270">
					<?php if($lift == 1){print "アライメントA";} ?>
					<?php if($lift == 4){print "アライメントB";} ?>
					<?php if($lift == 7){print "2FメインリフトA";} ?>
					<?php if($lift == 10){print "2FメインリフトB";} ?>
					<?php if($lift == 13){print "サブ";} ?>
				</td>
			</tr>
			<tr>
				<td class="<?php if($hanbai != $sheet_para[$modi_no][4]){print "td_title";} else{print "td_title2";} ?>">販売区分</td>
				<td>
					<?php if($hanbai == 1){print "アライメント";} ?>
					<?php if($hanbai == 2){print "店舗客注";} ?>
					<?php if($hanbai == 3){print "持ち込み";} ?>
					<?php if($hanbai == 4){print "WEB取り付け";} ?>
					<?php if($hanbai == 5){print "在庫KEEP";} ?>
					<?php if($hanbai == 0){print "仮予約";} ?>
				</td>
				<td class="<?php if($tantou != $sheet_para[$modi_no][5]){print "td_title";} else{print "td_title2";} ?>">担当者</td>
				<td><?php print "$tantou"; ?></td>
			</tr>
			<tr><td colspan="4" class="<?php if($sagyou != $sheet_para[$modi_no][6]){print "td_title";} else{print "td_title2";} ?>">作業内容</td></tr>
			<tr><td colspan="4"><?php print "$sagyou"; ?></td></tr>
			<tr><td colspan="4" class="<?php if($memo != $sheet_para[$modi_no][7]){print "td_title";} else{print "td_title2";} ?>">メモ欄・工賃</td></tr>
			<tr><td colspan="4"><?php print "$memo"; ?></td></tr>
			<tr><td colspan="4" class="<?php if($icon != $sheet_para[$modi_no][8]){print "td_title"; $icon_check = 1;} else{print "td_title2";} ?>">表示アイコン</td></tr>
			<tr>
				<td colspan="4" class="icon_td">
					<?php if($icon == 0){print "特になし";} ?>
					<?php if($icon == 1){print "<img src=img/mail_none.gif width=90 height=20 alt=メール未送信>";} ?>
					<?php if($icon == 2){print "<img src=img/minyuka.gif width=90 height=20 alt=商品未入荷>";} ?>
					<?php if($icon == 3){print "<img src=img/nouki.gif width=90 height=20 alt=納期要確認>";} ?>
					<?php if($icon == 4){print "<img src=img/minou.gif width=90 height=20 alt=未納あり>";} ?>
					<?php if($icon == 5){print "<img src=img/daisya.gif width=90 height=20 alt=代車使用>";} ?>
				</td>
			</tr>
		</table>
		<form method="post" action="work_sheet_modi_push.php">
		<input type="hidden" name="year" value="<?php print "$year"; ?>">
		<input type="hidden" name="mon" value="<?php print "$mon"; ?>">
		<input type="hidden" name="day" value="<?php print "$day"; ?>">
		<input type="hidden" name="sheet_file" value="<?php print "$sheet_file"; ?>">
		<input type="hidden" name="modi_no" value="<?php print "$modi_no"; ?>">
		<input type="hidden" name="time_s" value="<?php print "$time_s"; ?>">
		<input type="hidden" name="time_e" value="<?php print "$time_e"; ?>">
		<input type="hidden" name="lift" value="<?php print "$lift"; ?>">
		<input type="hidden" name="hanbai" value="<?php print "$hanbai"; ?>">
		<input type="hidden" name="tantou" value="<?php print "$tantou"; ?>">
		<input type="hidden" name="sagyou" value="<?php print "$sagyou"; ?>">
		<input type="hidden" name="memo" value="<?php print "$memo"; ?>">
		<input type="hidden" name="icon" value="<?php print "$icon"; ?>">
		<input type="hidden" name="time_check" value="<?php print "$time_check"; ?>">
		<input type="hidden" name="lift_change" value="<?php print "$lift_change"; ?>">
		<input type="hidden" name="icon_check" value="<?php print "$icon_check"; ?>">
		<div class="space10px"></div>
		<div class="user_check">この内容で宜しければ下のボタンをクリックして下さい</div>
		<div class="space10px"></div>
		<div class="user_check"><input class="del_all_cau" type="submit" value="作業内容を修正する"></div>
		</form>
		<div class="space10px"></div>
		<div class="footer">Copyright (C) Kind Techno Structure Corporation. All Rights Reserved.</div>
	</body>
</html>
<?php endif;?>
<?php if($modi_no != 0){exit();} ?>
