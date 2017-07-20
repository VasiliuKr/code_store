<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?CJSCore::Init(array("jquery"));?>

<?
$arResult["NavQueryString"] = str_replace('&amp;','&',$arResult["NavQueryString"]);
$do = preg_match('/.*bxajaxid=(\S+).*/',$arResult["NavQueryString"],$bxajaxid);
?>

<script>
	var ajax_nav = <?=CUtil::PhpToJSObject($arResult)?>;
	var bxajaxid = "<?=$bxajaxid[1]?>";
	jQuery(document).ready(function($) {
		$('.ajax_nav_origin').remove();
	});
</script>
<?
if(true)
{
	?>
	<div id ='ajax_nav'>
		<?if($arResult["NavPageNomer"] < $arResult["NavPageCount"]):?>
			<button class="button show-more-items">Больше предложений</button>
		<?endif;?>

		<?
		if(!$arResult["NavShowAlways"])
		{
			if ($arResult["NavRecordCount"] == 0 || ($arResult["NavPageCount"] == 1 && $arResult["NavShowAll"] == false))
				return;
		}
		?>
		<div class="modern-page-navigation pager__widget">
		<?

		$strNavQueryString = ($arResult["NavQueryString"] != "" ? $arResult["NavQueryString"]."&amp;" : "");
		$strNavQueryStringFull = ($arResult["NavQueryString"] != "" ? "?".$arResult["NavQueryString"] : "");
		?>
			<?/*<span class="modern-page-title"><?=GetMessage("pages")?></span>*/?>
		<?

		if($arResult["bDescPageNumbering"] === true):
			$bFirst = true;
			if ($arResult["NavPageNomer"] < $arResult["NavPageCount"]):
				if($arResult["bSavePage"]):
		?>

					<a class="modern-page-previous prev" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]+1)?>">Назад</a>
		    <div class="pages">
		<?
				else:
					if ($arResult["NavPageCount"] == ($arResult["NavPageNomer"]+1) ):
		?>

					<a class="modern-page-previous prev" href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>">Назад</a>
		        <div class="pages">
		<?
					else:
		?>

					<a class="modern-page-previous prev" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]+1)?>">Назад</a>
		       <div class="pages">
		            <?
					endif;
				endif;?>

				<?if ($arResult["nStartPage"] < $arResult["NavPageCount"]):
					$bFirst = false;
					if($arResult["bSavePage"]):
		?>
					<a class="modern-page-first" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["NavPageCount"]?>">1</a>
		<?
					else:
		?>
					<a class="modern-page-first" href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>">1</a>
		<?
					endif;
					if ($arResult["nStartPage"] < ($arResult["NavPageCount"] - 1)):
		/*?>
					<span class="modern-page-dots">...</span>
		<?*/
		?>	
					<a class="modern-page-dots" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=intVal($arResult["nStartPage"] + ($arResult["NavPageCount"] - $arResult["nStartPage"]) / 2)?>">...</a>
		<?
					endif;
				endif;
			endif;
			do
			{
				$NavRecordGroupPrint = $arResult["NavPageCount"] - $arResult["nStartPage"] + 1;
				
				if ($arResult["nStartPage"] == $arResult["NavPageNomer"]):
		?>
				<span class="<?=($bFirst ? "modern-page-first " : "")?>modern-page-current"><?=$NavRecordGroupPrint?></span>
		<?
				elseif($arResult["nStartPage"] == $arResult["NavPageCount"] && $arResult["bSavePage"] == false):
		?>
				<a href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>" class="<?=($bFirst ? "modern-page-first" : "")?>"><?=$NavRecordGroupPrint?></a>
		<?
				else:
		?>
				<a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["nStartPage"]?>"<?
					?> class="<?=($bFirst ? "modern-page-first" : "")?>"><?=$NavRecordGroupPrint?></a>
		<?
				endif;
				
				$arResult["nStartPage"]--;
				$bFirst = false;
			} while($arResult["nStartPage"] >= $arResult["nEndPage"]);
			
			if ($arResult["NavPageNomer"] > 1):
				if ($arResult["nEndPage"] > 1):
					if ($arResult["nEndPage"] > 2):
		/*?>
				<span class="modern-page-dots">...</span>
		<?*/
		?>
				<a class="modern-page-dots" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=round($arResult["nEndPage"] / 2)?>">...</a>
		<?
					endif;
		?>
				<a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=1"><?=$arResult["NavPageCount"]?></a>
		<?
				endif;
			
		?>
		       </div>
				<a class="modern-page-next next"href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]-1)?>">Дальше</a>

		<?
			endif; 

		else:
			$bFirst = true;

			if ($arResult["NavPageNomer"] > 1):
				if($arResult["bSavePage"]):
		?>
					<a class="modern-page-previous " href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]-1)?>">Назад</a>
		            <div class="pages">
		<?
				else:
					if ($arResult["NavPageNomer"] > 2):
		?>
					<a class="modern-page-previous prev" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]-1)?>">Назад</a>
		                <div class="pages">
		<?
					else:
		?>
					<a class="modern-page-previous prev" href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>">Назад</a>
		            <div class="pages">
		<?
					endif;
				
				endif;
				
				if ($arResult["nStartPage"] > 1):
					$bFirst = false;
					if($arResult["bSavePage"]):
		?>
					<a class="modern-page-first" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=1">1</a>
		<?
					else:
		?>
					<a class="modern-page-first" href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>">1</a>
		<?
					endif;
					if ($arResult["nStartPage"] > 2):
		/*?>
					<span class="modern-page-dots">...</span>
		<?*/
		?>
					<a class="modern-page-dots" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=round($arResult["nStartPage"] / 2)?>">...</a>
		<?
					endif;
				endif;
		        elseif($arResult["NavPageNomer"] == 1):
		            echo "<div class='pages'>";
			endif;

			do
			{
				if ($arResult["nStartPage"] == $arResult["NavPageNomer"]):
		?>
				<span class="<?=($bFirst ? "modern-page-first " : "")?>modern-page-current"><?=$arResult["nStartPage"]?></span>
		<?
				elseif($arResult["nStartPage"] == 1 && $arResult["bSavePage"] == false):
		?>
				<a href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>" class="<?=($bFirst ? "modern-page-first" : "")?>"><?=$arResult["nStartPage"]?></a>
		<?
				else:
		?>
				<a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["nStartPage"]?>"<?
					?> class="<?=($bFirst ? "modern-page-first" : "")?>"><?=$arResult["nStartPage"]?></a>
		<?
				endif;
				$arResult["nStartPage"]++;
				$bFirst = false;
			} while($arResult["nStartPage"] <= $arResult["nEndPage"]);
			
			if($arResult["NavPageNomer"] < $arResult["NavPageCount"]):
				if ($arResult["nEndPage"] < $arResult["NavPageCount"]):
					if ($arResult["nEndPage"] < ($arResult["NavPageCount"] - 1)):
		/*?>
				<span class="modern-page-dots">...</span>
		<?*/
		?>
				<a class="modern-page-dots" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=round($arResult["nEndPage"] + ($arResult["NavPageCount"] - $arResult["nEndPage"]) / 2)?>">...</a>
		<?
					endif;
		?>
				<a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["NavPageCount"]?>"><?=$arResult["NavPageCount"]?></a>
		<?
				endif;
		?>
		            </div>
				<a class="modern-page-next next" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]+1)?>">Дальше</a>
		<?
			endif;
		endif;?>

		</div>

		<? if ($arResult["bShowAll"]):
			if ($arResult["NavShowAll"]):
		?>
				<a class="modern-page-pagen" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>SHOWALL_<?=$arResult["NavNum"]?>=0"><?=GetMessage("nav_paged")?></a>
		<?
			else:
		?>
				<a class="modern-page-all" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>SHOWALL_<?=$arResult["NavNum"]?>=1"><?=GetMessage("nav_all")?></a>
		<?
			endif;
		endif
		?>

		</div>
		


	</div>
	<script type="text/javascript">
		/* isset for javascript */
		window.isset = function()
		{
			if (arguments.length===0) return false;
			var buff=arguments[0];
			for (var i=0; i<arguments.length; i++)
			{
				if (typeof(buff)==='undefined') return false;
				buff = buff[arguments[i+1]];
			}
			return true;
		}
		$(document).ready(
			function()
			{
				// $(window).scroll(         // ленивая подгрузка элементов
				$(".show-more-items").click(			// подгрузка элементов по клику

					function()
					{	
						$('#ajax_nav').addClass('ajax_nav_origin');
						if($(window).scrollTop()+$(window).height()>=$('#ajax_nav').offset().top)
						{
							if (ajax_nav.NavPageCount > ajax_nav.NavPageNomer )
							{
								if(bxajaxid.length == "")
								{
									bxajaxid = $('#ajax_nav').parents("div[id*='comp_']").attr('id').replace('comp_','');
									
									url = location.pathname+'?PAGEN_'+ajax_nav.NavNum+'='+(parseInt(ajax_nav.NavPageNomer)+1)+'&bxajaxid='+bxajaxid+'&'+ ajax_nav.NavQueryString
								}
								else url = location.pathname+'?PAGEN_'+ajax_nav.NavNum+'='+(parseInt(ajax_nav.NavPageNomer)+1)+'&'+ ajax_nav.NavQueryString;
								
								if (!isset(window, "ajax_sent")) //&& )
								{
									ajax_sent = true;
									
									$('#ajax_nav').addClass('bg_container_spinner'); 
									$.get(url,function(data)
										{

											$('#ajax_nav').removeClass('bg_container_spinner');
											
											bxajaxid = $('#ajax_nav').before(data);
																					
											ajax_sent = false;
											
										});
									

								}else if (ajax_sent == false)
								{
									ajax_sent = true;

									$('#ajax_nav').addClass('bg_container_spinner');
									$.get(url,function(data)
										{

											$('#ajax_nav').removeClass('bg_container_spinner');
											bxajaxid = $('#ajax_nav').before(data);
											
											ajax_sent = false;

										});
									if (ajax_nav.NavPageCount = ajax_nav.NavPageNomer) {
										$(".show-more-items").remove();
									}
								}
							}
							
						}
						
					});
			
			});
	</script>
<?}?>