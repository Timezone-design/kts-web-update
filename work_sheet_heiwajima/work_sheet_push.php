<?php
	/* table高さ設定 */
	$table_h = 10;
	$table_h2 = $table_h * 2;
	/* 各データの受け取り */
	$year = $_POST['year'];
	$mon = $_POST['mon'];
	$day = $_POST['day'];
	$sheet_file = $_POST['sheet_file'];
	$modi_no = $_POST['modi_no'];

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

	/* 日別作業データの取得 */
	setlocale(LC_ALL,'ja_JP.UTF-8');
	$fp_data = fopen("log/$day_file", "r");
	/* $fp_data = fopen("sample_ymd.dat", "r"); */
	$day_para = array();
	$i = 0;
	while($ret_csv = fgetcsv($fp_data)){
		for($col = 0; $col < count($ret_csv); $col++){
			$day_para[$i][$col] = $ret_csv[$col];
		}
		$i++;
	}
	fclose($fp_data);

	/* 最終更新日時DATファイル呼び出し */
	$fp_ymd = fopen("ymd.dat","r");
	$ymd = fgets($fp_ymd);
	fclose($fp_ymd);
?>
<?php /* キャッシュの無効化 */
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
					<form method="post" action="work_sheet.php">
						<input type="hidden" name="year" value="<?php print "$year"; ?>">
						<input type="hidden" name="mon" value="<?php print "$mon"; ?>">
						<input type="hidden" name="day" value="<?php print "$day"; ?>">
						<input type="hidden" name="sheet_file" value="<?php print "$sheet_file"; ?>">
						<input type="submit" value="前のページに戻る">
					</form>
				</td>
				<td><div><a href=index.php>月間表示画面へ戻る</a></div></td>
			</tr>
		</table>
		<div class="space10px"></div>
<?php /* 作業一覧表示部分 ここから */ ?>
		<table class="center" width="900" cellspacing="0" border="0" cellpadding="0" bgcolor="#000000">
			<tr valign="top">
				<?php /* 時間枠表示 */ ?>
				<td width="4%">
					<table class="sheet_l" bgcolor="#FFFFFF" border="1" bordercolor="#000000">
						<tr><td bgcolor="#FFFF99" height="45"></td></tr>
						<tr><td bgcolor="#FFFFCC" height="<?php print "$table_h2"; ?>">9</td></tr>
						<tr><td bgcolor="#FFFFCC" height="<?php print "$table_h2"; ?>">10</td></tr>
						<tr><td bgcolor="#FFFFCC" height="<?php print "$table_h2"; ?>">11</td></tr>
						<tr><td bgcolor="#FFFFCC" height="<?php print "$table_h2"; ?>">12</td></tr>
						<tr><td bgcolor="#FFFFCC" height="<?php print "$table_h2"; ?>">13</td></tr>
						<tr><td bgcolor="#FFFFCC" height="<?php print "$table_h2"; ?>">14</td></tr>
						<tr><td bgcolor="#FFFFCC" height="<?php print "$table_h2"; ?>">15</td></tr>
						<tr><td bgcolor="#FFFFCC" height="<?php print "$table_h2"; ?>">16</td></tr>
						<tr><td bgcolor="#FFFFCC" height="<?php print "$table_h2"; ?>">17</td></tr>
						<tr><td bgcolor="#FFFFCC" height="<?php print "$table_h2"; ?>">18</td></tr>
						<tr><td bgcolor="#FFFFCC" height="<?php print "$table_h2"; ?>">19</td></tr>
					</table>
				</td>
				<?php /* アライメントA */ ?>
				<td width="19.2%">
					<table class="sheet" bgcolor="#FFFFFF" border="1" bordercolor="#000000">
						<tr><td bgcolor="#FFFF99" height="45"><div><strong>アライメントA</strong></div></td></tr>
<?php
	/* 日別作業ファイルから一覧表を作成 */
	/* 配列の中が数値かチェック */
	for($i = 0; $i <= 21; $i++){
		$int_c = ctype_digit($day_para[1][$i]);
		if($int_c == TRUE){
			$td_r = $day_para[1][$i]; /* 必要コマ数の数値代入 */
			$td_h = $table_h * $td_r; /* td属性の高さ確定 */
			if($sheet_file == $day_para[2][$i]){print "<tr><td class=sheet_yoyaku_red height=$td_h> </td></tr>";}
			else{print "<tr><td class=sheet_yoyaku height=$td_h> </td></tr>";}
			$i = $i + $td_r - 1;
		}
		else{print "<tr><td height=$table_h> </td></tr>";}
	}
?>
					</table>
				</td>
				<?php /* アライメントB */ ?>
				<td width="19.2%">
					<table class="sheet" bgcolor="#FFFFFF" border="1" bordercolor="#000000">
						<tr><td bgcolor="#FFFF99" height="45"><div><strong>アライメントB</strong></div></td></tr>
<?php
	/* 日別作業ファイルから一覧表を作成 */
	/* 配列の中が数値かチェック */
	for($i = 0; $i <= 21; $i++){
		$int_c = ctype_digit($day_para[4][$i]);
		if($int_c == TRUE){
			$td_r = $day_para[4][$i]; /* 必要コマ数の数値代入 */
			$td_h = $table_h * $td_r; /* td属性の高さ確定 */
			if($sheet_file == $day_para[5][$i]){print "<tr><td class=sheet_yoyaku_red height=$td_h> </td></tr>";}
			else{print "<tr><td class=sheet_yoyaku height=$td_h> </td></tr>";}
			$i = $i + $td_r - 1;
		}
		else{print "<tr><td height=$table_h> </td></tr>";}
	}
?>
					</table>
				</td>
				<?php /* 2FメインリフトA */ ?>
				<td width="16%">
					<table class="sheet" bgcolor="#FFFFFF" border="1" bordercolor="#000000">
						<tr><td bgcolor="#FFFF99" height="45"><strong>2FメインリフトA</strong></td></tr>
<?php
	/* 日別作業ファイルから一覧表を作成 */
	/* 配列の中が数値かチェック */
	for($i = 0; $i <= 21; $i++){
		$int_c = ctype_digit($day_para[7][$i]);
		if($int_c == TRUE){
			$td_r = $day_para[7][$i]; /* 必要コマ数の数値代入 */
			$td_h = $table_h * $td_r; /* td属性の高さ確定 */
			if($sheet_file == $day_para[8][$i]){print "<tr><td class=sheet_yoyaku_red height=$td_h> </td></tr>";}
			else{print "<tr><td class=sheet_yoyaku height=$td_h> </td></tr>";}
			$i = $i + $td_r - 1;
		}
		else{print "<tr><td height=$table_h> </td></tr>";}
	}
?>
					</table>
				</td>
				<?php /* 2FメインリフトB */ ?>
				<td width="19.2%">
					<table class="sheet" bgcolor="#FFFFFF" border="1" bordercolor="#000000">
						<tr><td bgcolor="#FFFF99" height="45"><div><strong>2FメインリフトB</strong></div></td></tr>
<?php
	/* 日別作業ファイルから一覧表を作成 */
	/* 配列の中が数値かチェック */
	for($i = 0; $i <= 21; $i++){
		$int_c = ctype_digit($day_para[10][$i]);
		if($int_c == TRUE){
			$td_r = $day_para[10][$i]; /* 必要コマ数の数値代入 */
			$td_h = $table_h * $td_r; /* td属性の高さ確定 */
			if($sheet_file == $day_para[11][$i]){print "<tr><td class=sheet_yoyaku_red height=$td_h> </td></tr>";}
			else{print "<tr><td class=sheet_yoyaku height=$td_h> </td></tr>";}
			$i = $i + $td_r - 1;
		}
		else{print "<tr><td height=$table_h> </td></tr>";}
	}
?>
					</table>
				</td>
				<?php /* サブ */ ?>
				<td width="19.2%">
					<table class="sheet" bgcolor="#FFFFFF" border="1" bordercolor="#000000">
						<tr><td bgcolor="#FFFF99" height="45"><div><strong>サブ</strong></div></td></tr>
<?php
	/* 日別作業ファイルから一覧表を作成 */
	/* 配列の中が数値かチェック */
	for($i = 0; $i <= 21; $i++){
		$int_c = ctype_digit($day_para[13][$i]);
		if($int_c == TRUE){
			$td_r = $day_para[13][$i]; /* 必要コマ数の数値代入 */
			$td_h = $table_h * $td_r; /* td属性の高さ確定 */
			if($sheet_file == $day_para[14][$i]){print "<tr><td class=sheet_yoyaku_red height=$td_h> </td></tr>";}
			else{print "<tr><td class=sheet_yoyaku height=$td_h> </td></tr>";}
			$i = $i + $td_r - 1;
		}
		else{print "<tr><td height=$table_h> </td></tr>";}
	}
?>
					</table>
				</td>
			</tr>
		</table>
		<table class="center" width="400">
			<tr>
				<td class="sheet_yoyaku" width="15"></td>
				<td width="185">：予約済み</td>
				<td class="sheet_yoyaku_red" width="15"></td>
				<td width="185">：予約済み（同一車両）</td>
			</tr>
		</table>
<?php /* 作業一覧表示部分 ここまで */ ?>
		<div class="space10px"></div>
		<form method="post" action="work_sheet_push_check.php">
		<div class="center_900"><input type="checkbox" name="kariyoyaku" value="1">仮予約の場合は左のチェックボックスをチェックして下さい。</div>
		<div class="center_900"><span class="input_comm">※仮予約を行う場合は必ず開始時間・使用リフト・担当者を選択して下さい</span></div>
		<div class="space5px"></div>
		<table class="reserve">
			<tr>
				<td class="td_title2">日時</td>
				<td width="270">
					<select name="time_s">
						<option value="none" selected="selected">選択</option>
						<option value="0">9時00分</option>
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
					<select>
					～
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
					</select>
				</td>
				<td class="td_title2">リフト</td>
				<td width="270">
					<select name="lift">
						<option value="none" selected="selected">選択</option>
						<option value="1">アライメントA</option>
						<option value="4">アライメントB</option>
						<option value="7">2FメインリフトA</option>
						<option value="10">2FメインリフトB</option>
						<option value="13">サブ</option>
					</select>
				</td>
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
		<input type="hidden" name="sheet_file" value="<?php print "$sheet_file"; ?>">
		<input type="hidden" name="modi_no" value="<?php print "$modi_no"; ?>">
		<div class="sub_but"><input type="submit" value="確認"></div>
		</form>
		<div class="footer">Copyright (C) Kind Techno Structure Corporation. All Rights Reserved.</div>
	</body>
</html>
