<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<!--<pre>--><?//var_dump($arResult)?><!--</pre>-->
<p>_ _ _</p>
<b><?=GetMessage('CATALOG')?></b>
<ul>
    <?
    foreach($arResult['NEWS'] as $newCont):
        $str = '';
        foreach($newCont['SECTIONS'] as $sectID){
            $str .= ', ' . $arResult['PRODUCT_SECTIONS'][$sectID]['SECTION_NAME'];
        }
        ?>
    <li><?='<b>' . $newCont['NEWS_NAME'] . '</b> - '. $newCont['DATE_ACTIVE_FROM'] . ' (' . substr($str,2) . ')'?>
        <ul>
            <?foreach($newCont['PRODUCTS'] as $prodID):?>
            <li>
                <?=$arResult['PRODUCTS'][$prodID]['PROD_NAME'] .  ' - '. $arResult['PRODUCTS'][$prodID]['PRICE']
                .  ' - '. $arResult['PRODUCTS'][$prodID]['MATERIAL'] .  ' - '. $arResult['PRODUCTS'][$prodID]['ARTNUMBER']?>
            </li>
            <?endforeach;?>
        </ul>
    </li>
    <?endforeach;?>

</ul>
