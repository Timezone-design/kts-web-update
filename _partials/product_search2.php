<?php
$root_dir = __DIR__.'/..';
$uri = '#search-block';
$inch = isset($_POST['inch'])?$_POST['inch']:'';
$tire_width = isset($_POST['tire_width'])?$_POST['tire_width']:'';
$flatness = isset($_POST['flatness'])?$_POST['flatness']:'';
$product = isset($_POST['product'])?$_POST['product']:'';
$file = $root_dir.'/db/item-tire.csv';
//$filename = basename($_SERVER['PHP_SELF'], '.php');
$handle = fopen($file, "r");
$inches = []; $tire_widthes = []; $flatnesses = []; $filtered_products = [];
$row = fgetcsv($handle, 0, ",");
$button_pressed = isset($_POST['buttonpress'])?$_POST['buttonpress']:'0';

while (($row = fgetcsv($handle, 0, ",")) !== false) 
{
	//if($row[0] != $filename) continue;
	$price = (string) $row[7];
	if($price == "出さない"){
		$price = "お問い合わせください";
	}else{
		$price = "￥".number_format((int)$price);
	}
	$four_set = (string) $row[8];
	$four_set = "￥".number_format((int)$four_set);
    $product = array(
    	'manufacturer' => (string) $row[1],
    	'brand' => (string) $row[2],
    	'product_name' =>  $row[3],
    	'inch' => (string) $row[4],
    	'flatness' => (string) $row[5],
    	'tire_width' => (string) $row[6],
    	'price' => $price,
    	'four_set' => $four_set,
    	'note' => (string) $row[9],
    	'speed_notation' => (string) $row[10],
    	'genre' => (string) $row[11],
    	'id' => (string) $row[12]
    );

    $products []= $product;
    if($row[4] != '') $inches[$row[4]] = $row[4];

    if($inch != '' && $product['inch'] == $inch && $product['tire_width'] != ''){
		$tire_widthes[$product['tire_width']] = $product['tire_width'];
		if($tire_width == $product['tire_width'] && $tire_width != ''){
			$flatnesses[$product['flatness']] = $product['flatness'];
			if($flatness != '' && $flatness == $product['flatness']){
				$filtered_products []= $product;
			}else if($flatness == ''){
				$filtered_products []= $product;
			}
		}
	}
				
}

fclose($handle);
?>
<div class="search-block grey-wrapper" id="search-block">
	<div class="clearfix"></div>
	<h1 class="search">SEARCH</h1>
	<div class="clearfix"></div>
	<h2 class="ja">車種別に商品の適合を検索できます。</h2>
	<div class="clearfix"></div>
	<form action="<?=$uri?>" method="post" class="row ja">
            <input type="hidden" name="buttonpress" value="0" id="buttonpress">
		<div class="search-select col-md-4 col-sm-4">
			<select class="custom-select-lg" name="inch" onchange="this.form.tire_width=''; this.form.flatness = ''; submit(this.form)">
				<option value='' <?php if($inch == '') echo 'selected'; ?>>インチを選ぶ</option>
				<?php foreach ($inches as $key => $value) { ?>
				<option value="<?=$value?>" <?php if($inch == $value) echo 'selected'; ?>><?=$value?></option>
				<?php } ?>
			</select>
		</div>
		<div class="search-select col-md-4 col-sm-4">
			<select class="custom-select-lg" name="tire_width" onchange="this.form.flatness = ''; submit(this.form)">
				<option value='' <?php if($tire_width == '') echo 'selected'; ?> >幅を選ぶ</option>
				<?php foreach ($tire_widthes as $key => $value) { ?>
				<option value="<?=$value?>" <?php if($tire_width == $value) echo 'selected'; ?>><?=$value?></option>
				<?php } ?>
			</select>
		</div>
		<div class="search-select col-md-4 col-sm-4">
			<select class="custom-select-lg" name="flatness">
				<option value='' <?php if($flatness == '') echo 'selected'; ?>>扁平率を選ぶ</option>
				<?php foreach ($flatnesses as $key => $value) { ?>
				<option value="<?=$value?>" <?php if($flatness == $value) echo 'selected'; ?>><?=$value?></option>
				<?php } ?>
			</select>
		</div>
	  	<br>
			<div class="clearfix"></div>
	  	<button onclick="$('form.row').find('#buttonpress').val(1);" class="btn-search" type="submit" style="-webkit-font-size: 15px; -webkit-color: black;  -webkit-border: none;  -webkit-position: relative;  -webkit-height: 40px;  -webkit-width: 200px;  -webkit-background-color: lightblue;  -webkit-border-radius: 20px;  -webkit-outline: none;  font-size: 15px;  color: black;  border: none;  position: relative;  height: 40px;  width: 200px;  background-color: lightblue;  border-radius: 20px;  outline: none;<?=count($filtered_products)==0?'background-color: grey':''?>" <?=count($filtered_products)==0?'disabled':''?>>検索</button>
	</form>
	<?php if(count($filtered_products) > 0 && $button_pressed == '1'){ ?>
	<div class="search-results">	
		<table class="matching_table_all">
			<thead>
				<tr>
					<!-- 【インチ・扁平率・タイヤ幅・販売価格・4本セット・備考・速度表記】 -->
					<th></th>
					<th>メーカー</th>
					<th>ブランド</th>
					<th>商品名</th>
					<!-- <th>インチ</th>
					<th>扁平率</th>
					<th>タイヤ幅</th> -->
					<th>販売価格</th>
					<th>4本セット</th>
					<th>備考</th>
					<th>速度表記</th>
					<!-- <th>ジャンル</th> -->
				</tr>
			</thead>
			<tbody>
				<?php foreach ($filtered_products as $key => $value) { ?>
				<tr>
					<td style="width: 1%;"><a href='https://www.kts-web.com/ec_shop/products/detail/<?=$value["id"]?>'><img src="/product/img/buy_1.gif" alt="buy"></a></td>
					<td><?=$value['manufacturer']?></td>
					<td><?=$value['brand']?></td>
					<td><?=$value['product_name']?></td>
					<!-- <td style="color: black;"><?=$value['inch']?></td> -->
					<!-- <td style="color: black;"><?=$value['flatness']?></td> -->
					<!-- <td style="color: black;"><?=$value['tire_width']?></td> -->
					<td style="color: crimson;"><?=$value['price']?></td>
					<td style="color: crimson;"><?=$value['four_set']?></td>
					<td style="color: black;"><?=$value['note']?></td>
					<td style="color: black;"><?=$value['speed_notation']?></td>
					<!-- <td><?=$value['genre']?></td> -->
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
	<?php } ?>
	<div class="clearfix"></div>
</div>
