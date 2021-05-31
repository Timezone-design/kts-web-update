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
		$tire_db = "db/tire_cv.csv";

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
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, user-scalable=yes, initial-scale=1, maximum-scale=1">
		<meta name="viewport" content="width=device-width">
		<link rel="stylesheet" href="../../list_php.css" type="text/css" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
		<script type="text/javascript">
			jQuery(function($) {
				$('tr[data-href]').addClass('clickable')
					.click(function(e) {
						if(!$(e.target).is('a')){
							let href=$(e.target).closest('tr').data('href');
							window.open(href, "_blank");
						};
					});
			});
		</script>
		<title>KTSタイヤ・ホイール館　タイヤサイズ検索</title>
	</head>
	<body>
		<div class="set_title">KTSタイヤ・ホイール館　タイヤサイズ検索（商用車）</div>
<?php
	/* データ確認用 */
	/* print "<div>サイズチェック： $size_check</div>\n"; */
	/* print "<div>インチサイズ： $size_i</div>\n"; */
	/* print_r($data_i); */

	/* 初回アクセスまたはデータ無し */
	if($size_check == 0){
		print "		<div class=size_text>ご希望のインチサイズを選択して下さい。</div>\n";
		print "		<div class=set_maker>\n";
		print "			<form method=post action=size_cv.php>\n";
		print "				<select name=size_i onchange=this.form.submit()>\n";
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
		print "		<div class=size_text>ご希望の扁平率・タイヤ外径を選択して下さい。</div>\n";
		print "		<div class=size_text>選択中のインチサイズ： $size_i インチ</div>\n";
		print "		<div class=set_maker>\n";
		print "			<form method=post action=size_cv.php>\n";
		print "				<input type=hidden name=size_i value=$size_i>\n";
		print "				<select name=size_per onchange=this.form.submit()>\n";
		print "					<option value=non_selected>◆扁平率・タイヤ外径をご選択下さい◆</option>\n";
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
		print "		<div class=size_text>選択中のインチサイズ： $size_i インチ　扁平率・タイヤ外径： $size_per</div>\n";
		print "		<div class=set_maker>\n";
		print "			<form method=post action=size_cv.php>\n";
		print "				<input type=hidden name=size_i value=$size_i>\n";
		print "				<input type=hidden name=size_per value=$size_per>\n";
		print "				<select name=size_mm onchange=this.form.submit()>\n";
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
		print "		<div class=shipping_free><img class=shipping_img src=../img/shipping_free_900.gif></div>\n";
		print "		<div class=size_text>選択中のタイヤサイズ： $size_i / $size_per / $size_mm</div>\n";
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
<?php
	if($size_check == 3){
		print "		<div class=size_text>別サイズを検索する場合はインチサイズを選択して下さい。</div>\n";
		print "		<div class=set_maker>\n";
		print "			<form method=post action=size_cv.php>\n";
		print "				<select name=size_i onchange=this.form.submit()>\n";
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
		<div class="link_close"><a href="#" onClick="window.close(); return false;">閉じる</a></div>
		<hr>
		<div class="footer">Copyright (C) Kind Techno Structure Corporation. All Rights Reserved.</div>
	</body>
</html>
