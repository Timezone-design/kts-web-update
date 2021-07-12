<?php
	/* タイヤサイズからのリスト生成 */
	/* インチ(i)・扁平率(per)・タイヤ幅(mm)の受け取り */
	$size_i = $_POST['size_i'];
	$size_per = $_POST['size_per'];
	$size_mm = $_POST['size_mm'];

	/* 受け取りデータのチェック */
	$size_check = 0;
	if($size_i != ""){$size_check = 1;}
	if($size_per != ""){$size_check = 2;}
	if($size_mm != ""){$size_check = 3;}

	/* 各種項目設定 */

		/* DBパス */
		$tire_db = "../../db/item-tire.csv";

	/* CSVよりデータ取得 */
	setlocale(LC_ALL,'ja_JP.UTF-8');
	$fp_db = fopen("$tire_db", "r");
	$db_data = array();
	$data_i = array();
	$data_per = array();
	$data_mm = array();
	$i = 0;
	while($ret_csv = fgetcsv($fp_db)){
		for($col = 0; $col < count($ret_csv); $col++){
			$db_data[$i][$col] = $ret_csv[$col];
		}
		/* インチサイズの取得 */
		if($i != 0){array_push($data_i, $db_data[$i][3]);}
		/* 扁平率の取得（フィルタ：インチ） */
		if($i != 0 && $size_check == 1 && $db_data[$i][3] == $size_i){array_push($data_per, $db_data[$i][4]);}
		/* タイヤ幅の取得（フィルタ：インチ/扁平率） */
		if($i != 0 && $size_check == 2 && $db_data[$i][3] == $size_i && $db_data[$i][4] == $size_per){array_push($data_mm, $db_data[$i][5]);}
		$i++;
	}
	fclose($fp_db);

	/* インチサイズの重複削除および降順ソート */
	$data_i = array_unique($data_i);
	$data_i = array_values($data_i);
	rsort($data_i);
	/* 扁平率の重複削除および降順ソート */
	if($size_check == 1){
		$data_per = array_unique($data_per);
		$data_per = array_values($data_per);
		rsort($data_per);
	}
	/* タイヤ幅の重複削除および昇順ソート */
	if($size_check == 2){
		$data_mm = array_unique($data_mm);
		$data_mm = array_values($data_mm);
		sort($data_mm);
	}
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
	<meta name="keywords" content="">
	<meta name="description" content="">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, user-scalable=yes, initial-scale=1, maximum-scale=1">
	<meta name="viewport" content="width=device-width">
	<script src="/assets/js/jquery-3.6.0.min.js"></script>
	<script type="text/javascript">
		jQuery(function ($) {
			$('tr[data-href]').addClass('clickable')
				.click(function (e) {
					if (!$(e.target).is('a')) {
						let href = $(e.target).closest('tr').data('href');
						window.open(href, "_blank");
					};
				});
		});
	</script>
	<title>KTSタイヤ・ホイール館　タイヤサイズ検索</title>
	<link rel="stylesheet" href="/assets/css/search.css" type="text/css" />
	<link rel="stylesheet" href="/assets/css/bootstrap.min.css" type="text/css" />
	<link rel="stylesheet" href="/assets/css/header_blu.css" type="text/css" />
	<link rel="stylesheet" href="/assets/css/mainbody_blu.css" type="text/css" />
	<link rel="stylesheet" href="/assets/css/footer_blu.css" type="text/css" />
	<link rel="stylesheet" href="/assets/css/slick.css" type="text/css" />
	<link rel="stylesheet" href="/assets/css/custom.css" type="text/css" />
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
							<a class="navbar-brand" href="/"><img id="header-logo" class="logo" src="/img/opg.png" alt="Company Logo"
									srcset=""></a>
							<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText"
								aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
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
									<li class="nav-item"><a class="nav-link" href="/company/index.html">
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
									<div class="col-md-3 col-sm-6 col-xs-6"><a href="/product/index.html"><span>詳しく見る</span><img
												src="/assets/img/home/hks_202103_05_camp_480.png"></a></div>
									<div class="col-md-3 col-sm-6 col-xs-6"><a
											href="/shop_menu/set/ch_ma/liqui_moly_dpf.html"><span>詳しく見る</span><img
												src="/assets/img/home/liqui_moly_dpf_480.png"></a></div>
									<div class="col-md-3 col-sm-6 col-xs-6"><a
											href="/shop_menu/set/ch_ma/10674.html"><span>詳しく見る</span><img
												src="/assets/img/home/mahle_ozone_pro_480.png"></a></div>
									<div class="col-md-3 col-sm-6 col-xs-6"><a href="/tire_wheel/tire/index.php"><span>詳しく見る</span><img
												src="/assets/img/home/tire_wheel_480.png"></a></div>
								</div>
							</div>
						</div>
					</header> -->
					<!-- Header End -->
					<div class="main_c row grey-wrapper special">
							<div class="set_title">KTSタイヤ・ホイール館　タイヤサイズ検索</div>
							<?php
								/* データ確認用 */
								/* print "<div>サイズチェック： $size_check</div>\n"; */
								/* print "<div>インチサイズ： $size_i</div>\n"; */
								/* print_r($data_i); */

								/* 初回アクセスまたはデータ無し */
								if($size_check == 0){
									print "		<div class=size_text>ご希望のインチサイズを選択して下さい。</div>\n";
									print "		<div class=set_maker>\n";
									print "			<form method=post action=size.php>\n";
									print "				<select class='custom-select-lg' name=size_i onchange=this.form.submit()>\n";
									print "					<option value=non_selected>◆インチサイズをご選択下さい◆</option>\n";
									for($inch_i = 0; $inch_i < count($data_i); $inch_i++){
										print "					<option value=\"";
										print_r($data_i[$inch_i]);
										print "\">";
										print_r($data_i[$inch_i]);
										print "インチ</option>\n";
									}
									print "				</select>\n";
									print "			</form>\n";
									print "		</div>\n";
								}

								/* インチサイズ選択済み */
								if($size_check == 1){
									print "		<div class=size_text>ご希望の扁平率を選択して下さい。</div>\n";
									print "		<div class=size_text>選択中のインチサイズ： $size_i インチ</div>\n";
									print "		<div class=set_maker>\n";
									print "			<form method=post action=size.php>\n";
									print "				<input type=hidden name=size_i value=$size_i>\n";
									print "				<select class='custom-select-lg' name=size_per onchange=this.form.submit()>\n";
									print "					<option value=non_selected>◆扁平率をご選択下さい◆</option>\n";
									for($per_i = 0; $per_i < count($data_per); $per_i++){
										print "					<option value=\"";
										print_r($data_per[$per_i]);
										print "\">";
										print_r($data_per[$per_i]);
										print "</option>\n";
									}
									print "				</select>\n";
									print "			</form>\n";
									print "		</div>\n";
								}

								/* インチサイズ・扁平率選択済み */
								if($size_check == 2){
									print "		<div class=size_text>ご希望のタイヤ幅を選択して下さい。</div>\n";
									print "		<div class=size_text>選択中のインチサイズ： $size_i インチ　扁平率： $size_per</div>\n";
									print "		<div class=set_maker>\n";
									print "			<form method=post action=size.php>\n";
									print "				<input type=hidden name=size_i value=$size_i>\n";
									print "				<input type=hidden name=size_per value=$size_per>\n";
									print "				<select class='custom-select-lg' name=size_mm onchange=this.form.submit()>\n";
									print "					<option value=non_selected>◆タイヤ幅をご選択下さい◆</option>\n";
									for($mm_i = 0; $mm_i < count($data_mm); $mm_i++){
										print "					<option value=\"";
										print_r($data_mm[$mm_i]);
										print "\">";
										print_r($data_mm[$mm_i]);
										print "</option>\n";
									}
									print "				</select>\n";
									print "			</form>\n";
									print "		</div>\n";
								}

								/* 全サイズ入力時・検索一覧表示 */
								if($size_check == 3){
									print "		<div class=shipping_free><img class=shipping_img src=img/shipping_free_900.gif></div>\n";
									print "		<div class=size_text>選択中のタイヤサイズ： $size_i / $size_per / $size_mm</div>\n";
									print "<div class='php_table_wrapper'>\n";
									print "		<table class=table_php>\n";
									/* 読み込みデータを展開 */
									/* 先頭行の処理（タイトル行） */
									print "			<tr class=table_php_title>\n";
									print "				<td>"; print_r($db_data[0][0]); print "</td>\n";
									print "				<td>"; print_r($db_data[0][1]); print "</td>\n";
									print "				<td>"; print_r($db_data[0][2]); print "</td>\n";
									print "				<td>"; print_r($db_data[0][6]); print "</td>\n";
									print "				<td>"; print_r($db_data[0][7]); print "</td>\n";
									print "				<td>"; print_r($db_data[0][8]); print "</td>\n";
									print "				<td>"; print_r($db_data[0][9]); print "</td>\n";
									print "			</tr>\n";
									/* 適合データの処理 */
									$title_cnt = 1;
									for($cnt = 1; $cnt < $i; $cnt++){
										if($db_data[$cnt][3] == $size_i && $db_data[$cnt][4] == $size_per && $db_data[$cnt][5] == $size_mm){
											if($title_cnt % 25 == 0){
												print "			<tr class=table_php_title>\n";
												print "				<td>"; print_r($db_data[0][0]); print "</td>\n";
												print "				<td>"; print_r($db_data[0][1]); print "</td>\n";
												print "				<td>"; print_r($db_data[0][2]); print "</td>\n";
												print "				<td>"; print_r($db_data[0][6]); print "</td>\n";
												print "				<td>"; print_r($db_data[0][7]); print "</td>\n";
												print "				<td>"; print_r($db_data[0][8]); print "</td>\n";
												print "				<td>"; print_r($db_data[0][9]); print "</td>\n";
												print "			</tr>\n";
											}
											$title_cnt++;
											print "			<tr>\n";
											print "				<td>"; print_r($db_data[$cnt][0]); print "</td>\n";
											print "				<td>"; print_r($db_data[$cnt][1]); print "</td>\n";
											print "				<td>"; print_r($db_data[$cnt][2]); print "</td>\n";
											/* 販売価格に3桁毎のカンマを追加処理 */
											$price1 = number_format($db_data[$cnt][6]);
											$price2 = number_format($db_data[$cnt][7]);
											print "				<td class=link_ec>￥$price1</td>\n";
											print "				<td class=link_ec>￥$price2</td>\n";
											print "				<td>"; print_r($db_data[$cnt][8]); print "</td>\n";
											print "				<td>"; print_r($db_data[$cnt][9]); print "</td>\n";
											print "			</tr>\n";
										}
									}
									print "		</table>\n";
									print "</div>\n";
								}
							?>

									<?php
									if($size_check == 3){
										print "		<div class=size_text>別サイズを検索する場合はインチサイズを選択して下さい。</div>\n";
										print "		<div class=set_maker>\n";
										print "			<form method=post action=size.php>\n";
										print "				<select class='custom-select-lg' name=size_i onchange=this.form.submit()>\n";
										print "					<option value=non_selected>◆再検索はコチラから◆</option>\n";
										for($inch_i = 0; $inch_i < count($data_i); $inch_i++){
											print "					<option value=\"";
											print_r($data_i[$inch_i]);
											print "\">";
											print_r($data_i[$inch_i]);
											print "インチ</option>\n";
										}
										print "				</select>\n";
										print "			</form>\n";
										print "		</div>\n";
									}
								?>
							<div class="footer_text_t">
								<div class="footer_text_c1">※：</div>
								<div class="footer_text_c2">本ページ掲載価格はすべて税抜価格となります。</div>
							</div>
							<div class="footer_text_t">
								<div class="footer_text_c1">※：</div>
								<div class="footer_text_c2">本ページ掲載商品の送料は無料となります。（沖縄・離島を除く）</div>
							</div>
							<div class="footer_text_t">
								<div class="footer_text_c1">※：</div>
								<div class="footer_text_c2">「XL」はエクストラロード（荷重能力強化タイプ）のタイヤです。</div>
							</div>
							<div class="footer_text_t">
								<div class="footer_text_c1">※：</div>
								<div class="footer_text_c2">掲載に無い商品・サイズについてはお問い合わせ下さい。</div>
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
</body>

</html>