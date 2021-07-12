<?php
	/* サーバー時刻取得 */
	function get_time(){
		date_default_timezone_set('Asia/Tokyo');
		$sec = date("s");
		$min = date("i");
		$hour0 = date("H");
		$hour = date("G");
		$mday0 = date("d");
		$mday = date("j");
		$mon0 = date("m");
		$mon = date("n");
		$year = date("Y");
		$wday = date("N");
		$date = date("Y年n月j日G時i分");
		$ud = date("L");
		$mon_day = date("t");
		$rbday = date("z");
		return array ($sec, $min, $hour0, $hour, $mday0, $mday, $mon0, $mon, $year, $wday, $date, $ud, $mon_day, $rbday);
	}

	/* 曜日判定 */
	function youbi_check(){
		global $wday;
		if ($wday == 7) {$wday = 0;}
		if ($wday == 0) {$wday_c = "日";}
		if ($wday == 1) {$wday_c = "月";}
		if ($wday == 2) {$wday_c = "火";}
		if ($wday == 3) {$wday_c = "水";}
		if ($wday == 4) {$wday_c = "木";}
		if ($wday == 5) {$wday_c = "金";}
		if ($wday == 6) {$wday_c = "土";}
		return array ($wday, $wday_c);
	}
	
	/* 日数加算チェック */
	function days_check(){
		global $year, $mon, $mday, $mon_day;
		if($mday > $mon_day){$mday = 1; $mon++;}
		if($mon > 12){$mon = 1; $year++;}
		$mday0 = $mday;
		if($mday < 10){$mday0 = "0$mday";}
		$mon0 = $mon;
		if($mon < 10){$mon0 = "0$mon";}
		return array ($year, $mon0, $mon, $mday0, $mday, $mon_day);
	}
	
	/* 登録データの呼び出し */
	function filedata(){
		global $year, $mon0, $mday0, $hour0;
		$file_name = "../work_sheet_factory/log/$year$mon0$mday0.dat";
		$fp_data = fopen("$file_name", "r");
		$day_para = array();
		$day_i = 0;
		while($ret_csv = fgetcsv($fp_data)){
			for($col = 0; $col < count($ret_csv); $col++){
				$day_para[$day_i][$col] = $ret_csv[$col];
			}
			$day_i++;
		}
		fclose($fp_data);
		return array($day_para, $file_name);
	}

	/* 第3水曜日の日付確認（2週間表記まで対応） */
	function t_wed(){
		date_default_timezone_set('Asia/Tokyo');
		$t_year = date("Y");
		$t_mon = date("n");
		$timestamp = mktime(0, 0, 0, $t_mon, 1, $t_year);
		$t_w = date('w', $timestamp);
		/* 1日の曜日より第3水曜日を設定 */
		if($t_w == 0){$t_wed_day = 18;}
		if($t_w == 1){$t_wed_day = 17;}
		if($t_w == 2){$t_wed_day = 16;}
		if($t_w == 3){$t_wed_day = 15;}
		if($t_w == 4){$t_wed_day = 21;}
		if($t_w == 5){$t_wed_day = 20;}
		if($t_w == 6){$t_wed_day = 19;}
		return array($t_wed_day);
	}

	/* 日付範囲設定の初期化 */
	$days_range = 0;
	
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
	<title>KTS-web</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="robots" content="noindex,nofollow">
	<meta http-equiv="Pragma" content="no-cache">
	<meta http-equiv="cache-control" content="no-cache">
	<meta http-equiv="expires" content="0">
	<link rel="stylesheet" href="https://www.kts-web.com/web.css" type="text/css">

	<link rel="stylesheet" href="/assets/css/search.css" type="text/css" />
	<link rel="stylesheet" href="/assets/css/bootstrap.min.css"
		type="text/css" />
	<link rel="stylesheet" href="/assets/css/header_blu.css" type="text/css" />
	<link rel="stylesheet" href="/assets/css/mainbody_blu.css" type="text/css" />
	<link rel="stylesheet" href="/assets/css/footer_blu.css" type="text/css" />
	
	<link rel="stylesheet" href="/assets/css/slick.css" type="text/css" />
	<link rel="stylesheet" href="/assets/css/custom.css" type="text/css" />
	<script src="/assets/js/jquery-3.6.0.min.js"></script>
	<script src="/assets/js/popper.min.js"></script>
	<script src="/assets/js/bootstrap.min.js"></script>
	<script src="/assets/js/slick.min.js"></script>
</head>

<body>
	<main>
		<section id="wrapper">
			<div class="section-container">
				<div id="content-wrapper">
					<!-- Header Start -->

					<?php include($_SERVER['DOCUMENT_ROOT'].'/_partials/header.html'); ?>
<!-- <header class="site-header">
            <nav class="navbar navbar-expand-lg navbar-dark">
              <a class="navbar-brand" href="/"><img id="header-logo" class="logo" src="/img/opg.png" alt="Company Logo" srcset=""></a>
              <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>
              <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav ml-auto">
                  <li class="nav-item">
                    <a class="nav-link" href="/">
                      <div class="ja">トップ</div>
                      <div class="en">TOP</div>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="/shop_menu/set/index.html">
                      <div class="ja">ショップメニュー</div>
                      <div class="en">SHOP MENU</div>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="/product/index.html">
                      <div class="ja">オリジナル商品</div>
                      <div class="en">ORIGINAL PRODUCT</div>
                    </a>
                  </li>
                  <li class="nav-item"><a class="nav-link" href="/tire_wheel/index.html">
                      <div class="ja">タイヤ・ホイール</div>
                      <div class="en">TIRE / WHEEL</div>
                    </a>
                  </li>
                  <li class="nav-item"><a class="nav-link" href="/campaign/index.html">
                      <div class="ja">キャンペーン</div>
                      <div class="en">CAMPAIGN</div>
                    </a>
                  </li>
                  <li class="nav-item"><a class="nav-link" href="/houjin/index.html">
                      <div class="ja">業販</div>
                      <div class="en">WHOLESALE</div>
                    </a>
                  </li>
                  <li class="nav-item"><a class="nav-link" href="https://www.kts-web.com/blog/" target="_blank">
                      <div class="ja">ブログ</div>
                      <div class="en">BLOG</div>
                    </a></li>
                  <li class="nav-item"><a class="nav-link" href="/company.html">
                      <div class="ja">会社概要</div>
                      <div class="en">COMPANY</div>
                    </a>
                  </li>
                </ul>
              </div>
            </nav>
            <div id="top-carousel">
              <div class="header-banner-wrapper">
                <div class="row">
                  <div class="col-md-3 col-sm-6 col-xs-6"><a href="/product/index.html"><span>詳しく見る</span><img src="/assets/img/home/hks_202103_05_camp_480.png"></a></div>
                  <div class="col-md-3 col-sm-6 col-xs-6"><a href="/shop_menu/set/ch_ma/liqui_moly_dpf.html"><span>詳しく見る</span><img src="/assets/img/home/liqui_moly_dpf_480.png"></a></div>
                  <div class="col-md-3 col-sm-6 col-xs-6"><a href="/shop_menu/set/ch_ma/10674.html"><span>詳しく見る</span><img src="/assets/img/home/mahle_ozone_pro_480.png"></a></div>
                  <div class="col-md-3 col-sm-6 col-xs-6"><a href="/tire_wheel/tire/index.php"><span>詳しく見る</span><img src="/assets/img/home/tire_wheel_480.png"></a></div>
                </div>
              </div>
            </div>
          </header> -->
					<!-- Header End -->

					<div class="alignment row white-wrapper">
						<div class="clearfix"></div>
						<div class="row two_banners">
							<div class="two_banners_wrapper col-md-6 col-sm-6 col-xs-12">
								<div class="grey-wrapper">
									<div class="bq_lineup_title">オンライン決済用の空メール送信について</div>
									<div class="mail_text_n">KTS FACTORY店でのお支払いにオンライン決済をご希望するお客様専用のページとなります。</div>
									<div class="mail_text_f00">
										<div class="mail_text_cell_1">・</div>
										<div class="mail_text_cell_2">下記リンクボタンをクリックするとメールが起動しますので空メール送信をお願い致します。</div>
									</div>
									<div class="mail_text_f00">
										<div class="mail_text_cell_1">・</div>
										<div class="mail_text_cell_2">送信頂いたメールアドレス宛に案内メールを改めてお送り致しますので、<span
												class="font_ff0">「@messaging.squareup.com」</span>ドメインのメールが受信できるように指定の解除など、メールアドレスの設定確認をお願い致します。
										</div>
									</div>
									<div class="mail_text_f00">
										<div class="mail_text_cell_1">・</div>
										<div class="mail_text_cell_2">メールを送信される際、お客様確認の為、<span
												class="font_ff0">「お名前」「お電話番号」</span>をメールタイトル・件名にご入力下さい。</div>
									</div>
									<div class="mail_text_f00">
										<div class="mail_text_cell_1">・</div>
										<div class="mail_text_cell_2">本決済サービスはSquareのオンライン決済システムを使用しております。</div>
									</div>
									<div class="mail_img"><a href="mailto:factory@kts-web.com"><img src="mail.gif"></a>
									</div>
									<!-- <div class="lineup_close"><a href="#" onclick="window.close(); return false;">閉じる</a></div> -->
								</div>
							</div>
							<div class="two_banners_wrapper col-md-6 col-sm-6 col-xs-12">
								<div class="grey-wrapper">
									<!-- <div class="clearfix"></div> -->
									<div class="shop_menu_title"><img src="kawaguchi_gift_2020.gif"
											alt="当店では「元気！川口商品券」の共通券・専用券をご利用頂けます"></div>
									<!-- <div class="clearfix"></div> -->
									<div class="shop_menu_back">
										<div class="shop_menu_info_title">【元気！川口商品券のご利用について】</div>
										<div class="shop_menu_table">
											<div class="shop_menu_cell_1">・</div>
											<div class="shop_menu_cell_2">「元気！川口商品券」はKTS FACTORY店のみご利用が可能となります。</div>
										</div>
										<div class="shop_menu_table">
											<div class="shop_menu_cell_1">・</div>
											<div class="shop_menu_cell_2">KTS FACTORY店では共通券・専用券の両方をご利用頂けます。</div>
										</div>
										<div class="shop_menu_table">
											<div class="shop_menu_cell_1">・</div>
											<div class="shop_menu_cell_2">ご利用対象は商品のご購入、取付・施工時の工賃にご利用頂けます。</div>
										</div>
										<div class="shop_menu_table">
											<div class="shop_menu_cell_1">・</div>
											<div class="shop_menu_cell_2">ご利用金額を超えてご使用頂いた場合のお釣りは出ませんので予めご了承下さい。</div>
										</div>
										<div class="shop_menu_table">
											<div class="shop_menu_cell_1">・</div>
											<div class="shop_menu_cell_2">商品券のお求め、その他については<a class="link_n"
													href="https://www.kawaguchicci.or.jp/genki/index.html"
													target="_blank">川口商工会議所HP</a><span class="sp_none">（<a
														class="link_n"
														href="https://www.kawaguchicci.or.jp/genki/index.html"
														target="_blank">https://www.kawaguchicci.or.jp/genki/index.html</a>）</span>をご参照下さい。
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div>
							<table class="nostyle" width="700" border="0">
								<tr>
									<td><b>
											<font size="4">アライメント測定・調整　予約空き状況</font>
										</b></td>
								</tr>
								<tr>
									<td><b>
											<font size="3"><a href="http://www.kts-web.com/shop_menu/tenpo/factory.html"
													target="_blank">KTS FACTORY</a> TEL: 048-285-8906</font>
										</b></td>
								</tr>
							</table>
							<table class="nostyle" width="870">
								<tr>
									<td width="30"></td>
									<td><b>下記一覧表で「空き」ボタンをクリックすると該当日時のアライメント予約申し込みが可能です。</b></td>
								</tr>
								<tr>
									<td></td>
									<td>（※本日より2日後以降からの予約が可能となります。今日・明日のご予約についてはお電話にてお問い合わせ下さい。）</td>
								</tr>
								<tr>
									<td></td>
									<td>（※早朝アライメントのご予約についてはお電話にてお問い合わせ下さい。）</td>
								</tr>
							</table>
							<div><b>
									<font size="3" color="#FF0000">こちらの予約申し込みはアライメント作業のみとなります。車高変更等を含む別作業は同時にお申込み頂けません。
									</font>
								</b></div>
							<div><b>
									<font size="2">　（※同時に別作業をご希望の方はお手数ですがお電話にてお問い合わせ下さい）</font>
								</b></div>
							<table width="900" border="0" bgcolor="#66CCFF">
								<tr>
									<td>　</td>
									<td bgcolor="#0000FF">
										<font color="#FFFFFF"><b>9時</b></font>
									</td>
									<td bgcolor="#0000FF">
										<font color="#FFFFFF"><b>10時</b></font>
									</td>
									<td bgcolor="#0000FF">
										<font color="#FFFFFF"><b>11時</b></font>
									</td>
									<td bgcolor="#0000FF">
										<font color="#FFFFFF"><b>12時</b></font>
									</td>
									<td bgcolor="#0000FF">
										<font color="#FFFFFF"><b>13時</b></font>
									</td>
									<td bgcolor="#0000FF">
										<font color="#FFFFFF"><b>14時</b></font>
									</td>
									<td bgcolor="#0000FF">
										<font color="#FFFFFF"><b>15時</b></font>
									</td>
									<td bgcolor="#0000FF">
										<font color="#FFFFFF"><b>16時</b></font>
									</td>
									<td bgcolor="#0000FF">
										<font color="#FFFFFF"><b>17時</b></font>
									</td>
									<td bgcolor="#0000FF">
										<font color="#FFFFFF"><b>18時</b></font>
									</td>
								</tr>
								<?php list ($sec, $min, $hour0, $hour, $mday0, $mday, $mon0, $mon, $year, $wday, $date, $ud, $mon_day, $rbday) = get_time(); ?>
								<?php list ($t_wed_day) = t_wed(); ?>
								<?php
									for($i = $days_range; $i <= 6; $i++){
										list ($wday, $wday_c) = youbi_check();
										list ($year, $mon0, $mon, $mday0, $mday, $mon_day) = days_check();
										list ($day_para, $file_name) = filedata();
										$hizuke = $mon.'月'.$mday.'日（'.$wday_c.'）';
										if($wday == 0){print "<tr><td bgcolor=#FFFFCC><b><font color=#FF0000>$hizuke</font></b></td>\n";}
										if($wday == 6){print "<tr><td bgcolor=#FFFFCC><b><font color=#0000FF>$hizuke</font></b></td>\n";}
										if($wday != 0 and $wday != 6){print "<tr><td bgcolor=#FFFFCC><b><font color=#000000>$hizuke</font></b></td>\n";}
										$jikan = 1;
										$check_time = 0;
										while($jikan <= 10){
											$check_time_h = $check_time + 1;
											/* リフトPの初期化 */
											$lift1 = 1;
											$lift2 = 1;
											$lift3 = 1;
											/* アライメントリフトの確認 */
											if($day_para[1][$check_time] != "open" and $day_para[1][$check_time] != ""){$lift1 = 0;}
											if($day_para[1][$check_time_h] != "open" and $day_para[1][$check_time_h] != ""){$lift1 = 0;}
											/* Bリフトの確認 */
											if($day_para[7][$check_time] != "open" and $day_para[7][$check_time] != ""){$lift2 = 0;}
											if($day_para[7][$check_time_h] != "open" and $day_para[7][$check_time_h] != ""){$lift2 = 0;}
											/* Cリフトの確認 */
											if($day_para[10][$check_time] != "open" and $day_para[10][$check_time] != ""){$lift3 = 0;}
											if($day_para[10][$check_time_h] != "open" and $day_para[10][$check_time_h] != ""){$lift3 = 0;}
											/* リフト空き枠の確認 */
											$lift = $lift1 + $lift2 + $lift3;
											/* GW休業の処理2019 臨時処理 */
											/* if($mon == 5 and $mday == 3){print "<td><img src=https://www.kts-web.com/alignment_factory/jikangai.gif></td>\n";} */
											/* elseif($mon == 5 and $mday == 4){print "<td><img src=https://www.kts-web.com/alignment_factory/jikangai.gif></td>\n";} */
											/* elseif($mon == 5 and $mday == 5){print "<td><img src=https://www.kts-web.com/alignment_factory/jikangai.gif></td>\n";} */
											/* elseif($mon == 5 and $mday == 6){print "<td><img src=https://www.kts-web.com/alignment_factory/jikangai.gif></td>\n";} */
											/* 日曜限定 早朝アライメント枠処理（表示のみ） */
											if($wday == 0 and $jikan == 1 and $lift == 3){print "<td><img src=https://www.kts-web.com/alignment_factory/aki_2.gif></td>\n";}
											/* elseif($wday == 0 and $jikan == 1 and $lift == 3){print "<td><img src=https://www.kts-web.com/alignment_factory/aki_2.gif></td>\n";} */
											elseif($wday == 0 and $jikan == 1 and $lift == 2){print "<td><img src=https://www.kts-web.com/alignment_factory/aki_1.gif></td>\n";}
											elseif($wday == 0 and $jikan == 1 and $lift <= 1){print "<td><img src=https://www.kts-web.com/alignment_factory/yoyaku.gif></td>\n";}
											elseif($wday != 0 and $jikan == 1){print "<td><img src=https://www.kts-web.com/alignment_factory/jikangai.gif></td>\n";}
											/* 3月10時枠処理（4月以降削除）*/
											elseif($mon == 3 and $wday == 0 and $jikan == 2 and $lift == 3){print "<td><img src=https://www.kts-web.com/alignment_factory/aki_2.gif></td>\n";}
											elseif($mon == 3 and $wday == 0 and $jikan == 2 and $lift == 2){print "<td><img src=https://www.kts-web.com/alignment_factory/aki_1.gif></td>\n";}
											elseif($mon == 3 and $wday == 0 and $jikan == 2 and $lift <= 1){print "<td><img src=https://www.kts-web.com/alignment_factory/yoyaku.gif></td>\n";}
											elseif($mon == 3 and $wday != 0 and $jikan == 2){print "<td><img src=https://www.kts-web.com/alignment_factory/jikangai.gif></td>\n";}
											/* 4月19時枠処理（4月以降は表示領域そのものを変更予定）*/
											elseif($mon != 3 and $jikan == 11){print "<td><img src=https://www.kts-web.com/alignment_factory/jikangai.gif></td>\n";}
											/* 第3水曜日処理 */
											elseif($mday == $t_wed_day){print "<td><img src=https://www.kts-web.com/alignment_factory/jikangai.gif></td>\n";}
											/* ここから空き枠通常処理 */
											elseif($wday == 4){print "<td><img src=https://www.kts-web.com/alignment_factory/jikangai.gif></td>\n";}
											elseif($lift == 3 and $i <= 1){print "<td><img src=https://www.kts-web.com/alignment_factory/aki_3.gif></td>\n";}
											elseif($lift == 2 and $i <= 1){print "<td><img src=https://www.kts-web.com/alignment_factory/aki_2.gif></td>\n";}
											elseif($lift == 1 and $i <= 1){print "<td><img src=https://www.kts-web.com/alignment_factory/aki_1.gif></td>\n";}
											elseif($lift == 1 and $lift1 == 1 and $i <= 1){print "<td><img src=https://www.kts-web.com/alignment_factory/aki_1.gif></td>\n";}
											elseif($lift == 1 and $lift1 == 0 and $i <= 1){print "<td><img src=https://www.kts-web.com/alignment_factory/aki_1.gif></td>\n" ;}
											elseif($lift == 3 and $i > 1){print "<form method=post action=order.php><td><input type=hidden name=year value=$year><input type=hidden name=mon value=$mon><input type=hidden name=day value=$mday><input type=hidden name=time_s value=$check_time><input type=hidden name=lift value=1><input type=hidden name=change_lift value=10><input type=hidden name=rbday_check value=$rbday><input type=image src=https://www.kts-web.com/alignment_factory/aki_3.gif name=image></td></form>\n";}
											elseif($lift == 2 and $lift1 == 1 and $lift2 == 1 and $i > 1){print "<form method=post action=order.php><td><input type=hidden name=year value=$year><input type=hidden name=mon value=$mon><input type=hidden name=day value=$mday><input type=hidden name=time_s value=$check_time><input type=hidden name=lift value=1><input type=hidden name=change_lift value=7><input type=hidden name=rbday_check value=$rbday><input type=image src=https://www.kts-web.com/alignment_factory/aki_2.gif name=image></td></form>\n";}
											elseif($lift == 2 and $lift1 == 1 and $lift2 == 0 and $i > 1){print "<form method=post action=order.php><td><input type=hidden name=year value=$year><input type=hidden name=mon value=$mon><input type=hidden name=day value=$mday><input type=hidden name=time_s value=$check_time><input type=hidden name=lift value=1><input type=hidden name=change_lift value=10><input type=hidden name=rbday_check value=$rbday><input type=image src=https://www.kts-web.com/alignment_factory/aki_2.gif name=image></td></form>\n";}
											elseif($lift == 2 and $lift1 == 0 and $i > 1){print "<form method=post action=order.php><td><input type=hidden name=year value=$year><input type=hidden name=mon value=$mon><input type=hidden name=day value=$mday><input type=hidden name=time_s value=$check_time><input type=hidden name=lift value=10><input type=hidden name=change_lift value=0><input type=hidden name=rbday_check value=$rbday><input type=image src=https://www.kts-web.com/alignment_factory/aki_2.gif name=image></td></form>\n";}
											elseif($lift == 1 and $lift1 == 1 and $i > 1){print "<form method=post action=order.php><td><input type=hidden name=year value=$year><input type=hidden name=mon value=$mon><input type=hidden name=day value=$mday><input type=hidden name=time_s value=$check_time><input type=hidden name=lift value=1><input type=hidden name=change_lift value=0><input type=hidden name=rbday_check value=$rbday><input type=image src=https://www.kts-web.com/alignment_factory/aki_1.gif name=image></td></form>\n";}
											elseif($lift == 1 and $lift2 == 1 and $i > 1){print "<form method=post action=order.php><td><input type=hidden name=year value=$year><input type=hidden name=mon value=$mon><input type=hidden name=day value=$mday><input type=hidden name=time_s value=$check_time><input type=hidden name=lift value=7><input type=hidden name=change_lift value=0><input type=hidden name=rbday_check value=$rbday><input type=image src=https://www.kts-web.com/alignment_factory/aki_1.gif name=image></td></form>\n";}
											elseif($lift == 1 and $lift3 == 1 and $i > 1){print "<form method=post action=order.php><td><input type=hidden name=year value=$year><input type=hidden name=mon value=$mon><input type=hidden name=day value=$mday><input type=hidden name=time_s value=$check_time><input type=hidden name=lift value=10><input type=hidden name=change_lift value=0><input type=hidden name=rbday_check value=$rbday><input type=image src=https://www.kts-web.com/alignment_factory/aki_1.gif name=image></td></form>\n";}
											elseif($lift == 0){print "<td><img src=https://www.kts-web.com/alignment_factory/yoyaku.gif name=image></td>\n";}
											$check_time = $check_time + 2;
											$jikan++;
										}
										$mday++;
										$wday++;
										$rbday++;
									};
								?>
								</tr>
								<tr>
									<td>　</td>
									<td bgcolor="#0000FF">
										<font color="#FFFFFF"><b>9時</b></font>
									</td>
									<td bgcolor="#0000FF">
										<font color="#FFFFFF"><b>10時</b></font>
									</td>
									<td bgcolor="#0000FF">
										<font color="#FFFFFF"><b>11時</b></font>
									</td>
									<td bgcolor="#0000FF">
										<font color="#FFFFFF"><b>12時</b></font>
									</td>
									<td bgcolor="#0000FF">
										<font color="#FFFFFF"><b>13時</b></font>
									</td>
									<td bgcolor="#0000FF">
										<font color="#FFFFFF"><b>14時</b></font>
									</td>
									<td bgcolor="#0000FF">
										<font color="#FFFFFF"><b>15時</b></font>
									</td>
									<td bgcolor="#0000FF">
										<font color="#FFFFFF"><b>16時</b></font>
									</td>
									<td bgcolor="#0000FF">
										<font color="#FFFFFF"><b>17時</b></font>
									</td>
									<td bgcolor="#0000FF">
										<font color="#FFFFFF"><b>18時</b></font>
									</td>
								</tr>
								<?php
										for($i = $days_range; $i <= 6; $i++){
											list ($wday, $wday_c) = youbi_check();
											list ($year, $mon0, $mon, $mday0, $mday, $mon_day) = days_check();
											list ($day_para, $file_name) = filedata();
											$hizuke = $mon.'月'.$mday.'日（'.$wday_c.'）';
											if($wday == 0){print "<tr><td bgcolor=#FFFFCC><b><font color=#FF0000>$hizuke</font></b></td>\n";}
											if($wday == 6){print "<tr><td bgcolor=#FFFFCC><b><font color=#0000FF>$hizuke</font></b></td>\n";}
											if($wday != 0 and $wday != 6){print "<tr><td bgcolor=#FFFFCC><b><font color=#000000>$hizuke</font></b></td>\n";}
											$jikan = 1;
											$check_time = 0;
											while($jikan <= 10){
												$check_time_h = $check_time + 1;
												/* リフトPの初期化 */
												$lift1 = 1;
												$lift2 = 1;
												$lift3 = 1;
												/* アライメントリフトの確認 */
												if($day_para[1][$check_time] != "open" and $day_para[1][$check_time] != ""){$lift1 = 0;}
												if($day_para[1][$check_time_h] != "open" and $day_para[1][$check_time_h] != ""){$lift1 = 0;}
												/* Bリフトの確認 */
												if($day_para[7][$check_time] != "open" and $day_para[7][$check_time] != ""){$lift2 = 0;}
												if($day_para[7][$check_time_h] != "open" and $day_para[7][$check_time_h] != ""){$lift2 = 0;}
												/* Cリフトの確認 */
												if($day_para[10][$check_time] != "open" and $day_para[10][$check_time] != ""){$lift3 = 0;}
												if($day_para[10][$check_time_h] != "open" and $day_para[10][$check_time_h] != ""){$lift3 = 0;}
												/* リフト空き枠の確認 */
												$lift = $lift1 + $lift2 + $lift3;
												/* GW休業の処理2019 臨時処理 */
												/* if($mon == 5 and $mday == 3){print "<td><img src=https://www.kts-web.com/alignment_factory/jikangai.gif></td>\n";} */
												/* elseif($mon == 5 and $mday == 4){print "<td><img src=https://www.kts-web.com/alignment_factory/jikangai.gif></td>\n";} */
												/* elseif($mon == 5 and $mday == 5){print "<td><img src=https://www.kts-web.com/alignment_factory/jikangai.gif></td>\n";} */
												/* elseif($mon == 5 and $mday == 6){print "<td><img src=https://www.kts-web.com/alignment_factory/jikangai.gif></td>\n";} */
												/* 日曜限定 早朝アライメント枠処理（表示のみ） */
												if($wday == 0 and $jikan == 1 and $lift == 3){print "<td><img src=https://www.kts-web.com/alignment_factory/aki_2.gif></td>\n";}
												/* elseif($wday == 0 and $jikan == 1 and $lift == 3){print "<td><img src=https://www.kts-web.com/alignment_factory/aki_2.gif></td>\n";} */
												elseif($wday == 0 and $jikan == 1 and $lift == 2){print "<td><img src=https://www.kts-web.com/alignment_factory/aki_1.gif></td>\n";}
												elseif($wday == 0 and $jikan == 1 and $lift <= 1){print "<td><img src=https://www.kts-web.com/alignment_factory/yoyaku.gif></td>\n";}
												elseif($wday != 0 and $jikan == 1){print "<td><img src=https://www.kts-web.com/alignment_factory/jikangai.gif></td>\n";}
												/* 3月10時枠処理（4月以降削除）*/
												elseif($mon == 3 and $wday == 0 and $jikan == 2 and $lift == 3){print "<td><img src=https://www.kts-web.com/alignment_factory/aki_2.gif></td>\n";}
												elseif($mon == 3 and $wday == 0 and $jikan == 2 and $lift == 2){print "<td><img src=https://www.kts-web.com/alignment_factory/aki_1.gif></td>\n";}
												elseif($mon == 3 and $wday == 0 and $jikan == 2 and $lift <= 1){print "<td><img src=https://www.kts-web.com/alignment_factory/yoyaku.gif></td>\n";}
												elseif($mon == 3 and $wday != 0 and $jikan == 2){print "<td><img src=https://www.kts-web.com/alignment_factory/jikangai.gif></td>\n";}
												/* 4月19時枠処理（4月以降は表示領域そのものを変更予定）*/
												elseif($mon != 3 and $jikan == 11){print "<td><img src=https://www.kts-web.com/alignment_factory/jikangai.gif></td>\n";}
												/* 第3水曜日処理 */
												elseif($mday == $t_wed_day){print "<td><img src=https://www.kts-web.com/alignment_factory/jikangai.gif></td>\n";}
												/* ここから空き枠通常処理 */
												elseif($wday == 4){print "<td><img src=https://www.kts-web.com/alignment_factory/jikangai.gif></td>\n";}
												elseif($lift == 3){print "<form method=post action=order.php><td><input type=hidden name=year value=$year><input type=hidden name=mon value=$mon><input type=hidden name=day value=$mday><input type=hidden name=time_s value=$check_time><input type=hidden name=lift value=1><input type=hidden name=change_lift value=10><input type=hidden name=rbday_check value=$rbday><input type=image src=https://www.kts-web.com/alignment_factory/aki_3.gif name=image></td></form>\n";}
												elseif($lift == 2 and $lift1 == 1 and $lift2 == 1){print "<form method=post action=order.php><td><input type=hidden name=year value=$year><input type=hidden name=mon value=$mon><input type=hidden name=day value=$mday><input type=hidden name=time_s value=$check_time><input type=hidden name=lift value=1><input type=hidden name=change_lift value=7><input type=hidden name=rbday_check value=$rbday><input type=image src=https://www.kts-web.com/alignment_factory/aki_2.gif name=image></td></form>\n";}
												elseif($lift == 2 and $lift1 == 1 and $lift2 == 0){print "<form method=post action=order.php><td><input type=hidden name=year value=$year><input type=hidden name=mon value=$mon><input type=hidden name=day value=$mday><input type=hidden name=time_s value=$check_time><input type=hidden name=lift value=1><input type=hidden name=change_lift value=10><input type=hidden name=rbday_check value=$rbday><input type=image src=https://www.kts-web.com/alignment_factory/aki_2.gif name=image></td></form>\n";}
												elseif($lift == 2 and $lift1 == 0){print "<form method=post action=order.php><td><input type=hidden name=year value=$year><input type=hidden name=mon value=$mon><input type=hidden name=day value=$mday><input type=hidden name=time_s value=$check_time><input type=hidden name=lift value=10><input type=hidden name=change_lift value=0><input type=hidden name=rbday_check value=$rbday><input type=image src=https://www.kts-web.com/alignment_factory/aki_2.gif name=image></td></form>\n";}
												elseif($lift == 1 and $lift1 == 1){print "<form method=post action=order.php><td><input type=hidden name=year value=$year><input type=hidden name=mon value=$mon><input type=hidden name=day value=$mday><input type=hidden name=time_s value=$check_time><input type=hidden name=lift value=1><input type=hidden name=change_lift value=0><input type=hidden name=rbday_check value=$rbday><input type=image src=https://www.kts-web.com/alignment_factory/aki_1.gif name=image></td></form>\n";}
												elseif($lift == 1 and $lift2 == 1){print "<form method=post action=order.php><td><input type=hidden name=year value=$year><input type=hidden name=mon value=$mon><input type=hidden name=day value=$mday><input type=hidden name=time_s value=$check_time><input type=hidden name=lift value=7><input type=hidden name=change_lift value=0><input type=hidden name=rbday_check value=$rbday><input type=image src=https://www.kts-web.com/alignment_factory/aki_1.gif name=image></td></form>\n";}
												elseif($lift == 1 and $lift3 == 1){print "<form method=post action=order.php><td><input type=hidden name=year value=$year><input type=hidden name=mon value=$mon><input type=hidden name=day value=$mday><input type=hidden name=time_s value=$check_time><input type=hidden name=lift value=10><input type=hidden name=change_lift value=0><input type=hidden name=rbday_check value=$rbday><input type=image src=https://www.kts-web.com/alignment_factory/aki_1.gif name=image></td></form>\n";}
												elseif($lift == 0){print "<td><img src=https://www.kts-web.com/alignment_factory/yoyaku.gif name=image></td>\n";}
												$check_time = $check_time + 2;
												$jikan++;
											}
											$mday++;
											$wday++;
											$rbday++;
										};
									?>
								</tr>
								<tr>
									<td>　</td>
									<td bgcolor="#0000FF">
										<font color="#FFFFFF"><b>9時</b></font>
									</td>
									<td bgcolor="#0000FF">
										<font color="#FFFFFF"><b>10時</b></font>
									</td>
									<td bgcolor="#0000FF">
										<font color="#FFFFFF"><b>11時</b></font>
									</td>
									<td bgcolor="#0000FF">
										<font color="#FFFFFF"><b>12時</b></font>
									</td>
									<td bgcolor="#0000FF">
										<font color="#FFFFFF"><b>13時</b></font>
									</td>
									<td bgcolor="#0000FF">
										<font color="#FFFFFF"><b>14時</b></font>
									</td>
									<td bgcolor="#0000FF">
										<font color="#FFFFFF"><b>15時</b></font>
									</td>
									<td bgcolor="#0000FF">
										<font color="#FFFFFF"><b>16時</b></font>
									</td>
									<td bgcolor="#0000FF">
										<font color="#FFFFFF"><b>17時</b></font>
									</td>
									<td bgcolor="#0000FF">
										<font color="#FFFFFF"><b>18時</b></font>
									</td>
								</tr>
								<?php
										for($i = $days_range; $i <= 6; $i++){
											list ($wday, $wday_c) = youbi_check();
											list ($year, $mon0, $mon, $mday0, $mday, $mon_day) = days_check();
											list ($day_para, $file_name) = filedata();
											$hizuke = $mon.'月'.$mday.'日（'.$wday_c.'）';
											if($wday == 0){print "<tr><td bgcolor=#FFFFCC><b><font color=#FF0000>$hizuke</font></b></td>\n";}
											if($wday == 6){print "<tr><td bgcolor=#FFFFCC><b><font color=#0000FF>$hizuke</font></b></td>\n";}
											if($wday != 0 and $wday != 6){print "<tr><td bgcolor=#FFFFCC><b><font color=#000000>$hizuke</font></b></td>\n";}
											$jikan = 1;
											$check_time = 0;
											while($jikan <= 10){
												$check_time_h = $check_time + 1;
												/* リフトPの初期化 */
												$lift1 = 1;
												$lift2 = 1;
												$lift3 = 1;
												/* アライメントリフトの確認 */
												if($day_para[1][$check_time] != "open" and $day_para[1][$check_time] != ""){$lift1 = 0;}
												if($day_para[1][$check_time_h] != "open" and $day_para[1][$check_time_h] != ""){$lift1 = 0;}
												/* Bリフトの確認 */
												if($day_para[7][$check_time] != "open" and $day_para[7][$check_time] != ""){$lift2 = 0;}
												if($day_para[7][$check_time_h] != "open" and $day_para[7][$check_time_h] != ""){$lift2 = 0;}
												/* Cリフトの確認 */
												if($day_para[10][$check_time] != "open" and $day_para[10][$check_time] != ""){$lift3 = 0;}
												if($day_para[10][$check_time_h] != "open" and $day_para[10][$check_time_h] != ""){$lift3 = 0;}
												/* リフト空き枠の確認 */
												$lift = $lift1 + $lift2 + $lift3;
												/* GW休業の処理2019 臨時処理 */
												/* if($mon == 5 and $mday == 3){print "<td><img src=https://www.kts-web.com/alignment_factory/jikangai.gif></td>\n";} */
												/* elseif($mon == 5 and $mday == 4){print "<td><img src=https://www.kts-web.com/alignment_factory/jikangai.gif></td>\n";} */
												/* elseif($mon == 5 and $mday == 5){print "<td><img src=https://www.kts-web.com/alignment_factory/jikangai.gif></td>\n";} */
												/* elseif($mon == 5 and $mday == 6){print "<td><img src=https://www.kts-web.com/alignment_factory/jikangai.gif></td>\n";} */
												/* 日曜限定 早朝アライメント枠処理（表示のみ） */
												if($wday == 0 and $jikan == 1 and $lift == 3){print "<td><img src=https://www.kts-web.com/alignment_factory/aki_2.gif></td>\n";}
												/* elseif($wday == 0 and $jikan == 1 and $lift == 3){print "<td><img src=https://www.kts-web.com/alignment_factory/aki_2.gif></td>\n";} */
												elseif($wday == 0 and $jikan == 1 and $lift == 2){print "<td><img src=https://www.kts-web.com/alignment_factory/aki_1.gif></td>\n";}
												elseif($wday == 0 and $jikan == 1 and $lift <= 1){print "<td><img src=https://www.kts-web.com/alignment_factory/yoyaku.gif></td>\n";}
												elseif($wday != 0 and $jikan == 1){print "<td><img src=https://www.kts-web.com/alignment_factory/jikangai.gif></td>\n";}
												/* 3月10時枠処理（4月以降削除）*/
												elseif($mon == 3 and $wday == 0 and $jikan == 2 and $lift == 3){print "<td><img src=https://www.kts-web.com/alignment_factory/aki_2.gif></td>\n";}
												elseif($mon == 3 and $wday == 0 and $jikan == 2 and $lift == 2){print "<td><img src=https://www.kts-web.com/alignment_factory/aki_1.gif></td>\n";}
												elseif($mon == 3 and $wday == 0 and $jikan == 2 and $lift <= 1){print "<td><img src=https://www.kts-web.com/alignment_factory/yoyaku.gif></td>\n";}
												elseif($mon == 3 and $wday != 0 and $jikan == 2){print "<td><img src=https://www.kts-web.com/alignment_factory/jikangai.gif></td>\n";}
												/* 4月19時枠処理（4月以降は表示領域そのものを変更予定）*/
												elseif($mon != 3 and $jikan == 11){print "<td><img src=https://www.kts-web.com/alignment_factory/jikangai.gif></td>\n";}
												/* 第3水曜日処理 */
												elseif($mday == $t_wed_day){print "<td><img src=https://www.kts-web.com/alignment_factory/jikangai.gif></td>\n";}
												/* ここから空き枠通常処理 */
												elseif($wday == 4){print "<td><img src=https://www.kts-web.com/alignment_factory/jikangai.gif></td>\n";}
												elseif($lift == 3){print "<form method=post action=order.php><td><input type=hidden name=year value=$year><input type=hidden name=mon value=$mon><input type=hidden name=day value=$mday><input type=hidden name=time_s value=$check_time><input type=hidden name=lift value=1><input type=hidden name=change_lift value=10><input type=hidden name=rbday_check value=$rbday><input type=image src=https://www.kts-web.com/alignment_factory/aki_3.gif name=image></td></form>\n";}
												elseif($lift == 2 and $lift1 == 1 and $lift2 == 1){print "<form method=post action=order.php><td><input type=hidden name=year value=$year><input type=hidden name=mon value=$mon><input type=hidden name=day value=$mday><input type=hidden name=time_s value=$check_time><input type=hidden name=lift value=1><input type=hidden name=change_lift value=7><input type=hidden name=rbday_check value=$rbday><input type=image src=https://www.kts-web.com/alignment_factory/aki_2.gif name=image></td></form>\n";}
												elseif($lift == 2 and $lift1 == 1 and $lift2 == 0){print "<form method=post action=order.php><td><input type=hidden name=year value=$year><input type=hidden name=mon value=$mon><input type=hidden name=day value=$mday><input type=hidden name=time_s value=$check_time><input type=hidden name=lift value=1><input type=hidden name=change_lift value=10><input type=hidden name=rbday_check value=$rbday><input type=image src=https://www.kts-web.com/alignment_factory/aki_2.gif name=image></td></form>\n";}
												elseif($lift == 2 and $lift1 == 0){print "<form method=post action=order.php><td><input type=hidden name=year value=$year><input type=hidden name=mon value=$mon><input type=hidden name=day value=$mday><input type=hidden name=time_s value=$check_time><input type=hidden name=lift value=10><input type=hidden name=change_lift value=0><input type=hidden name=rbday_check value=$rbday><input type=image src=https://www.kts-web.com/alignment_factory/aki_2.gif name=image></td></form>\n";}
												elseif($lift == 1 and $lift1 == 1){print "<form method=post action=order.php><td><input type=hidden name=year value=$year><input type=hidden name=mon value=$mon><input type=hidden name=day value=$mday><input type=hidden name=time_s value=$check_time><input type=hidden name=lift value=1><input type=hidden name=change_lift value=0><input type=hidden name=rbday_check value=$rbday><input type=image src=https://www.kts-web.com/alignment_factory/aki_1.gif name=image></td></form>\n";}
												elseif($lift == 1 and $lift2 == 1){print "<form method=post action=order.php><td><input type=hidden name=year value=$year><input type=hidden name=mon value=$mon><input type=hidden name=day value=$mday><input type=hidden name=time_s value=$check_time><input type=hidden name=lift value=7><input type=hidden name=change_lift value=0><input type=hidden name=rbday_check value=$rbday><input type=image src=https://www.kts-web.com/alignment_factory/aki_1.gif name=image></td></form>\n";}
												elseif($lift == 1 and $lift3 == 1){print "<form method=post action=order.php><td><input type=hidden name=year value=$year><input type=hidden name=mon value=$mon><input type=hidden name=day value=$mday><input type=hidden name=time_s value=$check_time><input type=hidden name=lift value=10><input type=hidden name=change_lift value=0><input type=hidden name=rbday_check value=$rbday><input type=image src=https://www.kts-web.com/alignment_factory/aki_1.gif name=image></td></form>\n";}
												elseif($lift == 0){print "<td><img src=https://www.kts-web.com/alignment_factory/yoyaku.gif name=image></td>\n";}
												$check_time = $check_time + 2;
												$jikan++;
											}
											$mday++;
											$wday++;
											$rbday++;
										};
									?>
								</tr>
							</table>
							<table width="870">
								<tr>
									<td valign="top" width="30">
										<div align=right>
											<font color="#FF0000">※</font>
										</div>
									</td>
									<td>
										<font color="#FF0000">
											作業空き状況は随時更新しておりますが、更新タイミングによっては画面表示が「空き」でも実際には作業予約が入っている場合がございます。作業ご希望のお客様は必ず店舗までお電話にてご確認頂けますようお願い致します。
										</font>
									</td>
								</tr>
								<tr>
									<td valign="top">
										<div align="right">
											<font color="#FF0000">※</font>
										</div>
									</td>
									<td>
										<font color="#FF0000">作業枠が空いていれば当日作業も可能です。まずはお電話にてご確認下さい。</font>
									</td>
								</tr>
								<td valign="top">
									<div align="right">
										<font color="#FF0000">※</font>
									</div>
								</td>
								<td>
									<font color="#FF0000">
										こちらの空き状況はアライメントリフトの空き状況となります。「予約済み」になっていても別リフトが空いていれば他の作業は可能です。作業可能かどうかについてはお電話にてお問い合わせ下さい。
									</font>
								</td>
								<tr>
							</table>
						</div>
					</div>
					<?php include($_SERVER['DOCUMENT_ROOT'].'/_partials/footer.html'); ?> <!-- <footer>

            <div class="footer-title">
              <div id="footer-title" class="arrowed-title">
                <h1 class="ja">店舗情報</h1><img src="/assets/img/blue_arrow.svg" alt="Blue Arrow" srcset="">
              </div>
            </div>
            <div class="clearfix sp"></div>
            <div class="clearfix"></div>
            <br>
            <div class="footer-places row">
              <div class="footer-showbox row col-md-4 col-sm-12">
                <div class="footer-showbox-img">
                  <img src="/assets/img/factory.png" alt="factory" srcset="">
                </div>
                <div class="footer-factory-info">
                  <div class="footer-showbox-title">
                    <span class="showbox-title">FACTORY</span>
                  </div>
                  <table class="showbox-content">
                    <tr id="footer-showbox-address" class="footer-showbox-text">
                      <td colspan="2">〒334-0013 埼玉県川口市南鳩ヶ谷1-25-3</td>
                    </tr>
                    <tr id="footer-showbox-opentime" class="footer-showbox-text">
                      <td class="showbox-left">営業時間</td>
                      <td class="showbox-right">10:00 - 19:00</td>
                    </tr>
                    <tr id="footer-showbox-holiday" class="footer-showbox-text">
                      <td class="showbox-left">定休日</td>
                      <td class="showbox-right">第3水曜日・毎週木曜日</td>
                    </tr>
                    <tr class="tel">
                      <td class="showbox-left">TEL</td>
                      <td class="showbox-right">048-285-8906</td>
                    </tr>
                    <tr class="fax">
                      <td class="showbox-left">FAX</td>
                      <td class="showbox-right">048-285-8939</td>
                    </tr>
                  </table>
                </div>
                <div class="showbox-bottom">
                  <a class="arrowed-link" href="/shop_menu/tenpo/factory.html"><span>詳しく見る</span><img
                      src="/assets/img/black_arrow.svg" alt="Header Link"></a>
                </div>
              </div>
              <div class="footer-showbox row col-md-4 col-sm-12">
                <div class="footer-showbox-img">
                  <img src="/assets/img/heiwajima.png" alt="factory" srcset="">
                </div>
                <div class="footer-factory-info">
                  <div class="footer-showbox-title">
                    <span class="showbox-title">HEIWAJIMA</span>
                  </div>
                  <table class="showbox-content">
                    <tr id="footer-showbox-address" class="footer-showbox-text">
                      <td colspan="2">〒143-0016 東京都大田区大森北5ー10ー13</td>
                    </tr>
                    <tr id="footer-showbox-opentime" class="footer-showbox-text">
                      <td class="showbox-left">営業時間</td>
                      <td class="showbox-right">10:00 - 19:00</td>
                    </tr>
                    <tr id="footer-showbox-holiday" class="footer-showbox-text">
                      <td class="showbox-left">定休日</td>
                      <td class="showbox-right">第3水曜日・毎週木曜日</td>
                    </tr>
                    <tr class="tel">
                      <td class="showbox-left">TEL</td>
                      <td class="showbox-right">03-5767-5832</td>
                    </tr>
                    <tr class="fax">
                      <td class="showbox-left">FAX</td>
                      <td class="showbox-right">03-5767-5808</td>
                    </tr>
                  </table>
                </div>
                <div class="showbox-bottom">
                  <a class="arrowed-link" href="/shop_menu/tenpo/heiwajima.html"><span>詳しく見る</span><img
                      src="/assets/img/black_arrow.svg" alt="Header Link"></a>
                </div>
              </div>
              <div class="footer-showbox row col-md-4 col-sm-12">
                <div class="footer-showbox-img">
                  <img src="/assets/img/ichinoe.png" alt="factory" srcset="">
                </div>
                <div class="footer-factory-info">
                  <div class="footer-showbox-title">
                    <span class="showbox-title">ICHINOE</span>
                  </div>
                  <table class="showbox-content">
                    <tr id="footer-showbox-address" class="footer-showbox-text">
                      <td colspan="2">〒143-0016 東京都大田区大森北5-10-13</td>
                    </tr>
                    <tr id="footer-showbox-opentime" class="footer-showbox-text">
                      <td class="showbox-left">営業時間</td>
                      <td class="showbox-right">10:00 - 19:00</td>
                    </tr>
                    <tr id="footer-showbox-holiday" class="footer-showbox-text">
                      <td class="showbox-left">定休日</td>
                      <td class="showbox-right">第3水曜日・毎週木曜日</td>
                    </tr>
                    <tr class="tel">
                      <td class="showbox-left">TEL</td>
                      <td class="showbox-right">03-3674-2006</td>
                    </tr>
                    <tr class="fax">
                      <td class="showbox-left">FAX</td>
                      <td class="showbox-right">03-3674-2008</td>
                    </tr>
                  </table>
                </div>
                <div class="showbox-bottom">
                  <a class="arrowed-link" href="/shop_menu/tenpo/ichinoe.html"><span class="ja">詳しく見る</span><img
                      src="/assets/img/black_arrow.svg" alt="Header Link"></a>
                </div>
              </div>
            </div>
            <div class="footer-bottom row">
              <div class="col-md-4 col-sm-4 col-xs-4">
                <div class="col-md-6 col-sm-1 col-xs-1"></div>
                <div id="footer-logo-wrap" class="col-md-6 col-sm-11 col-xs-11">
                  <img id="footer-logo" class="logo" src="/assets/img/opg.png" alt="Company Logo" srcset="">
                </div>
              </div>
              <div class="footer-text col-md-8 col-sm-8 col-xs-8">
                <div class="company-title">K T S — — —</div>
                <div class="additional-info">フッターです</div>
                <div class="additional-info">フッターです</div>
                <div class="additional-info">フッターです</div>
              </div>
              <div class="copyright row"><span>&copy; — — — — — —</span></div>
            </div>
          </footer> -->
				</div>
			</div>
		</section>
	</main>
	<script src="/assets/js/header_blu.js"></script>

</html>