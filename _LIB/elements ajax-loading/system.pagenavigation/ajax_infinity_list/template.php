<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?CJSCore::Init(array("jquery"));?>
<?
$arResult["NavQueryString"] = str_replace('&amp;','&',$arResult["NavQueryString"]);
$do = preg_match('/.*bxajaxid=(\S+).*/',$arResult["NavQueryString"],$bxajaxid);
?>
<script>
	var ajax_nav = <?=CUtil::PhpToJSObject($arResult)?>;
	var bxajaxid = "<?=$bxajaxid[1]?>";
</script>
<?
if(!$do)
{
	?>
	<div id ='ajax_nav'>
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
		BX.ready(
			function()
			{
				$(window).scroll(
					function()
					{
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
									$('#ajax_nav').addClass('bx-core-waitwindow'); 
									$.get(url,function(data)
										{
											$('#ajax_nav').removeClass('bx-core-waitwindow');
											bxajaxid = $('#ajax_nav').before(data);
											ajax_sent = false;
										});

								}else if (ajax_sent == false)
								{
									ajax_sent = true;
									$('#ajax_nav').addClass('bx-core-waitwindow');
									$.get(url,function(data)
										{
											$('#ajax_nav').removeClass('bx-core-waitwindow');
											bxajaxid = $('#ajax_nav').before(data);
											ajax_sent = false;											
										});
								}
							}
						}
					});
			});
	</script>
<?}?>