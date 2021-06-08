<?php
	/* 入力データの受け取り */
	$faq_email = $_POST['email'];
	$faq_confirm_email = $_POST['confirm_email'];
	$faq_comm = $_POST['配信停止理由'];

	/* 問合せ時刻（サーバー時刻）の入手 */
	date_default_timezone_set('Asia/Tokyo');
	$date = date("Y年n月j日G時i分s秒");

	/* ユーザー環境の確認 */
	$u_agent = $_SERVER['HTTP_USER_AGENT'];
	$u_referer = $_SERVER['HTTP_REFERER'];

	/* 問合せデータログの保存 */
	$csv_r = "$date,$faq_email,$faq_comm,$u_agent,$u_referer\n";
	$fp_log = fopen("img2/log_c.dat", "a");
	fwrite($fp_log, "$csv_r");
	fclose($fp_log);

	/* セキュリティ対策（XSS対策） */
	$faq_email = htmlspecialchars($faq_email);

	/* メール送信部分 */
	/* 文字コード定義 */
	mb_language("Japanese");
	mb_internal_encoding("UTF-8");
	
	/* 件名 */
	$subject = mb_encode_mimeheader("KTSメールマガジン 解除申請を承りました");
	
	/* 宛先 */
	$to = $faq_email;
	
	/* headers */
	$headers = "From: magazine@kts-web.com\n";
	$headers .= "BCC: magazine@kts-web.com\n";
	$headers .= "Content-Type: text/plain; charset=UTF-8\n";

	/* メール本文 */
	$body = "KTSメールマガジンの解除申請を承りました。" . "\n";
	$body .= "次回配信分よりDBへ反映させて頂きます。" . "\n\n";
	$body .= "※ご送信頂いた時期によっては一度だけ配信されてしまう場合がございます。" . "\n";
	$body .= "　また、転送設定等を行っている場合は登録メールアドレスと一致しないため、" . "\n";
	$body .= "　再び配信される場合がございます。予めご了承下さい。" . "\n\n";
	$body .= "■ご送信内容の確認" . "\n";
	$body .= "　$date" . "\n";
	$body .= "　・メールアドレス： $faq_email" . "\n";
	$body .= "　・配信停止理由： $faq_comm" . "\n\n\n";
	$body .= "このメールに心当たりの無い場合は、お手数ですが" . "\n";
	$body .= "本メールにご返信頂くか、下記連絡先までお問合せください。" . "\n\n";
	$body .= "******************************************************" . "\n";
	$body .= "　オイル交換からエンジン換装まで" . "\n";
	$body .= "　　　　　　　　　　　　チューニングショップ　KTS" . "\n";
	$body .= "　FACTORY：048-285-8906　一之江店：03-3674-2006" . "\n";
	$body .= "　平和島店：03-5767-5832　通販部：050-3818-2161" . "\n";
	$body .= "　HP　http://www.kts-web.com/" . "\n";
	$body .= "******************************************************" . "\n";

	/* メール送信 */
	mail($to, $subject, $body, $headers);
?>
/* ここからHTML部分 */
<html>
	<head>
		<title>KTS-web</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="robots" content="index,follow">
		<meta name="keywords" content="KTS">
		<meta http-equiv="refresh" content="0; url=https://www.kts-web.com/magazine/thanks.html" />
	</head>
	<body>
	</body>
</html>
