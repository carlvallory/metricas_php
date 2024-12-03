<?php
require_once('../../config.php');

if(!isLogin()) {
  die('Error 401');
}

require_once __DIR__.'/../inc/functions.php';

if(isset($_GET['posts_rrss']) && isset($_GET['posts_cache']) && isset($_GET['program']) && isset($_GET['date-from']) && isset($_GET['date-to'])) {

	$rrss = $_GET['posts_rrss'];
	$program_name = $_GET['program'];
	$program_hashtag = $_GET['program_hashtag'];

	$posts = hoypy_get_manual_cache_by_time($_GET['posts_cache'], 'reports/post_list', 1440);

	if($posts !== null) {
		$posts = json_decode($posts, true);
		$fields = getReporteFields();
		$medios = getMedios();

		$mysqli->close();

		$field_picture = null;
		$field_text = null;
		$field_url = null;
		$field_types = [];
		$metricas = [];
		switch ($rrss) {
			case 'instagram':
				$field_picture = 'imageUrl';
				$field_text = 'content';
				$field_url = 'url';

				$metricas = [
            'impressions' => 0,
            'likes' => 0,
            'interactions' => 0,
            'posts_count' => 0,
        ];

				$field_types = [
					'REELS_VIDEO' => $metricas,
					'FEED_IMAGE' => $metricas,
					'FEED_CAROUSEL_ALBUM' => $metricas,
				];
				break;
			case 'facebook':
				$field_picture = 'picture';
				$field_text = 'text';
				$field_url = 'link';

				$metricas = [
            'impressions' => 0,
            'impressionsUnique' => 0,
            'reactions' => 0,
            'posts_count' => 0,
        ];

				$field_types = [
					'video' => $metricas,
					'photo' => $metricas,
					'album' => $metricas,
				];
				break;
			case 'tiktok':
				$field_picture = 'coverImageUrl';
				$field_text = 'title';
				$field_url = 'shareUrl';

				$metricas = [
            'viewCount' => 0,
            'likeCount' => 0,
            'shareCount' => 0,
            'posts_count' => 0,
        ];

				$field_types = [
					'VIDEO' => $metricas,
				];
				break;
			default:
				break;
		}



		require_once __DIR__.'/../inc/SimpleXLSXGen.php';

		$date_from = $_GET['date-from'];
		$date_to = $_GET['date-to'];

		$date_from_format = date("d-m-Y", strtotime($date_from));
		$date_to_format = date("d-m-Y", strtotime($date_to));

		$total_fields_number_count = 0;

		$xls_headers1 = ['<style font-size="11" bgcolor="#b5c6e6"><b>Publicación</b></style>', '<center><style font-size="11" bgcolor="#b5c6e6"><b>Medio</b></style></center>'];
		foreach ($fields[$rrss] as $key => $value) {
			if($key != 'posts') {
				$xls_headers1[] = '<center><style font-size="11" bgcolor="#b5c6e6"><b>'.lang($key).'</b></style></center>';
				$total_fields_number_count++;
			}
		}
		$xls_headers1[] = '<center><style font-size="11" bgcolor="#b5c6e6"><b>Link</b></style></center>';

		$xls_medios = [];
		foreach ($medios as $medio) {
			$xls_medios[] = $medio['name'];
		}

		$xls_gen = [
		    ['<style font-size="12"><b>Talentos | Nación Media</b></style>'],
		    ["<wraptext>Programa: ".$program_name.' ('.$program_hashtag.')'."\n".'Medios: '.implode(' - ', $xls_medios)."\n".'Red Social: '.ucfirst($rrss)."\n".'Rango de fechas: '.$date_from_format.' al '.$date_to_format."\n".'Fecha del reporte: '.date('d-m-Y H:i')."</wraptext>"],
		    [''],
		    $xls_headers1
		];

		if(count($posts) > 0) {
			foreach ($posts as $key => $post) {
				$xls_value = [];
				$xls_value[] = "<wraptext>".$post[$field_text]."</wraptext>";
				$xls_value[] = '<middle><center>'.$medios[$post['medio']]['name'].'</center></middle>';
				foreach ($fields[$rrss] as $keyField => $field) {
					if($keyField != 'posts') {
						if($rrss == 'instagram' && isset($post['reelId']) && isset($post['reach']) && $keyField == 'impressions' && $post[$keyField] == 0) {
							$post[$keyField] = $post['reach'];
						}
						$xls_value[] = '<middle><style nf="#,##0">'.$post[$keyField].'</style></middle>';
					}
				}
				$xls_value[] = '<middle><center><a href="'.$post[$field_url].'">Abrir</a></center></middle>';

				$xls_gen[] = $xls_value;
			}
		}

		$posts_count = count($posts);

		/*
		$xls_total = ['<b>TOTAL</b>', null, null];
		$programs_count = count($programs);
		foreach( range('D', 'Z') as $element) { 
		    $xls_total[] = '<style nf="#,##0"><b><f>SUM('.$element.'6:'.$element.($programs_count + 5).')</f></b></style>';
		}
		foreach( range('A', 'D') as $element) { 
		    $xls_total[] = '<style nf="#,##0"><b><f>SUM(A'.$element.'6:A'.$element.($programs_count + 5).')</f></b></style>';
		}
		$xls_gen[] = $xls_total;
		*/

		$alphabet = range('A', 'Z');

		$xlsx = Shuchkin\SimpleXLSXGen::fromArray( $xls_gen )
			->mergeCells('A1:'.$alphabet[(2+$total_fields_number_count+1-1)].'1')
			->mergeCells('A2:'.$alphabet[(2+$total_fields_number_count+1-1)].'2')
			//->mergeCells('A'.($posts_count + 4 + 1).':C'.($posts_count + 4 + 1))
			//->setColWidth(1, 15)
			->setColWidth(2, 16)
			->setColWidth(3, 16)
			->setColWidth(4, 16)
			->setColWidth(5, 16)
			->setColWidth(6, 16)
			->setColWidth(7, 16)
			->setColWidth(8, 16)
			->setColWidth(9, 16)
			->setColWidth(10, 16)
			->setColWidth(11, 16)
			->setColWidth(12, 16)
			->setColWidth(13, 16)
			->setColWidth(14, 16);
		$xlsx->downloadAs('Talentos de Nación Media - '.$program_name.' - '.ucfirst($rrss).' '.$date_from_format.' al '.$date_to_format.'.xlsx');
		die;
	}
}
echo 'Ha ocurrido un error, inténtelo nuevamente.';