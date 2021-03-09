<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if(isset($arParams['SPECIAL_DATE_PARAM']) && $arParams['SPECIAL_DATE_PARAM'] == 'Y'){
    $arResult['SPECIAL_DATE'] = $arResult['ITEMS'][0]['ACTIVE_FROM'];
    $this->getComponent()->setResultCacheKeys(array('SPECIAL_DATE'));
}
