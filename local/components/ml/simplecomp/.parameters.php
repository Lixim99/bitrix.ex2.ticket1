<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentParameters = array(
    'PARAMETERS' => array(
        'PRODUCTS_IBLOCK_ID' => array(
            'NAME' => GetMessage('PRODUCTS_IBLOCK_ID_MES'),
            'TYPE' =>'STRING'
        ),
        'NEWS_IBLOCK_ID' => array(
            'NAME' => GetMessage('NEWS_IBLOCK_ID_MES'),
            'TYPE' =>'STRING'
        ),
        'USER_CODE_PROD' => array(
            'NAME' => GetMessage('USER_CODE_PROD_MES'),
            'TYPE' =>'STRING'
        ),
        "CACHE_TIME"  =>  Array("DEFAULT"=>36000000),
    ),
);
