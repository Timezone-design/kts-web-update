<?php
	/* 五十音取得 */
	$gojuon = 1;
	// $gojuon = $_POST['gojuon'];

	/* 初期値セット */
	if($gojuon == ""){$gojuon = 1;}
?>
<html>
<head>
	<meta name="keywords" content="KTS,メーカー,リンク">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-wideth, user-scalable=yes, initial-scale=1, maximum-scale=1">
	<title>KTS-web 各メーカーリンク</title>
	<script type="text/javascript">
		$(function(){
				$("#toggle").click(function(){
					$("#menu").slideToggle();
					return false;
				});
				$(window).resize(function(){
					var win = $(window).width();
					var p = 480 ;
					if(win > p){
						$("#menu").show();
					}
					else {
						$("#menu").hide();
					}
				});
			});
		function MM_preloadImages() { //v3.0
		var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
		var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
		if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
		}
		function MM_swapImgRestore() { //v3.0
			var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
		}

		function MM_findObj(n, d) { //v4.01
			var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
				d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
			if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
			for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
			if(!x && d.getElementById) x=d.getElementById(n); return x;
		}

		function MM_swapImage() { //v3.0
			var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
			if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
		}
	</script>

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
</head>

<body>
  <main>
    <section id="wrapper">
      <div class="section-container">
        <div id="content-wrapper">
          <!-- Header Start -->

          <header class="site-header">
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
                  <li class="nav-item"><a class="nav-link" href="/wholesale/index.html">
                      <div class="ja">業販</div>
                      <div class="en">WHOLESALE</div>
                    </a>
                  </li>
                  <li class="nav-item"><a class="nav-link" href="/blog/">
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
                  <div class="col-md-3 col-sm-6 col-xs-6"><a href="/shop_menu/set/ch_ma/liqui_moly_dpf.html"><span>詳しく見る</span><img
                        src="/assets/img/home/liqui_moly_dpf_480.png"></a></div>
                  <div class="col-md-3 col-sm-6 col-xs-6"><a href="/shop_menu/set/ch_ma/10674.html"><span>詳しく見る</span><img
                        src="/assets/img/home/mahle_ozone_pro_480.png"></a></div>
                  <div class="col-md-3 col-sm-6 col-xs-6"><a href="/tire_wheel/tire/index.html"><span>詳しく見る</span><img src="/assets/img/home/tire_wheel_480.png"></a></div>
                </div>
              </div>
            </div>
                      </header>
          <!-- Header End -->
				<div class="main_c row grey-wrapper">
					<div class="clearfix-s"></div>
					<div class="link_back">
						<div class="link_text">下記メーカー・ブランド以外の商品も取り扱っております。掲載外のメーカー・ブランドがございましたらお気軽にお問い合わせ下さい。</div>
						<hr class="link_hr" noshade>
						<?php include('link.php'); ?>
					</div>
					<div class="clearfix-s"></div>
				</div>
			<!-- Footer Start -->
          <footer>

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
          </footer>
        </div>
      </div>
    </section>
  </main>
  <script src="/assets/js/header_blu.js"></script>

</html>
<!-- Footer End -->
