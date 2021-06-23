<?php
	/* 各データの受け取り */
	$year = $_POST['year'];
	$mon = $_POST['mon'];
	$comm_run = $_POST['comm_run'];

	/* 一桁月の処理 */
	$mon0 = $mon;
	if($mon0 < 10){$mon0 = "0$mon";}

	/* 月次コメントファイルの呼び出し */
	$m_comm_file = "$year" . "$mon0" . ".dat";
	setlocale(LC_ALL,'ja_JP.UTF-8');
	$fp_comm = fopen("log/mon/$m_comm_file", "r");
	$m_comm = fgets($fp_comm);
	fclose($fp_comm);

	if($comm_run == 1){$comm = $_POST['comm'];}

	/* 現在時刻の取得 */
	date_default_timezone_set('Asia/Tokyo');
	$date = date("Y年n月j日G時i分");

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
						<form method="post" action="index.php">
							<input type="submit" value="前のページに戻る">
						</form>
					</div>
				</td>
			</tr>
		</table>
		<div class="title_ymd"><?php print "$year"; ?>年<?php print "$mon"; ?>月</div>
<?php if($comm_run == 0):?>
		<form method="post" action="mon_work_comm.php">
		<table class="day_work_comm">
			<tr>
				<td width="100"><div align="right">コメント欄：</div></td>
				<td><input class="reserve_input2" type="text" name="comm" value="<?php print "$m_comm"; ?>"></td>
			</tr>
		</table>
		<div class="comm_w">
			<input type="hidden" name="year" value="<?php print "$year"; ?>">
			<input type="hidden" name="mon" value="<?php print "$mon"; ?>">
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
	/* $comm =str_replace(array("\r\n","\r","\n"), "　", $comm); */

	$comm_b = $m_comm;
	/* コメント欄の更新 */
	file_put_contents("log/mon/$m_comm_file","$comm");

	/* 最終更新日時の更新 */
	file_put_contents("ymd.dat","$date");

	/* 使用ログの保存 */
	$u_id = $_SERVER['REMOTE_USER']; /* ユーザーIDを取得 */
	$u_agent = $_SERVER['HTTP_USER_AGENT']; /* 使用環境を取得 */
	$u_referer = $_SERVER['HTTP_REFERER']; /* リファラー情報を取得 */
	$u_date = date("Y/m/d(D) H:i:s"); /* 日時を取得 */
	$u_todo = "月別コメントを修正"; /* 変更内容 */
	$u_log = "$u_date,$u_id,ファイル無し,ファイル無し,$u_todo,$u_agent,$u_referer" . "\n"; /* 保存ログデータ */
	$fp_log = fopen("log/work/work_access.log", "a");
	fwrite($fp_log, "$u_log");
	fclose($fp_log);
?>
		<div class="title_ymd">入力内容を保存しました。</div>
		<div class="comm_w">
			<form method="post" action="index.php">
				<input type="submit" value="月間表示画面へ戻る">
			</form>
		</div>
		<div class="footer">Copyright (C) Kind Techno Structure Corporation. All Rights Reserved.</div>
	</body>
</html>
<?php endif;?>
<?php if($comm_run == 1){exit();} ?>
