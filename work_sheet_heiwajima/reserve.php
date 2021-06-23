<?php
	/* 各データの受け取り */
	$year = $_POST['year'];
	$mon = $_POST['mon'];
	$day = $_POST['day'];
	/* $day_file = $_POST['day_file']; 毎回生成する */
	$lift = $_POST['lift'];
	$time_s = $_POST['time_s'];
	
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

	/* リフトIDの名称変換 */
	if($lift == 1){$lift_f = "アライメントA";} /* アライメント */
	elseif($lift == 4){$lift_f = "アライメントB";} /* アライメント */
	elseif($lift == 7){$lift_f = "2FメインリフトA";}
	elseif($lift == 10){$lift_f = "2FメインリフトB";}
	elseif($lift == 13){$lift_f = "サブ";} /* リフトを使用しない作業 */

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
				<td class="font_ymd" width="50%"><?php print "$year"; ?>年<?php print "$mon"; ?>月<?php print "$day"; ?>日（<?php print "$wday"; ?>）</td>
				<td>
					<form method="post" action="day_work.php">
						<input type="hidden" name="year" value="<?php print "$year"; ?>">
						<input type="hidden" name="mon" value="<?php print "$mon"; ?>">
						<input type="hidden" name="day" value="<?php print "$day"; ?>">
						<input type="submit" value="前のページに戻る">
					</form>
				</td>
				<td><div><a href=index.php>月間表示画面へ戻る</a></div></td>
			</tr>
		</table>
<?php /* 顧客情報部分 */ ?>
		<div class="space10px"></div>
		<form method="post" action="reserve_check.php">
		<div class="center_900"><input type="checkbox" name="kariyoyaku" value="1">仮予約の場合は左のチェックボックスをチェックして下さい。</div>
		<div class="center_900"><span class="input_comm">※仮予約を行う場合は必ず担当者を選択して下さい</span></div>
		<div class="space5px"></div>
		<table class="reserve">
			<tr>
				<td class="td_title">氏名</td>
				<td width="320"><input type="text" name="name"></td>
				<td class="td_title">氏名（フリガナ)</td>
				<td><input type="text" name="name_f"></td>
			</tr>
			<tr>
				<td class="td_title">電話番号</td>
				<td><input type="text" name="tel"><span class="input_comm">※半角英数</span></td>
				<td class="td_title" rowspan="4">作業車両</td>
				<td rowspan="4">
					<div>車種：<input type="text" name="car" class="reserve_input"></div>
					<div class="space3px"></div>
					<div>車輌型式：<input type="text" name="model" class="reserve_input"></div>
					<div class="space3px"></div>
					<div>エンジン：<input type="text" name="engine" class="reserve_input"></div>
					<div class="space3px"></div>
					<div>年式：<input type="text" name="car_old" class="reserve_input"></div>
					<div class="space3px"></div>
					<div>駆動：<input type="text" name="drive" class="reserve_input"></div>
				</td>
			</tr>
			<tr>
				<td class="td_title">郵便番号</td>
				<td><input type="text" name="p_code"><span class="input_comm">※半角英数</span></td>
			</tr>
			<tr>
				<td class="td_title">住所</td>
				<td><input type="text" name="address"></td>
			</tr>
			<tr>
				<td class="td_title">メールアドレス</td>
				<td><input type="text" name="mail"><span class="input_comm">※半角英数</span></td>
			</tr>
		</table>
		<div class="space10px"></div>
<?php /* 受注作業部分 */ ?>
		<table class="reserve">
			<tr>
				<td class="td_title2">日時</td>
				<td width="320"><?php print "$time_sf" . "～"; ?>
					<select name="time_e">
						<option value="none" selected="selected">選択</option>
						<option value="1">9時30分</option>
						<option value="2">10時00分</option>
						<option value="3">10時30分</option>
						<option value="4">11時00分</option>
						<option value="5">11時30分</option>
						<option value="6">12時00分</option>
						<option value="7">12時30分</option>
						<option value="8">13時00分</option>
						<option value="9">13時30分</option>
						<option value="10">14時00分</option>
						<option value="11">14時30分</option>
						<option value="12">15時00分</option>
						<option value="13">15時30分</option>
						<option value="14">16時00分</option>
						<option value="15">16時30分</option>
						<option value="16">17時00分</option>
						<option value="17">17時30分</option>
						<option value="18">18時00分</option>
						<option value="19">18時30分</option>
						<option value="20">19時00分</option>
						<option value="21">19時30分</option>
						<option value="22">20時00分</option>
					<select>
				</td>
				<td class="td_title2">リフト</td>
				<td><?php print "$lift_f"; ?></td>
			</tr>
			<tr>
				<td class="td_title2">販売区分</td>
				<td>
					<select name="hanbai">
						<option value="none" selected="selected">選択</option>
						<option value="1">アライメント</option>
						<option value="2">店舗客注</option>
						<option value="3">持ち込み</option>
						<option value="4">WEB取り付け</option>
						<option value="5">在庫KEEP</option>
					</select>
				</td>
				<td class="td_title2">担当者</td>
				<td>
					<select name="tantou">
						<option value="none" selected="selected">選択</option>
						<option value="杉田">杉田</option>
						<option value="平林">平林</option>
						<option value="板倉">板倉</option>
						<option value="黒川">黒川</option>
						<option value="柳田">柳田</option>
						<option value="高橋">高橋</option>
						<option value="柴田">柴田</option>
						<option value="安達">安達</option>
						<option value="小山">小山</option>
						<option value="今井">今井</option>
					</select>
				</td>
			</tr>
			<tr><td colspan="4" class="td_title2">作業内容</td></tr>
			<tr><td colspan="4"><input type="text" name="sagyou" class="reserve_input2"></td></tr>
			<tr><td colspan="4" class="td_title2">メモ欄・工賃</td></tr>
			<tr><td colspan="4"><input type="text" name="memo" class="reserve_input2"></td></tr>
			<tr><td colspan="4" class="td_title2">表示アイコン</td></tr>
			<tr><td colspan="4" class="icon_td"><input class="icon_radio" type="radio" name="icon" value="0" checked> 特になし　　<input class="icon_radio" type="radio" name="icon" value="2"><img src="img/minyuka.gif" width="90" height="20" alt="商品未入荷">　　<input class="icon_radio" type="radio" name="icon" value="3"><img src="img/nouki.gif" width="90" height="20" alt="納期要確認">　　<input class="icon_radio" type="radio" name="icon" value="4"><img src="img/minou.gif" width="90" height="20" alt="未納あり">　　<input class="icon_radio" type="radio" name="icon" value="5"><img src="img/daisya.gif" width="90" height="20" alt="代車使用"></td></tr>
		</table>
		<input type="hidden" name="year" value="<?php print "$year"; ?>">
		<input type="hidden" name="mon" value="<?php print "$mon"; ?>">
		<input type="hidden" name="day" value="<?php print "$day"; ?>">
		<input type="hidden" name="lift" value="<?php print "$lift"; ?>">
		<input type="hidden" name="time_s" value="<?php print "$time_s"; ?>">
		<div class="sub_but"><input type="submit" value="確認"></div>
		</form>
		<div class="footer">Copyright (C) Kind Techno Structure Corporation. All Rights Reserved.</div>
	</body>
</html>
