<?php
	/* 各データの受け取り */
	$year = $_POST['year'];
	$mon = $_POST['mon'];
	$day = $_POST['day'];
	$send_check = $_POST['send_check'];
	$tenpo_comment = $_POST['tenpo_comment'];
	$sheet_file = $_POST['sheet_file'];
	
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

	/* メール送信対象のデータを取得 */
	setlocale(LC_ALL,'ja_JP.UTF-8');
	$fp_data_s = fopen("log/$sheet_file", "r");
	$sheet_file_para = array();
	$sheet_i = 0;
	while($ret_csv = fgetcsv($fp_data_s)){
		for($col = 0; $col < count($ret_csv); $col++){
			$sheet_file_para[$sheet_i][$col] = $ret_csv[$col];
		}
		$sheet_i++;
	}

	/* 取得データを各変数に整理 */
	$name = $sheet_file_para[0][1];
	$mail = $sheet_file_para[0][6];
	$tel = $sheet_file_para[0][3];
	$address = $sheet_file_para[0][5];
	$car = $sheet_file_para[0][7];
	$model = $sheet_file_para[0][8];
	$car_old = $sheet_file_para[0][10];
	$arm = $sheet_file_para[0][13];
	$wheel = $sheet_file_para[0][14];
	$fender = $sheet_file_para[0][15];
	$camber = $sheet_file_para[0][16];
	$lift1 = $sheet_file_para[0][17];
	$lift2 = $sheet_file_para[0][18];
	
	$lift = $sheet_file_para[1][3];
	$time_s = $sheet_file_para[1][1];

	/* 開始時間パラメーターの変換 */
	if($time_s == 0){$time_sf = "9時00分～";}
	elseif($time_s == 1){$time_sf = "9時30分～";}
	elseif($time_s == 2){$time_sf = "10時00分～";}
	elseif($time_s == 3){$time_sf = "10時30分～";}
	elseif($time_s == 4){$time_sf = "11時00分～";}
	elseif($time_s == 5){$time_sf = "11時30分～";}
	elseif($time_s == 6){$time_sf = "12時00分～";}
	elseif($time_s == 7){$time_sf = "12時30分～";}
	elseif($time_s == 8){$time_sf = "13時00分～";}
	elseif($time_s == 9){$time_sf = "13時30分～";}
	elseif($time_s == 10){$time_sf = "14時00分～";}
	elseif($time_s == 11){$time_sf = "14時30分～";}
	elseif($time_s == 12){$time_sf = "15時00分～";}
	elseif($time_s == 13){$time_sf = "15時30分～";}
	elseif($time_s == 14){$time_sf = "16時00分～";}
	elseif($time_s == 15){$time_sf = "16時30分～";}
	elseif($time_s == 16){$time_sf = "17時00分～";}
	elseif($time_s == 17){$time_sf = "17時30分～";}
	elseif($time_s == 18){$time_sf = "18時00分～";}
	elseif($time_s == 19){$time_sf = "18時30分～";}
	elseif($time_s == 20){$time_sf = "19時00分～";}
	elseif($time_s == 21){$time_sf = "19時30分～";}

	/* アーム有無文字変換 */
	if($arm == 0){$arm_f = "無し";}
	if($arm == 1){$arm_f = "あり";}

	/* ホイールサイズ文字変換 */
	if($wheel == 22){$wheel_f = "22インチ以上";}
	if($wheel != 22){$wheel_f = "$wheel" . "インチ";}

	/* 送信フラグの確認 */
	if($sheet_ara[0][17] != 0){$send_f = 1;}

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
<?php if($send_f == 1):?>
		<table class="day_work_comm">
			<tr>
				<td class="font_ymd" width="50%"><?php print "$year"; ?>年<?php print "$mon"; ?>月<?php print "$day"; ?>日（<?php print "$wday"; ?>）</td>
				<td>
					<form method="post" action="day_work.php">
						<input type="hidden" name="year" value="<?php print "$year"; ?>">
						<input type="hidden" name="mon" value="<?php print "$mon"; ?>">
						<input type="hidden" name="day" value="<?php print "$day"; ?>">
						<input type="submit" value="作業一覧ページへ戻る">
					</form>
				</td>
				<td><div><a href=index.php>月間表示画面へ戻る</a></div></td>
			</tr>
		</table>
		<div class="send_mail_title">既に送信済みか無効なデータです。</div>
		<div class="space5px"></div>
		<div class="footer">Copyright (C) Kind Techno Structure Corporation. All Rights Reserved.</div>
	</body>
<?php endif;?>
<?php if($send_f == 1){exit();} ?>
<?php if($send_check == 2):?>
		<table class="day_work_comm">
			<tr>
				<td class="font_ymd" width="50%"><?php print "$year"; ?>年<?php print "$mon"; ?>月<?php print "$day"; ?>日（<?php print "$wday"; ?>）</td>
				<td>
					<form method="post" action="alignment.php">
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
		<div class="send_mail_title">予約希望日時：<?php print "$year"; ?>年<?php print "$mon"; ?>月<?php print "$day"; ?>日（<?php print "$wday"; ?>）<?php print "$time_sf"; ?></div>
		<hr width="100%">
		<div class="space5px"></div>
		<div class="center_900">メールを送信せずにパラメーターのみ変更します。宜しければ下記ボタンをクリックして下さい。</div>
		<div class="center_900">
			<form method="post" action="alignment_send.php">
				<input type="hidden" name="year" value="<?php print "$year"; ?>">
				<input type="hidden" name="mon" value="<?php print "$mon"; ?>">
				<input type="hidden" name="day" value="<?php print "$day"; ?>">
				<input type="hidden" name="send_check" value="<?php print "$send_check"; ?>">
				<input type="hidden" name="sheet_file" value="<?php print "$sheet_file"; ?>">
				<input type="submit" value="送信">
			</form>
		</div>
		<div class="space5px"></div>
		<div class="footer">Copyright (C) Kind Techno Structure Corporation. All Rights Reserved.</div>
	</body>
</html>
<?php endif;?>
<?php if($send_check == 2){exit();} ?>
		<table class="day_work_comm">
			<tr>
				<td class="font_ymd" width="50%"><?php print "$year"; ?>年<?php print "$mon"; ?>月<?php print "$day"; ?>日（<?php print "$wday"; ?>）</td>
				<td>
					<form method="post" action="alignment.php">
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
		<div class="send_mail_title">予約希望日時：<?php print "$year"; ?>年<?php print "$mon"; ?>月<?php print "$day"; ?>日（<?php print "$wday"; ?>）<?php print "$time_sf"; ?></div>
		<hr width="100%">
		<div class="space5px"></div>
		<div class="center_900">下記内容でお客様にメールを送信します。</div>
		<div class="space5px"></div>
		<table class="center_900" cellspacing="0" border="1" bordercolor="#00FFFF">
			<tr><td>件名： 【KTS平和島店】アライメント予約完了のご連絡</td></tr>
			<tr>
				<td>
					<div><?php print "$name"; ?> 様</div><br>
					<div>この度は本サービスをご利用頂きまして誠にありがとうございます。</div><br>
					<div>下記内容にてアライメント作業のご予約を承りましたのでご確認下さい。</div><br>
					<div>■作業店舗： KTS平和島店</div>
					<div>■作業日時： <?php print "$year"; ?>年<?php print "$mon"; ?>月<?php print "$day"; ?>日（<?php print "$wday"; ?>）<?php print "$time_sf"; ?></div>
					<div>　※作業開始時間となりますので上記時刻の5分前までにご来店下さい。</div><br>
					<div>■お名前： <?php print "$name"; ?></div><br>
					<div>■メールアドレス： <?php print "$mail"; ?></div><br>
					<div>■電話番号： <?php print "$tel"; ?></div><br>
					<div>■住所： <?php print "$address"; ?></div><br>
					<div>■車種： <?php print "$car"; ?></div><br>
					<div>■車輌型式： <?php print "$model"; ?></div><br>
					<div>■年式： <?php print "$car_old"; ?></div><br>
					<div>■社外アームの有無： <?php print "$arm_f"; ?></div><br>
					<div>■ホイールサイズ： <?php print "$wheel_f"; ?></div><br>
					<div>■その他：</div>
					<?php if($fender == 1){print "<div>　【フェンダーがリムにかぶる状態】</div>\n";} ?>
					<?php if($camber == 1){print "<div>　【キャンバー角が5度以上ついている】</div>\n";} ?>
					<?php if($lift1 == 1){print "<div>　【ジャッキアップポイントにLED、ネオン管等が付いている】</div>\n";} ?>
					<?php if($lift2 == 1){print "<div>　【ジャッキアップポイントがサイドステップ等で隠れている】</div>\n";} ?>
					<br>
					<div>上記内容に誤りがある場合はお手数ですがお電話にてお問合せ下さい。</div><br>
					<?php if($tenpo_comment != ""){print "<div>$tenpo_comment</div>\n";} ?>
					<br>
					<div>それでは当日のご来店をお待ちしております。</div><br><br>
					<div>KTS平和島店</div>
					<div>TEL：03-5767-5832</div>
					<div>〒143-0016</div>
					<div>東京都大田区大森北5-10-13</div>
				</td>
			</tr>
		</table>
		<div class="space5px"></div>
		<div class="center_900">
			<form method="post" action="alignment_send.php">
				<input type="hidden" name="send_check" value="1">
				<input type="hidden" name="year" value="<?php print "$year"; ?>">
				<input type="hidden" name="mon" value="<?php print "$mon"; ?>">
				<input type="hidden" name="day" value="<?php print "$day"; ?>">
				<input type="hidden" name="sheet_file" value="<?php print "$sheet_file"; ?>">
				<input type="hidden" name="tenpo_comment" value="<?php print "$tenpo_comment"; ?>">
				<input type="submit" value="送信">
			</form>
		</div>
		<div class="space5px"></div>
		<div class="footer">Copyright (C) Kind Techno Structure Corporation. All Rights Reserved.</div>
	</body>
</html>
