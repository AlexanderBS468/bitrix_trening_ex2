<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Helpik");
?>&lt;?if (isset($arResul['PROPERTIES']['DOC']['VALUE'])):?&gt;<br>
 &lt;div class="exam-review-doc"&gt;<br>
 &nbsp; &nbsp; &nbsp; &nbsp;&lt;p&gt;&lt;?=$arResult['PROPERTIES']['DOC']['NAME']?&gt;:&lt;/p&gt;<br>
 &nbsp; &nbsp; &nbsp; &nbsp; &lt;?foreach($arResult['PROPERTIES']['DOC']['VALUE'] as $arElem):?&gt;<br>
 &nbsp; &nbsp; &nbsp; &nbsp; &lt;?<br>
 &nbsp; &nbsp; &nbsp; &nbsp; $arDoc = CFile::GetByID($arElem);<br>
 &nbsp; &nbsp; &nbsp; &nbsp; $doc = $arDoc-&gt;GetNext();<br>
 &nbsp; &nbsp; &nbsp; &nbsp; $name_file = current(explode('.', $doc['ORIGINAL_NAME']));<br>
 &nbsp; &nbsp; &nbsp; &nbsp; ?&gt;<br>
 &nbsp; &nbsp; &nbsp; &nbsp; &lt;div class="exam-review-item-doc"&gt;<br>
 &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &lt;img class="rew-doc-ico"&gt; src="&lt;?=SITE_TEMPLATE_PATH?&gt;/img/icons/pdf_ico_40.pdf"&gt;<br>
 &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &lt;a href="&lt;?=CFile::GetPath($arElem)?&gt;"&gt;&lt;?=$name_file?&gt;&lt;/a&gt;<br>
 &nbsp; &nbsp; &nbsp; &nbsp; &lt;/div&gt;<br>
 &lt;?endforeach;?&gt;<br>
 &lt;/div&gt;<br>
 &lt;?endif;?&gt;<br>
 <br>
 -----------------------------<br>
 &lt;?if($arItem["PREVIEW_PICTURE"] == NULL):?&gt;<br>
 &nbsp; &nbsp; &nbsp;&lt;img<br>
 &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp; &nbsp;border="0"<br>
 &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp; &nbsp;src="&lt;?=SITE_TEMPLATE_PATH?&gt;/img/no_photo_left_block.jpg"<br>
 &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp; &nbsp;alt="no_photo"<br>
 &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp; &nbsp;title="no_photo"<br>
 &nbsp; &nbsp; &nbsp;/&gt;<br>
 &lt;?else:?&gt;<br>
 &nbsp; &nbsp; &nbsp;&lt;?<br>
 &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;$file = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE'], array('width'=&gt;49, 'height'=&gt;49), BX_RESIZE_IMAGE_PROPORTIONAL, true);<br>
 &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp; &nbsp;$img_resize = '&lt;img src="' . $file['src'] . '" width="' . $file['WIDTH'] . '" height="' . $file['HEIGHT'] . '" /&gt;';<br>
 &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp; &nbsp;echo "$img_resize";<br>
 &nbsp; &nbsp; &nbsp;?&gt;<br>
 &lt;?endif;?&gt;<br>
 ------------------------------------<br>
 select * from b_event order by id desc<br>
 ------------------------------------<br>
 &lt;a href="&lt;?echo $APPLICATION-&gt;GetCurPageParam("logout=yes", array(<pre>     "login",
     "logout",
     "register",
     "forgot_password",
     "change_password"));?&gt;"&gt;Закончить сеанс (logout)&lt;/a&gt;
</pre> -----------------------------------<br>
 &lt;script src="&lt;?CUtil::GetAdditionalFileUrl(SITE_TEMPLATE_PATH.'/js/script.js')?&gt;"&gt;&lt;/script&gt;<br><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>