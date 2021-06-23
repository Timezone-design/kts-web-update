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

	/* 入力漏れチェック */
	$input_check = 0;
	if($time_s == "" or $time_s == "none"){$c_time_s = 1; $time_s = "none";}
	if($time_e == "" or $time_e == "none"){$c_time_e = 1; $input_check = 1;}
	if($time_e <= $time_s){$c_time_e = 1; $input_check = 1;}
	if($lift == "" or $lift == "none"){$c_lift = 1; $input_check = 1;}
	if($hanbai == "" or $hanbai == "none"){$c_hanbai = 1; $input_check = 1;}
	if($tantou == "" or $tantou == "none"){$c_tantou = 1; $input_check = 1;}
	if($sagyou == ""){$c_sagyou = 1; $input_check = 1;}
	if($kariyoyaku == 1 and $input_check == 1){$input_check = 2;}
	if($kariyoyaku == 1 and $input_check == 0){$input_check = 0;}
	/* 時間修正判定フラグ */
	if($c_time_s == 1 or $c_lift == 1){$input_check = 1;}

	/* 仮予約時の自動入力 */
	if($input_check == 2){
		if($c_time_e == 1){$c_time_e = 0; $time_e = $time_s + 2;} /* 2コマ（1時間の枠をキープ） */
		if($c_hanbai == 1){$c_hanbai = 0; $hanbai = 0;} /* 表示は仮予約。通常時選択不可。 */
		if($c_sagyou == 1){$c_sagyou = 0; $sagyou = "仮予約";}
	}

	/* 担当者選択チェック */
	if($c_tantou == 1){$input_check = 1;}

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

	/* 販売区分の名称変換 */
	if($hanbai == 1){$hanbai_f = "アライメント";}
	elseif($hanbai == 2){$hanbai_f = "店舗客注";}
	elseif($hanbai == 3){$hanbai_f = "持ち込み";}
	elseif($hanbai == 4){$hanbai_f = "WEB取り付け";}
	elseif($hanbai == 5){$hanbai_f = "在庫KEEP";}
	elseif($hanbai == 0){$hanbai_f = "仮予約";}
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
			</tr>
		</table>
		<table class="day_work_comm">
			<tr>
				<td class="day_work_date" width="50%"><?php print "$year"; ?>年<?php print "$mon"; ?>月<?php print "$day"; ?>日（<?php print "$wday"; ?>）</td>
				<td>
					<form method="post" action="work_sheet.php">
						<input type="hidden" name="year" value="<?php print "$year"; ?>">
						<input type="hidden" name="mon" value="<?php print "$mon"; ?>">
						<input type="hidden" name="day" value="<?php print "$day"; ?>">
						<input type="hidden" name="sheet_file" value="<?php print "$sheet_file"; ?>">
						<input type="submit" value="前のページに戻る">
					</form>
				</td>
				<td><div><a href="index.php">月間表示画面へ戻る</a></div></td>
			</tr>
		</table>
		<div class="space10px"></div>
<?php /* 入力漏れの場合 */ ?>
<?php if($input_check == 1):?>
		<div class="input_comm2">*の箇所が未入力です</div>
		<form method="post" action="work_sheet_push_check.php">
		<div class="center_900"><input type="checkbox" name="kariyoyaku" value="1">仮予約の場合は左のチェックボックスをチェックして下さい。</div>
		<div class="center_900"><span class="input_comm">※仮予約を行う場合は必ず開始時間・使用リフト・担当者を選択して下さい</span></div>
		<div class="space5px"></div>
		<table class="reserve">
			<tr>
				<td class="td_title2"><?php if($c_time_s == 1 or $c_time_e == 1){print "<span class=input_comm2>*</span>";} ?>日時</td>
				<td width="270">
					<select name="time_s">
						<option value="none"<?php if($c_time_s == 1){print " selected";} ?>>選択</option>
						<option value="0"<?php if($time_s == 0 and $c_time_s != 1){print " selected";} ?>>9時00分</option>
						<option value="1"<?php if($time_s == 1){print " selected";} ?>>9時30分</option>
						<option value="2"<?php if($time_s == 2){print " selected";} ?>>10時00分</option>
						<option value="3"<?php if($time_s == 3){print " selected";} ?>>10時30分</option>
						<option value="4"<?php if($time_s == 4){print " selected";} ?>>11時00分</option>
						<option value="5"<?php if($time_s == 5){print " selected";} ?>>11時30分</option>
						<option value="6"<?php if($time_s == 6){print " selected";} ?>>12時00分</option>
						<option value="7"<?php if($time_s == 7){print " selected";} ?>>12時30分</option>
						<option value="8"<?php if($time_s == 8){print " selected";} ?>>13時00分</option>
						<option value="9"<?php if($time_s == 9){print " selected";} ?>>13時30分</option>
						<option value="10"<?php if($time_s == 10){print " selected";} ?>>14時00分</option>
						<option value="11"<?php if($time_s == 11){print " selected";} ?>>14時30分</option>
						<option value="12"<?php if($time_s == 12){print " selected";} ?>>15時00分</option>
						<option value="13"<?php if($time_s == 13){print " selected";} ?>>15時30分</option>
						<option value="14"<?php if($time_s == 14){print " selected";} ?>>16時00分</option>
						<option value="15"<?php if($time_s == 15){print " selected";} ?>>16時30分</option>
						<option value="16"<?php if($time_s == 16){print " selected";} ?>>17時00分</option>
						<option value="17"<?php if($time_s == 17){print " selected";} ?>>17時30分</option>
						<option value="18"<?php if($time_s == 18){print " selected";} ?>>18時00分</option>
						<option value="19"<?php if($time_s == 19){print " selected";} ?>>18時30分</option>
						<option value="20"<?php if($time_s == 20){print " selected";} ?>>19時00分</option>
						<option value="21"<?php if($time_s == 21){print " selected";} ?>>19時30分</option>
					</select>
					～
					<select name="time_e">
						<option value="none"<?php if($c_time_e == 1){print " selected";} ?>>選択</option>
						<option value="1"<?php if($time_e == 1){print " selected";} ?>>9時30分</option>
						<option value="2"<?php if($time_e == 2){print " selected";} ?>>10時00分</option>
						<option value="3"<?php if($time_e == 3){print " selected";} ?>>10時30分</option>
						<option value="4"<?php if($time_e == 4){print " selected";} ?>>11時00分</option>
						<option value="5"<?php if($time_e == 5){print " selected";} ?>>11時30分</option>
						<option value="6"<?php if($time_e == 6){print " selected";} ?>>12時00分</option>
						<option value="7"<?php if($time_e == 7){print " selected";} ?>>12時30分</option>
						<option value="8"<?php if($time_e == 8){print " selected";} ?>>13時00分</option>
						<option value="9"<?php if($time_e == 9){print " selected";} ?>>13時30分</option>
						<option value="10"<?php if($time_e == 10){print " selected";} ?>>14時00分</option>
						<option value="11"<?php if($time_e == 11){print " selected";} ?>>14時30分</option>
						<option value="12"<?php if($time_e == 12){print " selected";} ?>>15時00分</option>
						<option value="13"<?php if($time_e == 13){print " selected";} ?>>15時30分</option>
						<option value="14"<?php if($time_e == 14){print " selected";} ?>>16時00分</option>
						<option value="15"<?php if($time_e == 15){print " selected";} ?>>16時30分</option>
						<option value="16"<?php if($time_e == 16){print " selected";} ?>>17時00分</option>
						<option value="17"<?php if($time_e == 17){print " selected";} ?>>17時30分</option>
						<option value="18"<?php if($time_e == 18){print " selected";} ?>>18時00分</option>
						<option value="19"<?php if($time_e == 19){print " selected";} ?>>18時30分</option>
						<option value="20"<?php if($time_e == 20){print " selected";} ?>>19時00分</option>
						<option value="21"<?php if($time_e == 21){print " selected";} ?>>19時30分</option>
						<option value="22"<?php if($time_e == 22){print " selected";} ?>>20時00分</option>
					<select>
				</td>
				<td class="td_title2"><?php if($c_lift == 1){print "<span class=input_comm2>*</span>";} ?>リフト</td>
				<td width="270">
					<select name="lift">
						<option value="none"<?php if($c_lift == 1){print " selected";} ?>>選択して下さい</option>
						<option value="1"<?php if($lift == 1){print " selected";} ?>>アライメントA</option>
						<option value="4"<?php if($lift == 4){print " selected";} ?>>アライメントB</option>
						<option value="7"<?php if($lift == 7){print " selected";} ?>>2FメインリフトA>
						<option value="10"<?php if($lift == 10){print " selected";} ?>>2FメインリフトB</option>
						<option value="13"<?php if($lift == 13){print " selected";} ?>>サブ</option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="td_title2"><?php if($c_hanbai == 1){print "<span class=input_comm2>*</span>";} ?>販売区分</td>
				<td>
					<select name="hanbai">
						<option value="none"<?php if($c_hanbai == 1){print " selected";} ?>>選択</option>
						<option value="0"<?php if($hanbai == 0 and $c_hanbai != 1){print " selected";} ?>>仮予約</option>
						<option value="1"<?php if($hanbai == 1){print " selected";} ?>>アライメント</option>
						<option value="2"<?php if($hanbai == 2){print " selected";} ?>>店舗客注</option>
						<option value="3"<?php if($hanbai == 3){print " selected";} ?>>持ち込み</option>
						<option value="4"<?php if($hanbai == 4){print " selected";} ?>>WEB取り付け</option>
						<option value="5"<?php if($hanbai == 5){print " selected";} ?>>在庫KEEP</option>
					</select>
				</td>
				<td class="td_title2"><?php if($c_tantou == 1){print "<span class=input_comm2>*</span>";} ?>担当者</td>
				<td>
					<select name="tantou">
						<option value="none"<?php if($c_tantou == 1){print " =selected";} ?>>選択</option>
						<option value="仮予約"<?php if($tantou == "仮予約"){print " selected";} ?>>仮予約</option>
						<option value="杉田"<?php if($tantou == "杉田"){print " selected=selected";} ?>>杉田</option>
						<option value="平林"<?php if($tantou == "平林"){print " selected=selected";} ?>>平林</option>
						<option value="板倉"<?php if($tantou == "板倉"){print " selected=selected";} ?>>板倉</option>
						<option value="黒川"<?php if($tantou == "黒川"){print " selected=selected";} ?>>黒川</option>
						<option value="柳田"<?php if($tantou == "柳田"){print " selected=selected";} ?>>柳田</option>
						<option value="高橋"<?php if($tantou == "高橋"){print " selected=selected";} ?>>高橋</option>
						<option value="柴田"<?php if($tantou == "柴田"){print " selected=selected";} ?>>柴田</option>
						<option value="安達"<?php if($tantou == "安達"){print " selected=selected";} ?>>安達</option>
						<option value="小山"<?php if($tantou == "小山"){print " selected=selected";} ?>>小山</option>
						<option value="今井"<?php if($tantou == "今井"){print " selected=selected";} ?>>今井</option>
					</select>
				</td>
			</tr>
			<tr><td colspan="4" class="td_title2"><?php if($c_sagyou == 1){print "<span class=input_comm2>*</span>";} ?>作業内容</td></tr>
			<tr><td colspan="4"><input type="text" name="sagyou" class="reserve_input2" value="<?php print "$sagyou"; ?>"></td></tr>
			<tr><td colspan="4" class="td_title2">メモ欄・工賃</td></tr>
			<tr><td colspan="4"><input type="text" name="memo" class="reserve_input2" value="<?php print "$memo"; ?>"></td></tr>
			<tr><td colspan="4" class="td_title2">表示アイコン</td></tr>
			<tr><td colspan="4" class="icon_td"><input class="icon_radio" type="radio" name="icon" value="0"<?php if($icon == 0){print " checked";} ?>> 特になし　　<input class="icon_radio" type="radio" name="icon" value="2"<?php if($icon == 2){print " checked";} ?>><img src="img/minyuka.gif" width="90" height="20" alt="商品未入荷">　　<input class="icon_radio" type="radio" name="icon" value="3"<?php if($icon == 3){print " checked";} ?>><img src="img/nouki.gif" width="90" height="20" alt="納期要確認">　　<input class="icon_radio" type="radio" name="icon" value="4"<?php if($icon == 4){print " checked";} ?>><img src="img/minou.gif" width="90" height="20" alt="未納あり">　　<input class="icon_radio" type="radio" name="icon" value="5"<?php if($icon == 5){print " checked";} ?>><img src="img/daisya.gif" width="90" height="20" alt="代車使用"></td></tr>
		</table>
		<input type="hidden" name="year" value="<?php print "$year"; ?>">
		<input type="hidden" name="mon" value="<?php print "$mon"; ?>">
		<input type="hidden" name="day" value="<?php print "$day"; ?>">
		<input type="hidden" name="sheet_file" value="<?php print "$sheet_file"; ?>">
		<input type="hidden" name="modi_no" value="<?php print "$modi_no"; ?>">
		<div class="sub_but"><input type="submit" value="確認"></div></div>
		</form>
		<div class="footer">Copyright (C) Kind Techno Structure Corporation. All Rights Reserved.</div>
	</body>
</html>
<?php endif;?>
<?php if($input_check == 1){exit();} ?>
<?php /* 記入漏れ無し */ ?>
		<div class="center_900">下記内容で登録しても宜しいですか？</div>
		<div class="space5px"></div>
		<form method="post" action="work_sheet_anmeldung.php">
		<?php /* 仮予約の場合 */
			if($input_check == 2){
				print "<div class=input_comm2>仮予約での登録となります</div>\n";
				print "<input type=hidden name=kariyoyaku value=1>\n";
			}
		?>			
		<div class="space5px"></div>
		<table class="reserve">
			<tr>
				<td class="td_title2">日時</td>
				<td width="270"><?php print "$time_sf" . "～" . "$time_ef"; ?><input type="hidden" name="time_e" value="<?php print "$time_e"; ?>"></td>
				<td class="td_title2">リフト</td>
				<td width="270"><?php print "$lift_f"; ?></td>
			</tr>
			<tr>
				<td class="td_title2">販売区分</td>
				<td><?php print "$hanbai_f"; ?><input type="hidden" name="hanbai" value="<?php print "$hanbai"; ?>"></td>
				<td class="td_title2">担当者</td>
				<td><?php print "$tantou"; ?><input type="hidden" name="tantou" value="<?php print "$tantou"; ?>"></td>
			</tr>
			<tr><td colspan="4" class="td_title2">作業内容</td></tr>
			<tr><td colspan="4"><?php print "$sagyou"; ?><input type="hidden" name="sagyou" value="<?php print "$sagyou"; ?>"></td></tr>
			<tr><td colspan="4" class="td_title2">メモ欄・工賃</td></tr>
			<tr><td colspan="4"><?php print "$memo"; ?><input type="hidden" name="memo" value="<?php print "$memo"; ?>"></td></tr>
			<tr><td colspan="4" class="td_title2">表示アイコン</td></tr>
			<tr><td colspan="4" class="icon_td">
<?php 
	if($icon == 0){print "<input type=hidden name=icon value=0>特になし";}
	elseif($icon == 1){print "<input type=hidden name=icon value=1><img src=img/mail_none.gif width=90 height=20 alt=メール未送信>";}
	elseif($icon == 2){print "<input type=hidden name=icon value=2><img src=img/minyuka.gif width=90 height=20 alt=商品未入荷>";}
	elseif($icon == 3){print "<input type=hidden name=icon value=3><img src=img/nouki.gif width=90 height=20 alt=納期要確認>";}
	elseif($icon == 4){print "<input type=hidden name=icon value=4><img src=img/minou.gif width=90 height=20 alt=未納あり>";}
	elseif($icon == 5){print "<input type=hidden name=icon value=5><img src=img/daisya.gif width=90 height=20 alt=代車使用>";}
?>
			</td></tr>
		</table>
		<input type="hidden" name="year" value="<?php print "$year"; ?>">
		<input type="hidden" name="mon" value="<?php print "$mon"; ?>">
		<input type="hidden" name="day" value="<?php print "$day"; ?>">
		<input type="hidden" name="lift" value="<?php print "$lift"; ?>">
		<input type="hidden" name="time_s" value="<?php print "$time_s"; ?>">
		<input type="hidden" name="sheet_file" value="<?php print "$sheet_file"; ?>">
		<input type="hidden" name="modi_no" value="<?php print "$modi_no"; ?>">
		<div class="sub_but"><input type="submit" value="登録"></div>
		</form>
		<div class="footer">Copyright (C) Kind Techno Structure Corporation. All Rights Reserved.</div>
	</body>
</html>
