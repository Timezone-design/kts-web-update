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

	/* サーバー時刻取得 */
	$date = date("Y年n月j日G時i分");

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

	/* 対象日別作業データを取得 */
	setlocale(LC_ALL,'ja_JP.UTF-8');
	$fp_data_d = fopen("log/$day_file", "r");
	$day_para = array();
	$day_i = 0;
	while($ret_csv = fgetcsv($fp_data_d)){
		for($col = 0; $col < count($ret_csv); $col++){
			$day_para[$day_i][$col] = $ret_csv[$col];
		}
		$day_i++;
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
	if($sheet_file_para[0][19] != 0){$send_f = 1;}

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
		<div class="send_mail_title">既に送信済み、もしくは無効なデータです。</div>
		<div class="space5px"></div>
		<div class="footer">Copyright (C) Kind Techno Structure Corporation. All Rights Reserved.</div>
	</body>
</html>
<?php endif;?>
<?php if($send_f == 1){exit();} ?>
<?php
	/* 送信フラグ、及び、アイコン表示を修正保存 */
	/* 作業一覧ファイルの修正 */
	$lift = $sheet_file_para[1][3];
	$icon_p = $lift + 2;
	$day_para[$icon_p][$time_s] = "";
	$order_put_d = $day_para;
	$fp_put_d = fopen("log/$day_file", "w");
	foreach($order_put_d as $fields) fputcsv($fp_put_d, $fields);
	fclose($fp_put_d);
	/* 個別作業表ファイルの修正 */
	$sheet_file_para[0][19] = $send_check;
	$sheet_file_para[1][8] = 0;
	/* メモ欄・工賃への追加（20190718修正） */
	if($tenpo_comment != ""){
		if($sheet_file_para[1][7] != ""){$sheet_file_para[1][7] .= "、";}
		$sheet_file_para[1][7] .= $tenpo_comment;
	}
	$order_put_s = $sheet_file_para;
	$fp_put_s = fopen("log/$sheet_file", "w");
	foreach($order_put_s as $fields) fputcsv($fp_put_s, $fields);
	fclose($fp_put_s);

	/* 最終更新日時の更新 */
	file_put_contents("ymd.dat","$date");

	/* 使用ログの保存 */
	$u_id = $_SERVER['REMOTE_USER']; /* ユーザーIDを取得 */
	$u_agent = $_SERVER['HTTP_USER_AGENT']; /* 使用環境を取得 */
	$u_referer = $_SERVER['HTTP_REFERER']; /* リファラー情報を取得 */
	$u_date = date("Y/m/d(D) H:i:s"); /* 日時を取得 */
	if($send_check == 1){$u_todo = "お客様にメールを送信";} /* 変更内容 */
	elseif($send_check == 2){$u_todo = "メールを送信しないでパラメーターのみ変更";} /* 変更内容 */
	$u_log = "$u_date,$u_id,$day_file,$sheet_file,$u_todo,$u_agent,$u_referer" . "\n"; /* 保存ログデータ */
	$fp_log = fopen("log/work/work_access.log", "a");
	fwrite($fp_log, "$u_log");
	fclose($fp_log);

	/* 確認用パラメーター表示 */
	/* print "<div>" . "lift：" . "$lift" . "</div>"; */
	/* print "<div>" . "icon_p：" . "$icon_p" . "</div>"; */
	/* print "<div>" . "time_s：" . "$time_s" . "</div>"; */
	/* print "<div>" . "send_check：" . "$send_check" . "</div>"; */
	/* print "<div>" . "sheet_file：" . "$sheet_file" . "</div>"; */
	/* print_r ($day_para); */

?>
<?php if($send_check == 2):?>
		<table class="day_work_comm">
			<tr>
				<td class="font_ymd" width="50%"><?php print "$year"; ?>年<?php print "$mon"; ?>月<?php print "$day"; ?>日（<?php print "$wday"; ?>）</td>
			</tr>
		</table>
		<div class="send_mail_title">予約希望日時：<?php print "$year"; ?>年<?php print "$mon"; ?>月<?php print "$day"; ?>日（<?php print "$wday"; ?>）<?php print "$time_sf"; ?></div>
		<hr width="100%">
		<div class="space5px"></div>
		<div class="center_900">メールを送信せずにパラメーターのみ変更しました。</div>
		<div class="space5px"></div>
		<div class="center_900">
			<form method="post" action="work_sheet.php">
				<input type="hidden" name="year" value="<?php print "$year"; ?>">
				<input type="hidden" name="mon" value="<?php print "$mon"; ?>">
				<input type="hidden" name="day" value="<?php print "$day"; ?>">
				<input type="hidden" name="sheet_file" value="<?php print "$sheet_file"; ?>">
				<input type="submit" value="個別作業一覧ページへ">
			</form>
		</div>
		<div class="space5px"></div>
		<div class="footer">Copyright (C) Kind Techno Structure Corporation. All Rights Reserved.</div>
	</body>
</html>
<?php endif;?>
<?php if($send_check == 2){exit();} ?>
<?php
	/* メールを送信 */

	/* 文字コード定義 */
	mb_language("Japanese");
	mb_internal_encoding("UTF-8");
	
	/* 件名 */
	$subject = mb_encode_mimeheader("【KTS平和島店】アライメント予約完了のご連絡");
	
	/* 宛先 */
	$to = $mail;
	
	/* headers */
	$headers = "From: alignment-heiwajima@kts-web.com\n";
	/* $headers = "From: sugawara@kts-web.com\n"; */
	$headers .= "BCC: sugawara@kts-web.com,k.hanaki@kts-web.com,alignment-heiwajima@kts-web.com\n";
	/* $headers .= "BCC: info@kts-web.com\n"; */
	$headers .= "Content-Type: text/plain; charset=UTF-8\n";

	/* メール本文 */
	$body = "$name 様" . "\n\n";
	$body .= "この度は本サービスをご利用頂きまして誠にありがとうございます。" . "\n\n";
	$body .= "下記内容にてアライメント作業のご予約を承りましたのでご確認下さい。" . "\n\n\n";
	$body .= "■作業店舗： KTS平和島店" . "\n\n";
	$body .= "■ご希望日時： " . "$year" . "年" . "$mon" . "月" . "$day" . "日（" . "$wday" . "）" . "$time_sf" . "\n";
	$body .= "　※作業開始時間となりますので上記時刻の5分前までにご来店下さい。" . "\n\n";
	$body .= "■お名前： $name" . "\n\n";
	$body .= "■メールアドレス： $mail" . "\n\n";
	$body .= "■電話番号： $tel" . "\n\n";
	$body .= "■住所： $address" . "\n\n";
	$body .= "■車種： $car" . "\n\n";
	$body .= "■車輌型式： $model" . "\n\n";
	$body .= "■年式： $car_old" . "\n\n";
	$body .= "■社外アームの有無： $arm_f" . "\n\n";
	$body .= "■ホイールサイズ： $wheel_f" . "\n\n";
	$body .= "■その他：" . "\n";
	if($fender == 1){$body .= "　【フェンダーがリムにかぶる状態】" . "\n";}
	if($camber == 1){$body .= "　【キャンバー角が5度以上ついている】" . "\n";}
	if($lift1 == 1){$body .= "　【ジャッキアップポイントにLED、ネオン管等が付いている】" . "\n";}
	if($lift2 == 1){$body .= "　【ジャッキアップポイントがサイドステップ等で隠れている】" . "\n";}
	$body .= "\n";
	$body .= "上記内容に誤りがある場合はお手数ですがお電話にてお問合せ下さい。" . "\n\n";
	$body .= "$tenpo_comment" . "\n\n";
	$body .= "それでは当日のご来店をお待ちしております。" . "\n\n\n";
	$body .= "KTS平和島店" . "\n";
	$body .= "TEL：03-5767-5832" . "\n";
	$body .= "〒143-0016" . "\n";
	$body .= "東京都大田区大森北5-10-13" . "\n";

	/* Return-Path */
	$mparameter = "-f alignment-heiwajima@kts-web.com";
	/* メール送信 */
	mail($to, $subject, $body, $headers, $mparameter);
?>
		<table class="day_work_comm">
			<tr>
				<td class="font_ymd" width="50%"><?php print "$year"; ?>年<?php print "$mon"; ?>月<?php print "$day"; ?>日（<?php print "$wday"; ?>）</td>
			</tr>
		</table>
		<div class="send_mail_title">予約希望日時：<?php print "$year"; ?>年<?php print "$mon"; ?>月<?php print "$day"; ?>日（<?php print "$wday"; ?>）<?php print "$time_sf"; ?></div>
		<hr width="100%">
		<div class="space5px"></div>
		<div class="center_900">お客様にメールを送信しました。</div>
		<div class="space5px"></div>
		<div class="space5px"></div>
		<div class="center_900">
			<form method="post" action="work_sheet.php">
				<input type="hidden" name="year" value="<?php print "$year"; ?>">
				<input type="hidden" name="mon" value="<?php print "$mon"; ?>">
				<input type="hidden" name="day" value="<?php print "$day"; ?>">
				<input type="hidden" name="sheet_file" value="<?php print "$sheet_file"; ?>">
				<input type="submit" value="個別作業一覧ページへ">
			</form>
		</div>
		<div class="space5px"></div>
		<div class="footer">Copyright (C) Kind Techno Structure Corporation. All Rights Reserved.</div>
	</body>
</html>
