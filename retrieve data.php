<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php
error_reporting(0);
Header("Content-type: text/xml"); 
$com_sym=$_GET["input"];
$url_front="http://query.yahooapis.com/v1/public/yql?q=Select%20Name%2C%20Symbol%2C%20LastTradePriceOnly%2C%20Change%2C%20ChangeinPercent%2C%20PreviousClose%2C%20DaysLow%2C%20DaysHigh%2C%20Open%2C%20YearLow%2C%20YearHigh%2C%20Bid%2C%20Ask%2C%20AverageDailyVolume%2C%20OneyrTargetPrice%2C%20MarketCapitalization%2C%20Volume%2C%20Open%2C%20YearLow%20from%20yahoo.finance.quotes%20where%20symbol%3D%22";
$url_end="%22&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys";
$url=$url_front.$com_sym.$url_end;
$xml=simplexml_load_file($url);
$xmlformat="";
if($xml->children()->children()->children()->Change[0]=="")
$xmlformat="<result><Name></Name><Symbol></Symbol><Quote><ChangeType></ChangeType><Change></Change><ChangeInPercent></ChangeInPercent><LastTradePriceOnly></LastTradePriceOnly><PreviousClose></PreviousClose><DaysLow></DaysLow><DaysHigh></DaysHigh><Open></Open><YearLow></YearLow><YearHigh></YearHigh><Bid></Bid><Volume></Volume><Ask></Ask><AverageDailyVolume></AverageDailyVolume><OneYearTargetPrice></OneYearTargetPrice><MarketCapitalization></MarketCapitalization></Quote>";
else
{
	$name=$xml->children()->children()->children()->Name[0];

	$symbol=$xml->children()->children()->children()->Symbol[0];
	
	if($xml->children()->children()->children()->Change[0]>=0){$changetype="+";$change=(double)$xml->children()->children()->children()->Change[0];$changeinpercent=(double)$xml->children()->children()->children()->ChangeinPercent[0];}
	if($xml->children()->children()->children()->Change[0]<0){$changetype="-";$change=0-(double)$xml->children()->children()->children()->Change[0];$changeinpercent=0-(double)$xml->children()->children()->children()->ChangeinPercent[0];}
	
	$change=number_format($change,2,".",",");
	$changeinpercent=number_format($changeinpercent,2,".",",");
	$lasttradeprice=number_format((double)$xml->children()->children()->children()->LastTradePriceOnly[0],2,".",",");
	$previousclose=number_format((double)$xml->children()->children()->children()->PreviousClose[0],2,".",",");
	$daylow=number_format((double)$xml->children()->children()->children()->DaysLow[0],2,".",",");
	$dayhigh=number_format((double)$xml->children()->children()->children()->DaysHigh[0],2,".",",");
	$open=number_format((double)$xml->children()->children()->children()->Open[0],2,".",",");
	$yearlow=number_format((double)$xml->children()->children()->children()->YearLow[0],2,".",",");
	$yearhigh=number_format((double)$xml->children()->children()->children()->YearHigh[0],2,".",",");
	$bid=number_format((double)$xml->children()->children()->children()->Bid[0],2,".",",");
	$volume=number_format((double)$xml->children()->children()->children()->Volume[0],0,".",",");
	$ask=number_format((double)$xml->children()->children()->children()->Ask[0],2,".",",");
	$averagedailyvolume=number_format((double)$xml->children()->children()->children()->AverageDailyVolume[0],0,".",",");
	$oneyeartargetprice=number_format((double)$xml->children()->children()->children()->OneyrTargetPrice[0],2,".",",");
	$arr=str_split($xml->children()->children()->children()->MarketCapitalization[0]);
	$pic=$arr[count($arr)-1];
	$marketcapitalization=number_format((double)$xml->children()->children()->children()->MarketCapitalization[0],1,".",",").$pic;
	
	$xmlformat="<result><Name>".str_replace('&','&amp;',htmlentities($name,ENT_QUOTES,'UTF-8'))."</Name><Symbol>".$symbol."</Symbol><Quote><ChangeType>".$changetype."</ChangeType><Change>".$change."</Change><ChangeInPercent>".$changeinpercent."%"."</ChangeInPercent><LastTradePriceOnly>".$lasttradeprice."</LastTradePriceOnly><PreviousClose>".$previousclose."</PreviousClose><DaysLow>".$daylow."</DaysLow><DaysHigh>".$dayhigh."</DaysHigh><Open>".$open."</Open><YearLow>".$yearlow."</YearLow><YearHigh>".$yearhigh."</YearHigh><Bid>".$bid."</Bid><Volume>".$volume."</Volume><Ask>".$ask."</Ask><AverageDailyVolume>".$averagedailyvolume."</AverageDailyVolume><OneYearTargetPrice>".$oneyeartargetprice."</OneYearTargetPrice><MarketCapitalization>".$marketcapitalization."</MarketCapitalization></Quote>";
}

$new_url_front="http://feeds.finance.yahoo.com/rss/2.0/headline?s=";
$new_url_end="&region=US&lang=en-US";
$new_url=$new_url_front.$com_sym.$new_url_end;
$new_xml=simplexml_load_file($new_url);
if($new_xml->children()->children()->title[0]=="Yahoo! Finance: RSS feed not found")$xmlformat=$xmlformat."<News></News>";
else
{   $xmlformat=$xmlformat."<News>";
	foreach($new_xml->children()->children()->item as $child)
	{$xmlformat=$xmlformat."<Item><Title>".htmlentities( $child->title[0], ENT_QUOTES, 'UTF-8')."</Title><Link>".htmlentities( $child->link[0], ENT_QUOTES, 'UTF-8')."</Link></Item>";}
	$xmlformat=$xmlformat."</News>";
}
	$charturl="http://chart.finance.yahoo.com/t?s=".$com_sym."&amp;lang=en-US&amp;amp;width=300&amp;height=180";
    $xmlformat=$xmlformat."<StockChartImageURL>".$charturl."</StockChartImageURL>";
    $xmlformat=$xmlformat."</result>";

echo $xmlformat;
?>

