<?php
	/* メーカー・ブランド・商品からのリスト生成 */
	/* 選択メーカー・ブランド・商品の受け取り */
	$maker = $_POST['maker'];
	$brand = $_POST['brand'];
	$item = $_POST['item'];

	/* 各種項目設定 */

		/* DBパス */
		$tire_db = "db/tire.csv";

	/* CSVよりデータ取得 */
	setlocale(LC_ALL,'ja_JP.UTF-8');
	$fp_db = fopen("$tire_db", "r");
	$db_data = array();
	$maker_data = array();
	$i = 0;
	while($ret_csv = fgetcsv($fp_db)){
		for($col = 0; $col < count($ret_csv); $col++){
			$db_data[$i][$col] = $ret_csv[$col];
		}
		$i++;
	}
	fclose($fp_db);
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
	<link rel="stylesheet" href="/assets/css/search.css" type="text/css" />
	<link rel="stylesheet" href="/assets/css/bootstrap.min.css" type="text/css" />
	<link rel="stylesheet" href="/assets/css/header_blu.css" type="text/css" />
	<link rel="stylesheet" href="/assets/css/mainbody_blu.css" type="text/css" />
	<link rel="stylesheet" href="/assets/css/footer_blu.css" type="text/css" />
	<link rel="stylesheet" href="/assets/css/slick.css" type="text/css" />
	<link rel="stylesheet" href="/assets/css/custom.css" type="text/css" />
	<script src="/assets/js/jquery-3.6.0.min.js"></script>
	<script src="/assets/js/popper.min.js"></script>
	<script src="/assets/js/bootstrap.min.js"></script>
	<script src="/assets/js/slick.min.js"></script>
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
	<title><?php print "$maker $brand $item"; ?></title>
</head>

<body>
	<section id="wrapper">
		<div class="section-container">
			<div id="content-wrapper">
				<!-- Header Start -->

				<!-- Header End -->
				<div class="main_c grey-wrapper row special">
					<!-- <div class="shipping_free"><img class="shipping_img" src="img/shipping_free_900.gif"></div> -->
					<div class="set_title"><?php print "$maker $brand $item"; ?></div>
					<div class="php_table_wrapper">
					<table class="table_php">
						<?php
	/* 読み込みデータを展開 */
	/* 先頭行の処理（タイトル行） */
	print "			<tr class=table_php_title>\n";
	print "				<td>"; print_r($db_data[0][3]); print "</td>\n";
	print "				<td>"; print_r($db_data[0][4]); print "</td>\n";
	print "				<td>"; print_r($db_data[0][5]); print "</td>\n";
	print "				<td>"; print_r($db_data[0][6]); print "</td>\n";
	print "				<td>"; print_r($db_data[0][7]); print "</td>\n";
	print "				<td>"; print_r($db_data[0][8]); print "</td>\n";
	print "				<td>"; print_r($db_data[0][9]); print "</td>\n";
	print "			</tr>\n";
	/* 適合データの処理 */
	$title_cnt = 1;
	for($cnt = 1; $cnt < $i; $cnt++){
		if($db_data[$cnt][0] == $maker && $db_data[$cnt][1] == $brand && $db_data[$cnt][2] == $item){
			if($title_cnt % 25 == 0){
				print "			<tr class=table_php_title>\n";
				print "				<td>"; print_r($db_data[0][3]); print "</td>\n";
				print "				<td>"; print_r($db_data[0][4]); print "</td>\n";
				print "				<td>"; print_r($db_data[0][5]); print "</td>\n";
				print "				<td>"; print_r($db_data[0][6]); print "</td>\n";
				print "				<td>"; print_r($db_data[0][7]); print "</td>\n";
				print "				<td>"; print_r($db_data[0][8]); print "</td>\n";
				print "				<td>"; print_r($db_data[0][9]); print "</td>\n";
				print "			</tr>\n";
			}
			$title_cnt++;
			print "			<tr>\n";
			print "				<td>"; print_r($db_data[$cnt][3]); print "</td>\n";
			print "				<td>"; print_r($db_data[$cnt][4]); print "</td>\n";
			print "				<td>"; print_r($db_data[$cnt][5]); print "</td>\n";
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
?>
					</table>
					</div>
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
					<div class="th_top_b"><a href="/tire_wheel/tire/index.php" target="_blank"><img class="pc_img" src="img/size_link_900.gif"
								alt="タイヤサイズからの検索はコチラ"></a></div>
					<div class="th_top_b"><a href="/tire_wheel/tire/index.php" target="_blank"><img class="sp_img" src="img/size_link_sp.gif"
								alt="タイヤサイズからの検索はコチラ"></a></div>
					<hr>
				</div>
				<!-- Footer Start -->
				
				</div>
			</div>
		</div>
	</section>
	</main>
	<script src="/assets/js/header_blu.js"></script>

</html>
<!-- Footer End -->