<?php

	/* 判別フラグ初期化 */
	$check_h = 0;
	
	/* 日時データの受け取り */
	$year = $_POST['year'];
	$mon = $_POST['mon'];
	$day = $_POST['day'];
	$time_s = $_POST['time_s'];
	$lift = $_POST['lift'];
	$change_lift = $_POST['change_lift'];
	$rbday_check = $_POST['rbday_check'];

	$mon0 = $mon;
	$day0 = $day;
	if($mon < 10){$mon0 = "0$mon";}
	if($day < 10){$day0 = "0$day";}
	$day_file = "../work_sheet_heiwajima/log/$year$mon0$day0.dat";

	/* 入力データの受け取り */
	$kiyaku = $_POST['kiyaku'];
	$name = $_POST['name'];
	$mail = $_POST['mail'];
	$tel = $_POST['tel'];
	$address = $_POST['address'];
	$car = $_POST['car'];
	$model = $_POST['model'];
	$old = $_POST['old'];
	$arm = $_POST['arm'];
	$wheel = $_POST['wheel'];
	$fender = $_POST['fender'];
	$camber = $_POST['camber'];
	$lift1 = $_POST['lift1'];
	$lift2 = $_POST['lift2'];

	/* アーム有無文字変換 */
	if($arm == 0){$arm_f = "無し";}
	if($arm == 1){$arm_f = "あり";}

	/* ホイールサイズ文字変換 */
	/* if($wheel == 22){$wheel_f = "22インチ以上";} */
	/* if($wheel != 22){$wheel_f = "$wheel" . "インチ";} */
	$wheel_f = "$wheel" . "インチ";

	/* サーバー時刻取得 */
	date_default_timezone_set('Asia/Tokyo');
	$rbday = date("z");
	$date = date("Y年n月j日G時i分");

	/* 取得データより開始時間への変換 */
	$jikan = $time_s * 0.5 + 9;
	$order_time = "$year" . "年" . "$mon" . "月" . "$day" . "日" . "$jikan" . "時";

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
	$fp_data = fopen("$day_file", "r");
	$day_para = array();
	$i = 0;
	while($ret_csv = fgetcsv($fp_data)){
		for($col = 0; $col < count($ret_csv); $col++){
			$day_para[$i][$col] = $ret_csv[$col];
			if($day_para[$i][$col] == ""){$day_para[$i][$col] = "null";}
		}
		$i++;
	}
	fclose($fp_data);

	/* リフト・開始時刻の照合 */
	$time_e = $time_s + 2;
	for($time_c = $time_s; $time_c < $time_e; $time_c++){
		if($day_para[$lift][$time_c] != "open" and $day_para[$lift][$time_c] != ""){$sheet_check = 1;}
	}

	/* 放置対策 */
	$time_check = $rbday_check - $rbday;
	if($time_check < 2){$sheet_check = 2;}

	/* ダイレクトリンク、不正アクセス対策 */
	$link_off = 0;
	if($year == ""){$link_off = 1;}
	if($mon == ""){$link_off = 1;}
	if($day == ""){$link_off = 1;}
	if($time_s == ""){$link_off = 1;}
	if($lift == ""){$link_off = 1;}
	if($name == ""){$link_off = 1;}
	if($mail == ""){$link_off = 1;}
	if($tel == ""){$link_off = 1;}
	if($address == ""){$link_off = 1;}
	if($car == ""){$link_off = 1;}
	if($model == ""){$link_off = 1;}
	if($old == ""){$link_off = 1;}
	if($arm == ""){$link_off = 1;}
	if($wheel == ""){$link_off = 1;}

	/* 動作確認用 */
	/*$check_h = 1;*/
?>
<html>
	<head>
		<title>KTS-web</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="robots" content="noindex,nofollow">
		<link rel="stylesheet" href="http://www.kts-web.com/web.css" type="text/css">
		<link rel="stylesheet" href="https://www.kts-web.com/web.css" type="text/css">
	</head>
	<body bgcolor="#FFFFFF" text="#000000">

<?php if($link_off == 1):?>
		<div>データ送信時にエラー、もしくは不正なアクセスを検知しました。</div>
		<div>お手数ですが最初からやり直して頂くか、お電話にてお問合せ下さい。</div>
		<table width="700">
			<tr>
				<td>
					<div align="right"><a href="alignment.php"><img src="yoyaku_jokyou.gif" border="0"></a></div>
				</td>
			</tr>
		</table>
	</body>
</html>
<?php endif;?>
<?php if($link_off == 1){exit();} ?>

<?php if($sheet_check == 1):?>
		<div>大変申し訳ございませんが、<?php print "$year"; ?>年<?php print "$mon"; ?>月<?php print "$day"; ?>日<?php print "$jikan"; ?>時からの作業枠は既に埋まっている為、ご予約頂けません。</div>
		<div>お手数ですが別の日時をご指定下さい。</div>
		<table width="700">
			<tr>
				<td>
					<div align="right"><a href="alignment.php"><img src="yoyaku_jokyou.gif" border="0"></a></div>
				</td>
			</tr>
		</table>
	</body>
</html>
<?php endif;?>
<?php if($check_h == 1){exit();} ?>

<?php if($check_h == 2):?>
		<div>大変申し訳ございませんが、ご指定頂いた日時は本サービスからのご予約ができません。</div>
		<div>お手数ですが別の日時をご指定頂くか店舗までお電話にてお問い合わせ下さい。</div>
		<table width="700">
			<tr>
				<td>
					<div align="right"><a href="alignment.php"><img src="yoyaku_jokyou.gif" border="0"></a></div>
				</td>
			</tr>
		</table>
	</body>
</html>
<?php endif;?>
<?php if($check_h == 2){exit();} ?>

<?php
	/* 予約データ処理 */
	/* ユーザー環境の確認 */
	$u_agent = $_SERVER['HTTP_USER_AGENT'];
	$u_referer = $_SERVER['HTTP_REFERER'];
	
	/* 入力ログデータの保存 */
	$csv_r = "$date,$order_time,$name,$mail,$tel,$address,$car,$model,$old,$arm_f,$u_agent,$u_referer\n";
	
	$fp_log = fopen("kanri/log.dat", "a");
	fwrite($fp_log, "$csv_r");
	fclose($fp_log);

	/* 店舗作業DBへの保存 */
	setlocale(LC_ALL,'ja_JP.UTF-8');
	$fp_data_p = fopen("$day_file", "r");
	if($fp_data_p == "FALSE" or $fp_data_p == ""){ /* ファイルが存在しない場合はファイル作成 */
		$i = 0;
		$comm_f = 0;
		while($i < 16){
			for($col = 0; $col <= 21; $col++){
				if($i == 0){
					$sheet_para[0][0] = "連絡事項があればここに入力して下さい。"; /* 初期値として代入 */
					$sheet_para[0][1] = 0; /* 個別作業表用連番（後でプラスするので0スタート） */
					break;
				}
				elseif($i == 1){$sheet_para[$i][$col] = "open";}
				elseif($i == 4){$sheet_para[$i][$col] = "open";}
				elseif($i == 7){$sheet_para[$i][$col] = "open";}
				elseif($i == 10){$sheet_para[$i][$col] = "open";}
				elseif($i == 13){$sheet_para[$i][$col] = "open";}
				else{$sheet_para[$i][$col] = "";}
			}
			$i++;
		}
	}
	else{
		$i = 0;
		$comm_f = 1;
		while($ret_csv = fgetcsv($fp_data_p)){
			for($col = 0; $col < count($ret_csv); $col++){
				$sheet_para[$i][$col] = $ret_csv[$col];
			}
			$i++;
		}
	}
	fclose($fp_data_p);

	/* 登録データの整理（アライメント専用情報未補完部分のまとめ） */
	$other_f .= "【アーム：" . $arm_f . "】";
	$other_f .= "【ホイール：" . $wheel_f . "】";
	if($wagon_check == 1){$other_f .= "【ワゴン車輌】";}
	if($fender == 1){$other_f .= "【フェンダーがリムにかぶる状態】";}
	if($camber == 1){$other_f .= "【キャンバー角が5度以上ついている】";}
	if($lift1 == 1){$other_f .= "【ジャッキアップポイントにLED、ネオン管等が付いている】";}
	if($lift2 == 1){$other_f .= "【ジャッキアップポイントがサイドステップ等で隠れている】";}
	/* 作業ファイル（実質新規作成） */
	$sheet_para[0][1]++;
	$file_no = $sheet_para[0][1];
	$mon0 = $mon;
	if($mon0 < 10){$mon0 = "0$mon";}
	$day0 =	$day;
	if($day0 < 10){$day0 = "0$day";}
	$work_file = "$year" . "$mon0" . "$day0" . "_" . "$file_no" . ".dat";
	/* お客様情報 管理No. 氏名 氏名（フリガナ） 電話番号 郵便番号 住所 メール 車輌 車輌型式 エンジン 年式 駆動 最終更新日 アーム ホイール フェンダー キャンバー ジャッキアップ1 ジャッキアップ2 送信フラグ*/
	$in_ind = "0,$name,アライメント,$tel,郵便番号,$address,$mail,$car,$model,アライメント予約,$old,アライメント予約,$date,$arm,$wheel,$fender,$camber,$lift1,$lift2,0" . "\n";
	/* 作業内容 管理No. 開始時間 終了時間 リフト 販売区分 担当者 作業内容 メモ欄 アイコン 最終更新日 */
	$in_work = "1,$time_s,$time_e,$lift,1,アライメント予約,WEBアライメント測定・調整,$other_f,1,$date" . "\n"; /* 作業内容 */
	$in_data = "$in_ind" . "$in_work";
	file_put_contents("../work_sheet_heiwajima/log/$work_file","$in_data"); /* 配列としてではなくカンマ・改行付きの文字列として保存（CSV形式なので問題無し） */
	/* 作業一覧ファイル */
	$time_w = $time_e - $time_s;
	$lift_file = $lift + 1;
	$lift_icon = $lift + 2;
	for($time_c = $time_s; $time_c < $time_e; $time_c++){
		if($time_c == $time_s){
			$sheet_para[$lift][$time_c] = $time_w;
			$sheet_para[$lift_file][$time_c] = "$work_file";
			$sheet_para[$lift_icon][$time_c] = 1;
		}
		else{$sheet_para[$lift][$time_c] = "reserve";}
	}
	/* CSV形式に変換して上書き保存 */
	$order_put = $sheet_para;
	$fp_put = fopen("$day_file", "w");
	foreach($order_put as $fields) fputcsv($fp_put, $fields);
	fclose($fp_put);

	/* 最終更新日時の更新 */
	file_put_contents("../work_sheet_heiwajima/ymd.dat","$date");
?>

<?php
	/* メール送信部分 */
	/* 文字コード定義 */
	mb_language("Japanese");
	mb_internal_encoding("UTF-8");
	
	/* 件名 */
	$subject = mb_encode_mimeheader("【KTS平和島店】アライメント予約");
	
	/* 宛先 */
	$to = $mail;
	
	/* headers */
	$headers = "From: alignment-heiwajima@kts-web.com\n";
	/* $headers = "From: sugawara@kts-web.com\n"; */
	$headers .= "BCC: sugawara@kts-web.com,k.hanaki@kts-web.com,alignment-heiwajima@kts-web.com\n";
	$headers .= "Content-Type: text/plain; charset=UTF-8\n";

	/* メール本文 */
	$body = "$name 様" . "\n\n";
	$body .= "この度は本サービスをご利用頂きまして誠にありがとうございます。" . "\n\n";
	$body .= "※本メールは自動返信メールとなります。弊社より確定メールをお送りした時点での作業予約完了となりますのでご注意下さい。" . "\n\n";
	$body .= "下記ご入力内容にてご希望を承りました。" . "\n";
	$body .= "確認後、弊社スタッフより折り返しメールにてご連絡致します。" . "\n\n";
	$body .= "■作業店舗： KTS平和島店" . "\n\n";
	$body .= "■ご希望日時： " . "$order_time" . "～" . "\n\n";
	$body .= "■お名前： $name" . "\n\n";
	$body .= "■メールアドレス： $mail" . "\n\n";
	$body .= "■電話番号： $tel" . "\n\n";
	$body .= "■住所： $address" . "\n\n";
	$body .= "■車種： $car" . "\n\n";
	$body .= "■車輌型式： $model" . "\n\n";
	$body .= "■年式： $old" . "\n\n";
	$body .= "■社外アームの有無： $arm_f" . "\n\n";
	$body .= "■ホイールサイズ： $wheel_f" . "\n\n";
	$body .= "■その他：" . "\n";
	if($fender == 1){$body .= "　【フェンダーがリムにかぶる状態】" . "\n";}
	if($camber == 1){$body .= "　【キャンバー角が5度以上ついている】" . "\n";}
	if($lift1 == 1){$body .= "　【ジャッキアップポイントにLED、ネオン管等が付いている】" . "\n";}
	if($lift2 == 1){$body .= "　【ジャッキアップポイントがサイドステップ等で隠れている】" . "\n";}
	$body .= "\n";
	$body .= "■データ送信日時： $date" . "\n\n";
	$body .= "できるだけ早くご返信できるよう心がけておりますが、" . "\n";
	$body .= "連休や繁忙期等でご連絡が遅れる場合がございます。" . "\n";
	$body .= "ご迷惑をお掛け致しますが予めご了承下さい。" . "\n\n";
	$body .= "また、こちらのご予約申し込みはアライメント作業のみが対象となります。" . "\n";
	$body .= "車高調整など別作業を同時にご希望の場合はお手数ですが" . "\n";
	$body .= "お電話にて改めてお問い合わせ下さい。" . "\n\n";
	$body .= "KTS平和島店" . "\n";
	$body .= "TEL：03-5767-5832" . "\n";

	/* Return-Path */
	$mparameter = "-f alignment-heiwajima@kts-web.com";
	/* メール送信 */
	mail($to, $subject, $body, $headers, $mparameter);
?>

		<table width="700" align="center">
			<tr><td><img src=send.gif></td></td>
		</table>
		<table width="700" align="center">
			<tr><td><div align="right"><a href="alignment.php"><img src="yoyaku_jokyou.gif" border="0"></a></div></td></tr>
		</table>
<?php /* Google広告用ソース */ ?>
		<!-- Google Code for &#12450;&#12521;&#12452;&#12513;&#12531;&#12488;&#20104;&#32004;&#12467;&#12531;&#12496;&#12540;&#12472;&#12519;&#12531; Conversion Page -->
		<script type="text/javascript">
		/* <![CDATA[ */
			var google_conversion_id = 970727982;
			var google_conversion_language = "en";
			var google_conversion_format = "3";
			var google_conversion_color = "ffffff";
			var google_conversion_label = "NTpiCKu68V0QrsTwzgM";
			var google_remarketing_only = false;
		/* ]]> */
		</script>
		<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
		</script>
		<noscript>
		<div style="display:inline;">
			<img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/970727982/?label=NTpiCKu68V0QrsTwzgM&amp;guid=ON&amp;script=0"/>
		</div>
		</noscript>
	</body>
</html>
