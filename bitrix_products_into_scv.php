<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

//название раздела - название подкаталога - название товара - url товара
CModule::IncludeModule('iblock');
$obj = CIBlockElement::GetList(
	array(),
	array("IBLOCK_ID" => 22,'SECTION_ID'=>158,'ACTIVE'=>'Y','SECTION_GLOBAL_ACTIVE'=>'Y','INCLUDE_SUBSECTIONS'=>'Y'),
	false,
	false,
	array('SECTION_ID','NAME','DETAIL_PAGE_URL')
);

$products = [];
$sections_ids = [];
while( $res = $obj->GetNext() ){
	if( !in_array($res['IBLOCK_SECTION_ID'], $sections_ids) )
		$sections_ids[] = $res['IBLOCK_SECTION_ID'];

	$products[] = [
		'name' => $res['NAME'],
		'url' => $res['DETAIL_PAGE_URL'],
		'section' => $res['IBLOCK_SECTION_ID'],
	];
}

$sections_arr = [];
foreach( $sections_ids as $sect_id ){
	$nav = CIBlockSection::GetNavChain(22, $sect_id, array('DEPTH_LEVEL','NAME'));
	while( $nav_res = $nav->Fetch() ){
		if( $nav_res['DEPTH_LEVEL'] > 1 ){
			if( $nav_res['DEPTH_LEVEL'] == 2 ){
				$sections_arr[$sect_id]['main'] = $nav_res['NAME'];
			}else{
				$sections_arr[$sect_id]['depth'][] = $nav_res['NAME'];
			}
		}
		
	}
}

$fp = fopen($_SERVER['DOCUMENT_ROOT'].'/instrument_14052020.csv', 'w');
fputcsv($fp, ['Раздел','Подразделы','Название товара','url товара']);
foreach( $products as $product ){
	$content = [
		$sections_arr[$product['section']]['main'],
		implode(' / ', $sections_arr[$product['section']]['depth']),
		$product['name'],
		$product['url']
	];
	fputcsv($fp, $content);
}
fclose($fp);
