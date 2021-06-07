<?php
	/* 入力データの受け取り */
	$faq_email = $_POST['email'];
	$faq_confirm_email = $_POST['confirm_email'];
	$faq_name = $_POST['name'];
	$faq_title = $_POST['title'];
	$faq_comment = $_POST['comment'];

	/* 問合せ時刻（サーバー時刻）の入手 */
	date_default_timezone_set('Asia/Tokyo');
	$date = date("Y年n月j日G時i分s秒");

	/* ユーザー環境の確認 */
	$u_agent = $_SERVER['HTTP_USER_AGENT'];
	$u_referer = $_SERVER['HTTP_REFERER'];

	/* 問合せデータログの保存 */
	$csv_r = "$date,$faq_name,$faq_email,$faq_title,$faq_comment,$u_agent,$u_referer\n";
	$fp_log = fopen("img2/log.dat", "a");
	fwrite($fp_log, "$csv_r");
	fclose($fp_log);

	/* 店舗宛メールのフラグ処理 */
	$shop_add_mail = 0;
	if($faq_title == "KTS FACTORY店について"){$shop_add_mail = 1;}
	if($faq_title == "KTS一之江店について"){$shop_add_mail = 2;}
	if($faq_title == "KTS平和島店について"){$shop_add_mail = 3;}

	/* セキュリティ対策（XSS対策） */
	$faq_email = htmlspecialchars($faq_email);
	$faq_name = htmlspecialchars($faq_name);
	$faq_title = htmlspecialchars($faq_title);
	$faq_comment = htmlspecialchars($faq_comment);

	/* メール送信部分 */
	/* 文字コード定義 */
	mb_language("Japanese");
	mb_internal_encoding("UTF-8");
	
	/* 件名 */
	$subject = mb_encode_mimeheader("【KTS】お問合せありがとうございます");
	$subject2 = mb_encode_mimeheader("【KTS】お問合せが届いています");

	/* 宛先 */
	$to = $faq_email;
	$to2 = "info@kts-web.com,k.hanaki@kts-web.com";
	if($shop_add_mail == 1){$to3 = "factory@kts-web.com,imai@kts-web.com";}
	if($shop_add_mail == 2){$to3 = "ichinoe@kts-web.com,imai@kts-web.com";}
	if($shop_add_mail == 3){$to3 = "heiwajima@kts-web.com,imai@kts-web.com";}
	
	/* headers */
	$headers = "From: info@kts-web.com\n";
	$headers .= "Content-Type: text/plain; charset=UTF-8\n";

	$headers2 = "From: $faq_email" . "\n";
	$headers2 .= "Content-Type: text/plain; charset=UTF-8\n";

	/* メール本文 */
	$body = "$faq_name 様" . "\n\n";
	$body .= "お問合せありがとうございます。" . "\n\n";
	$body .= "下記ご入力内容にてお問合せを承りました。" . "\n";
	$body .= "確認後、弊社スタッフより折り返しメールにてご連絡致します。" . "\n\n";
	$body .= "※本メールは自動返信メールとなります。" . "\n\n";
	$body .= "■お名前： $faq_name" . "\n\n";
	$body .= "■メールアドレス： $faq_email" . "\n\n";
	$body .= "■お問合せジャンル： $faq_title" . "\n\n";
	$body .= "■お問合せ内容：" . "\n";
	$body .= "$faq_comment" . "\n\n";
	$body .= "■お問合せ日時： $date" . "\n\n";
	$body .= "できるだけ早くご返信できるよう心がけておりますが、" . "\n";
	$body .= "連休や繁忙期等でご連絡が遅れる場合がございます。" . "\n";
	$body .= "ご迷惑をお掛け致しますが予めご了承下さい。" . "\n\n";
	$body .= "******************************************************" . "\n";
	$body .= "　オイル交換からエンジン換装まで" . "\n";
	$body .= "　　　　　　　　　　　　チューニングショップ　KTS" . "\n";
	$body .= "　FACTORY：048-285-8906　一之江店：03-3674-2006" . "\n";
	$body .= "　平和島店：03-5767-5832　通販部：050-3532-5017" . "\n";
	$body .= "　HP　http://www.kts-web.com/" . "\n";
	$body .= "******************************************************" . "\n";

	$body2 = "下記内容でお問合せが届いています。". "\n\n";
	$body2 .= "■お名前： $faq_name" . "\n\n";
	$body2 .= "■メールアドレス： $faq_email" . "\n\n";
	$body2 .= "■お問合せジャンル： $faq_title" . "\n\n";
	$body2 .= "■お問合せ内容：" . "\n";
	$body2 .= "$faq_comment" . "\n\n";
	$body2 .= "■お問合せ日時： $date" . "\n\n";
	if($shop_add_mail != 0){$body3 = $body2;}
	$body2 .= "[USER AGENT] $u_agent" . "\n\n";
	$body2 .= "[USER REFERER] $u_referer" . "\n";

	/* メール送信 */
	mail($to, $subject, $body, $headers);
	mail($to2, $subject2, $body2, $headers2);
	if($shop_add_mail != 0){mail($to3, $subject2, $body3, $headers2);}
?>
/* ここからHTML部分 */
<html>
	<head>
		<title>KTS-web</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="robots" content="index,follow">
		<meta name="keywords" content="KTS">
		<meta http-equiv="refresh" content="0; url=https://www.kts-web.com/faq/thanks.html" />
		<link rel="stylesheet" href="web.css" type="text/css">
	</head>
	<body>
	</body>
</html>
