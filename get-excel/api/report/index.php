<?php 
http_response_code(200);
header('Content-Type: application/json');
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require_once('../../../config.php');

if(!(isset($_GET['access_token']) && $_GET['access_token'] == 'Nh4oLvm0dfm5W6xwjqhkg2cbfyf5hsJa')) {
	echo json_encode(['error' => 401]);
	die();
}

$programs_db = getPrograms();
$medios = getMedios();
$fields = getReporteFields();

$mysqli->close();

require_once __DIR__.'/../../inc/functions.php';

$date_from = date("Y-m").'-01';
$date_to = date("Y-m-d");

$talents = get_reporte($programs_db, $date_from, $date_to);
//echo $talents; die;
$talents = !empty($talents) ? json_decode($talents, true) : [];

//echo json_encode($talents); die;

//$talents = json_decode('[]', true);

//print_r($talents); die;

//print_r($talents);

$rrss_list = ['instagram', 'facebook', 'tiktok'];

$return_data = [];

foreach ($rrss_list as $rrss) {

	$return_data[$rrss] = [];

    $metricas  = [];

    switch ($rrss) {
        case 'instagram':
            $metricas = [
                'm1' => [
                    'title' => 'Impresiones',
                    'field' => 'impressions'
                ],
                'm2' => [
                    'title' => 'Likes',
                    'field' => 'likes'
                ],
                'm3' => [
                    'title' => 'Interacciones',
                    'field' => 'interactions'
                ]
            ];
            break;
        case 'facebook':
            $metricas = [
                'm1' => [
                    'title' => 'Impresiones',
                    'field' => 'impressions'
                ],
                'm2' => [
                    'title' => 'Alcance',
                    'field' => 'impressionsUnique'
                ],
                'm3' => [
                    'title' => 'Reacciones',
                    'field' => 'reactions'
                ]
            ];
            break;
        case 'tiktok':
            $metricas = [
                'm1' => [
                    'title' => 'Visualizaciones',
                    'field' => 'viewCount'
                ],
                'm2' => [
                    'title' => 'Likes',
                    'field' => 'likeCount'
                ],
                'm3' => [
                    'title' => 'Compartidos',
                    'field' => 'shareCount'
                ]
            ];
            break;
        
        default:
            // code...
            break;
    }

    switch ($rrss) {
        case 'facebook':
            usort($talents, 'arrayOrderByFacebookImpressions');
            break;
        case 'tiktok':
            usort($talents, 'arrayOrderByTiktokViewCount');
            break;    
        default:
            // code...
            break;
    }
                 
    $count = 1;
    foreach ($talents as $talent) {
    	$return_data[$rrss][] = [
    		'position' => $count++,
    		'talent' => $talent['title'].' ('.$talent['hashtag'].')',
    		$metricas['m1']['field'] => $talent['analytics'][$rrss][$metricas['m1']['field']],
    		$metricas['m2']['field'] => $talent['analytics'][$rrss][$metricas['m2']['field']],
    		$metricas['m3']['field'] => $talent['analytics'][$rrss][$metricas['m3']['field']]
    	];
    }
}

$return_data = [
	'date_from' => $date_from,
	'date_to' => $date_to,
	'data' => $return_data
];

echo json_encode($return_data);
