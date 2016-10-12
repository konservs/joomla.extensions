<?php
function brill_datediapason($date1,$date2){
	$d1=(int)$date1->format('d');
	$m1=(int)$date1->format('m');
	$y1=(int)$date1->format('Y');

	$d2=(int)$date2->format('d');
	$m2=(int)$date2->format('m');
	$y2=(int)$date2->format('Y');

	if($y1!=$y2)
		return $d1.'.'.$m1.'.'.$y1.'&nbsp;&mdash; '.$d2.'.'.$m2.'.'.$y2;
	if($m1!=$m2)
		return $d1.'&nbsp;'.JText::_('MF_'.$m1).'&nbsp;&mdash; '.$d2.'&nbsp;'.JText::_('MF_'.$m2).' '.$y2;
	return $d1.'&nbsp;&mdash; '.$d2.' '.JText::_('MF_'.$m2).' '.$y2;
	}
