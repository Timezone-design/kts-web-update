<html>

  <head>
    <meta content="アライメント,車高調,KTS,走行会,クラッチ,LSD,取付,工賃" name="keywords" />
    <meta content="KTSは車好きの為のチューニングショップです。オイル交換からエンジンの載せ換えまで、チューニングの事ならお任せを！" name="description" />
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <meta content="width=device-width, user-scalable=yes, initial-scale=1, maximum-scale=1" name="viewport" />
    <meta content="width=device-width" name="viewport" />
    <meta content="article" property="og:type" />
    <meta content="KTS-web" property="og:title" />
    <meta content="KTSは車好きの為のチューニングショップです。オイル交換からエンジンの載せ換えまで、チューニングの事ならお任せを！" property="og:description" />
    <meta content="https://www.kts-web.com/" property="og:url" />
    <meta content="https://www.kts-web.com/favicon.ico" property="og:image" />
    <meta content="KTS-web" property="og:site_name" />
    <meta content="ja_JP" property="og:locale" />
    <title>
      KTS-web
    </title>
    <link href="/assets/css/search.css" rel="stylesheet" type="text/css" />
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/css/header_blu.css" rel="stylesheet" type="text/css" />
    <link href="/assets/css/mainbody_blu.css" rel="stylesheet" type="text/css" />
    <link href="/assets/css/footer_blu.css" rel="stylesheet" type="text/css" />
    <script src="/assets/js/jquery-3.6.0.min.js">
    </script>
    <script src="/assets/js/popper.min.js">
    </script>
    <script src="/assets/js/bootstrap.min.js">
    </script>
    <script src="/assets/js/slick.min.js">
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP&display=swap" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet" type="text/css" />
  </head>

  <body>
    <main>
      <section id="wrapper">
        <div class="section-container">
          <div id="content-wrapper">
            <!-- Header Start -->
            <?php include($_SERVER['DOCUMENT_ROOT'].'/_partials/header.html'); ?>
            <!-- Header End -->
            <div class="clearfix-s">
            </div>
            <div class="arrowed-title" id="menu-title">
              <h1>
                MENU
              </h1>
              <img alt="Blue Arrow" src="/assets/img/blue_arrow.svg" srcset="" />
            </div>
            <div class="clearfix">
            </div>
            <div class="clearfix sp">
            </div>
            <br />
            <div class="four-of-a-kind row">
              <div class="four-of-a-kind-one col-md-3 col-sm-6 col-xs-6" style="background-image: url('/assets/img/four-of-a-kind/1.jpg');">
                <img alt="" src="/assets/img/four-of-a-kind/1.jpg" srcset="" style="visibility: hidden; max-width: 100%; max-height: 100%;" />
                <a class="arrowed-link white" href="/shop_menu/set/index.html">
                  <div class="title_ja">
                    <b>ショップメニュー</b>
                  </div>
                  <img alt="Menu Link" src="/assets/img/white_arrow.svg" />
                </a>
              </div>
              <div class="four-of-a-kind-one col-md-3 col-sm-6 col-xs-6" style="background-image: url('/assets/img/four-of-a-kind/2.jpg');">
                <img alt="" src="/assets/img/four-of-a-kind/2.jpg" srcset="" style="visibility: hidden; max-width: 100%; max-height: 100%;" />
                <a class="arrowed-link white" href="/product/index.html">
                  <div class="title_ja">
                    <b>オリジナル商品</b>
                  </div>
                  <img alt="Menu Link" src="/assets/img/white_arrow.svg" />
                </a>
              </div>
              <div class="four-of-a-kind-one col-md-3 col-sm-6 col-xs-6" style="background-image: url('/assets/img/four-of-a-kind/3.jpg');">
                <img alt="" src="/assets/img/four-of-a-kind/3.jpg" srcset="" style="visibility: hidden; max-width: 100%; max-height: 100%;" />
                <a class="arrowed-link white" href="/tire_wheel/index.html">
                  <div class="title_ja">
                    <b>タイヤ・ホイール</b>
                  </div>
                  <img alt="Menu Link" src="/assets/img/white_arrow.svg" />
                </a>
              </div>
              <div class="four-of-a-kind-one col-md-3 col-sm-6 col-xs-6" style="background-image: url('/assets/img/four-of-a-kind/4.jpg');">
                <img alt="" src="/assets/img/four-of-a-kind/4.jpg" srcset="" style="visibility: hidden; max-width: 100%; max-height: 100%;" />
                <a class="arrowed-link white" href="/houjin/index.html">
                  <div class="title_ja">
                    <b>アライメント予約</b>
                  </div>
                  <img alt="Menu Link" src="/assets/img/white_arrow.svg" />
                </a>
              </div>
            </div>
            <?php
          $root_dir = __DIR__;
          $uri = '#search-block';
          $car_manu = isset($_POST['car_manu'])?$_POST['car_manu']:'';
          $car_model_cat = isset($_POST['car_model_cat'])?$_POST['car_model_cat']:'';
          $model = isset($_POST['model'])?$_POST['model']:'';
          $car_manus = []; $car_model_cats = []; $product_models = []; $filtered_products = [];
          $button_pressed = isset($_POST['buttonpress'])?$_POST['buttonpress']:'0';
          
            $file = $root_dir.'/db/item.csv';
            $buffer=explode("\n",file_get_contents($file));

            foreach($buffer as $row_buf){
                $row = explode(",", $row_buf);
                if(count($row) < 19) continue;
                if($row[7] == "") continue;
                $product = new stdClass();
                $product->car_manu = $row[7];
                $car_manus[$row[7]] = $row[7];
                if($product->car_manu == $car_manu && $car_manu != ''){
                  $product->car_model_cat = $row[1];
                  if($product->car_model_cat != ''){
                    $car_model_cats[$product->car_model_cat] = $product->car_model_cat;
                    if($car_model_cat == $product->car_model_cat && $car_model_cat != ''){
                      $product->manufacturer_name = $row[3];
                      $product->product_name = $row[4];
                      $product->price = $row[18];
                      $product->car_type = $row[8];
                      $product->model = $row[9];
                      $product->model_year = $row[10];
                      $product->driving = $row[11];
                      $product->compliance_details = $row[12];
                      $product->specification = $row[13];
                      $product->manu_part_number = $row[5];
                      $product->id = $row[14];
                      $product_models [$product->product_name]= $product->product_name;
                      // if($product->price == "出さない"){
                      if(!is_numeric($product->price)){
                        $product->price = "お問い合わせください";
                      }else{
                        $product->price = "￥".number_format((int) $product->price);
                      }
                      if($model != '' && $model == $product->product_name){
                        $filtered_products []= $product;
					  } else if($model == ''){
						$filtered_products []= $product;
					  }
                    }	
                  }
                  
                }
            }
          
          ?>
          <div class="search-block grey-wrapper" id="search-block">
            <div class="clearfix"></div>
            <div class="clearfix"></div>
            <div class="clearfix"></div>
            <h1 class="search">SEARCH</h1>
            <div class="clearfix"></div>
            <div class="clearfix"></div>
            <div class="clearfix"></div>
            <h2 class="ja">車種別に商品の適合を検索できます。</h2>
            <div class="clearfix"></div>
            <div class="clearfix"></div>
            <div class="clearfix"></div>
            <form action="/<?=$uri?>" method="post" class="row ja">
            <input type="hidden" name="buttonpress" value="0" id="buttonpress">
              <div class="search-select col-md-4 col-sm-4">
                <select class="custom-select-lg" name="car_manu"
                  onchange="this.form.car_model_cat=''; this.form.model = ''; submit(this.form)">
                  <option value='' <?php if($car_manu == '') echo 'selected'; ?>>メーカーを選ぶ</option>
                  <?php foreach ($car_manus as $key => $value) { ?>
                  <option value="<?=$value?>" <?php if($car_manu == $value) echo 'selected'; ?>><?=$value?></option>
                  <?php } ?>
                </select>
              </div>
              <div class="search-select col-md-4 col-sm-4">
                <select class="custom-select-lg" name="car_model_cat"
                  onchange="this.form.model = ''; submit(this.form)">
                  <option value='' <?php if($car_model_cat == '') echo 'selected'; ?>>車種を選ぶ</option>
                  <?php foreach ($car_model_cats as $key => $value) { ?>
                  <option value="<?=$value?>" <?php if($car_model_cat == $value) echo 'selected'; ?>><?=$value?>
                  </option>
                  <?php } ?>
                </select>
              </div>
              <div class="search-select col-md-4 col-sm-4">
                <select class="custom-select-lg" name="model">
                  <option value='' <?php if($model == '') echo 'selected'; ?>>製品を選ぶ</option>
                  <?php foreach ($product_models as $key => $value) { ?>
                  <option value="<?=$value?>" <?php if($model == $value) echo 'selected'; ?>><?=$value?></option>
                  <?php } ?>
                </select>
              </div>
              <br>
              <div class="clearfix"></div>
              <div class="clearfix"></div>
              <div class="clearfix"></div>
              <button onclick="$('form.row').find('#buttonpress').val(1);" type="submit" class="btn-search" style="-webkit-font-size: 15px; -webkit-color: black;  -webkit-border: none;  -webkit-position: relative;  -webkit-height: 40px;  -webkit-width: 200px;  -webkit-background-color: lightblue;  -webkit-border-radius: 20px;  -webkit-outline: none;  font-size: 15px;  color: black;  border: none;  position: relative;  height: 40px;  width: 200px;  background-color: lightblue;  border-radius: 20px;  outline: none;<?=count($filtered_products)==0?'background-color: grey':''?>" <?=count($filtered_products)==0?'disabled':''?>>検索</button>
            </form>
            <?php if(count($filtered_products) > 0 && $button_pressed == '1'){ ?>
            <div class="search-results">
              <table class="matching_table_all">
                <thead>
                  <!-- 【メーカー名・商品名・価格・車種・型式・年式・駆動・適合詳細・仕様・メーカー品番】 -->
                  <tr>
                    <th>&nbsp;</th>
                    <th>メーカー名</th>
                    <th>商品名</th>
                    <th>工賃セット価格</th>
                    <th>車種</th>
                    <th>型式</th>
                    <th>年式</th>
                    <th>駆動</th>
                    <!-- <th>適合詳細</th> -->
                    <!-- <th>仕様</th> -->
                    <th>メーカー品番</th>
                  </tr>
                </thead>
                <tbody class="top">
                  <?php foreach ($filtered_products as $key => $value) { ?>
                  <tr>
                    <td style="width: 1%;"><a href='https://www.kts-web.com/ec_shop/products/detail/<?=$value->id?>'><img src="/product/img/buy_1.gif" alt="buy"></a></td>
                    <td><?=$value->manufacturer_name?></td>
                    <td><?=$value->product_name?></td>
                    <td style="color: crimson;"><?=$value->price?></td>
                    <td><?=$value->car_type?></td>
                    <td><?=$value->model?></td>
                    <td><?=$value->model_year?></td>
                    <td><?=$value->driving?></td>
                    <!-- <td><?=$value->compliance_details?></td> -->
                    <!-- <td><?=$value->specification?></td> -->
                    <td><?=$value->manu_part_number?></td>
                  </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
            <?php } ?>
            <div class="clearfix"></div>
            <div class="item_info_text_small" style="text-align:right;">
                    ▼ご希望の商品をタップすると詳細・購入ページに遷移します。<br>※表示価格は税込です。<br>※輸入車は平和島店・一之江店のみの対応です。</div>
            <div class="clearfix"></div>
          </div>

<script>
function enableSearchButton(value){
  if(value != ""){
    document.getElementsByClassName("btn-search")[0].disabled=false;
  }else{
    document.getElementsByClassName("btn-search")[0].disabled=true;
  }
}
</script>
            <div class="clearfix">
            </div>
            <div class="notice">
              <div class="arrowed-title" id="notice-title">
                <h1 class="ja">
                  お知らせ
                </h1>
                <img alt="Blue Arrow" src="/assets/img/blue_arrow.svg" srcset="" />
              </div>
              <div class="clearfix sp">
              </div>
              <div class="clearfix sp">
              </div>
              <div class="clearfix sp">
              </div>
              <div class="clearfix sp">
              </div>
              <div class="clearfix sp">
              </div>
              <table id="notice-table">
                <tr>
                  <td>
                    2021.06.15
                  </td>
                  <td>
                    <a href="/campaign/month.html">
                      <span class="new-notice active">
                        NEW
                      </span>
                      <span class="ja">
                        4輪アライメント強化月間開催中！期間中は「HP見た」とお伝え頂ければアライメント測定・調整料金がお得に！！
                      </span>
                    </a>
                  </td>
                </tr>
                <tr>
                  <td>
                    2021.04.09
                  </td>
                  <td>
                    <a href="/product/intake/kf.html">
                      <span class="new-notice">
                        NEW
                      </span>
                      <span class="ja">
                        COOL POWERサクションKIT スイフトスポーツ ZC33S用が新発売！
                      </span>
                    </a>
                  </td>
                </tr>
                <tr>
                  <td>
                    2020.05.15
                  </td>
                  <td>
                    <a href="/covid19.html">
                      <span class="new-notice">
                        NEW
                      </span>
                      <span class="ja">
                        新型コロナウイルスに対する弊社の対策について
                      </span>
                    </a>
                  </td>
                </tr>
                <tr>
                  <td>
                    2020.02.28
                  </td>
                  <td>
                    <a href="/neo_tune/index.html">
                      <span class="new-notice">
                        NEW
                      </span>
                      <span class="ja">
                        NeoTune ショックアブソーバー施工サービス開始しました！
                      </span>
                    </a>
                  </td>
                </tr>
              </table>
              <div class="clearfix">
              </div>
            </div>
            <!-- <div class="clearfix"></div> -->
            <!-- Footer Start -->
            <?php include($_SERVER['DOCUMENT_ROOT'].'/_partials/footer.html'); ?>
          </div>
        </div>
      </section>
    </main>
    <script src="/assets/js/header_blu.js">
    </script>
  </body>

</html>
<!-- Footer End -->
