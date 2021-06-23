<?php
	/* IDチェック */
	$log_check = 0;
	$u_id = $_SERVER['REMOTE_USER']; /* ユーザーIDを取得 */
	if($u_id != "kts_master" and $u_id != "imai"){$log_check = 1;}

	/* ログファイル */
	$log_file = "log/work/work_access.log";

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
		<link rel="stylesheet" href="https://www.kts-web.com/work_sheet_ichinoe/work_sheet.css" type="text/css">
	</head>
	<body bgcolor="#FFFFFF" text="#000000">
		<table class="title">
			<tr>
				<td>KTS作業予約管理システム<span class="shop_title">（KTS平和島店）作業ログ</span></td>
				<td><div class="last_ymd">最終更新日時：<?php print "$ymd"; ?></div></td>
			</tr>
		</table>
		<hr>
		<table>
<?php if($log_check == 1):?>
		<table class="day_work_comm">
			<tr>
				<td><div class="font_b_110">閲覧権限が無い為、表示できません。</div></td>
			</tr>
		</table>
		<table class="day_work_comm">
			<tr><td><div><a href="index.php">月間表示画面へ戻る</a></div></td></tr>
		</table>
		<div class="space10px"></div>
		<div class="footer">Copyright (C) Kind Techno Structure Corporation. All Rights Reserved.</div>
	</body>
</html>
<?php endif;?>
<?php if($log_check ==1){exit();} ?>
		<table class="log_table">
<?php
	/* ログファイルよりデータ取得 */
	setlocale(LC_ALL,'ja_JP.UTF-8');
	$fp_log = fopen("$log_file", "r");
	$log_data = array();
	$i = 0;
	while($ret_csv = fgetcsv($fp_log)){
		for($col = 0; $col < count($ret_csv); $col++){
			$log_data[$i][$col] = $ret_csv[$col];
		}
		$i++;
	}
	fclose($fp_data);
	for($cnt = 0; $cnt < $i; $cnt++){
		print "<tr>\n";
		print "<td>"; print_r($log_data[$cnt][0]); print "</td>\n";
		print "<td>"; print_r($log_data[$cnt][1]); print "</td>\n";
		print "<td>"; print_r($log_data[$cnt][2]); print "</td>\n";
		print "<td>"; print_r($log_data[$cnt][3]); print "</td>\n";
		print "<td>"; print_r($log_data[$cnt][4]); print "</td>\n";
		print "</tr>\n";
	}
?>
		</table>
		<hr>
		<table class="day_work_comm">
			<tr><td><div><a href="index.php">月間表示画面へ戻る</a></div></td></tr>
		</table>
		<div class="space10px"></div>
		<div class="footer">Copyright (C) Kind Techno Structure Corporation. All Rights Reserved.</div>
	</body>
</html>
