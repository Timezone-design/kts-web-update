<?php
	/* 判別フラグ初期化 */
	$check_h = 0;

	/* 各データ受け取り */
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
	$day_file = "../work_sheet_factory/log/$year$mon0$day0.dat";

	/* サーバー時刻取得 */
	date_default_timezone_set('Asia/Tokyo');
	$rbday = date("z");

	/* 取得データより開始時間への変換 */
	$jikan = $time_s * 0.5 + 9;

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
	$sheet_check = 0;
	$time_e = $time_s + 2;
	for($time_c = $time_s; $time_c < $time_e; $time_c++){
		if($day_para[$lift][$time_c] != "open" and $day_para[$lift][$time_c] != ""){$sheet_check = 1;}
	}


	/* 放置対策 */
	$time_check = $rbday_check - $rbday;
	if($time_check < 2){$sheet_check = 2;}
	
	/* 動作確認用 */
	/* $check_h = 2; */
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
<?php if($sheet_check == 1){exit();} ?>

<?php if($sheet_check == 2):?>
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
<?php if($sheet_check == 2){exit();} ?>

<?php if($sheet_check == 0):?>
		<table width="700" border="0">
			<tr>
				<td><b><font size=4 color="#000000">アライメント測定・調整　予約空き状況</font></b></td>
			</tr>
			<tr>
				<td><b><font size=3 color="#000000"><a href="http://www.kts-web.com/shop_menu/tenpo/factory.html" target="_blank">KTS FACTORY</a> TEL: 048-285-8906</font></b></td>
			</tr>
		</table>
		<table width="700">
			<form method="post" action="check.php">
			<tr>
				<td><font size="5"><b><font color="#FF0000">予約希望日時： <?php print "$year"; ?>年<?php print "$mon"; ?>月<?php print "$day"; ?>日（<?php print "$wday"; ?>）<?php print "$jikan"; ?>時～</font></b></font></td>
			</tr>
			<tr>
				<td><hr width="100%" align="left" size="1"></td>
			</tr>
			<tr>
				<td>
					<font size="4"><b>本サービスをご利用頂くには下記利用規約にご同意頂く必要がございます。</b></font>
					<table width="100%">
						<tr>
							<td valign="top"><div align="right"><b>・</b></td>
							<td><font color="FF0000"><b>ホイールのリムにフェンダーが被っている車輌、キャンバー角が過度に付き過ぎている車輌については作業が行えない可能\性がございます。</b></font></td>
						</tr>
						<tr>
							<td valign="top"><div align="right"><b>・</b></td>
							<td><b>アライメント以外の作業について同時予約はお受けしておりません。同時作業をご希望の場合はお手数ですがお電話にてお問い合わせ下さい。</b></td>
						</tr>
						<tr>
							<td valign="top" width="30"><div align="right"><b>・</b></td>
							<td><b>入力データを送信した時点では予約受付は完了致しません。<br>弊社店舗より予約完了メールをお送りした時点での予約受付となりますのでご注意下さい。</b></td>
						</tr>
						<tr>
							<td valign="top"><div align="right"><b>・</b></td>
							<td><b>KTS FACTORY店では国産車のみの対応となり、輸入車については作業をお断りさせて頂いております。<br>輸入車の作業をご希望の方はお手数ですがKTS平和島店または一之江店まで改めてお問い合わせ下さい。</b></td>
						</tr>
						<tr>
							<td valign="top"><div align="right"><b>・</b></td>
							<td><b>アライメントをはじめ、各種作業のご依頼はお電話や店頭でも承っております。<br>タイミングによってはご希望の日時にてご予約頂けない場合がございます。予めご了承下さい。</b></td>
						</tr>
						<tr>
							<td valign="top"><div align="right"><b>・</b></td>
							<td><b>お車の状態によっては通常のアライメント作業料金に加え、追加料金が発生する場合がございます。予めご了承下さい。</b><br>【主な例】<br>　・ストラット車（主にスバル車など）のキャンバーボルトによる調整作業が必要な場合（￥3,000の追加料金）<br>　・エアロパーツやディフューザー等の脱着作業が必要な場合（1箇所につき￥2,000の追加料金）</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td><hr width="100%" align="left" size="1"></td>
			</tr>
			<tr>
				<td>
					　　　　　<input type="checkbox" name="kiyaku" value="1">利用規約に同意する
					<table width="98%" align="center">
						<tr><td width="130"><div align="right"><b>お名前：</b></div></td><td><input type="text" name="name" size="50"></td></tr>
						<tr><td><div align="right"><b>メールアドレス：</b></div></td><td><input type="text" name="mail" size="50">※半角英数</td></tr>
						<tr><td><div align="right"><b>　</b></div></td><td><font color="#FF0000">携帯アドレスをご利用される場合は「alignment-factory@kts-web.com」から受信可能か確認して下さい。</font></td></tr>
						<tr><td><div align="right"><b>電話番号：</b></div></td><td><input type="text" name="tel" size="50">※半角・ハイフンで区切って下さい</td></tr>
						<tr><td><div align="right"><b>住所：</b></div></td><td><input type="text" name="address" size="50"></td></tr>
						<tr><td><div align="right"><b>車種：</b></div></td><td><input type="text" name="car" size="50"></td></tr>
						<tr><td><div align="right"><b>車輌型式：</b></div></td><td><input type="text" name="model" size="50"></td></tr>
						<tr><td><div align="right"><b>年式：</b></div></td><td><input type="text" name="old" size="50"></td></tr>
						<tr><td></td><td><input type="checkbox" name="wagon_check" value="1"><b><font color="#FF0000">※ワゴン・ミニバン・SUV車輌に該当する場合はチェックして下さい</td></tr>
						<tr><td></td></tr>
						<tr><td><div align="right"><b>社外アームの有無：</b></div></td><td><input type="radio" name="arm" value="1">あり　<input type="radio" name="arm" value="0">無し</td></tr>
						<tr><td></td></tr>
						<tr>
							<td valign="top"><div align="right"><b>装着ホイールサイズ：</b></div></td>
							<td><input type="radio" name="wheel" value="13">13インチ　<input type="radio" name="wheel" value="14">14インチ　<input type="radio" name="wheel" value="15">15インチ　<input type="radio" name="wheel" value="16">16インチ　<input type="radio" name="wheel" value="17">17インチ　<input type="radio" name="wheel" value="18">18インチ　<input type="radio" name="wheel" value="19">19インチ<br><input type="radio" name="wheel" value="20">20インチ　<input type="radio" name="wheel" value="21">21インチ　<input type="radio" name="wheel" value="22">22インチ以上</td>
						</tr>
						<tr><td></td></tr>
						<tr><td valign="top"><div align="right"><b>その他：</b></div></td><td><b>当てはまる項目にチェックを入れて下さい（複数選択可）</b><br><font color="#FF0000">※下記項目に該当する場合、作業が行えない可能性がございますので予めご了承下さい。</font><br><input type="checkbox" name="rimguard" value="1">社外リムガードが装着されている<br><input type="checkbox" name="fender" value="1">フェンダーがリムにかぶる状態<br><input type="checkbox" name="camber" value="1">キャンバー角が5度以上ついている<br><input type="checkbox" name="lift1" value="1">ジャッキアップポイントにLED、ネオン管等が付いている<br><input type="checkbox" name="lift2" value="1">ジャッキアップポイントがサイドステップ等で隠れている</td></tr>
					</table>
				</td>
			</tr>
			
			<input type="hidden" name="year" value=<?php print "$year"; ?>>
			<input type="hidden" name="mon" value=<?php print "$mon"; ?>>
			<input type="hidden" name="day" value=<?php print "$day"; ?>>
			<input type="hidden" name="time_s" value=<?php print "$time_s"; ?>>
			<input type="hidden" name="rbday_check" value=<?php print "$rbday_check"; ?>>
			<input type="hidden" name="lift" value="<?php print "$lift"; ?>">
			<input type="hidden" name="change_lift" value="<?php print "$change_lift"; ?>">
			
			<tr>
				<td>
					<table cellspacing="0" cellpadding="0" align="center">
						<tr><td><input type="submit" value="確認"></td></tr>
					</table>
				</td>
			</tr>
			</form>
		</table>
<?php endif;?>

		<div><b><font size="3" color="#FF0000">こちらの予約申し込みはアライメント作業のみとなります。車高変更等を含む別作業は同時にお申込み頂けません。</font></b></div>
		<div><b><font size="2">　（※同時に別作業をご希望の方はお手数ですがお電話にてお問い合わせ下さい）</font></b></div>

		<table width="700">
			<tr>
				<td>
					<div align="right"><a href="alignment.php"><img src="yoyaku_jokyou.gif" border="0"></a></div>
				</td>
			</tr>
		</table>
	</body>
</html>
