<?php
	/* table高さ設定 */
	$table_h = 35;
	$table_h2 = $table_h * 2;
	/* 各データの受け取り */
	$year = $_POST['year'];
	$mon = $_POST['mon'];
	$day = $_POST['day'];
	/* $day_file = $_POST['day_file']; 毎回生成 */

	$mon0 = $mon;
	if($mon0 < 10){$mon0 = "0$mon";}
	$day0 = $day;
	if($day0 < 10){$day0 = "0$day";}
	$day_file = "$year" . "$mon0" . "$day0" . ".dat";

	/* 移動用データの生成 */
	/* 前日移動用 */
	$b_year = $year;
	$b_mon = $mon;
	$b_day = $day - 1;
	if($b_day == 0){
		$b_mon = $b_mon - 1;
		if($mon == 1){$b_mon = 12; $b_year = $b_year - 1;}
		date_default_timezone_set('Asia/Tokyo');
		$b_day = date("t", mktime(0, 0, 0, $b_mon, 1, $b_year));
	}
	$b_mon0 = $b_mon;
	if($b_mon0 < 10){$b_mon0 = "0$b_mon";}
	$b_day0 = $b_day;
	if($b_day0 < 10){$b_day0 = "0$b_day";}
	$b_day_file = "$b_year$b_mon0$b_day0" . ".dat";
	/* 翌日移動用 */
	$a_year = $year;
	$a_mon = $mon;
	$a_day = $day + 1;
	date_default_timezone_set('Asia/Tokyo');
	$a_day_check = date("t", mktime(0, 0, 0, $mon, 1, $year));
	if($a_day > $a_day_check){
		$a_mon = $a_mon + 1;
		if($mon == 12){$a_mon = 1; $a_year = $a_year + 1;}
		$a_day = 1;
	}
	$a_mon0 = $a_mon;
	if($a_mon0 < 10){$a_mon0 = "0$a_mon";}
	$a_day0 = $a_day;
	if($a_day0 < 10){$a_day0 = "0$a_day";}
	$a_day_file = "$a_year$a_mon0$a_day0" . ".dat";
	/* 当日移動用 */
	date_default_timezone_set('Asia/Tokyo');
	$t_year = date("Y");
	$t_mon = date("n");
	$t_mon0 = date("m");
	$t_day = date("j");
	$t_day0 = date("d");
	$t_day_file = "$t_year$t_mon0$t_day0" . ".dat";

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
	$sheet_para = array();
	$i = 0;
	while($ret_csv = fgetcsv($fp_data)){
		for($col = 0; $col < count($ret_csv); $col++){
			$sheet_para[$i][$col] = $ret_csv[$col];
			if($sheet_para[$i][$col] == ""){$sheet_para[$i][$col] = "null";}
		}
		$i++;
	}
	fclose($fp_data);

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
		<title>作業予約システム</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link rel="stylesheet" href="https://www.kts-web.com/work_sheet_heiwajima/work_sheet.css" type="text/css">
	</head>
	<body bgcolor="#FFFFFF" text="#000000">
		<table class="title">
			<tr>
				<td>KTS作業予約管理システム<span class="shop_title">（KTS平和島店）</span></td>
				<td><div class="last_ymd">最終更新日時：<?php print "$ymd"; ?></div></td>
<!-- 旧印刷ボタン
				<td><div class="print_right"><input type="button" name="print" value="ページ内を印刷" onClick="javascript:window.print()"></div></td>
新印刷ボタン -->
				<td><div class="print_right">
					<form method="post" action="print_day_work.php">
						<input type="hidden" name="year" value="<?php print "$year"; ?>">
						<input type="hidden" name="mon" value="<?php print "$mon"; ?>">
						<input type="hidden" name="day" value="<?php print "$day"; ?>">
						<input type="hidden" name="day_file" value="<?php print "$day_file"; ?>">
						<input type="submit" value="作業一覧を印刷する">
					</form>
				</div></td>
			</tr>
		</table>
		<table class="print_center">
			<tr>
				<td>
					<form method="post" action="day_work.php">
						<input type="hidden" name="year" value="<?php print "$b_year"; ?>">
						<input type="hidden" name="mon" value="<?php print "$b_mon"; ?>">
						<input type="hidden" name="day" value="<?php print "$b_day"; ?>">
						<input type="hidden" name="day_file" value="<?php print "$b_day_file"; ?>">
						<input class="time_move" type="submit" value="←前日">
					</form>
				</td>
				<td>
					<form method="post" action="day_work.php">
						<input type="hidden" name="year" value="<?php print "$t_year"; ?>">
						<input type="hidden" name="mon" value="<?php print "$t_mon"; ?>">
						<input type="hidden" name="day" value="<?php print "$t_day"; ?>">
						<input type="hidden" name="day_file" value="<?php print "$t_day_file"; ?>">
						<input class="time_move" type="submit" value="今日">
					</form>
				</td>
				<td>
					<form method="post" action="day_work.php">
						<input type="hidden" name="year" value="<?php print "$a_year"; ?>">
						<input type="hidden" name="mon" value="<?php print "$a_mon"; ?>">
						<input type="hidden" name="day" value="<?php print "$a_day"; ?>">
						<input type="hidden" name="day_file" value="<?php print "$a_day_file"; ?>">
						<input class="time_move" type="submit" value="翌日→">
					</form>
				</td>
			</tr>
			<tr>
				<td colspan="3"><div align="center"><a href=index.php>月間表示画面へ戻る</a></div></td>
			</tr>
		</table>
		<div class="title_ymd"><?php print "$year"; ?>年<?php print "$mon"; ?>月<?php print "$day"; ?>日（<?php print "$wday"; ?>）</div>
		<table class="day_work_comm">
			<tr>
				<td width="100"><div align="right">コメント欄：</div></td>
				<td><?php print_r($sheet_para[0][0]); ?></td>
			</tr>
		</table>
		<div class="comm_w">
			<form method="post" action="day_work_comm.php">
				<input type="hidden" name="year" value="<?php print "$year"; ?>">
				<input type="hidden" name="mon" value="<?php print "$mon"; ?>">
				<input type="hidden" name="day" value="<?php print "$day"; ?>">
				<input type="hidden" name="day_file" value="<?php print "$day_file"; ?>">
				<input type="hidden" name="comm_run" value="0">
				<input type="submit" class="comm_b" value="コメント欄を修正する">
			</form>
		</div>
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
		$int_c = ctype_digit($sheet_para[1][$i]);
		if($int_c == TRUE){
			$td_r = $sheet_para[1][$i]; /* 必要コマ数の数値代入 */
			$td_h = $table_h * $td_r; /* td属性の高さ確定 */
			$td_para = $sheet_para[3][$i]; /* アイコン有無のチェック */
			$sheet_file = $sheet_para[2][$i];
			/* コマ数からアイコン・フォントサイズを決定 */
			if($td_r == 1){$icon_w = 45; $icon_h = 10; $yoyaku_css = "sheet_yoyaku_30"; $car_model_css = "car_model_30";}
			else{$icon_w = 90; $icon_h = 20; $yoyaku_css = "sheet_yoyaku"; $car_model_css = "car_model";}
			/* 作業連番を取得 */
			$wsn = substr("$sheet_file", -6, 2);
			$wsn = str_replace("_", "", $wsn);
			/* print "<tr><td height=$td_h rowspan=$td_r>ここに作業内容</td></tr>"; */
			print "<tr><td class=$yoyaku_css height=$td_h>";
			/* コマ別作業詳細の表示＆リンク処理 */
			setlocale(LC_ALL,'ja_JP.UTF-8');
			$fp_data_s = fopen("log/$sheet_file", "r");
			$sheet_file_para = array();
			$sheet_i = 0;
			while($ret_csv = fgetcsv($fp_data_s)){
				for($col = 0; $col < count($ret_csv); $col++){
					$sheet_file_para[$sheet_i][$col] = $ret_csv[$col];
				}
				if($sheet_file_para[$sheet_i][1] == $i){
					$sheet_sagyou = $sheet_file_para[$sheet_i][6];
					/* 作業内容の文字列をSJISに変換 */
					/* $sheet_sagyou = mb_convert_encoding($sheet_sagyou,'SJIS','UTF-8'); */
					/* 先頭から半角18文字（全角は9文字）を取得 */
					$sheet_sagyou = mb_strimwidth($sheet_sagyou, 0, 21, "...", 'UTF-8');
					/* 作業内容の文字列をUTF-8に変換 */
					/* $sheet_sagyou = mb_convert_encoding($sheet_sagyou,'UTF-8','SJIS'); */
				}
				$sheet_i++;
			}
			$sheet_name = $sheet_file_para[0][1];
			$sheet_name_30 = $sheet_name;
			/* お客様名の文字数操作 */
			/* $sheet_name = mb_convert_encoding($sheet_name,'SJIS','UTF-8'); /* お客様名の文字列をSJISに変換 */
			$sheet_name = mb_strimwidth($sheet_name, 0 ,19, "...", 'UTF-8'); /* 先頭から半角16文字（全角は8文字）を取得 */
			/* $sheet_name = mb_convert_encoding($sheet_name,'UTF-8','SJIS'); /* お客様名の文字列をUTF-8に変換 */
			$sheet_name = $sheet_name . " 様";
			fclose($fp_data_s);
			$sheet_model = $sheet_file_para[0][8];
			/* 車輌型式の文字数操作 */
			$sheet_model_herf = $sheet_model;
			/* $sheet_model_herf = mb_convert_encoding($sheet_model_herf,'SJIS','UTF-8'); /* 車輌型式の文字列をSJISに変換 */
			$sheet_model_herf = mb_strimwidth($sheet_model_herf, 0, 7, "...", 'UTF-8'); /* 先頭から半角で4文字（全角は2文字）を取得 */
			/* $sheet_model_herf = mb_convert_encoding($sheet_model_herf,'UTF-8','SJIS'); /* 車輌型式の文字列をUTF-8に変換 */
			if($td_para == 1){print "<form method=post action=alignment.php><input type=hidden name=year value=$year><input type=hidden name=mon value=$mon><input type=hidden name=day value=$day><input type=hidden name=sheet_file value=$sheet_file><div class=$car_model_css>$sheet_model_herf<input type=image src=img/mail_none.gif name=image></div></form>";}
			elseif($td_para == 2){print "<div class=$car_model_css>$sheet_model_herf<img src=img/minyuka.gif width=$icon_w height=$icon_h alt=商品未入荷></div>";}
			elseif($td_para == 3){print "<div class=$car_model_css>$sheet_model_herf<img src=img/nouki.gif width=$icon_w height=$icon_h alt=納期要確認></div>";}
			elseif($td_para == 4){print "<div class=$car_model_css>$sheet_model_herf<img src=img/minou.gif width=$icon_w height=$icon_h alt=未納あり></div>";}
			elseif($td_para == 5){print "<div class=$car_model_css>$sheet_model_herf<img src=img/daisya.gif width=$icon_w height=$icon_h alt=代車使用></div>";}
			else{print "<div class=$car_model_css>$sheet_model</div>";}
			if($td_r == 1){
				/* $sheet_sagyou = mb_convert_encoding($sheet_sagyou,'SJIS','UTF-8'); /* 作業内容の文字列をSJISに変換 */
				$sheet_sagyou = mb_strimwidth($sheet_sagyou, 0, 14, "...", 'UTF-8'); /* 先頭から半角11文字（全角は4文字）を取得 */
				/* $sheet_sagyou = mb_convert_encoding($sheet_sagyou,'UTF-8','SJIS'); /* 作業内容の文字列をUTF-8に変換 */
				/* $sheet_name_30 = mb_convert_encoding($sheet_name_30,'SJIS','UTF-8'); /* お客様名の文字列をSJISに変換 */
				$sheet_name_30 = mb_strimwidth($sheet_name_30, 0 ,11, "...", 'UTF-8'); /* 先頭から半角11文字（全角は4文字）を取得 */
				/* $sheet_name_30 = mb_convert_encoding($sheet_name_30,'UTF-8','SJIS'); /* お客様名の文字列をUTF-8に変換 */
				$sheet_name_sagyou = $sheet_name_30 . " 様 " . $sheet_sagyou;
				print "<form method=post action=work_sheet.php><input type=hidden name=year value=$year><input type=hidden name=mon value=$mon><input type=hidden name=day value=$day><input type=hidden name=sheet_file value=$sheet_file><input id=work_sheet_30 type=submit value='$sheet_name_sagyou'></form>";
				print "<div class=work_sn_30>$wsn</div>";
			}
			else{
				print "<form method=post action=work_sheet.php><input type=hidden name=year value=$year><input type=hidden name=mon value=$mon><input type=hidden name=day value=$day><input type=hidden name=sheet_file value=$sheet_file><input id=work_sheet type=submit value='$sheet_name'></form>";
				print "<form method=post action=work_sheet.php><input type=hidden name=year value=$year><input type=hidden name=mon value=$mon><input type=hidden name=day value=$day><input type=hidden name=sheet_file value=$sheet_file><input id=work_sheet_sagyou type=submit value='$sheet_sagyou'></form>";
				print "<div class=work_sn>$wsn</div>";
			}
			print "</td></tr>";
			$i = $i + $td_r - 1;
		}
		else{print "<tr><td height=$table_h><form method=post action=reserve.php><input type=hidden name=year value=$year><input type=hidden name=mon value=$mon><input type=hidden name=day value=$day><input type=hidden name=day_file value=$day_file><input type=hidden name=time_s value=$i><input type=hidden name=lift value=1><input id=submit_reserve type=submit value=予約する></form></td></tr>";}
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
		$int_c = ctype_digit($sheet_para[4][$i]);
		if($int_c == TRUE){
			$td_r = $sheet_para[4][$i]; /* 必要コマ数の数値代入 */
			$td_h = $table_h * $td_r; /* td属性の高さ確定 */
			$td_para = $sheet_para[6][$i]; /* アイコン有無のチェック */
			$sheet_file = $sheet_para[5][$i];
			/* コマ数からアイコン・フォントサイズを決定 */
			if($td_r == 1){$icon_w = 45; $icon_h = 10; $yoyaku_css = "sheet_yoyaku_30"; $car_model_css = "car_model_30";}
			else{$icon_w = 90; $icon_h = 20; $yoyaku_css = "sheet_yoyaku"; $car_model_css = "car_model";}
			/* 作業連番を取得 */
			$wsn = substr("$sheet_file", -6, 2);
			$wsn = str_replace("_", "", $wsn);
			/* print "<tr><td height=$td_h rowspan=$td_r>ここに作業内容</td></tr>"; */
			print "<tr><td class=$yoyaku_css height=$td_h>";
			/* コマ別作業詳細の表示＆リンク処理 */
			setlocale(LC_ALL,'ja_JP.UTF-8');
			$fp_data_s = fopen("log/$sheet_file", "r");
			$sheet_file_para = array();
			$sheet_i = 0;
			while($ret_csv = fgetcsv($fp_data_s)){
				for($col = 0; $col < count($ret_csv); $col++){
					$sheet_file_para[$sheet_i][$col] = $ret_csv[$col];
				}
				if($sheet_file_para[$sheet_i][1] == $i){
					$sheet_sagyou = $sheet_file_para[$sheet_i][6];
					/* 作業内容の文字列をSJISに変換 */
					/* $sheet_sagyou = mb_convert_encoding($sheet_sagyou,'SJIS','UTF-8'); */
					/* 先頭から半角18文字（全角は9文字）を取得 */
					$sheet_sagyou = mb_strimwidth($sheet_sagyou, 0, 21, "...", 'UTF-8');
					/* 作業内容の文字列をUTF-8に変換 */
					/* $sheet_sagyou = mb_convert_encoding($sheet_sagyou,'UTF-8','SJIS'); */
				}
				$sheet_i++;
			}
			$sheet_name = $sheet_file_para[0][1];
			$sheet_name_30 = $sheet_name;
			/* お客様名の文字数操作 */
			/* $sheet_name = mb_convert_encoding($sheet_name,'SJIS','UTF-8'); /* お客様名の文字列をSJISに変換 */
			$sheet_name = mb_strimwidth($sheet_name, 0 ,19, "...", 'UTF-8'); /* 先頭から半角16文字（全角は8文字）を取得 */
			/* $sheet_name = mb_convert_encoding($sheet_name,'UTF-8','SJIS'); /* お客様名の文字列をUTF-8に変換 */
			$sheet_name = $sheet_name . " 様";
			fclose($fp_data_s);
			$sheet_model = $sheet_file_para[0][8];
			/* 車輌型式の文字数操作 */
			$sheet_model_herf = $sheet_model;
			/* $sheet_model_herf = mb_convert_encoding($sheet_model_herf,'SJIS','UTF-8'); /* 車輌型式の文字列をSJISに変換 */
			$sheet_model_herf = mb_strimwidth($sheet_model_herf, 0, 7, "...", 'UTF-8'); /* 先頭から半角で4文字（全角は2文字）を取得 */
			/* $sheet_model_herf = mb_convert_encoding($sheet_model_herf,'UTF-8','SJIS'); /* 車輌型式の文字列をUTF-8に変換 */
			if($td_para == 1){print "<form method=post action=alignment.php><input type=hidden name=year value=$year><input type=hidden name=mon value=$mon><input type=hidden name=day value=$day><input type=hidden name=sheet_file value=$sheet_file><div class=$car_model_css>$sheet_model_herf<input type=image src=img/mail_none.gif name=image></div></form>";}
			elseif($td_para == 2){print "<div class=$car_model_css>$sheet_model_herf<img src=img/minyuka.gif width=$icon_w height=$icon_h alt=商品未入荷></div>";}
			elseif($td_para == 3){print "<div class=$car_model_css>$sheet_model_herf<img src=img/nouki.gif width=$icon_w height=$icon_h alt=納期要確認></div>";}
			elseif($td_para == 4){print "<div class=$car_model_css>$sheet_model_herf<img src=img/minou.gif width=$icon_w height=$icon_h alt=未納あり></div>";}
			elseif($td_para == 5){print "<div class=$car_model_css>$sheet_model_herf<img src=img/daisya.gif width=$icon_w height=$icon_h alt=代車使用></div>";}
			else{print "<div class=$car_model_css>$sheet_model</div>";}
			if($td_r == 1){
				/* $sheet_sagyou = mb_convert_encoding($sheet_sagyou,'SJIS','UTF-8'); /* 作業内容の文字列をSJISに変換 */
				$sheet_sagyou = mb_strimwidth($sheet_sagyou, 0, 14, "...", 'UTF-8'); /* 先頭から半角11文字（全角は4文字）を取得 */
				/* $sheet_sagyou = mb_convert_encoding($sheet_sagyou,'UTF-8','SJIS'); /* 作業内容の文字列をUTF-8に変換 */
				/* $sheet_name_30 = mb_convert_encoding($sheet_name_30,'SJIS','UTF-8'); /* お客様名の文字列をSJISに変換 */
				$sheet_name_30 = mb_strimwidth($sheet_name_30, 0 ,11, "...", 'UTF-8'); /* 先頭から半角11文字（全角は4文字）を取得 */
				/* $sheet_name_30 = mb_convert_encoding($sheet_name_30,'UTF-8','SJIS'); /* お客様名の文字列をUTF-8に変換 */
				$sheet_name_sagyou = $sheet_name_30 . " 様 " . $sheet_sagyou;
				print "<form method=post action=work_sheet.php><input type=hidden name=year value=$year><input type=hidden name=mon value=$mon><input type=hidden name=day value=$day><input type=hidden name=sheet_file value=$sheet_file><input id=work_sheet_30 type=submit value='$sheet_name_sagyou'></form>";
				print "<div class=work_sn_30>$wsn</div>";
			}
			else{
				print "<form method=post action=work_sheet.php><input type=hidden name=year value=$year><input type=hidden name=mon value=$mon><input type=hidden name=day value=$day><input type=hidden name=sheet_file value=$sheet_file><input id=work_sheet type=submit value='$sheet_name'></form>";
				print "<form method=post action=work_sheet.php><input type=hidden name=year value=$year><input type=hidden name=mon value=$mon><input type=hidden name=day value=$day><input type=hidden name=sheet_file value=$sheet_file><input id=work_sheet_sagyou type=submit value='$sheet_sagyou'></form>";
				print "<div class=work_sn>$wsn</div>";
			}
			print "</td></tr>";
			$i = $i + $td_r - 1;
		}
		else{print "<tr><td height=$table_h><form method=post action=reserve.php><input type=hidden name=year value=$year><input type=hidden name=mon value=$mon><input type=hidden name=day value=$day><input type=hidden name=time_s value=$i><input type=hidden name=lift value=4><input id=submit_reserve type=submit value=予約する></form></td></tr>";}
	}
?>
					</table>
				</td>
				<?php /* 2FメインリフトA */ ?>
				<td width="19.2%">
					<table class="sheet" bgcolor="#FFFFFF" border="1" bordercolor="#000000">
						<tr><td bgcolor="#FFFF99" height="45"><div><strong>2FメインリフトA</strong></td></tr>
<?php
	/* 日別作業ファイルから一覧表を作成 */
	/* 配列の中が数値かチェック */
	for($i = 0; $i <= 21; $i++){
		$int_c = ctype_digit($sheet_para[7][$i]);
		if($int_c == TRUE){
			$td_r = $sheet_para[7][$i]; /* 必要コマ数の数値代入 */
			$td_h = $table_h * $td_r; /* td属性の高さ確定 */
			$td_para = $sheet_para[9][$i]; /* アイコン有無のチェック */
			$sheet_file = $sheet_para[8][$i];
			/* コマ数からアイコン・フォントサイズを決定 */
			if($td_r == 1){$icon_w = 45; $icon_h = 10; $yoyaku_css = "sheet_yoyaku_30"; $car_model_css = "car_model_30";}
			else{$icon_w = 90; $icon_h = 20; $yoyaku_css = "sheet_yoyaku"; $car_model_css = "car_model";}
			/* 作業連番を取得 */
			$wsn = substr("$sheet_file", -6, 2);
			$wsn = str_replace("_", "", $wsn);
			/* print "<tr><td height=$td_h rowspan=$td_r>ここに作業内容</td></tr>"; */
			print "<tr><td class=$yoyaku_css height=$td_h>";
			/* コマ別作業詳細の表示＆リンク処理 */
			setlocale(LC_ALL,'ja_JP.UTF-8');
			$fp_data_s = fopen("log/$sheet_file", "r");
			$sheet_file_para = array();
			$sheet_i = 0;
			while($ret_csv = fgetcsv($fp_data_s)){
				for($col = 0; $col < count($ret_csv); $col++){
					$sheet_file_para[$sheet_i][$col] = $ret_csv[$col];
				}
				if($sheet_file_para[$sheet_i][1] == $i){
					$sheet_sagyou = $sheet_file_para[$sheet_i][6];
					/* 作業内容の文字列をSJISに変換 */
					/* $sheet_sagyou = mb_convert_encoding($sheet_sagyou,'SJIS','UTF-8'); */
					/* 先頭から半角18文字（全角は9文字）を取得 */
					$sheet_sagyou = mb_strimwidth($sheet_sagyou, 0, 21, "...", 'UTF-8');
					/* 作業内容の文字列をUTF-8に変換 */
					/* $sheet_sagyou = mb_convert_encoding($sheet_sagyou,'UTF-8','SJIS'); */
				}
				$sheet_i++;
			}
			$sheet_name = $sheet_file_para[0][1];
			$sheet_name_30 = $sheet_name;
			/* お客様名の文字数操作 */
			/* $sheet_name = mb_convert_encoding($sheet_name,'SJIS','UTF-8'); /* お客様名の文字列をSJISに変換 */
			$sheet_name = mb_strimwidth($sheet_name, 0 ,19, "...", 'UTF-8'); /* 先頭から半角16文字（全角は8文字）を取得 */
			/* $sheet_name = mb_convert_encoding($sheet_name,'UTF-8','SJIS'); /* お客様名の文字列をUTF-8に変換 */
			$sheet_name = $sheet_name . " 様";
			fclose($fp_data_s);
			$sheet_model = $sheet_file_para[0][8];
			/* 車輌型式の文字数操作 */
			$sheet_model_herf = $sheet_model;
			/* $sheet_model_herf = mb_convert_encoding($sheet_model_herf,'SJIS','UTF-8'); /* 車輌型式の文字列をSJISに変換 */
			$sheet_model_herf = mb_strimwidth($sheet_model_herf, 0, 7, "...", 'UTF-8'); /* 先頭から半角で4文字（全角は2文字）を取得 */
			/* $sheet_model_herf = mb_convert_encoding($sheet_model_herf,'UTF-8','SJIS'); /* 車輌型式の文字列をUTF-8に変換 */
			if($td_para == 1){print "<form method=post action=alignment.php><input type=hidden name=year value=$year><input type=hidden name=mon value=$mon><input type=hidden name=day value=$day><input type=hidden name=sheet_file value=$sheet_file><div class=$car_model_css>$sheet_model_herf<input type=image src=img/mail_none.gif name=image></div></form>";}
			elseif($td_para == 2){print "<div class=$car_model_css>$sheet_model_herf<img src=img/minyuka.gif width=$icon_w height=$icon_h alt=商品未入荷></div>";}
			elseif($td_para == 3){print "<div class=$car_model_css>$sheet_model_herf<img src=img/nouki.gif width=$icon_w height=$icon_h alt=納期要確認></div>";}
			elseif($td_para == 4){print "<div class=$car_model_css>$sheet_model_herf<img src=img/minou.gif width=$icon_w height=$icon_h alt=未納あり></div>";}
			elseif($td_para == 5){print "<div class=$car_model_css>$sheet_model_herf<img src=img/daisya.gif width=$icon_w height=$icon_h alt=代車使用></div>";}
			else{print "<div class=$car_model_css>$sheet_model</div>";}
			if($td_r == 1){
				/* $sheet_sagyou = mb_convert_encoding($sheet_sagyou,'SJIS','UTF-8'); /* 作業内容の文字列をSJISに変換 */
				$sheet_sagyou = mb_strimwidth($sheet_sagyou, 0, 14, "...", 'UTF-8'); /* 先頭から半角11文字（全角は4文字）を取得 */
				/* $sheet_sagyou = mb_convert_encoding($sheet_sagyou,'UTF-8','SJIS'); /* 作業内容の文字列をUTF-8に変換 */
				/* $sheet_name_30 = mb_convert_encoding($sheet_name_30,'SJIS','UTF-8'); /* お客様名の文字列をSJISに変換 */
				$sheet_name_30 = mb_strimwidth($sheet_name_30, 0 ,11, "...", 'UTF-8'); /* 先頭から半角11文字（全角は4文字）を取得 */
				/* $sheet_name_30 = mb_convert_encoding($sheet_name_30,'UTF-8','SJIS'); /* お客様名の文字列をUTF-8に変換 */
				$sheet_name_sagyou = $sheet_name_30 . " 様 " . $sheet_sagyou;
				print "<form method=post action=work_sheet.php><input type=hidden name=year value=$year><input type=hidden name=mon value=$mon><input type=hidden name=day value=$day><input type=hidden name=sheet_file value=$sheet_file><input id=work_sheet_30 type=submit value='$sheet_name_sagyou'></form>";
				print "<div class=work_sn_30>$wsn</div>";
			}
			else{
				print "<form method=post action=work_sheet.php><input type=hidden name=year value=$year><input type=hidden name=mon value=$mon><input type=hidden name=day value=$day><input type=hidden name=sheet_file value=$sheet_file><input id=work_sheet type=submit value='$sheet_name'></form>";
				print "<form method=post action=work_sheet.php><input type=hidden name=year value=$year><input type=hidden name=mon value=$mon><input type=hidden name=day value=$day><input type=hidden name=sheet_file value=$sheet_file><input id=work_sheet_sagyou type=submit value='$sheet_sagyou'></form>";
				print "<div class=work_sn>$wsn</div>";
			}
			print "</td></tr>";
			$i = $i + $td_r - 1;
		}
		else{print "<tr><td height=$table_h><form method=post action=reserve.php><input type=hidden name=year value=$year><input type=hidden name=mon value=$mon><input type=hidden name=day value=$day><input type=hidden name=time_s value=$i><input type=hidden name=lift value=7><input id=submit_reserve type=submit value=予約する></form></td></tr>";}
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
		$int_c = ctype_digit($sheet_para[10][$i]);
		if($int_c == TRUE){
			$td_r = $sheet_para[10][$i]; /* 必要コマ数の数値代入 */
			$td_h = $table_h * $td_r; /* td属性の高さ確定 */
			$td_para = $sheet_para[12][$i]; /* アイコン有無のチェック */
			$sheet_file = $sheet_para[11][$i];
			/* コマ数からアイコン・フォントサイズを決定 */
			if($td_r == 1){$icon_w = 45; $icon_h = 10; $yoyaku_css = "sheet_yoyaku_30"; $car_model_css = "car_model_30";}
			else{$icon_w = 90; $icon_h = 20; $yoyaku_css = "sheet_yoyaku"; $car_model_css = "car_model";}
			/* 作業連番を取得 */
			$wsn = substr("$sheet_file", -6, 2);
			$wsn = str_replace("_", "", $wsn);
			/* print "<tr><td height=$td_h rowspan=$td_r>ここに作業内容</td></tr>"; */
			print "<tr><td class=$yoyaku_css height=$td_h>";
			/* コマ別作業詳細の表示＆リンク処理 */
			setlocale(LC_ALL,'ja_JP.UTF-8');
			$fp_data_s = fopen("log/$sheet_file", "r");
			$sheet_file_para = array();
			$sheet_i = 0;
			while($ret_csv = fgetcsv($fp_data_s)){
				for($col = 0; $col < count($ret_csv); $col++){
					$sheet_file_para[$sheet_i][$col] = $ret_csv[$col];
				}
				if($sheet_file_para[$sheet_i][1] == $i){
					$sheet_sagyou = $sheet_file_para[$sheet_i][6];
					/* 作業内容の文字列をSJISに変換 */
					/* $sheet_sagyou = mb_convert_encoding($sheet_sagyou,'SJIS','UTF-8'); */
					/* 先頭から半角18文字（全角は9文字）を取得 */
					$sheet_sagyou = mb_strimwidth($sheet_sagyou, 0, 21, "...", 'UTF-8');
					/* 作業内容の文字列をUTF-8に変換 */
					/* $sheet_sagyou = mb_convert_encoding($sheet_sagyou,'UTF-8','SJIS'); */
				}
				$sheet_i++;
			}
			$sheet_name = $sheet_file_para[0][1];
			$sheet_name_30 = $sheet_name;
			/* お客様名の文字数操作 */
			/* $sheet_name = mb_convert_encoding($sheet_name,'SJIS','UTF-8'); /* お客様名の文字列をSJISに変換 */
			$sheet_name = mb_strimwidth($sheet_name, 0 ,19, "...", 'UTF-8'); /* 先頭から半角16文字（全角は8文字）を取得 */
			/* $sheet_name = mb_convert_encoding($sheet_name,'UTF-8','SJIS'); /* お客様名の文字列をUTF-8に変換 */
			$sheet_name = $sheet_name . " 様";
			fclose($fp_data_s);
			$sheet_model = $sheet_file_para[0][8];
			/* 車輌型式の文字数操作 */
			$sheet_model_herf = $sheet_model;
			/* $sheet_model_herf = mb_convert_encoding($sheet_model_herf,'SJIS','UTF-8'); /* 車輌型式の文字列をSJISに変換 */
			$sheet_model_herf = mb_strimwidth($sheet_model_herf, 0, 7, "...", 'UTF-8'); /* 先頭から半角で4文字（全角は2文字）を取得 */
			/* $sheet_model_herf = mb_convert_encoding($sheet_model_herf,'UTF-8','SJIS'); /* 車輌型式の文字列をUTF-8に変換 */
			if($td_para == 1){print "<form method=post action=alignment.php><input type=hidden name=year value=$year><input type=hidden name=mon value=$mon><input type=hidden name=day value=$day><input type=hidden name=sheet_file value=$sheet_file><div class=$car_model_css>$sheet_model_herf<input type=image src=img/mail_none.gif name=image></div></form>";}
			elseif($td_para == 2){print "<div class=$car_model_css>$sheet_model_herf<img src=img/minyuka.gif width=$icon_w height=$icon_h alt=商品未入荷></div>";}
			elseif($td_para == 3){print "<div class=$car_model_css>$sheet_model_herf<img src=img/nouki.gif width=$icon_w height=$icon_h alt=納期要確認></div>";}
			elseif($td_para == 4){print "<div class=$car_model_css>$sheet_model_herf<img src=img/minou.gif width=$icon_w height=$icon_h alt=未納あり></div>";}
			elseif($td_para == 5){print "<div class=$car_model_css>$sheet_model_herf<img src=img/daisya.gif width=$icon_w height=$icon_h alt=代車使用></div>";}
			else{print "<div class=$car_model_css>$sheet_model</div>";}
			if($td_r == 1){
				/* $sheet_sagyou = mb_convert_encoding($sheet_sagyou,'SJIS','UTF-8'); /* 作業内容の文字列をSJISに変換 */
				$sheet_sagyou = mb_strimwidth($sheet_sagyou, 0, 14, "...", 'UTF-8'); /* 先頭から半角11文字（全角は4文字）を取得 */
				/* $sheet_sagyou = mb_convert_encoding($sheet_sagyou,'UTF-8','SJIS'); /* 作業内容の文字列をUTF-8に変換 */
				/* $sheet_name_30 = mb_convert_encoding($sheet_name_30,'SJIS','UTF-8'); /* お客様名の文字列をSJISに変換 */
				$sheet_name_30 = mb_strimwidth($sheet_name_30, 0 ,11, "...", 'UTF-8'); /* 先頭から半角11文字（全角は4文字）を取得 */
				/* $sheet_name_30 = mb_convert_encoding($sheet_name_30,'UTF-8','SJIS'); /* お客様名の文字列をUTF-8に変換 */
				$sheet_name_sagyou = $sheet_name_30 . " 様 " . $sheet_sagyou;
				print "<form method=post action=work_sheet.php><input type=hidden name=year value=$year><input type=hidden name=mon value=$mon><input type=hidden name=day value=$day><input type=hidden name=sheet_file value=$sheet_file><input id=work_sheet_30 type=submit value='$sheet_name_sagyou'></form>";
				print "<div class=work_sn_30>$wsn</div>";
			}
			else{
				print "<form method=post action=work_sheet.php><input type=hidden name=year value=$year><input type=hidden name=mon value=$mon><input type=hidden name=day value=$day><input type=hidden name=sheet_file value=$sheet_file><input id=work_sheet type=submit value='$sheet_name'></form>";
				print "<form method=post action=work_sheet.php><input type=hidden name=year value=$year><input type=hidden name=mon value=$mon><input type=hidden name=day value=$day><input type=hidden name=sheet_file value=$sheet_file><input id=work_sheet_sagyou type=submit value='$sheet_sagyou'></form>";
				print "<div class=work_sn>$wsn</div>";
			}
			print "</td></tr>";
			$i = $i + $td_r - 1;
		}
		else{print "<tr><td height=$table_h><form method=post action=reserve.php><input type=hidden name=year value=$year><input type=hidden name=mon value=$mon><input type=hidden name=day value=$day><input type=hidden name=time_s value=$i><input type=hidden name=lift value=10><input id=submit_reserve type=submit value=予約する></form></td></tr>";}
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
		$int_c = ctype_digit($sheet_para[13][$i]);
		if($int_c == TRUE){
			$td_r = $sheet_para[13][$i]; /* 必要コマ数の数値代入 */
			$td_h = $table_h * $td_r; /* td属性の高さ確定 */
			$td_para = $sheet_para[15][$i]; /* アイコン有無のチェック */
			$sheet_file = $sheet_para[14][$i];
			/* コマ数からアイコン・フォントサイズを決定 */
			if($td_r == 1){$icon_w = 45; $icon_h = 10; $yoyaku_css = "sheet_yoyaku_30"; $car_model_css = "car_model_30";}
			else{$icon_w = 90; $icon_h = 20; $yoyaku_css = "sheet_yoyaku"; $car_model_css = "car_model";}
			/* 作業連番を取得 */
			$wsn = substr("$sheet_file", -6, 2);
			$wsn = str_replace("_", "", $wsn);
			/* print "<tr><td height=$td_h rowspan=$td_r>ここに作業内容</td></tr>"; */
			print "<tr><td class=$yoyaku_css height=$td_h>";
			/* コマ別作業詳細の表示＆リンク処理 */
			setlocale(LC_ALL,'ja_JP.UTF-8');
			$fp_data_s = fopen("log/$sheet_file", "r");
			$sheet_file_para = array();
			$sheet_i = 0;
			while($ret_csv = fgetcsv($fp_data_s)){
				for($col = 0; $col < count($ret_csv); $col++){
					$sheet_file_para[$sheet_i][$col] = $ret_csv[$col];
				}
				if($sheet_file_para[$sheet_i][1] == $i){
					$sheet_sagyou = $sheet_file_para[$sheet_i][6];
					/* 作業内容の文字列をSJISに変換 */
					/* $sheet_sagyou = mb_convert_encoding($sheet_sagyou,'SJIS','UTF-8'); */
					/* 先頭から半角18文字（全角は9文字）を取得 */
					$sheet_sagyou = mb_strimwidth($sheet_sagyou, 0, 21, "...", 'UTF-8');
					/* 作業内容の文字列をUTF-8に変換 */
					/* $sheet_sagyou = mb_convert_encoding($sheet_sagyou,'UTF-8','SJIS'); */
				}
				$sheet_i++;
			}
			$sheet_name = $sheet_file_para[0][1];
			$sheet_name_30 = $sheet_name;
			/* お客様名の文字数操作 */
			/* $sheet_name = mb_convert_encoding($sheet_name,'SJIS','UTF-8'); /* お客様名の文字列をSJISに変換 */
			$sheet_name = mb_strimwidth($sheet_name, 0 ,19, "...", 'UTF-8'); /* 先頭から半角16文字（全角は8文字）を取得 */
			/* $sheet_name = mb_convert_encoding($sheet_name,'UTF-8','SJIS'); /* お客様名の文字列をUTF-8に変換 */
			$sheet_name = $sheet_name . " 様";
			fclose($fp_data_s);
			$sheet_model = $sheet_file_para[0][8];
			/* 車輌型式の文字数操作 */
			$sheet_model_herf = $sheet_model;
			/* $sheet_model_herf = mb_convert_encoding($sheet_model_herf,'SJIS','UTF-8'); /* 車輌型式の文字列をSJISに変換 */
			$sheet_model_herf = mb_strimwidth($sheet_model_herf, 0, 7, "...", 'UTF-8'); /* 先頭から半角で4文字（全角は2文字）を取得 */
			/* $sheet_model_herf = mb_convert_encoding($sheet_model_herf,'UTF-8','SJIS'); /* 車輌型式の文字列をUTF-8に変換 */
			if($td_para == 1){print "<form method=post action=alignment.php><input type=hidden name=year value=$year><input type=hidden name=mon value=$mon><input type=hidden name=day value=$day><input type=hidden name=sheet_file value=$sheet_file><div class=$car_model_css>$sheet_model_herf<input type=image src=img/mail_none.gif name=image></div></form>";}
			elseif($td_para == 2){print "<div class=$car_model_css>$sheet_model_herf<img src=img/minyuka.gif width=$icon_w height=$icon_h alt=商品未入荷></div>";}
			elseif($td_para == 3){print "<div class=$car_model_css>$sheet_model_herf<img src=img/nouki.gif width=$icon_w height=$icon_h alt=納期要確認></div>";}
			elseif($td_para == 4){print "<div class=$car_model_css>$sheet_model_herf<img src=img/minou.gif width=$icon_w height=$icon_h alt=未納あり></div>";}
			elseif($td_para == 5){print "<div class=$car_model_css>$sheet_model_herf<img src=img/daisya.gif width=$icon_w height=$icon_h alt=代車使用></div>";}
			else{print "<div class=$car_model_css>$sheet_model</div>";}
			if($td_r == 1){
				/* $sheet_sagyou = mb_convert_encoding($sheet_sagyou,'SJIS','UTF-8'); /* 作業内容の文字列をSJISに変換 */
				$sheet_sagyou = mb_strimwidth($sheet_sagyou, 0, 14, "...", 'UTF-8'); /* 先頭から半角11文字（全角は4文字）を取得 */
				/* $sheet_sagyou = mb_convert_encoding($sheet_sagyou,'UTF-8','SJIS'); /* 作業内容の文字列をUTF-8に変換 */
				/* $sheet_name_30 = mb_convert_encoding($sheet_name_30,'SJIS','UTF-8'); /* お客様名の文字列をSJISに変換 */
				$sheet_name_30 = mb_strimwidth($sheet_name_30, 0 ,11, "...", 'UTF-8'); /* 先頭から半角11文字（全角は4文字）を取得 */
				/* $sheet_name_30 = mb_convert_encoding($sheet_name_30,'UTF-8','SJIS'); /* お客様名の文字列をUTF-8に変換 */
				$sheet_name_sagyou = $sheet_name_30 . " 様 " . $sheet_sagyou;
				print "<form method=post action=work_sheet.php><input type=hidden name=year value=$year><input type=hidden name=mon value=$mon><input type=hidden name=day value=$day><input type=hidden name=sheet_file value=$sheet_file><input id=work_sheet_30 type=submit value='$sheet_name_sagyou'></form>";
				print "<div class=work_sn_30>$wsn</div>";
			}
			else{
				print "<form method=post action=work_sheet.php><input type=hidden name=year value=$year><input type=hidden name=mon value=$mon><input type=hidden name=day value=$day><input type=hidden name=sheet_file value=$sheet_file><input id=work_sheet type=submit value='$sheet_name'></form>";
				print "<form method=post action=work_sheet.php><input type=hidden name=year value=$year><input type=hidden name=mon value=$mon><input type=hidden name=day value=$day><input type=hidden name=sheet_file value=$sheet_file><input id=work_sheet_sagyou type=submit value='$sheet_sagyou'></form>";
				print "<div class=work_sn>$wsn</div>";
			}
			print "</td></tr>";
			$i = $i + $td_r - 1;
		}
		else{print "<tr><td height=$table_h><form method=post action=reserve.php><input type=hidden name=year value=$year><input type=hidden name=mon value=$mon><input type=hidden name=day value=$day><input type=hidden name=time_s value=$i><input type=hidden name=lift value=13><input id=submit_reserve type=submit value=予約する></form></td></tr>";}
	}
?>
					</table>
				</td>
			</tr>
		</table>
		<div class="footer">Copyright (C) Kind Techno Structure Corporation. All Rights Reserved.</div>
	</body>
</html>
