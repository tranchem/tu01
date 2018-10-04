<?php
include('../libs/bootstrap.php');
$xp=new XTemplate('../views/ticket.html');
$sqlZone = "SELECT * FROM zone_group WHERE 1=1";
$zoneList = $db->fetchAll($sqlZone);

$sqlPlace = "SELECT zone.zone_id, zone_name, zone_group_name,adult_price,children_price,weekend FROM zone
			INNER JOIN zone_group ON zone.zone_group_id=zone_group.zone_group_id
			INNER JOIN price ON zone.zone_id=price.zone_id
			WHERE 1=1";
$placeList = $db->fetchAll($sqlPlace);

$places=$_POST['places'];
$adultNum=$_POST['adultNum'];
$childrenNum=$_POST['childrenNum'];
$currentDate = date('Y-m-d');
$ticketDate=date('Y-m-d',strtotime($_POST['ticketDate']));
$ticketDay=date('D',strtotime($ticketDate));
$count=1;
$totalPrice=0;


foreach($places as $place)
{
	foreach($placeList as $placeRow)
	{
		if($placeRow['zone_id']==$place)
		{
			if($ticketDay=='Sun'||$ticketDay=='Sat')
			{
				$totalAdultPrice=$adultNum*$placeRow['adult_price']*(100+$placeRow['weekend'])/100;
				$totalChildrenPrice=$childrenNum*$placeRow['children_price']*(100+$placeRow['weekend'])/100;
			}
			else
			{
				$totalAdultPrice=$adultNum*$placeRow['adult_price'];
				$totalChildrenPrice=$childrenNum*$placeRow['children_price'];
			}
			$placeRow['total_adult_price']=$totalAdultPrice;
			$placeRow['total_children_price']=$totalChildrenPrice;
			$placeRow['no']=$count;
			$xp->insert_loop('TICKET.CONFIRMPLACE',array('CONFIRMPLACE'=>$placeRow));
			$totalPrice=$totalPrice+$totalChildrenPrice+$totalAdultPrice;
		}
	}
	$count++;

}


echo $xp->text('TICKET.CONFIRMPLACE');

echo "---{$totalPrice}";
