<?php 

require_once('../config.php');
if(!isLogin()) {
	die('ERROR 401');
}

$programs_db = getPrograms();
$medios = getMedios();
$fields = getReporteFields();

$mysqli->close();

require_once __DIR__.'/inc/functions.php';
require_once __DIR__.'/inc/SimpleXLSXGen.php';

$date_from = $_GET['date-from'];
$date_to = $_GET['date-to'];

$date_from_format = date("d-m-Y", strtotime($date_from));
$date_to_format = date("d-m-Y", strtotime($date_to));

$programs = get_reporte($programs_db, $date_from, $date_to);
//echo $programs; die;
$programs = !empty($programs) ? json_decode($programs, true) : [];

//echo json_encode($programs); die;

//$programs = json_decode('[]', true);

//print_r($programs); die;

$xls_headers1 = ['<style font-size="11" bgcolor="#b5c6e6"><b>Tipo</b></style>', '<style font-size="11" bgcolor="#b5c6e6"><b>Talento</b></style>', '<style font-size="11" bgcolor="#b5c6e6"><b>Username</b></style>'];
$ban = true;
foreach ($fields as $key => $items) {
	$ban = !$ban;
	$xls_headers1[] = '<style font-size="11" bgcolor="#'.($ban?'b5c6e6':'cbd4e5').'"><center><b>'.lang($key).'</b></center></style>';	
	for ($i=0; $i < (count($items) - 1); $i++) { 
		$xls_headers1[] = null;
	}
}
$xls_headers2 = ['<style bgcolor="#b5c6e6"></style>', '<style bgcolor="#b5c6e6"></style>', '<style bgcolor="#b5c6e6"></style>'];
$ban = true;
foreach ($fields as $key => $items) {
	$ban = !$ban;
	foreach ($items as $key2 => $field) {
		$xls_headers2[] = '<style bgcolor="#'.($ban?'b5c6e6':'cbd4e5').'"><center><b>'.lang($key2).'</b></center></style>';
	}
}

$xls_medios = [];
foreach ($medios as $medio) {
	$xls_medios[] = $medio['name'];
}

$xls_gen = [
    ['<style font-size="12"><b>Talentos | Nación Media</b></style>'],
    ['Medios: '.implode(' - ', $xls_medios)."\n".'Redes Sociales: Instagram - Facebook - Tiktok'."\n".'Rango de fechas: '.$date_from_format.' al '.$date_to_format."\n".'Fecha del reporte: '.date('d-m-Y H:i')],
    [''],
    $xls_headers1,
    $xls_headers2
];

foreach ($programs as $key => $program) {
	$xls_value = [];
	$color = $program['color'];
	$xls_value[] = '<style bgcolor="'.$color.'">'.$program['type'].'</style>';
	$xls_value[] = '<style bgcolor="'.$color.'">'.$program['title'].'</style>';
	$xls_value[] = '<style bgcolor="'.$color.'">'.$program['hashtag'].'</style>';

	foreach ($program['analytics'] as $key2 => $analytics_rrss) {
		foreach ($analytics_rrss as $key3 => $value) {
			if($key3 != 'medios_posts' && $key3 != 'post_list') {
				$xls_value[] = '<style bgcolor="'.$color.'" nf="#,##0">'.$value.'</style>';
			}
		}
	}
	$xls_gen[] = $xls_value;
}

$xls_total = ['<b>TOTAL</b>', null, null];
$programs_count = count($programs);
foreach( range('D', 'X') as $element) { 
    $xls_total[] = '<style nf="#,##0"><b><f>SUM('.$element.'6:'.$element.($programs_count + 5).')</f></b></style>';
}
/* foreach( range('A', 'D') as $element) { 
    $xls_total[] = '<style nf="#,##0"><b><f>SUM(A'.$element.'6:A'.$element.($programs_count + 5).')</f></b></style>';
} */

$xls_gen[] = $xls_total;

$xlsx = Shuchkin\SimpleXLSXGen::fromArray( $xls_gen )
	->mergeCells('A1:X1')
	->mergeCells('A2:X2')
	->mergeCells('D4:K4')
	->mergeCells('L4:S4')
	->mergeCells('T4:X4')
	->mergeCells('A'.($programs_count + 5 + 1).':C'.($programs_count + 5 + 1))
	->setColWidth(1, 15)
	->setColWidth(2, 16)
	->setColWidth(3, 16)
	->setColWidth(4, 15)
	->setColWidth(5, 15)
	->setColWidth(6, 15)
	->setColWidth(7, 15)
	->setColWidth(8, 15)
	->setColWidth(9, 15)
	->setColWidth(10, 15)
	->setColWidth(11, 15)
	->setColWidth(12, 15)
	->setColWidth(13, 15)
	->setColWidth(14, 15)
	->setColWidth(15, 15)
	->setColWidth(16, 15)
	->setColWidth(17, 15)
	->setColWidth(18, 15)
	->setColWidth(19, 15)
	->setColWidth(20, 15)
	->setColWidth(21, 15)
	->setColWidth(22, 15)
	->setColWidth(23, 15)
	->setColWidth(24, 15)
	->setColWidth(25, 15);
$xlsx->downloadAs('Talentos de Nación Media '.$date_from_format.' al '.$date_to_format.'.xlsx');
