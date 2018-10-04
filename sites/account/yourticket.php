<?php

$xp= new XTemplate('views/account/yourticket.html');
require_once('libs/userstatus.php');
$xp->assign('baseUrl',$baseUrl);

//Ticket list without sorting
$sqlBooking="SELECT booking_id, user_name, booking_date, ticket_date, number_of_adults, number_of_child, total, booking_status FROM booking WHERE 1=1 AND user_name='{$_SESSION['user_name']}'";
$bookingList=$db->fetchAll($sqlBooking);
if(empty($bookingList))
{
	$xp->assign('baseUrl',$baseUrl);
	$xp->parse('YOURTICKET.ALERT');
	
}

$condition="";
$orderby=" ORDER BY ticket_date DESC";

if(isset($_GET['date'])&&isset($_GET['status']))
{
	$sortDate=$_GET['date'];
	$sortStatus=$_GET['status'];
	
	//Sort by ticket_date
	switch ($sortDate) {
		case "desc":
			$orderby=" ORDER BY ticket_date DESC";
			$xp->assign('date_desc','selected');
			break;
		case "asc":
			$orderby=" ORDER BY ticket_date ASC";
			$xp->assign('date_asc','selected');
			break;
		default:
			break;
	}

	//Sort by booking_status
	switch ($sortStatus) {
		case "all":
			$condition="";
			$xp->assign('status_all','selected');
			break;
		case "booked":
			$condition=" AND booking_status='booked' ";
			$xp->assign('status_booked','selected');
			break;
		case "cancelled":
			$condition=" AND booking_status='cancelled' ";
			$xp->assign('status_cancelled','selected');
			break;
		case "expired":
			$condition=" AND booking_status='expired' ";
			$xp->assign('status_expired','selected');
			break;
		default:
			break;
	}
}

if(!empty($bookingList))
{
	$xp->parse('YOURTICKET.SORT');
}

//Ticket list with sorting
$sqlBooking="SELECT booking_id, user_name, booking_date, ticket_date, number_of_adults, number_of_child, total, booking_status FROM booking WHERE 1=1 AND user_name='{$_SESSION['user_name']}' {$condition} {$orderby}";
$bookingList=$db->fetchAll($sqlBooking);

$sqlBookingDetail="SELECT booking_detail.booking_id, zone_name, adult_price, children_price, weekend, sub_total FROM booking_detail 
				   INNER JOIN booking ON booking.booking_id=booking_detail.booking_id
				   INNER JOIN price ON booking_detail.price_id=price.price_id
				   INNER JOIN zone ON price.zone_id=zone.zone_id
				   WHERE 1=1";
$bookingDetailList=$db->fetchAll($sqlBookingDetail);

$t = count($bookingList);
$l = 3;
$p = (isset($_GET['p']))?$_GET['p']:1;
$fs = ($p-1)*$l;
$sqlBooking = "SELECT booking_id, user_name, booking_date, ticket_date, number_of_adults, number_of_child, total, booking_status FROM booking WHERE 1=1 AND user_name='{$_SESSION['user_name']}' {$condition} {$orderby} LIMIT {$fs},{$l}";
$bookingList = $db->fetchAll($sqlBooking);


if(!empty($bookingList))
{
	foreach ($bookingList as $bookingRow)
	{

		$ticketDay=date('D',strtotime($bookingRow['ticket_date']));

		if($bookingRow['user_name']==$_SESSION['user_name'])
		{

			$xp->assign('number_of_adults',$bookingRow['number_of_adults']);
			$xp->assign('number_of_child',$bookingRow['number_of_child']);
			$xp->assign('booking_date',$bookingRow['booking_date']);
			$xp->assign('ticket_date',$bookingRow['ticket_date']);
			$xp->assign('total',$bookingRow['total']);
			$xp->assign('booking_status',$bookingRow['booking_status']);
			$xp->assign('booking_id',$bookingRow['booking_id']);

			if($bookingRow['booking_status']=='booked')
			{
				$xp->assign('ticket_color','#bedbe5');
				$xp->assign('font_color','black');
				$xp->assign('status','');
				$xp->parse('YOURTICKET.TICKET.CANCEL');
			}
			else if($bookingRow['booking_status']=='cancelled')
			{
				$xp->assign('ticket_color','#cecece');
				$xp->assign('font_color','#3d3d3d');
				$xp->assign('status','disabled');
			}
			else if($bookingRow['booking_status']=='expired')
			{
				$xp->assign('ticket_color','#ffaaaa');
				$xp->assign('font_color','#3d3d3d');
				$xp->assign('status','disabled');
			}

			foreach($bookingDetailList as $bookingDetailRow)
			{

				if($bookingDetailRow['booking_id']==$bookingRow['booking_id'])
				{

					if($ticketDay=='Sun'||$ticketDay=='Sat')
					{
						$bookingDetailRow['price_each_adult']=$bookingDetailRow['adult_price']*(100+$bookingDetailRow['weekend'])/100;
						$bookingDetailRow['price_each_child']=$bookingDetailRow['children_price']*(100+$bookingDetailRow['weekend'])/100;
					}
					else
					{
						$bookingDetailRow['price_each_adult']=$bookingDetailRow['adult_price'];
						$bookingDetailRow['price_each_child']=$bookingDetailRow['children_price'];
					}


					$xp->insert_loop('YOURTICKET.TICKET.TICKETDETAIL',array('TICKETDETAIL'=>$bookingDetailRow));
				}
			}
			
			$xp->parse('YOURTICKET.TICKET');
		}
	}
	if(isset($_GET['date'])&&isset($_GET['status']))
	{
		$url = $baseUrl."/?m=account&a=yourticket&date={$sortDate}&status={$sortStatus}";
	}
	else
	{
		$url = $baseUrl."/?m=account&a=yourticket";
	}
	$pagers = $f->getPagers($t,$l,$url);
	$xp->assign('pagers',$pagers);
	$xp->parse('YOURTICKET.PAGE');
	
}

//Cancel ticket
if(isset($_POST['btnCancel']))
{
	$ticket_id=$_POST['btnCancel'];
	foreach($bookingList as $bookingRow)
	{
		if($bookingRow['booking_id']==$ticket_id&&$bookingRow['booking_status']=='booked'&&$bookingRow['user_name']==$_SESSION['user_name'])
		{
			$sql="UPDATE booking SET booking_status='cancelled' WHERE booking_id='$ticket_id'";
			$db->execSQL($sql);
			$f->redir($baseUrl.'/?m=account&a=yourticket');
		}
	}
	
}


$xp->parse('YOURTICKET');
$acontent=$xp->text('YOURTICKET');