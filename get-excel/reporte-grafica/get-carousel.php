<?php 

require_once('../../config.php');
if(!isLogin()) {
    die('Error 401');
}

http_response_code(200);
header('Content-Type: application/json');
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$result = [
    'status' => 'error'
];

if(isset($_POST['date_from']) && isset($_POST['date_to']) && isset($_POST['rrss']) && isset($_POST['items_per_page'])) {

    $programs_db = getPrograms();
    $medios = getMedios();

    $mysqli->close();

    require_once __DIR__.'/../inc/functions.php';

    $date_from = $_POST['date_from'];
    $date_to = $_POST['date_to'];
    $rrss = $_POST['rrss'];
    $items_per_page = !empty($_POST['items_per_page']) ? $_POST['items_per_page'] : 10;

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

    $talents = get_reporte($programs_db, $date_from, $date_to);
    $talents = !empty($talents) ? json_decode($talents, true) : [];

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

    $html = '';

    $html .= '<div id="carouselRanking" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">';
                 
                $count = 1;
                $first_loop = true;
                $carousel_item_count = 0;
                $talents_desktop = array_chunk($talents, $items_per_page, true);
                foreach ($talents_desktop as $talents_desktop_column) { $carousel_item_count++;

                $html .= '<div class="carousel-item'.($first_loop ? ' active' : '').'">
                    <div class="list-column">';
                    $first_loop = false;
                        
                        $count_column = 0;
                        foreach ($talents_desktop_column as $talent) {
                            $html .= '<div class="gdh-main gdh-btn-posts" data-posts_title="'.$talent['title'].'" data-posts_username="'.$talent['hashtag'].'" data-posts_rrss="'.$rrss.'" data-posts_cache="'.$talent['analytics'][$rrss]['post_list'].'">
                                <div class="picture">
                                    <img src="pictures/'.str_replace('@', '', $talent['hashtag']).'.jpg" alt="'.$talent['title'].'" />
                                </div>
                                <div class="vm-gc1">
                                    <div class="now">'.$talent['title'].'</div>
                                    <div class="now username">'.$talent['hashtag'].'</span></div>
                                    <div class="details">
                                        <div class="item">'.$metricas['m2']['title'].': <span class="number">'.number_format($talent['analytics'][$rrss][$metricas['m2']['field']],0,'','.').'</span></div>
                                        <div class="item">'.$metricas['m3']['title'].': <span class="number">'.number_format($talent['analytics'][$rrss][$metricas['m3']['field']],0,'','.').'</span></div>
                                    </div>
                                </div>
                                <div class="vm-gc2">
                                    <div class="position">#'.($count++).'</div>
                                    <div class="num">'.number_format($talent['analytics'][$rrss][$metricas['m1']['field']],0,'','.').'</div>
                                    <div class="numtitle">'.$metricas['m1']['title'].'</div>
                                </div>
                            </div>';
                            
                            $count_column++;
                            if($count_column == $items_per_page/2) {
                                $html .= '</div><div class="list-column">';
                            }
                        }
                    $html .= '</div>
                </div>';
                }
            $html .= '</div>
        </div>';

        $result = [
            'status' => 'success',
            'carousel_item_count' => $carousel_item_count,
            'html' => $html
        ];
}

echo json_encode($result);