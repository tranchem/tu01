<?php
$xp=new XTemplate('views/ticket.html');
require_once('libs/userstatus.php');

$adultNum=0;
$childrenNum=0;

$sql1 = "SELECT booking_id FROM booking ORDER BY booking_id DESC LIMIT 1";
$result = $db->fetchOne($sql1);
$id_booking=substr($result["booking_id"],1,3);
if($id_booking<9){
	$booking_id = "B00".($id_booking + 1);
}else{
	if($id_booking<99)
	{
		$booking_id = "B0".($id_booking + 1);
	}else
	{
		$booking_id = "B".($id_booking + 1);
	}
}


$sqlZone = "SELECT * FROM zone_group WHERE 1=1";
$zoneList = $db->fetchAll($sqlZone);

$sqlPlace = "SELECT zone.zone_id, zone_name, zone_group_name,price_id,adult_price,children_price,weekend FROM zone
			INNER JOIN zone_group ON zone.zone_group_id=zone_group.zone_group_id
			INNER JOIN price ON zone.zone_id=price.zone_id
			WHERE 1=1";
$placeList = $db->fetchAll($sqlPlace);


$currentDate = date('Y-m-d');

$do_book=1;
$totalPrice=0;


if(isset($_POST['bookTicket']))
{
	$adultNum=$_POST['txtAdultCount'];
	$childrenNum=$_POST['txtChildrenCount'];
	
	
	$ticketDate=date('Y-m-d',strtotime($_POST['txtDate']));
	$ticketDay=date('D',strtotime($ticketDate));
	
	if(!empty($_POST['txtPlaces']))
	{
		$places=$_POST['txtPlaces'];
	}
	else
	{
		$xp->assign('place_err','You must select at least a zone');
		$do_book=-1;
	}
	
	if($ticketDate<$currentDate)
	{
		$xp->assign('date_err','Your ticket must be in the future!');
		$do_book=-1;
	}
	
	if($do_book==1)
	{	
		$sqlBooking="insert into booking (booking_id, user_name, booking_date, ticket_date, number_of_adults, number_of_child, booking_status) values ('{$booking_id}','{$_SESSION['user_name']}','{$currentDate}', '{$ticketDate}','{$adultNum}','{$childrenNum}','booked')";
		$db->execSQL($sqlBooking);

		foreach($places as $place)
		{
			foreach($placeList as $placeRow)
			{
				if($placeRow['zone_id']==$place)
				{
					if($ticketDay=='Sun'||$ticketDay=='Sat')
					{
						$totalZonePrice=$adultNum*$placeRow['adult_price']*(100+$placeRow['weekend'])/100+$childrenNum*$placeRow['children_price']*(100+$placeRow['weekend'])/100;
					}
					else
					{
						$totalZonePrice=$adultNum*$placeRow['adult_price']+$childrenNum*$placeRow['children_price'];
					}

					$totalPrice=$totalPrice+$totalZonePrice;

					$sqlBookingDetail="insert into booking_detail(booking_id,price_id,sub_total) values ('{$booking_id}','{$placeRow['price_id']}','{$totalZonePrice}')";

					$db->execSQL($sqlBookingDetail);
				}
			}
		}

		$sqlPrice="UPDATE booking SET total={$totalPrice} WHERE booking_id='{$booking_id}'";
		$db->execSQL($sqlPrice);
		$f->redir($baseUrl.'/?m=account&a=yourticket');
	}

	$xp->assign('ticketDate',$ticketDate);
}

$xp->assign('baseUrl',$baseUrl);
$xp->parse('TICKET');
$acontent=$xp->text('TICKET');