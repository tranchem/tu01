<?php
include('../libs/bootstrap.php');
$xp=new XTemplate('../views/ticket.html');
$sqlZone = "SELECT * FROM zone_group WHERE 1=1";
$zoneList = $db->fetchAll($sqlZone);

$sqlPlace = "SELECT zone.zone_id, zone_name, zone_group_name, avatar_img,adult_price,children_price,weekend FROM zone
			INNER JOIN zone_group ON zone.zone_group_id=zone_group.zone_group_id
			INNER JOIN price ON zone.zone_id=price.zone_id
			WHERE 1=1";
$placeList = $db->fetchAll($sqlPlace);

$currentDate = date('Y-m-d');

$ticketDate=date('Y-m-d',strtotime($_POST['ticketDate']));
$ticketDay=date('D',strtotime($ticketDate));

foreach($zoneList as $zoneRow)
{
	$zoneRowName=ucwords($zoneRow['zone_group_name']);
	$xp->assign('zone_group_name',$zoneRowName);
	
	foreach($placeList as $placeRow)
	{
		if($placeRow['zone_group_name']==$zoneRow['zone_group_name'])
		{
			if($ticketDay=='Sun'||$ticketDay=='Sat')
			{
				$placeRow['adult_price']+=$placeRow['adult_price']*$placeRow['weekend']/100;
				$placeRow['children_price']+=$placeRow['children_price']*$placeRow['weekend']/100;
			}
			$xp->insert_loop('TICKET.ZONEGROUP.ZONE',array('ZONE'=>$placeRow));
		}
	}

	$xp->parse('TICKET.ZONEGROUP');
	
}
echo $xp->text('TICKET.ZONEGROUP');
