<?php
	/* 選択メーカーの受け取り */
	$maker = $_POST['maker'];
	$select_car_name = $_POST['select_car_name'];

	/* 各種項目設定 */

		/* ファイル名 */
		$file_name = "stealth_basic.php";

		/* 商品名 */
		$set_name = "FINAL KONNEXION STEALTH Basic";

		/* DBパス */
		$set_db = "db/stealth_basic.csv";

		/* Campスイッチ 0：通常　1：バナー1　2：バナー2 */
		$camp_b_switch = 0;

		/* 前後価格表記switch 0：通常　1：前後表記 */
		$ba_price = 0;

		/* 車種選択Form 0：OFF　1：ON */
		$car_name_switch = 1;

	/* 車輌メーカーデータの取得 */
	setlocale(LC_ALL,'ja_JP.UTF-8');
	$fp_maker = fopen("../../shop_menu/set/maker.csv", "r");
	$maker_data = array();
	$maker_i = 0;
	while($maker_csv = fgetcsv($fp_maker)){
		for($col = 0; $col < count($maker_csv); $col++){
			$maker_data[$maker_i][$col] = $maker_csv[$col];
		}
		$maker_i++;
	}
	fclose($fp_maker);

	/* CSVよりデータ取得 */
	setlocale(LC_ALL,'ja_JP.UTF-8');
	$fp_db = fopen("$set_db", "r");
	$db_data = array();
	$car_name = array();
	$i = 0;
	while($ret_csv = fgetcsv($fp_db)){
		for($col = 0; $col < count($ret_csv); $col++){
			$db_data[$i][$col] = $ret_csv[$col];
			/* 適合車種DB内のメーカー情報と照合 */
			for($m_col = 0; $m_col < $maker_i; $m_col++){
				if($db_data[$i][0] == $maker_data[$m_col][1]){$maker_data[$m_col][0] = 1;}
			}
		}
		/* 車種データの取得 */
		if($db_data[$i][0] == $maker){array_push($car_name, $db_data[$i][1]);}
		$i++;
	}
	fclose($fp_db);

	/* 該当メーカー数の確認とアイコン表示の補足設定 */
	$icon_i = 0;
	for($m_col = 0; $m_col < $maker_i; $m_col++){
		if($maker_data[$m_col][0] == 1){$icon_i++;}
	}
	$icon_pc = $icon_i % 10;
	$icon_pc_comp = 10 - $icon_pc;
	$icon_sp = $icon_i % 5;
	$icon_sp_comp = 5 - $icon_sp;

	/* 車種データの重複削除 */
	$car_name = array_unique($car_name);
	$car_name = array_values($car_name);

	/* 車種データ無し又はnone_select時の処理 */
	if($select_car_name == "" or $select_car_name == "none_select"){$car_name_status = 0;}
	else{$car_name_status = 1;}
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
		<meta name="viewport" content="width=device-wideth, user-scalable=yes, initial-scale=1, maximum-scale=1">
		<meta name="viewport" content="width=device-width">
		<link rel="stylesheet" href="../sale_php.css" type="text/css" />
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
		<title><?php print "$set_name"; ?></title>
	</head>
	<body>
		<div class="set_title"><?php print "$set_name"; ?></div>
<?php
	/* キャンペーンバナー */
	if($camp_b_switch == 1){print "			<div class=camp_b><img src=../img/camp_b_1.gif></div>\n";}
	if($camp_b_switch == 2){print "			<div class=camp_b><img src=../img/camp_b_2.gif></div>\n";}
	/* メーカーアイコン処理（PC） */
	$icon_pc_cnt = 1;
	for($icon = 0; $icon < $maker_i; $icon++){
		$icon_pc_cnt_h = $icon_pc_cnt % 10;
		if($maker_data[$icon][0] == 1){
			if($icon_pc_cnt_h == 1){print "		<div class=icon_pc_table>\n";}
			print "			<div class=icon_pc_cell>\n";
			print "				<form method=post action=$file_name>\n";
			print "					<input type=hidden name=maker value=";
			print_r($maker_data[$icon][1]);
			print ">\n					<input type=image src=../img/maker/";
			print_r($maker_data[$icon][2]);
			print ".gif name=image>\n				</form>\n			</div>\n";
			if($icon_pc_cnt_h == 0){print "			</div>\n";}
			$icon_pc_cnt++;
		}
	}
	$icon_pc_end = $icon_pc_comp - 1;
	if($icon_pc != 0){
		for($icon = 0; $icon < $icon_pc_comp; $icon++){
			print "			<div class=icon_pc_cell></div>\n";
			if($icon == $icon_pc_end){print "		</div>\n";}
		}
	}
	/* メーカーアイコン処理（SP） */
	$icon_sp_cnt = 1;
	for($icon = 0; $icon < $maker_i; $icon++){
		$icon_sp_cnt_h = $icon_sp_cnt % 5;
		if($maker_data[$icon][0] == 1){
			if($icon_sp_cnt_h == 1){print "		<div class=icon_sp_table>\n";}
			print "			<div class=icon_sp_cell>\n";
			print "				<form method=post action=$file_name>\n";
			print "					<input type=hidden name=maker value=";
			print_r($maker_data[$icon][1]);
			print ">\n					<input class=sp_button type=image src=../img/maker/";
			print_r($maker_data[$icon][2]);
			print ".gif name=image>\n				</form>\n			</div>\n";
			if($icon_sp_cnt_h == 0){print "		</div>\n";}
			$icon_sp_cnt++;
		}
	}
	$icon_sp_end = $icon_sp_comp - 1;
	if($icon_sp != 0){
		for($icon = 0; $icon < $icon_sp_comp; $icon++){
			print "			<div class=icon_sp_cell></div>\n";
			if($icon == $icon_sp_end){print "		</div>\n";}
		}
	}
?>
		<div class="set_maker">設定車種： <?php print "$maker";?></div>
<?php
	/* 車種選択がされている場合 */
	if($car_name_status == 1){print "		<div class=set_maker>選択車種： $select_car_name</div>\n";}
	/* 車種選択Form */
	if($car_name_switch == 1){
		print "		<div class=set_maker>\n";
		print "			<form method=post action=$file_name>\n";
		print "				<input type=hidden name=maker value=$maker>\n";
		print "				<select name=select_car_name onchange=this.form.submit()>\n";
		print "					<option value=none_select selected>◆車種名をご選択下さい◆</option>\n";
		for($car_i = 0; $car_i < count($car_name); $car_i++){
			print "					<option value=\"";
			print_r($car_name[$car_i]);
			print "\">";
			print_r($car_name[$car_i]);
			print "</option>\n";
		}
		print "				</select>\n";
		print "			</form>\n";
		print "		</div>\n";
	}
?>
		<table class="table_php">
<?php
	/* 読み込みデータを展開 */
	/* 先頭行の処理（タイトル行） */
	print "			<tr class=table_php_title>\n";
	print "				<td>"; print_r($db_data[0][1]); print "</td>\n";
	print "				<td>"; print_r($db_data[0][2]); print "</td>\n";
	print "				<td>"; print_r($db_data[0][3]); print "</td>\n";
	print "				<td>"; print_r($db_data[0][4]); print "</td>\n";
	print "				<td>"; print_r($db_data[0][5]); print "</td>\n";
	print "				<td>"; print_r($db_data[0][6]); print "（税抜）</td>\n";
	print "			</tr>\n";
	/* 適合データの処理 */
	for($cnt = 1; $cnt < $i; $cnt++){
		if($car_name_status == 1 and $select_car_name != $db_data[$cnt][1]){continue;}
		if($db_data[$cnt][0] == $maker){
			print "			<tr data-href=https://www.kts-web.com/ec_shop/products/detail/";
			print_r($db_data[$cnt][8]);
			print ">\n";
			print "				<td>"; print_r($db_data[$cnt][1]); print "</td>\n";
			print "				<td>"; print_r($db_data[$cnt][2]); print "</td>\n";
			print "				<td>"; print_r($db_data[$cnt][3]); print "</td>\n";
			print "				<td>"; print_r($db_data[$cnt][4]); print "</td>\n";
			print "				<td>"; print_r($db_data[$cnt][5]); print "</td>\n";
			/* 販売価格に3桁毎のカンマを追加処理 */
			$price1 = number_format($db_data[$cnt][6]);
			$price2 = number_format($db_data[$cnt][7]);
			if($ba_price == 0){print "				<td class=link_ec>￥$price1</td>\n";}
			if($ba_price == 1){
				print "				<td>\n";
				print "					<div class=price1>￥$price1</div>\n";
				print "					<div class=link_ec>￥$price2</div>\n				</td>\n";
			}
			print "			</tr>\n";
		}
	}
?>
		</table>
		<div class="footer_text_t">
			<div class="footer_text_c1">※：</div>
			<div class="footer_text_c2">販売価格の金額をクリックすると弊社直販サイトの購入ページへリンク致します。</div>
		</div>
		<div class="footer_text_t">
			<div class="footer_text_c1">※：</div>
			<div class="footer_text_c2">本ページ掲載価格は商品単体価格となり、取付工賃等は含まれておりません。取付ご希望の場合は別途お問い合わせ下さい。</div>
		</div>
		<div class="footer_text_t">
			<div class="footer_text_c1">※：</div>
			<div class="footer_text_c2">掲載に無い商品・車種についてはお問い合わせ下さい。</div>
		</div>
		<div class="link_close"><a href="#" onClick="window.close(); return false;">閉じる</a></div>
		<hr>
		<div class="footer">Copyright (C) Kind Techno Structure Corporation. All Rights Reserved.</div>
	</body>
</html>
