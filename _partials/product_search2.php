<?php
$root_dir = __DIR__.'/..';
$uri = '#search-block';
$id_template = $_smarty_tpl->tpl_vars['page']->value['id_template'];
if ($id_template == 1 || $id_template == 24 || $id_template == 25) {
	$car_manu = isset($_POST['car_manu'])?$_POST['car_manu']:'';
	$car_model_cat = isset($_POST['car_model_cat'])?$_POST['car_model_cat']:'';
	$product_name = isset($_POST['product_name'])?$_POST['product_name']:'';
	$file = $root_dir.'/db/car_manus.csv';
	$handle = fopen($file, "r");
	$car_manus = []; $car_model_cats = []; $product_names = []; $filtered_products = [];

	while (($row = fgetcsv($handle, 0, ",")) !== false) 
	{
	    if($row[0] != '') $car_manus[$row[0]] = $row[0];
	}

	fclose($handle);

	if($car_manu != ''){
		$file = $root_dir.'/db/item.gz';
		$handle = gzopen($file, "rb");
    	
		$products = json_decode(gzread($handle, 150000000));
		
		foreach ($products as $key => $product) {
			if($product->car_manu == $car_manu){
				if($product->car_model_cat != ''){
					$car_model_cats[$product->car_model_cat] = $product->car_model_cat;
					if($car_model_cat == $product->car_model_cat && $car_model_cat != ''){
						$product_names [$product->product_name]= $product->product_name;
						if($product_name != '' && $product_name == $product->product_name)
							$filtered_products []= $product;
					}	
				}
				
			}
		}

		gzclose($handle);
	}

?>
<div class="search-block grey-wrapper" id="search-block">
	<div class="clearfix"></div>
	<h1 class="search">SEARCH</h1>
	<div class="clearfix"></div>
	<h2 class="ja">車種別に商品の適合を検索できます。</h2>
	<div class="clearfix"></div>
	<form action="/<?=$uri?>" method="post" class="row ja">
		<div class="search-select col-md-4 col-sm-4">
			<select class="custom-select-lg" name="car_manu" onchange="this.form.car_model_cat=''; this.form.product_name = ''; submit(this.form)">
				<option value='' <?php if($car_manu == '') echo 'selected'; ?>>メーカーを選ぶ</option>
				<?php foreach ($car_manus as $key => $value) { ?>
				<option value="<?=$value?>" <?php if($car_manu == $value) echo 'selected'; ?>><?=$value?></option>
				<?php } ?>
			</select>
		</div>
		<div class="search-select col-md-4 col-sm-4">
			<select class="custom-select-lg" name="car_model_cat" onchange="this.form.product_name = ''; submit(this.form)">
				<option value='' <?php if($car_model_cat == '') echo 'selected'; ?> >車種を選ぶ</option>
				<?php foreach ($car_model_cats as $key => $value) { ?>
				<option value="<?=$value?>" <?php if($car_model_cat == $value) echo 'selected'; ?>><?=$value?></option>
				<?php } ?>
			</select>
		</div>
		<div class="search-select col-md-4 col-sm-4">
			<select class="custom-select-lg" name="product_name">
				<option value='' <?php if($product_name == '') echo 'selected'; ?>>製品を選ぶ</option>
				<?php foreach ($product_names as $key => $value) { ?>
				<option value="<?=$value?>" <?php if($product_name == $value) echo 'selected'; ?>><?=$value?></option>
				<?php } ?>
			</select>
		</div>
		<br>
		<div class="clearfix"></div>
		<button type="submit" class="btn-search"><img src="/assets/img/search_button.png" alt="Search"><div class="ja">検索</div></button>
	</form>
	<?php if(count($filtered_products) > 0){ ?>
	<div class="search-results">	
		<table class="matching_table_all">
			<thead>
				<tr>
					<th>メーカー名</th>
					<th>商品名</th>
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
				<tr>
					<td><?=$value->manufacturer_name?></td>
					<td><?=$value->product_name?></td>
					<td><?=$value->price?></td>
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
<?php
}else if ($id_template == 43) {
	$inch = isset($_POST['inch'])?$_POST['inch']:'';
	$tire_width = isset($_POST['tire_width'])?$_POST['tire_width']:'';
	$flatness = isset($_POST['flatness'])?$_POST['flatness']:'';
	$product = isset($_POST['product'])?$_POST['product']:'';
	$file = $root_dir.'/db/tire.csv';
	$handle = fopen($file, "r");
	$inches = []; $tire_widthes = []; $flatnesses = []; $filtered_products = [];
	$row = fgetcsv($handle, 0, ",");

	while (($row = fgetcsv($handle, 0, ",")) !== false) 
	{
	    $product = array(
	    	'manufacturer' => (string) $row[1],
	    	'brand' => (string) $row[2],
	    	'product_name' =>  $row[3],
	    	'inch' => (string) $row[4],
	    	'flatness' => (string) $row[5],
	    	'tire_width' => (string) $row[6],
	    	'price' => (string) $row[7],
	    	'four_set' => (string) $row[8],
	    	'note' => (string) $row[9],
	    	'speed_notation' => (string) $row[10],
	    	'genre' => (string) $row[11]
	    );

	    $products []= $product;
	    if($row[4] != '') $inches[$row[4]] = $row[4];

	    if($inch != '' && $product['inch'] == $inch && $product['tire_width'] != ''){
			$tire_widthes[$product['tire_width']] = $product['tire_width'];
			if($tire_width == $product['tire_width'] && $tire_width != ''){
				$flatnesses[$product['flatness']] = $product['flatness'];
				if($flatness != '' && $flatness == $product['flatness'])
					$filtered_products []= $product;
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
	<form action="/<?=$uri?>" method="post" class="row ja">
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
	  	<button class="btn-search" type="submit"><img src="/assets/img/search_button.png" alt="Search"><div class="ja">検索</div></button>
	</form>
	<?php if(count($filtered_products) > 0){ ?>
	<div class="search-results">	
		<table class="matching_table_all">
			<thead>
				<tr>
					<th>メーカー</th>
					<th>ブランド</th>
					<th>商品名</th>
					<th>インチ</th>
					<th>扁平率</th>
					<th>タイヤ幅</th>
					<th>販売価格</th>
					<th>4本セット</th>
					<th>備考</th>
					<th>速度表記</th>
					<th>ジャンル</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($filtered_products as $key => $value) { ?>
				<tr>
					<td><?=$value['manufacturer']?></td>
					<td><?=$value['brand']?></td>
					<td><?=$value['product_name']?></td>
					<td><?=$value['inch']?></td>
					<td><?=$value['flatness']?></td>
					<td><?=$value['tire_width']?></td>
					<td><?=$value['price']?></td>
					<td><?=$value['four_set']?></td>
					<td><?=$value['note']?></td>
					<td><?=$value['speed_notation']?></td>
					<td><?=$value['genre']?></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
	<?php } ?>
	<div class="clearfix"></div>
</div>
<?php } ?>