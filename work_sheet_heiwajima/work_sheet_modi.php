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

	/* 日別作業データの取得 */
	setlocale(LC_ALL,'ja_JP.UTF-8');
	$fp_data_d = fopen("log/$day_file", "r");
	/* $fp_data = fopen("sample_ymd.dat", "r"); */
	$day_para = array();
	$i = 0;
	while($ret_csv = fgetcsv($fp_data_d)){
		for($col = 0; $col < count($ret_csv); $col++){
			$day_para[$i][$col] = $ret_csv[$col];
		}
		$i++;
	}
	fclose($fp_data_d);

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
<?php /* お客様情報の修正 */ ?>
<?php if($modi_no == 0):?>
		<form method="post" action="work_sheet_modi_check.php">
		<table class="reserve">
			<tr>
				<td class="td_title">氏名</td>
				<td width="320"><input type="text" name="name" value="<?php print_r($sheet_para[0][1]); ?>"></td>
				<td class="td_title">氏名（フリガナ）</td>
				<td><input type="text" name="name_f" value="<?php print_r($sheet_para[0][2]); ?>"></td>
			</tr>
			<tr>
				<td class="td_title">電話番号</td>
				<td><input type="text" name="tel" value="<?php print_r($sheet_para[0][3]); ?>"><span class="input_comm">※半角英数</span></td>
				<td class="td_title" rowspan="4">作業車両</td>
				<td rowspan="4">
					<div>車種：<input class="reserve_input" type="text" name="car" value="<?php print_r($sheet_para[0][7]); ?>"></div>
					<div class="space3px"></div>
					<div>車輌型式：<input class="reserve_input" type="text" name="car_model" value="<?php print_r($sheet_para[0][8]); ?>"></div>
					<div class="space3px"></div>
					<div>エンジン：<input class="reserve_input" name="engine" value="<?php print_r($sheet_para[0][9]); ?>"></div>
					<div class="space3px"></div>
					<div>年式：<input class="reserve_input" type="text" name="car_old" value="<?php print_r($sheet_para[0][10]); ?>"></div>
					<div class="space3px"></div>
					<div>駆動：<input class="reserve_input" type="text" name="drive" value="<?php print_r($sheet_para[0][11]); ?>"></div>
				</td>
			</tr>
			<tr>
				<td class="td_title">郵便番号</td>
				<td><input type="text" name="p_code" value="<?php print_r($sheet_para[0][4]); ?>"><span class="input_comm">※半角英数</span></td>
			</tr>
			<tr>
				<td class="td_title">住所</td>
				<td><input type="text" name="address" value="<?php print_r($sheet_para[0][5]); ?>"></td>
			</tr>
			<tr>
				<td class="td_title">メールアドレス</td>
				<td><input type="text" name="mail" value="<?php print_r($sheet_para[0][6]); ?>"><span class="input_comm">※半角英数</span></td>
			</tr>
		</table>
		<input type="hidden" name="year" value="<?php print "$year"; ?>">
		<input type="hidden" name="mon" value="<?php print "$mon"; ?>">
		<input type="hidden" name="day" value="<?php print "$day"; ?>">
		<input type="hidden" name="sheet_file" value="<?php print "$sheet_file"; ?>">
		<input type="hidden" name="modi_no" value="0">
		<div class="space10px"></div>
		<div class="del_all"><input class="sagyou_push" type="submit" value="入力内容・変更箇所を確認"></div>
		</form>
		<div class="space10px"></div>
		<div class="footer">Copyright (C) Kind Techno Structure Corporation. All Rights Reserved.</div>
	</body>
</html>
<?php endif;?>
<?php if($modi_no == 0){exit();} ?>
<?php /* 作業内容の修正 */ ?>
<?php if($modi_no != 0):?>
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
			if($sheet_file == $day_para[2][$i] and $sheet_para[$modi_no][1] == $i){print "<tr><td class=sheet_yoyaku_blue height=$td_h> </td></tr>";}
			elseif($sheet_file == $day_para[2][$i] and $sheet_para[$modi_no][1] != $i){print "<tr><td class=sheet_yoyaku_red height=$td_h> </td></tr>";}
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
			if($sheet_file == $day_para[5][$i] and $sheet_para[$modi_no][1] == $i){print "<tr><td class=sheet_yoyaku_blue height=$td_h> </td></tr>";}
			elseif($sheet_file == $day_para[5][$i] and $sheet_para[$modi_no][1] != $i){print "<tr><td class=sheet_yoyaku_red height=$td_h> </td></tr>";}
			else{print "<tr><td class=sheet_yoyaku height=$td_h> </td></tr>";}
			$i = $i + $td_r - 1;
		}
		else{print "<tr><td height=$table_h> </td></tr>";}
	}
?>
					</table>
				</td>
				<?php /* 2FメインリフトA */ ?>
				<td width="19.2%">
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
			if($sheet_file == $day_para[8][$i] and $sheet_para[$modi_no][1] == $i){print "<tr><td class=sheet_yoyaku_blue height=$td_h> </td></tr>";}
			elseif($sheet_file == $day_para[8][$i] and $sheet_para[$modi_no][1] != $i){print "<tr><td class=sheet_yoyaku_red height=$td_h> </td></tr>";}
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
			if($sheet_file == $day_para[11][$i] and $sheet_para[$modi_no][1] == $i){print "<tr><td class=sheet_yoyaku_blue height=$td_h> </td></tr>";}
			elseif($sheet_file == $day_para[11][$i] and $sheet_para[$modi_no][1] != $i){print "<tr><td class=sheet_yoyaku_red height=$td_h> </td></tr>";}
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
			if($sheet_file == $day_para[14][$i] and $sheet_para[$modi_no][1] == $i){print "<tr><td class=sheet_yoyaku_blue height=$td_h> </td></tr>";}
			elseif($sheet_file == $day_para[14][$i] and $sheet_para[$modi_no][1] != $i){print "<tr><td class=sheet_yoyaku_red height=$td_h> </td></tr>";}
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
		<table class="center" width="600">
			<tr>
				<td class="sheet_yoyaku" width="15"></td>
				<td width="185">：予約済み</td>
				<td class="sheet_yoyaku_red" width="15"></td>
				<td width="185">：予約済み（同一車両）</td>
				<td class="sheet_yoyaku_blue" width="15"></td>
				<td width="185">：予約済み（該当作業枠）</td>
			</tr>
		</table>
<?php /* 作業一覧表示部分 ここまで */ ?>
		<form method="post" action="work_sheet_modi_check.php">
		<table class="reserve">
			<tr>
				<td class="td_title2">日時</td>
				<td width="270">
					<select name="time_s">
						<option value="0"<?php if($sheet_para[$modi_no][1] == 0){print " selected";} ?>>9時00分</option>
						<option value="1"<?php if($sheet_para[$modi_no][1] == 1){print " selected";} ?>>9時30分</option>
						<option value="2"<?php if($sheet_para[$modi_no][1] == 2){print " selected";} ?>>10時00分</option>
						<option value="3"<?php if($sheet_para[$modi_no][1] == 3){print " selected";} ?>>10時30分</option>
						<option value="4"<?php if($sheet_para[$modi_no][1] == 4){print " selected";} ?>>11時00分</option>
						<option value="5"<?php if($sheet_para[$modi_no][1] == 5){print " selected";} ?>>11時30分</option>
						<option value="6"<?php if($sheet_para[$modi_no][1] == 6){print " selected";} ?>>12時00分</option>
						<option value="7"<?php if($sheet_para[$modi_no][1] == 7){print " selected";} ?>>12時30分</option>
						<option value="8"<?php if($sheet_para[$modi_no][1] == 8){print " selected";} ?>>13時00分</option>
						<option value="9"<?php if($sheet_para[$modi_no][1] == 9){print " selected";} ?>>13時30分</option>
						<option value="10"<?php if($sheet_para[$modi_no][1] == 10){print " selected";} ?>>14時00分</option>
						<option value="11"<?php if($sheet_para[$modi_no][1] == 11){print " selected";} ?>>14時30分</option>
						<option value="12"<?php if($sheet_para[$modi_no][1] == 12){print " selected";} ?>>15時00分</option>
						<option value="13"<?php if($sheet_para[$modi_no][1] == 13){print " selected";} ?>>15時30分</option>
						<option value="14"<?php if($sheet_para[$modi_no][1] == 14){print " selected";} ?>>16時00分</option>
						<option value="15"<?php if($sheet_para[$modi_no][1] == 15){print " selected";} ?>>16時30分</option>
						<option value="16"<?php if($sheet_para[$modi_no][1] == 16){print " selected";} ?>>17時00分</option>
						<option value="17"<?php if($sheet_para[$modi_no][1] == 17){print " selected";} ?>>17時30分</option>
						<option value="18"<?php if($sheet_para[$modi_no][1] == 18){print " selected";} ?>>18時00分</option>
						<option value="19"<?php if($sheet_para[$modi_no][1] == 19){print " selected";} ?>>18時30分</option>
						<option value="20"<?php if($sheet_para[$modi_no][1] == 20){print " selected";} ?>>19時00分</option>
						<option value="21"<?php if($sheet_para[$modi_no][1] == 21){print " selected";} ?>>19時30分</option>
					</select>
					～
					<select name="time_e">
						<option value="1"<?php if($sheet_para[$modi_no][2] == 1){print " selected";} ?>>9時30分</option>
						<option value="2"<?php if($sheet_para[$modi_no][2] == 2){print " selected";} ?>>10時00分</option>
						<option value="3"<?php if($sheet_para[$modi_no][2] == 3){print " selected";} ?>>10時30分</option>
						<option value="4"<?php if($sheet_para[$modi_no][2] == 4){print " selected";} ?>>11時00分</option>
						<option value="5"<?php if($sheet_para[$modi_no][2] == 5){print " selected";} ?>>11時30分</option>
						<option value="6"<?php if($sheet_para[$modi_no][2] == 6){print " selected";} ?>>12時00分</option>
						<option value="7"<?php if($sheet_para[$modi_no][2] == 7){print " selected";} ?>>12時30分</option>
						<option value="8"<?php if($sheet_para[$modi_no][2] == 8){print " selected";} ?>>13時00分</option>
						<option value="9"<?php if($sheet_para[$modi_no][2] == 9){print " selected";} ?>>13時30分</option>
						<option value="10"<?php if($sheet_para[$modi_no][2] == 10){print " selected";} ?>>14時00分</option>
						<option value="11"<?php if($sheet_para[$modi_no][2] == 11){print " selected";} ?>>14時30分</option>
						<option value="12"<?php if($sheet_para[$modi_no][2] == 12){print " selected";} ?>>15時00分</option>
						<option value="13"<?php if($sheet_para[$modi_no][2] == 13){print " selected";} ?>>15時30分</option>
						<option value="14"<?php if($sheet_para[$modi_no][2] == 14){print " selected";} ?>>16時00分</option>
						<option value="15"<?php if($sheet_para[$modi_no][2] == 15){print " selected";} ?>>16時30分</option>
						<option value="16"<?php if($sheet_para[$modi_no][2] == 16){print " selected";} ?>>17時00分</option>
						<option value="17"<?php if($sheet_para[$modi_no][2] == 17){print " selected";} ?>>17時30分</option>
						<option value="18"<?php if($sheet_para[$modi_no][2] == 18){print " selected";} ?>>18時00分</option>
						<option value="19"<?php if($sheet_para[$modi_no][2] == 19){print " selected";} ?>>18時30分</option>
						<option value="20"<?php if($sheet_para[$modi_no][2] == 20){print " selected";} ?>>19時00分</option>
						<option value="21"<?php if($sheet_para[$modi_no][2] == 21){print " selected";} ?>>19時30分</option>
						<option value="22"<?php if($sheet_para[$modi_no][2] == 22){print " selected";} ?>>20時00分</option>
					</select>
				</td>
				<td class="td_title2">リフト</td>
				<td width="270">
					<select name="lift">
						<option value="1"<?php if($sheet_para[$modi_no][3] == 1){print " selected";} ?>>アライメントA</option>
						<option value="4"<?php if($sheet_para[$modi_no][3] == 4){print " selected";} ?>>アライメントB</option>
						<option value="7"<?php if($sheet_para[$modi_no][3] == 7){print " selected";} ?>>2FメインリフトA</option>
						<option value="10"<?php if($sheet_para[$modi_no][3] == 10){print " selected";} ?>>2FメインリフトB</option>
						<option value="13"<?php if($sheet_para[$modi_no][3] == 13){print " selected";} ?>>サブ</option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="td_title2">販売区分</td>
				<td>
					<select name="hanbai">
						<option value="1"<?php if($sheet_para[$modi_no][4] == 1){print " selected";} ?>>アライメント</option>
						<option value="2"<?php if($sheet_para[$modi_no][4] == 2){print " selected";} ?>>店舗客注</option>
						<option value="3"<?php if($sheet_para[$modi_no][4] == 3){print " selected";} ?>>持ち込み</option>
						<option value="4"<?php if($sheet_para[$modi_no][4] == 4){print " selected";} ?>>WEB取り付け</option>
						<option value="5"<?php if($sheet_para[$modi_no][4] == 5){print " selected";} ?>>在庫KEEP</option>
						<option value="0"<?php if($sheet_para[$modi_no][4] == 0){print " selected";} ?>>仮予約</option>
					</select>
				</td>
				<td class="td_title2">担当者</td>
				<td>
					<select name="tantou">
						<?php if($sheet_para[$modi_no][5] == "アライメント予約"){print "<option value=アライメント予約 selected>アライメント予約</option>";} ?>
						<option value="仮予約"<?php if($sheet_para[$modi_no][5] == "仮予約"){print " selected";} ?>>仮予約</option>
						<option value="杉田"<?php if($sheet_para[$modi_no][5] == "杉田"){print " selected";} ?>>杉田</option>
						<option value="平林"<?php if($sheet_para[$modi_no][5] == "平林"){print " selected";} ?>>平林</option>
						<option value="板倉"<?php if($sheet_para[$modi_no][5] == "板倉"){print " selected";} ?>>板倉</option>
						<option value="黒川"<?php if($sheet_para[$modi_no][5] == "黒川"){print " selected";} ?>>黒川</option>
						<option value="柳田"<?php if($sheet_para[$modi_no][5] == "柳田"){print " selected";} ?>>柳田</option>
						<option value="高橋"<?php if($sheet_para[$modi_no][5] == "高橋"){print " selected";} ?>>高橋</option>
						<option value="柴田"<?php if($sheet_para[$modi_no][5] == "柴田"){print " selected";} ?>>柴田</option>
						<option value="安達"<?php if($sheet_para[$modi_no][5] == "安達"){print " selected";} ?>>安達</option>
						<option value="小山"<?php if($sheet_para[$modi_no][5] == "小山"){print " selected";} ?>>小山</option>
						<option value="今井"<?php if($sheet_para[$modi_no][5] == "今井"){print " selected";} ?>>今井</option>
					</select>
				</td>
			</tr>
			<tr><td colspan="4" class="td_title2">作業内容</td></tr>
			<tr><td colspan="4"><input class="reserve_input2" type="text" name="sagyou" value="<?php print_r($sheet_para[$modi_no][6]); ?>"></td></tr>
			<tr><td colspan="4" class="td_title2">メモ欄・工賃</td></tr>
			<tr><td colspan="4"><input class="reserve_input2" type="text" name="memo" value="<?php print_r($sheet_para[$modi_no][7]); ?>"></td></tr>
			<tr><td colspan="4" class="td_title2">表示アイコン</td></tr>
			<tr><td colspan="4" class="icon_td">
<?php endif;?>
<?php if($sheet_para[$modi_no][8] == 1):?>
				<input type="hidden" name="icon" value="1"><div class="icon_para1_modi">メール未送信の為、変更できません</div>
<?php endif;?>
<?php if($sheet_para[$modi_no][8] != 1):?>
				<input class="icon_radio" type="radio" name="icon" value="0"<?php if($sheet_para[$modi_no][8] == 0){print " checked";} ?>> 特になし　　<input class="icon_radio" type="radio" name="icon" value="2"<?php if($sheet_para[$modi_no][8] == 2){print " checked";} ?>><img src="img/minyuka.gif" width="90" height="20" alt="商品未入荷">　　<input class="icon_radio" type="radio" name="icon" value="3"<?php if($sheet_para[$modi_no][8] == 3){print " checked";} ?>><img src="img/nouki.gif" width="90" height="20" alt="納期要確認">　　<input class="icon_radio" type="radio" name="icon" value="4"<?php if($sheet_para[$modi_no][8] == 4){print " checked";} ?>><img src="img/minou.gif" width="90" height="20" alt="未納あり">　　<input class="icon_radio" type="radio" name="icon" value="5"<?php if($sheet_para[$modi_no][8] == 5){print " checked";} ?>><img src="img/daisya.gif" width="90" height="20" alt="代車使用">
<?php endif;?>
<?php if($modi_no != 0):?>
			</td></tr>
		</table>
		<input type="hidden" name="year" value="<?php print "$year"; ?>">
		<input type="hidden" name="mon" value="<?php print "$mon"; ?>">
		<input type="hidden" name="day" value="<?php print "$day"; ?>">
		<input type="hidden" name="sheet_file" value="<?php print "$sheet_file"; ?>">
		<input type="hidden" name="modi_no" value="<?php print "$modi_no"; ?>">
		<div class="space10px"></div>
		<div class="del_all"><input class="sagyou_push" type="submit" value="入力内容・変更箇所を確認"></div>
		</form>
		<div class="space10px"></div>
		<div class="footer">Copyright (C) Kind Techno Structure Corporation. All Rights Reserved.</div>
	</body>
</html>
<?php endif;?>
<?php if($modi_no != 0){exit();} ?>
