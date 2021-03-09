<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader,
    Bitrix\Iblock;

if(!$productIBlockID = (int) $arParams['PRODUCTS_IBLOCK_ID']) return false;
if(!$newsIBlockID = (int) $arParams['NEWS_IBLOCK_ID']) return false;
if(!$userProdCode = trim($arParams['USER_CODE_PROD'])) return false;

if($this->startResultCache()){

    //NEWS
    $newsQuery = CIBlockElement::GetList(
        Array(),
        Array("IBLOCK_ID"=>$newsIBlockID,"ACTIVE"=>"Y"),
        false,
        false,
        Array("ID", "NAME", "DATE_ACTIVE_FROM")
    );

    while($ar_res_new = $newsQuery->GetNext()) {
        if (!isset($arNews[$ar_res_new['ID']])) {
            $arNews[$ar_res_new['ID']] = array(
                'NEWS_NAME' => $ar_res_new['NAME'],
                'DATE_ACTIVE_FROM' => $ar_res_new['DATE_ACTIVE_FROM'],
                'SECTIONS' => array(),
                'PRODUCTS' => array(),
            );

        }
    }

    //PROD SECTIONS
    $productSectQuery = CIBlockSection::GetList(
        false,
        array("IBLOCK_ID" => $productIBlockID, '!'.$userProdCode=>false),
        false,
        array('ID', 'NAME', $userProdCode)
    );
    while($ar_result = $productSectQuery->GetNext())
    {
        if(!isset($arProductSections[$ar_result['ID']])){
            $arProductSections[$ar_result['ID']] = array(
                'SECTION_NAME' => $ar_result['NAME'],
                'USERS_SECTION_PROPERTY' => $ar_result[$userProdCode],
            );
        }

    }
    $minPrice = 999999999;
    $maxPrice = 0;
    //PRODUCTS
    $productsQuery = CIBlockElement::GetList(
        Array(),
        Array("IBLOCK_ID"=>$productIBlockID,"ACTIVE"=>"Y", "SECTION_ID" => array_keys($arProductSections)),
        false,
        false,
        Array("ID", "NAME","PROPERTY_PRICE","PROPERTY_MATERIAL","PROPERTY_ARTNUMBER","IBLOCK_SECTION_ID")
    );

    while($ar_res_prod = $productsQuery->GetNext()){

        if($minPrice > $ar_res_prod['PROPERTY_PRICE_VALUE']){
            $minPrice = $ar_res_prod['PROPERTY_PRICE_VALUE'];
        }
        if($maxPrice < $ar_res_prod['PROPERTY_PRICE_VALUE']){
            $maxPrice = $ar_res_prod['PROPERTY_PRICE_VALUE'];
        }

        if(!isset($arProducts[$ar_res_prod['ID']])){
            $arProducts[$ar_res_prod['ID']] = array(
                'PROD_NAME' => $ar_res_prod['NAME'],
                'PRICE' => $ar_res_prod['PROPERTY_PRICE_VALUE'],
                'MATERIAL' => $ar_res_prod['PROPERTY_MATERIAL_VALUE'],
                'ARTNUMBER' => $ar_res_prod['PROPERTY_ARTNUMBER_VALUE'],
            );
        }
        foreach($arProductSections[$ar_res_prod['IBLOCK_SECTION_ID']]['USERS_SECTION_PROPERTY'] as $sectElem){
            $arNews[$sectElem]['PRODUCTS'][] = $ar_res_prod['ID'];
            if(!in_array($ar_res_prod['IBLOCK_SECTION_ID'] ,$arNews[$sectElem]['SECTIONS'])){
                $arNews[$sectElem]['SECTIONS'][] = $ar_res_prod['IBLOCK_SECTION_ID'];
            }
        }
    }

    $arResult['NEWS'] = $arNews;
    $arResult['PRODUCT_SECTIONS'] = $arProductSections;
    $arResult['PRODUCTS'] = $arProducts;
    $arResult['COUNT_PRODUCTS'] = count($arProducts);
    $arResult['MAX_PRICE'] = $maxPrice;
    $arResult['MIN_PRICE'] = $minPrice;

    $this->SetResultCacheKeys(array(
        "COUNT_PRODUCTS",
        "MAX_PRICE",
        "MIN_PRICE",
    ));
    $this->IncludeComponentTemplate();
}

$APPLICATION->SetPageProperty('title', GetMessage('COUNT_PROD')  . $arResult['COUNT_PRODUCTS']);
$APPLICATION->SetPageProperty('h1', GetMessage('ELEMS')  . $arResult['COUNT_PRODUCTS']);
$APPLICATION->SetPageProperty('add_simpcomp', '<div style="color:red; margin: 34px 15px 35px 15px">'. GetMessage('MAX_PRICE_MES') .
    $arResult['MAX_PRICE'] . '</br>' . GetMessage('MIN_PRICE_MES').  $arResult['MIN_PRICE'] . '</div>');