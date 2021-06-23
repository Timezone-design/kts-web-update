<?php
	/* 移動データの取得 */
	$move_p = $_POST['move_p'];
	$move_y = $_POST['move_y'];
	$move_m = $_POST['move_m'];

	/* 1日の曜日、1ヶ月の日数を取得 */
	/* 当月以外への移動 */
	if($move_p == 1){
		date_default_timezone_set('Asia/Tokyo');
		$year = $move_y;
		$mon = $move_m + 1;
		if($mon > 12){$year = $year + 1; $mon = 1;}
		$wday = date("w", mktime(0, 0, 0, $mon, 1, $year));
		$mday = date("t", mktime(0, 0, 0, $mon, 1, $year));
	}
	elseif($move_p == -1){
		date_default_timezone_set('Asia/Tokyo');
		$year = $move_y;
		$mon = $move_m - 1;
		if($mon < 1){$year = $year - 1; $mon = 12;}
		$wday = date("w", mktime(0, 0, 0, $mon, 1, $year));
		$mday = date("t", mktime(0, 0, 0, $mon, 1, $year));
	}
	/* 初回接続時 or 当月リターン時 */
	else{
		date_default_timezone_set('Asia/Tokyo');
		$year = date("Y");
		$mon = date("n");
		$mday = date("t");
		$wday = date("w", mktime(0, 0, 0, $mon, 1, $year));
	}

	/* 当日の日時データを個別取得 */
	date_default_timezone_set('Asia/Tokyo');
	$t_year = date("Y");
	$t_mon = date("n");
	$t_day = date("j");

	/* 一桁月の処理 */
	$mon0 = $mon;
	if($mon0 < 10){$mon0 = "0$mon";}

	/* 月次コメントファイルの呼び出し */
	$m_comm_file = "$year" . "$mon0" . ".dat";
	setlocale(LC_ALL,'ja_JP.UTF-8');
	$fp_comm = fopen("log/mon/$m_comm_file", "r");
	$m_comm = fgets($fp_comm);
	fclose($fp_comm);

	/* 最終更新日時DATファイル呼び出し */
	$fp_ymd = fopen("ymd.dat","r");
	$ymd = fgets($fp_ymd);
	fclose($fp_ymd);
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
		<table class="center">
			<tr>
				<td>
					<form method="post" action="index.php">
						<input type="hidden" name="move_p" value="-1">
						<input type="hidden" name="move_y" value="<?php print "$year"; ?>">
						<input type="hidden" name="move_m" value="<?php print "$mon"; ?>">
						<input class="time_move" type="submit" value="前月">
					</form>
				</td>
				<td>
					<form method="post" action="index.php">
						<input type="hidden" name="move_p" value="0">
						<input class="time_move" type="submit" value="今月に移動">
					</form>
				</td>
				<td>
					<form method="post" action="index.php">
						<input type="hidden" name="move_p" value="1">
						<input type="hidden" name="move_y" value="<?php print "$year"; ?>">
						<input type="hidden" name="move_m" value="<?php print "$mon"; ?>">
						<input class="time_move" type="submit" value="翌月">
					</form>
				</td>
			</tr>
		</table>
		<div class="title_ymd"><?php print "$year"; ?>年<?php print "$mon"; ?>月</div>
		<?php if($m_comm != "FALSE" and $m_comm != ""){print "<table class=day_work_comm><tr><td width=100><div align=right>コメント欄：</div></td><td>$m_comm</td></tr></table>";} ?>
		<div class="comm_w">
			<form method="post" action="mon_work_comm.php">
				<input type="hidden" name="year" value="<?php print "$year"; ?>">
				<input type="hidden" name="mon" value="<?php print "$mon"; ?>">
				<input type="hidden" name="comm_run" value="0">
				<input type="submit" class="comm_b" value="コメント欄を修正する">
			</form>
		</div>
		<table class="cal">
			<tr>
				<td class="cal_sun">日</td>
				<td class="cal_normal">月</td>
				<td class="cal_normal">火</td>
				<td class="cal_normal">水</td>
				<td class="cal_normal">木</td>
				<td class="cal_normal">金</td>
				<td class="cal_sat">土</td>
			</tr>
			<tr>
<?php
	/* 第一週目の処理（チェックコードはm_head） */
	$day_c = 1;
	$m_head = 0;
	for($wday_c = 0; $wday_c <= 6; $wday_c++){
		$day0 = $day_c;
		if($day0 < 10){$day0 = "0$day_c";}
		$day_file = "$year$mon0$day0" . ".dat";
		/* 日別ファイルよりアイコン情報を取得 */
		$mail_none = 0;
		$minyuka = 0;
		$nouki = 0;
		$daisya = 0;
		setlocale(LC_ALL,'ja_JP.UTF-8');
		$fp_data = fopen("log/$day_file", "r");
		$sheet_para = array();
		$i = 0;
		while($ret_csv = fgetcsv($fp_data)){
			for($col = 0; $col < count($ret_csv); $col++){
				$sheet_para[$i][$col] = $ret_csv[$col];
			}
			$i++;
		}
		$icon_i = 3;
		while($icon_i < $i){
			for($cnt = 0; $cnt < $col; $cnt++){
				if($sheet_para[$icon_i][$cnt] == 1){$mail_none = 1;}
				if($sheet_para[$icon_i][$cnt] == 2){$minyuka = 1;}
				if($sheet_para[$icon_i][$cnt] == 3){$nouki = 1;}
				if($sheet_para[$icon_i][$cnt] == 5){$daisya = 1;}
			}
			$icon_i = $icon_i + 3;
		}
		fclose($fp_data);
		/* 当日チェック */
		$c_today = 1;
		if($t_year != $year){$c_today = 0;}
		if($t_mon != $mon){$c_today = 0;}
		if($t_day != $day_c){$c_today = 0;}
		/* 当日チェック終了 */
		if($wday_c != $wday and $m_head == 0){print "<td class=cal_normal></td>"; continue;}
		if($wday_c == $wday and $m_head == 0){
			if($wday_c == 0){
				if($c_today == 1){print "<td class=cal_sun_t>";}
				else{print "<td class=cal_sun>";}
				print "<form action=day_work.php method=post><input type=hidden name=year value=$year><input type=hidden name=mon value=$mon><input type=hidden name=day value=$day_c><input type=hidden name=day_file value=$day_file><input id=submit_cal_sun type=submit value=$day_c></form>";
				if($mail_none == 1){print "<div><img src=img/top_mail_none.gif width=70 height=16 alt=メール未送信></div>";}
				if($minyuka == 1){print "<div><img src=img/top_minyuka.gif width=70 height=16 alt=商品未入荷></div>";}
				if($nouki == 1){print "<div><img src=img/top_nouki.gif width=70 height=16 alt=納期要確認></div>";}
				if($daisya == 1){print "<div><img src=img/top_daisya.gif width=70 height=16 alt=代車使用></div>";}
				print "</td>";
			}
			elseif($wday_c == 6){
				if($c_today == 1){print "<td class=cal_sat_t>";}
				else{print "<td class=cal_sat>";}
				print "<form action=day_work.php method=post><input type=hidden name=year value=$year><input type=hidden name=mon value=$mon><input type=hidden name=day value=$day_c><input type=hidden name=day_file value=$day_file><input id=submit_cal_sat type=submit value=$day_c></form>";
				if($mail_none == 1){print "<div><img src=img/top_mail_none.gif width=70 height=16 alt=メール未送信></div>";}
				if($minyuka == 1){print "<div><img src=img/top_minyuka.gif width=70 height=16 alt=商品未入荷></div>";}
				if($nouki == 1){print "<div><img src=img/top_nouki.gif width=70 height=16 alt=納期要確認></div>";}
				if($daisya == 1){print "<div><img src=img/top_daisya.gif width=70 height=16 alt=代車使用></div>";}
				print "</td>";
			}
			else{
				if($c_today == 1){print "<td class=cal_normal_t>";}
				else{print "<td class=cal_normal>";}
				print "<form action=day_work.php method=post><input type=hidden name=year value=$year><input type=hidden name=mon value=$mon><input type=hidden name=day value=$day_c><input type=hidden name=day_file value=$day_file><input id=submit_cal type=submit value=$day_c></form>";
				if($mail_none == 1){print "<div><img src=img/top_mail_none.gif width=70 height=16 alt=メール未送信></div>";}
				if($minyuka == 1){print "<div><img src=img/top_minyuka.gif width=70 height=16 alt=商品未入荷></div>";}
				if($nouki == 1){print "<div><img src=img/top_nouki.gif width=70 height=16 alt=納期要確認></div>";}
				if($daisya == 1){print "<div><img src=img/top_daisya.gif width=70 height=16 alt=代車使用></div>";}
				print "</td>";
			}
			$m_head = 1;
			$day_c++;
			continue;
		}
		if($m_head == 1){
			if($wday_c == 6){
				if($c_today == 1){print "<td class=cal_sat_t>";}
				else{print "<td class=cal_sat>";}
				print "<form action=day_work.php method=post><input type=hidden name=year value=$year><input type=hidden name=mon value=$mon><input type=hidden name=day value=$day_c><input type=hidden name=day_file value=$day_file><input id=submit_cal_sat type=submit value=$day_c></form>";
				if($mail_none == 1){print "<div><img src=img/top_mail_none.gif width=70 height=16 alt=メール未送信></div>";}
				if($minyuka == 1){print "<div><img src=img/top_minyuka.gif width=70 height=16 alt=商品未入荷></div>";}
				if($nouki == 1){print "<div><img src=img/top_nouki.gif width=70 height=16 alt=納期要確認></div>";}
				if($daisya == 1){print "<div><img src=img/top_daisya.gif width=70 height=16 alt=代車使用></div>";}
				print "</td>";
			}
			else{
				if($c_today == 1){print "<td class=cal_normal_t>";}
				else{print "<td class=cal_normal>";}
				print "<form action=day_work.php method=post><input type=hidden name=year value=$year><input type=hidden name=mon value=$mon><input type=hidden name=day value=$day_c><input type=hidden name=day_file value=$day_file><input id=submit_cal type=submit value=$day_c></form>";
				if($mail_none == 1){print "<div><img src=img/top_mail_none.gif width=70 height=16 alt=メール未送信></div>";}
				if($minyuka == 1){print "<div><img src=img/top_minyuka.gif width=70 height=16 alt=商品未入荷></div>";}
				if($nouki == 1){print "<div><img src=img/top_nouki.gif width=70 height=16 alt=納期要確認></div>";}
				if($daisya == 1){print "<div><img src=img/top_daisya.gif width=70 height=16 alt=代車使用></div>";}
				print "</td>";
			}
			$day_c++;
		}
	}
?>
			</tr>
<?php
	/* 第二週目以降の処理 */
	while($day_c <= $mday){
		print "<tr>" . "\n";
		for($wday_c = 0; $wday_c <= 6; $wday_c++){
			$day0 = $day_c;
			if($day0 < 10){$day0 = "0$day_c";}
			$day_file = "$year$mon0$day0" . ".dat";
			/* 日別ファイルよりアイコン情報を取得 */
			$mail_none = 0;
			$minyuka = 0;
			$nouki = 0;
			$daisya = 0;
			setlocale(LC_ALL,'ja_JP.UTF-8');
			$fp_data = fopen("log/$day_file", "r");
			$sheet_para = array();
			$i = 0;
			while($ret_csv = fgetcsv($fp_data)){
				for($col = 0; $col < count($ret_csv); $col++){
					$sheet_para[$i][$col] = $ret_csv[$col];
				}
				$i++;
			}
			$icon_i = 3;
			while($icon_i < $i){
				for($cnt = 0; $cnt < $col; $cnt++){
					if($sheet_para[$icon_i][$cnt] == 1){$mail_none = 1;}
					if($sheet_para[$icon_i][$cnt] == 2){$minyuka = 1;}
					if($sheet_para[$icon_i][$cnt] == 3){$nouki = 1;}
					if($sheet_para[$icon_i][$cnt] == 5){$daisya = 1;}
				}
				$icon_i = $icon_i + 3;
			}
			fclose($fp_data);
			/* 当日チェック */
			$c_today = 1;
			if($t_year != $year){$c_today = 0;}
			if($t_mon != $mon){$c_today = 0;}
			if($t_day != $day_c){$c_today = 0;}
			/* 当日チェック終了 */
			if($day_c > $mday){print "<td class=cal_normal></td>" . "\n"; continue;}
			if($wday_c == 0){
				if($c_today == 1){print "<td class=cal_sun_t>";}
				else{print "<td class=cal_sun>";}
				print "<form action=day_work.php method=post><input type=hidden name=year value=$year><input type=hidden name=mon value=$mon><input type=hidden name=day value=$day_c><input type=hidden name=day_file value=$day_file><input id=submit_cal_sun type=submit value=$day_c></form>";
				if($mail_none == 1){print "<div><img src=img/top_mail_none.gif width=70 height=16 alt=メール未送信></div>";}
				if($minyuka == 1){print "<div><img src=img/top_minyuka.gif width=70 height=16 alt=商品未入荷></div>";}
				if($nouki == 1){print "<div><img src=img/top_nouki.gif width=70 height=16 alt=納期要確認></div>";}
				if($daisya == 1){print "<div><img src=img/top_daisya.gif width=70 height=16 alt=代車使用></div>";}
				print "</td>" ;
			}
			elseif($wday_c == 6){
				if($c_today == 1){print "<td class=cal_sat_t>";}
				else{print "<td class=cal_sat>";}
				print "<form action=day_work.php method=post><input type=hidden name=year value=$year><input type=hidden name=mon value=$mon><input type=hidden name=day value=$day_c><input type=hidden name=day_file value=$day_file><input id=submit_cal_sat type=submit value=$day_c></form>";
				if($mail_none == 1){print "<div><img src=img/top_mail_none.gif width=70 height=16 alt=メール未送信></div>";}
				if($minyuka == 1){print "<div><img src=img/top_minyuka.gif width=70 height=16 alt=商品未入荷></div>";}
				if($nouki == 1){print "<div><img src=img/top_nouki.gif width=70 height=16 alt=納期要確認></div>";}
				if($daisya == 1){print "<div><img src=img/top_daisya.gif width=70 height=16 alt=代車使用></div>";}
				print "</td>";
			}
			else{
				if($c_today == 1){print "<td class=cal_normal_t>";}
				else{print "<td class=cal_normal>";}
				print "<form action=day_work.php method=post><input type=hidden name=year value=$year><input type=hidden name=mon value=$mon><input type=hidden name=day value=$day_c><input type=hidden name=day_file value=$day_file><input id=submit_cal type=submit value=$day_c></form>";
				if($mail_none == 1){print "<div><img src=img/top_mail_none.gif width=70 height=16 alt=メール未送信></div>";}
				if($minyuka == 1){print "<div><img src=img/top_minyuka.gif width=70 height=16 alt=商品未入荷></div>";}
				if($nouki == 1){print "<div><img src=img/top_nouki.gif width=70 height=16 alt=納期要確認></div>";}
				if($daisya == 1){print "<div><img src=img/top_daisya.gif width=70 height=16 alt=代車使用></div>";}
				print "</td>";
			}
			$day_c++;
		}
		print "</tr>" . "\n";
	}
?>
		</table>
		<div class="space10px"></div>
		<table class="center_900_right">
			<tr>
				<td>
					<form method="post" action="log.php">
						<input type="submit" value="操作ログを表示">
					</form>
				</td>
				<td width="19%">
					<form method="post" action="print_work_sheet.php">
						<input type="hidden" name="year" value="<?php print "$year"; ?>">
						<input type="hidden" name="mon" value="<?php print "$mon"; ?>">
						<input type="hidden" name="day" value="<?php print "$day"; ?>">
						<input type="hidden" name="sheet_file" value="<?php print "$sheet_file"; ?>">
						<input type="submit" value="白紙の作業書を印刷する">
					</form>
				</td>
			</tr>
		</table>
		<div class="space30px"></div>
		<table class="center_900_center">
			<tr>
				<td><a href="https://www.kts-web.com/work_sheet_factory/index.php"><img src="img/link_factory.gif" width="400" height="36"></a></td>
				<td><a href="https://www.kts-web.com/work_sheet_ichinoe/index.php"><img src="img/link_ichinoe.gif" width="400" height="36"></a></td>
			</tr>
		</table>
		<div class="space10px"></div>
		<div class="footer">Copyright (C) Kind Techno Structure Corporation. All Rights Reserved.</div>
	</body>
</html>
