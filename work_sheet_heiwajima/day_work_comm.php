<?php
	/* 各データの受け取り */
	$year = $_POST['year'];
	$mon = $_POST['mon'];
	$day = $_POST['day'];
	$day_file = $_POST['day_file'];
	$comm_run = $_POST['comm_run'];

	if($comm_run == 1){$comm = $_POST['comm'];}

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
	
	/* 現在時刻の取得 */
	$date = date("Y年n月j日G時i分");

	/* 日別作業データの取得 */
	setlocale(LC_ALL,'ja_JP.UTF-8');
	$fp_data = fopen("log/$day_file", "r");
	$sheet_para = array();
	if($fp_data == "FALSE" or $fp_data == ""){ /* ファイルが存在しない場合はファイル作成 */
		$i = 0;
		while($i < 19){
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
				elseif($i == 16){$sheet_para[$i][$col] = "open";}
				else{$sheet_para[$i][$col] = "";}
			}
			$i++;
		}
	}
	else{
		$i = 0;
		while($ret_csv = fgetcsv($fp_data)){
			for($col = 0; $col < count($ret_csv); $col++){
				$sheet_para[$i][$col] = $ret_csv[$col];
			}
			$i++;
		}
	}
	fclose($fp_data);

	/* 最終更新日時DATファイル呼び出し */
	$fp_ymd = fopen("ymd.dat","r");
	$ymd = fgets($fp_ymd);
	fclose($fp_ymd);
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
		<title>作業予約システム</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link rel="stylesheet" href="https://www.kts-web.com/work_sheet_heiwajima/work_sheet.css" type="text/css">
	</head>
	<body bgcolor="#FFFFFF" text="#000000">
		<table class="title">
			<tr>
				<td>KTS作業予約管理システム<span class="shop_title">（KTS平和島店）</span></td>
				<td><div class="last_ymd">最終更新日時：<?php print "$ymd"; ?></div></td>
				<td>
					<div align="right">
						<form method="post" action="day_work.php">
							<input type="hidden" name="year" value="<?php print "$year"; ?>">
							<input type="hidden" name="mon" value="<?php print "$mon"; ?>">
							<input type="hidden" name="day" value="<?php print "$day"; ?>">
							<input type="submit" value="前のページに戻る">
						</form>
					</div>
				</td>
			</tr>
		</table>
		<div class="title_ymd"><?php print "$year"; ?>年<?php print "$mon"; ?>月<?php print "$day"; ?>日（<?php print "$wday"; ?>）</div>
<?php if($comm_run == 0):?>
		<form method="post" action="day_work_comm.php">
		<table class="day_work_comm">
			<tr>
				<td width="100"><div align="right">コメント欄：</div></td>
				<td><input class="reserve_input2" type="text" name="comm" value="<?php print_r($sheet_para[0][0]); ?>"></td>
			</tr>
		</table>
		<div class="comm_w">
			<input type="hidden" name="year" value="<?php print "$year"; ?>">
			<input type="hidden" name="mon" value="<?php print "$mon"; ?>">
			<input type="hidden" name="day" value="<?php print "$day"; ?>">
			<input type="hidden" name="day_file" value="<?php print "$day_file"; ?>">
			<input type="hidden" name="comm_run" value="1">
			<input type="submit" class="comm_b" value="入力内容で保存する">
		</div>
		</form>
		<div class="footer">Copyright (C) Kind Techno Structure Corporation. All Rights Reserved.</div>
	</body>
</html>
<?php endif;?>
<?php if($comm_run == 0){exit();} ?>
<?php if($comm_run == 1):?>
<?php
	/* 改行コードをスペースに変換 */
	$comm =str_replace(array("\r\n","\r","\n"), "　", $comm);

	$comm_b = $sheet_para[0][0];
	$sheet_para[0][0] = $comm;
	/* CSV形式に変換して上書き保存 */
	$order_put = $sheet_para;
	$fp_put = fopen("log/$day_file", "w");
	foreach($order_put as $fields) fputcsv($fp_put, $fields);
	fclose($fp_put);

	/* 最終更新日時の更新 */
	file_put_contents("ymd.dat","$date");

	/* 使用ログの保存 */
	$u_id = $_SERVER['REMOTE_USER']; /* ユーザーIDを取得 */
	$u_agent = $_SERVER['HTTP_USER_AGENT']; /* 使用環境を取得 */
	$u_referer = $_SERVER['HTTP_REFERER']; /* リファラー情報を取得 */
	$u_date = date("Y/m/d(D) H:i:s"); /* 日時を取得 */
	$u_todo = "日別コメントを修正"; /* 変更内容 */
	$u_log = "$u_date,$u_id,$day_file,ファイル無し,$u_todo,$u_agent,$u_referer" . "\n"; /* 保存ログデータ */
	$fp_log = fopen("log/work/work_access.log", "a");
	fwrite($fp_log, "$u_log");
	fclose($fp_log);
?>
		<div class="title_ymd">入力内容を保存しました。</div>
		<div class="comm_w">
			<form method="post" action="day_work.php">
				<input type="hidden" name="year" value="<?php print "$year"; ?>">
				<input type="hidden" name="mon" value="<?php print "$mon"; ?>">
				<input type="hidden" name="day" value="<?php print "$day"; ?>">
				<input type="submit" value="作業一覧画面へ戻る">
			</form>
		</div>
		<div class="footer">Copyright (C) Kind Techno Structure Corporation. All Rights Reserved.</div>
	</body>
</html>
<?php endif;?>
<?php if($comm_run == 1){exit();} ?>
