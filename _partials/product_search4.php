<?php
          $root_dir = __DIR__.'/..';
          $car_manu = isset($_POST['car_manu'])?$_POST['car_manu']:'';
          $car_model_cat = isset($_POST['car_model_cat'])?$_POST['car_model_cat']:'';
          $model = isset($_POST['model'])?$_POST['model']:'';
          $car_manus = []; $car_model_cats = []; $product_models = []; $filtered_products = [];

          $filename = basename($_SERVER['PHP_SELF'], '.php');

            $file = $root_dir.'/db/item.csv';
            $buffer=explode("\n",file_get_contents($file));
            foreach($buffer as $row_buf){
                $row = explode(",", $row_buf);
                if(count($row) < 19) continue;
                if($row[7] == "" || $row[0] != $filename) continue;
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
                      $product->price1 = $row[19];
                      $product->car_type = $row[8];
                      $product->model = $row[9];
                      $product->model_year = $row[10];
                      $product->driving = $row[11];
                      $product->compliance_details = $row[12];
                      $product->specification = $row[13];
                      $product->id = $row[14];
                      $product->manu_part_number = $row[5];
                      $product_models [$product->model]= $product->model;
                      if($product->price == "出さない"){
                        $product->price = "お問い合わせください";
                      }else{
                        $product->price = "\\".number_format($product->price);
                      }
                      if($product->price1 == "出さない"){
                        $product->price1 = "お問い合わせください";
                      }else{
                        if($product->price1 != "" && $product->price1 != null) $product->price1= "\\".number_format((int)$product->price1);
                      }
                      if($model != '' && $model == $product->model)
                        $filtered_products []= $product;
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
            <form action="#search-block" method="post" class="row ja">
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
                <select class="custom-select-lg" name="model" onchange="enableSearchButton(this.value);">
                  <option value='' <?php if($model == '') echo 'selected'; ?>>型式を選ぶ</option>
                  <?php foreach ($product_models as $key => $value) { ?>
                  <option value="<?=$value?>" <?php if($model == $value) echo 'selected'; ?>><?=$value?></option>
                  <?php } ?>
                </select>
              </div>
              <br>
              <div class="clearfix"></div>
              <div class="clearfix"></div>
              <div class="clearfix"></div>
              <button type="submit" class="btn-search" disabled>検索</button>
            </form>
            <?php if(count($filtered_products) > 0){ ?>
            <div class="search-results">
              <table class="matching_table_all">
                <thead>
                  <!-- 【価格・車種・型式・年式・駆動・適合詳細・仕様・メーカー品番】 -->
                  <tr>
                    <th>価格</th>
                    <th>車種</th>
                    <th>型式</th>
                    <th>年式</th>
                    <th>駆動</th>
                    <th>適合詳細</th>
                    <th>仕様</th>
                    <th>メーカー品番</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($filtered_products as $key => $value) { ?>
                  <tr onclick="window.open('https://www.kts-web.com/ec_shop/products/detail/<?=$value->id?>')">
                    <td>
                    <?php 
                    if($value->price1 != "" && $value->price1 > 0){
                      echo "<p>".$value->price."</p>".$value->price1;
                    }else{
                      echo $value->price;
                    }
                    ?>
                    </td>
                    <td><?=$value->car_type?></td>
                    <td><?=$value->model?></td>
                    <td><?=$value->model_year?></td>
                    <td><?=$value->driving?></td>
                    <td><?=$value->compliance_details?></td>
                    <td><?=$value->specification?></td>
                    <td><?=$value->manu_part_number?></td>
                  </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
            <?php } ?>
            <div class="clearfix"></div>
          </div>
          <div class="item_info_text_small">
                  ▼ご希望の商品をタップすると詳細・購入ページに遷移します。<br>※表示価格は税込です。<br>※輸入車は平和島店・一之江店のみの対応です。</div>
          <div class="clearfix"></div>

<script>
function enableSearchButton(value){
  if(value != ""){
    document.getElementsByClassName("btn-search")[0].disabled=false;
  }else{
    document.getElementsByClassName("btn-search")[0].disabled=true;
  }
}
</script>