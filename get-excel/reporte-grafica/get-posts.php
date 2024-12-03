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

require_once __DIR__.'/../inc/functions.php';

$result = [
	'status' => 'error'
];

if(isset($_POST['posts_rrss']) && isset($_POST['posts_cache'])) {

	$rrss = $_POST['posts_rrss'];

	$posts = hoypy_get_manual_cache_by_time($_POST['posts_cache'], 'reports/post_list', 1440);

	if($posts !== null) {
		$posts = json_decode($posts, true);
		$fields = getReporteFields();
		$medios = getMedios();

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




		$html = '<div class="table-responsive">
							<table class="table table-striped table-hover">';

		$html .= '<thead>
					    <tr>
					      <th class="text-center" scope="col">#</th>
					      <th class="text-start" scope="col">Publicación</th>
					      <th class="text-center" scope="col"></th>
					      <th class="text-center" scope="col">Medio</th>
					      ';

		$totales = [];
		foreach ($fields[$rrss] as $key => $value) {
			if($key != 'posts') {
				$html .= '<th scope="col">'.lang($key).'</th>';
				$totales[$key] = 0;
			}
		}

		$html .= '</tr>
				  	</thead>';

		$html .= '<tbody>';

		if(count($posts) > 0) {
			$count = 1;
			foreach ($posts as $key => $post) {
				$html .= '<tr onclick="window.open(\''.$post[$field_url].'\')">
							      <th class="text-center" scope="row">'.($count++).'</th>
							      <td class="text-start"><div class="gpp-post"><span class="gpp-img"><img src="'.$post[$field_picture].'" /></span><span class="gpp-text">'.$post[$field_text].'</span></div></td>
							      <th class="text-center" scope="row"><span class="ct_i ct_'.strtolower(($rrss == 'youtube' ? 'video' : $post['type'])).'"></span></th>
							      <th class="text-center" scope="row"><img class="gpp-medio" src="../images/'.$post['medio'].'.jpg" alt="'.$medios[$post['medio']]['name'].'" /></th>';

				foreach ($fields[$rrss] as $keyField => $field) {
					if($keyField != 'posts') {
						if($rrss == 'instagram' && isset($post['reelId']) && isset($post['reach']) && $keyField == 'impressions' && $post[$keyField] == 0) {
							$post[$keyField] = $post['reach'];

						}
						$html .= '<td>'.number_format($post[$keyField], 0, '', '.').'</td>';
						$totales[$keyField] += $post[$keyField];

						if($rrss == 'youtube') {
							if(isset($field_types['video'][$keyField])){
								$field_types['video'][$keyField] += $post[$keyField];
							}
						} else if(isset($post['type'])) {
							if(isset($field_types[$post['type']]) && isset($field_types[$post['type']][$keyField])) {
								$field_types[$post['type']][$keyField] += $post[$keyField];
							}
						}
					}
				}

				$html .= '</tr>';

				if($rrss == 'youtube') {
					$field_types['video']['posts_count']++;
				} else if(isset($post['type'])) {
					if(isset($field_types[$post['type']])) {
						$field_types[$post['type']]['posts_count']++;
					}
				}
			}

			$html .= '<tr>
							<td class="text-start" colspan="4"><b>Total</b></td>';
			foreach ($totales as $key => $total) {
				$html .= '<td><b>'.number_format($total, 0, '', '.').'</b></td>';
			}
			$html .= '</tr>';
		} else {
			$html .= '<tr>
									<td class="text-start" colspan="'.(4+count($totales)).'"><em>Sin publicaciones...</em></td>
								</tr>';
		}

		$html .= '</tbody>
						</table>
					</div>';

		$html_posts_by_type = '<div class="posts-by-type">';
		//arsort($field_types);
		$html_posts_by_type .= '<div class="table-responsive">
															<table class="table table-striped table-hover">';
		$html_posts_by_type .= '<thead>
					    <tr>
					      <th class="text-start" scope="col">Tipo</th>
					      ';
		foreach ($metricas as $key => $value) {
			$html_posts_by_type .= '<th scope="col">'.lang($key).'</th>';
		}
		$html_posts_by_type .= '</tr>
				  	</thead>';
		$html_posts_by_type .= '<tbody>';
		foreach ($field_types as $key => $values) {
			$html_posts_by_type .= '<tr>
							<td class="text-start"><span class="ct_i ct_'.strtolower($key).'"></span><b>'.lang($key).'</b></td>';
			foreach ($values as $key2 => $value2) {
				$html_posts_by_type .= '<td>'.number_format($value2, 0, '', '.').'</td>';
			}
			$html_posts_by_type .= '</tr>';
		}
		$html_posts_by_type .= '</tbody>
						</table>
					</div>
				</div>';

		$result = [
			'status' => 'success',
			'html' => $html,
			'posts_by_type' => $html_posts_by_type
		];
	}
}

echo json_encode($result);

$mysqli->close();