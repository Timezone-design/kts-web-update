<?php
	/* 各データの受け取り */
	$year = $_POST['year'];
	$mon = $_POST['mon'];
	$day = $_POST['day'];
	$sheet_file = $_POST['sheet_file'];
	$print_id = $_POST['print_id'];

	/* 該当データの呼び出し */
	setlocale(LC_ALL,'ja_JP.UTF-8');
	$fp_data = fopen("log/$sheet_file", "r");
	$sheet_para = array();
	$i = 0;
	while($ret_csv = fgetcsv($fp_data)){
		for($col = 0; $col < count($ret_csv); $col++){
			$sheet_para[$i][$col] = $ret_csv[$col];
		}
		$i++;
	}

	/* お客様情報を変数へ代入 */
	$name = $sheet_para[0][1];
	$name_f = $sheet_para[0][2];
	$tel = $sheet_para[0][3];
	$p_code = $sheet_para[0][4];
	$address = $sheet_para[0][5];
	$mail = $sheet_para[0][6];
	$car = $sheet_para[0][7];
	$model = $sheet_para[0][8];
	$engine = $sheet_para[0][9];
	$car_old = $sheet_para[0][10];
	$drive = $sheet_para[0][11];
	
	$arm = $sheet_para[0][13];
	$wheel = $sheet_para[0][14];
	$fender = $sheet_para[0][15];
	$camber = $sheet_para[0][16];
	$lift1 = $sheet_para[0][17];
	$lift2 = $sheet_para[0][18];

	/* 仮予約・WEBアライメント時の空白処理 */
	if($name == "仮予約"){$name = "";}
	if($name_f == "カリヨヤク" or $name_f == "アライメント"){$name_f = "";}
	if($tel == "仮予約"){$tel = "";}
	if($p_code == "仮予約" or $p_code == "郵便番号"){$p_code = "";}
	if($address == "仮予約"){$address = "";}
	if($mail == "仮予約"){$mail = "";}
	if($car == "仮予約"){$car = "　　　　　　　　　　　　　　";}
	if($model == "仮予約"){$model = "　";}
	if($engine == "仮予約" or $engine == "アライメント予約"){$engine = "";}
	if($car_old =="仮予約"){$car_old = "　　　　　　　　　　";}
	if($drive == "仮予約" or $drive == "アライメント予約"){$drive = "";}

	/* 印刷用パラメーターの書き出し */
	/* 日付 */
	$heisei = $year - 1988; /* 和暦 */
//	$mon
//	$day
//	$wday
	/* 個人情報 */
//	$name
//	$name_f
//	$tel
//	$p_code
//	$address
//	$mail
	/* 車輌情報 */
//	$car
//	$car_old
//	$model
	/* 備考欄掲載用 */
	$car_other = "";
	if($engine != ""){$car_other .= "【" . "$engine" . "】";}
	if($drive != ""){$car_other .= "【" . "$drive" . "】";}
	if($arm == 0 and $arm != ""){$car_other .= "【アーム無】";}
	elseif($arm == 1){$car_other .= "【アーム有】";}
	if($wheel == 22){$car_other .= "【22インチ以上】";}
	elseif($wheel != 22 and $wheel != ""){$car_other .= "【" . "$wheel" . "インチ】";}
	if($fender == 1){$car_other .= "【リムかぶり】";}
	if($camber == 1){$car_other .= "【キャンバー5度以上】";}
	if($lift1 == 1){$car_other .= "【LED、ネオン管】";}
	if($lift2 == 1){$car_other .= "【サイドステップ】";}

	/* 配列内のお客様情報を削除 */
	unset($sheet_para[0][0],$sheet_para[0][1],$sheet_para[0][2],$sheet_para[0][3],$sheet_para[0][4],$sheet_para[0][5],$sheet_para[0][6],$sheet_para[0][7],$sheet_para[0][8],$sheet_para[0][9],$sheet_para[0][10],$sheet_para[0][11],$sheet_para[0][12]);

	/* 配列内をソート */
	foreach($sheet_para as $key => $value){
		$key_id[$key] = $value[1];
	}
	array_multisort($key_id , SORT_NUMERIC , $sheet_para);

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

	/* 最終更新日時DATファイル呼び出し */
	$fp_ymd = fopen("ymd.dat","r");
	$ymd = fgets($fp_ymd);
	fclose($fp_ymd);

	/* 白紙作業書印刷時 */
	if($print_id != "input"){
		/* $heisei = "　　　　"; */
		$year = "　　　　　　　　";
		$mon = "　　　　";
		$day = "　　　　";
		$wday = "　　　";
		$car = "　　　　　　　　　　　　　　";
		$car_old ="　　　　　　　　　　";
		$model = "　";
		$car_other = "　";
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
		<title>作業予約管理システム</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="Pragma" content="no-cache">
		<meta http-equiv="cache-control" content="no-cache">
		<meta http-equiv="expires" content="0">
		<link rel="stylesheet" href="https://www.kts-web.com/work_sheet_ichinoe/print.css" type="text/css">
	</head>
	<body bgcolor="#FFFFFF" text="#000000">
		<table class="print_title">
			<tr>
				<td width="17%"><div class="print_none"><input type="button" name="print" value="ページ内を印刷" onClick="javascript:window.print()"></div></td>
				<td width="17%">
					<div class="print_none">
					<?php
						if($print_id == "input"){
							print "<form method=post action=work_sheet.php>";
							print "<input type=hidden name=year value=$year>";
							print "<input type=hidden name=mon value=$mon>";
							print "<input type=hidden name=day value=$day>";
							print "<input type=hidden name=sheet_file value=$sheet_file>";
							print "<input type=submit value=前の画面に戻る>";
							print "</form>";
						}
						else{print "<div class=font_14><a href=index.php>月間表示画面へ戻る</a></div>";}
					?>
					</div>
				</td>
				<td class="print_text_center" width="33%">作業書</td>
				<td class="print_text_right" width="33%">KTS平和島店</td>
			</tr>
		</table>
		<table class="print_om_mein">
			<tr>
				<td colspan="4" width="67%"><div class="font_24">作業日： <?php print "$year"; ?>年 <?php print "$mon"; ?>月 <?php print "$day"; ?>日（<?php print "$wday"; ?>）</div></td>
				<td colspan="2" width="33%"><div class="print_text_center">客注　/　店舗　/　持込</div></td>
			</tr>
			<tr>
				<td colspan="4" hight="20"><span class="font_20_b">預り日：</span> 　　　　　　　　年　　　　月　　　　日（　　　）</td>
				<td colspan="2"><span class="font_20_b">納車予定：</span> 　　　　月　　　　日（　　　）</td>
			</tr>
			<tr>
				<td class="va_top" colspan="3" width="50%">
					<div class="font_b">お名前</div>
					<table class="box_right" width="100%">
						<tr>
							<td width="20%" style="border:none;" class="print_text_right">フリガナ：</td>
							<td style="border:none;"><?php print "$name_f"; ?></td>
							<td rowspan="2" style="border:none;" class="print_text_right_sama">様</td>
						</tr>
						<tr>
							<td style="border:none;" class="print_text_right">氏名：</td>
							<td style="border:none;" class="font_20"><?php print "$name"; ?></td>
						</tr>
					</table>
				</td>
				<td class="va_top" colspan="3" width="50%">
					<div class="font_b">住所</div>
					<table class="box_right">
						<tr><td style="border:none;">〒<?php print "$p_code"; ?></td></td>
						<tr><td style="border:none;" class="font_20"><?php print "$address"; ?></td></tr>
					</table>
				</td>
			</tr>
			<tr>
				<td class="va_top" colspan="2">
					<div class="font_b">携帯電話</div>
					<table class="box_right">
						<tr>
							<td style="border:none;" class="font_20"><?php print "$tel"; ?></td>
						</tr>
					</table>
				</td>
				<td class="va_top" colspan="2">
					<div class="font_b">E-mail</div>
					<table class="box_right">
						<tr>
							<td style="border:none;" class="font_20"><?php print "$mail"; ?></td>
						</tr>
					</table>
				</td>
				<td class="va_top" width="16.6%"><div class="font_b">担当</div></td>
				<td class="va_top" width="16.6%"><div class="font_b">受付</div></td>
			</tr>
			<tr>
				<td width="16.6%"><div class="font_b_center">車輌情報</div></td>
				<td colspan="5" width="83.4%">
					<div class="font_20">車種： <?php print "$car"; ?>　　　年式： <?php print "$car_old"; ?>　　　型式： <?php print "$model"; ?></div>
					<div class="font_20">備考（　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　）</div>
				</td>
			</tr>
			<tr>
				<td class="print_text_center" colspan="2" width="33%" height="30"><span class="font_b">アライメント</span>　　<span class="font_20">有　/　無</span></td>
				<td class="print_text_center" colspan="2" width="33%"><span class="font_b">代金</span>　　<span class="font_20">〔　済　・　未　〕</span></td>
				<td class="print_text_center" colspan="2" width="33%"><spna class="font_b">未納</span>　<spna class="font_20">￥　　　　　　　　　　</span></td>
			</tr>
			<tr>
				<td colspan="6">
					<div class="font_b">◆作業内容◆</div>
					<table class="box_right">
					<?php
						if($print_id == "input"){
							$roop_i = $i - 1;
							for($roop = 1; $roop <= $roop_i; $roop++){
								if($sheet_para[$roop][0] == "del"){continue;}
								print "<table class=box_right width=50%><tr><td class=va_top style=border:none; height=22>";
								print_r($sheet_para[$roop][6]);
								print "</td>";
								print "<td class=va_top style=border:none; width=15%>リフト：</td>";
								print "<td class=va_top style=border:none; width=20%>時間：　　　　　～　　　　　</td>";
								print "<td class=va_top style=border:none; width=15%>作業者：</td></tr></table><hr class=hr_sagyou>";
							}
						}
						else{
							print "<table class=box_right height=22><tr><td style=border:none; width=50%>　</td><td class=va_top style=border:none width=15%>リフト：</td><td class=va_top style=border:none width=20%>時間：　　　　　～　　　　　</td><td class=va_top style=border:none width=15%>作業者：</td></tr></table><hr class=hr_sagyou>";
							print "<table class=box_right height=22><tr><td style=border:none; width=50%>　</td><td class=va_top style=border:none width=15%>リフト：</td><td class=va_top style=border:none width=20%>時間：　　　　　～　　　　　</td><td class=va_top style=border:none width=15%>作業者：</td></tr></table><hr class=hr_sagyou>";
							print "<table class=box_right height=22><tr><td style=border:none; width=50%>　</td><td class=va_top style=border:none width=15%>リフト：</td><td class=va_top style=border:none width=20%>時間：　　　　　～　　　　　</td><td class=va_top style=border:none width=15%>作業者：</td></tr></table><hr class=hr_sagyou>";
						}
					?>
					<table class="box_right" height="22">
						<tr>
							<td style="border:none;" width="50%">　</td>
							<td class="va_top" style="border:none" width="15%">リフト：</td>
							<td class="va_top" style="border:none" width="20%">時間：　　　　　～　　　　　</td>
							<td class="va_top" style="border:none" width="15%">作業者：</td>
						</tr>
					</table>
					<hr class="hr_sagyou">
					<table class="box_right" height="22">
						<tr>
							<td style="border:none;" width="50%">　</td>
							<td class="va_top" style="border:none" width="15%">リフト：</td>
							<td class="va_top" style="border:none" width="20%">時間：　　　　　～　　　　　</td>
							<td class="va_top" style="border:none" width="15%">作業者：</td>
						</tr>
					</table>
					<hr class="hr_sagyou">
					<table class="box_right" height="22">
						<tr>
							<td style="border:none;" width="50%">　</td>
							<td class="va_top" style="border:none" width="15%">リフト：</td>
							<td class="va_top" style="border:none" width="20%">時間：　　　　　～　　　　　</td>
							<td class="va_top" style="border:none" width="15%">作業者：</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td class="print_text_center" colspan="2" height="30"><span class="font_b">推奨メニューの案内：</span>　<span class="font_20">済 ・ 未</span></td>
				<td class="print_text_center" colspan="4">警告灯 / 無 / E/G / ABS / AFS / ！ / ブレーキ / TRC / バッテリー / その他（　　　　）</td>
			</tr>
			<tr>
				<td colspan="6">
					<div>◆備考◆</div>
					<table class="box_left"><tr><td style="border:none;"><?php print "$car_other"; ?></td></tr></table>
					<div class="space100px"></div>
					<table class="box_left">
						<tr><td style="border:none;">〔車高確認： 有 ・ 無 〕　　〔ホイール注意： 済 〕　　〔ロックナット： 有 ・ 無 （　　　　　）〕　　〔外した部品： 処分 ・ 持帰 ・ 下取 〕</td></tr>
						<tr><td style="border:none;">車高： Fr〔　　　　　　　　　〕 Rr〔　　　　　　　　　〕　　減衰： Fr〔　　　　　　　　　〕 Rr〔　　　　　　　　　〕</td></tr>
					</table>
				</td>
			</tr>
			<tr>
				<td class="va_top" colspan="3">
					<div class="font_b">Front</div>
					<table class="box_left">
						<tr><td style="border:none;" class="print_text_right">トー：</td><td style="border:none;">基準 ・ （イン / アウト）　トータル　　　　　　mm</td></tr>
						<tr><td style="border:none;" class="print_text_right">キャンバー：</td><td style="border:none;">基準 ・ 指定（　　　　　°　　　　　’）</td></tr>
						<tr><td style="border:none;" class="print_text_right">キャスター：</td><td style="border:none;">基準 ・ 指定（　　　　　°　　　　　’）</td></tr>
					</table>
				</td>
				<td class="va_top" colspan="3">
					<div class="font_b">Rear</div>
					<table class="box_left">
						<tr><td style="border:none;" class="print_text_right">トー：</td><td style="border:none;">基準 ・ （イン / アウト）　トータル　　　　　　mm</td></tr>
						<tr><td style="border:none;" class="print_text_right">キャンバー：</td><td style="border:none;">基準 ・ 指定（　　　　　°　　　　　’）</td></tr>
					</table>
				</td>
			</tr>
			<tr>
				<td class="va_top" colspan="2">
					<div class="font_b">車両ナンバー</div>
				</td>
				<td class="va_top" colspan="2">
					<div class="font_b">タイヤ空気圧</div>
					<div class="space10px"></div>
					<div class="print_text_center">Fr　　　　　　基準　/　Rr　　　　　　基準</div>
				</td>
				<td class="va_top" colspan="2">
					<div class="font_b">走行距離</div>
					<div class="space10px"></div>
					<div class="print_text_center">　　　　　　　　km　純正・社外（　　　　　　）</div>
				</td>
			</tr>
		</table>
		<div class="space10px"></div>
		<table class="print_om_2nd">
			<tr>
				<td>
					<hr>
					<div class="space5px"></div>
					<div class="font_b">【アンケート】</div>
					<div class="space10px"></div>
					<table class="box_right"><tr><td style="border:none;"><div class="font_14">◆走行仕様　〔 街乗り　/　サーキット　/　首都高　/　峠 〕　　〔 グリップ　/　ドリフト 〕</div></td></tr></table>
					<div class="space10px"></div>
					<table class="box_right"><tr><td><div class="font_14">◆キャンペーン・割引券などのメルマガ/ダイレクトメールの送付　〔 必要　/　不要 〕 <span class="font_f00">*無記入は送付とさせて頂きます。</span></div></td></tr></table>
					<div class="space10px"></div>
					<table class="box_right">
						<tr><td colspan="2"><div class="font_14">◆当店（キャンペーン）をどのようにお知りになりましたか？（複数回答可。該当するものに○をお願いします）</div></td></tr>
						<tr><td width="15%"><div class="font_14">　　・カー雑誌</div></td><td><div class="font_14">〔 オプション / スタイルワゴン / Kcarスペシャル / VIPスタイル / ドリフト天国 / 不明 / その他 〕</div></td><tr>
						<tr><td>
							<div class="font_14">　　・インターネット</div></td><td><div class="font_14">〔 KTSホームページ / KTS取付ブログ / みんカラ / facebook / twitter / mixi / 楽天 / ヤフオク / Amazon 〕</div><div class="font_14">〔 その他（　　　　　　　　　　　　　　　） / 不明 〕</div></td></tr>
						<tr><td><div class="font_14">　　・知人の紹介</div></td><td><div class="font_14">〔 家族 / 友人 / SNS上の知り合い 〕</div></td></tr>
						<tr><td colspan="2"><div class="font_14">　　・近所だから / 昔から知っていた / 通りかかって知った / その他（　　　　　　　　　　　　　　　　　　　　　　）</div></td></tr>
					</table>
					<div class="space10px"></div>
					<hr>
					<div class="space5px"></div>
					<div class="font_b_f00">《KTSからのお願い》</div>
					<div class="space10px"></div>
					<table>
						<tr>
							<td class="va_top">*</td>
							<td>
								<div class="font_14">取り付けパーツの構造・設計、及び、持込みパーツの作動等の原因による一切の不具合は保証致しかねます。</div>
								<div class="font_14">（異音や振動・タイヤサイズ変更車など社外パーツ同士の相性・中古パーツの作動不良など）</div>
							</td>
						</tr>
						<tr>
							<td class="va_top">*</td>
							<td>
								<div class="font_14">工賃は基本工賃です。お車の状態や不具合により追加工賃が発生する場合があります。</div>
								<div class="font_14">追加料金発生の場合、事前にご説明・ご相談を致しますが、不具合があれば車輌の状態を事前にお伝え下さい。</div>
							</td>
						</tr>
						<tr>
							<td class="va_top">*</td>
							<td>
								<div class="font_14">作業後、取付パーツの作動確認の為に<span class="font_f00">法定レベルでの試乗を行います</span>ので予めご了承願います。（必要に応じて試乗します）</div>
								<div class="font_14">試乗走行を希望しない場合、不具合や路上走行できない応対の場合は事前にお伝え下さい。</div>
								<div class="font_14">車輌の劣化・パーツ不良（駆動系・エンジン・その他）お客様の車輌の状態による試乗走行中のトラブル等は保証致しかねます。</div>
							</td>
						</tr>
						<tr>
							<td class="va_top">*</td>
							<td>
								<div class="font_14">お客様の車輌の状態はお伝え頂かないと分りかねますので不具合等は必ず事前にお申し出下さい。</div>
							</td>
						</tr>
					</table>
					<hr>
					<div class="space5px"></div>
				</td>
				<td>
					<table class="print_check_list" width="120">
						<tr>
							<td></td>
							<td>前</td>
							<td>後</td>
						</tr>
						<tr>
							<td>外装Fr</td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td>外装サイド</td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td>A/W傷</td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td>電装</td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td>内装</td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td>スピードM</td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td>バック灯</td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td>試乗</td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td>各部締</td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td>1G締</td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td>付属品</td>
							<td colspan="2"></td>
						</tr>
						<tr>
							<td>ロックナット</td>
							<td colspan="2"></td>
						</tr>
						<tr>
							<td>持帰品</td>
							<td colspan="2"></td>
						</tr>
						<tr>
							<td>タイヤT</td>
							<td class="va_bottom"><div class="space20px"></div><div class="font_10">一回</div></td>
							<td class="va_bottom"><div class="space20px"></div><div class="font_10">二回</div></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<div class="space40px"></div>
		<table class="print_om_2nd">
			<tr>
				<td><div class="font_24_b_right"><span class="font_f00">上記の内容にご同意頂き、ご署名をお願い致します</span>： </div></td>
				<td class="name_sign"></td>
			</tr>
		</table>
		<div class="space20px"></div>
		<table class="print_om_2nd">
			<tr>
				<td width="50%"><div>最終確認者： </div></td>
				<td width="50%">店舗備考欄：</div></td>
			</tr>
		</table>
	</body>
</html>
