<?php

function hoypy_get_manual_cache_by_time($cache_name, $folder, $cachetime = 30) {
   $cachefile = __DIR__.'/cache/'.$folder.'/'.$cache_name.'.cache';
   $data = null;
   $cachelast = 0;
   $cachetime = 60 * $cachetime; //en segundos
   if(@file_exists($cachefile)) {
      $cachelast = @filemtime($cachefile);
      if (time() - $cachetime < $cachelast) { //echo '###FROM CACHE###';
         $data = @file_get_contents($cachefile);
         $data = $data;
      }
   } //if(!$data) echo '###FROM QUERY###';
   return $data;
}

function hoypy_set_manual_cache_by_time($cache_name, $folder, $data) {
   $cachefile = __DIR__.'/cache/'.$folder.'/'.$cache_name.'.cache';
   @file_put_contents($cachefile, $data);
}

function get_url_content($url, $v2 = true){

	$cache_name = md5($url.($v2?1:0));
	$api_data = hoypy_get_manual_cache_by_time($cache_name, 'metricool');
	if(!$api_data) {

		$agent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.104 Safari/537.36';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_USERAGENT, $agent);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		//curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$api_data = curl_exec($ch);
		curl_close($ch);
	  
	  	hoypy_set_manual_cache_by_time($cache_name, 'metricool', $api_data);
	}		

	$posts = [];
	if(!empty($api_data)) {
		$api_data = json_decode($api_data);
		if($v2) {
			if($api_data && isset($api_data->data) && count($api_data->data) > 0) {
				$posts = $api_data->data;
			}
		}else{
			if($api_data && count($api_data) > 0) {
				$posts = $api_data;
			}
		}	
	}

	return $posts;
}

function lang($text) {
	$fields_lang_es = [
		'impressions' => 'Impresiones',
		'impressionsUnique' => 'Alcance',
		'comments' => 'Comentarios',
		'reactions' => 'Reacciones',
		'shares' => 'Compartidos',
		'clicks' => 'Clics',
		'videoViews' => 'Reproducciones',
		'posts' => 'Publicaciones',
		'likes' => 'Likes',
		'interactions' => 'Interacciones',
		'reach' => 'Alcance',
		'saved' => 'Guardado',
		'total_impressions' => 'Impresiones',
		'total_retweets' => 'Reposts',
		'total_likes' => 'Likes',
		'total_replies' => 'Respuestas',
		'total_videoviews' => 'Reproducciones',
		'viewCount' => 'Visualizaciones',
		'likeCount' => 'Likes',
		'commentCount' => 'Comentarios',
		'shareCount' => 'Compartidos',
		'views' => 'Visualizaciones',
		'dislikes' => 'No me gusta',
		'facebook' => 'Facebook',
		'instagram' => 'Instagram',
		//'instagram_reels' => 'Instagram Reels',
		'twitter' => 'Twitter',
		'tiktok' => 'Tiktok',
		'youtube' => 'Youtube',
		'facebook' => 'Facebook',
		'REELS_VIDEO' => 'Reels',
		'FEED_IMAGE' => 'Imagen',
		'FEED_CAROUSEL_ALBUM' => 'Carrousel',
		'video' => 'Video',
		'photo' => 'Imagen',
		'album' => 'Álbum',
		'VIDEO' => 'Video',
		'posts_count' => 'Posteos',	
	];

	return isset($fields_lang_es[$text]) ? $fields_lang_es[$text] : $text;
}

function arrayOrderByInstagramImpressions($array1, $array2) {
    return $array1['total_instagram_impressions'] < $array2['total_instagram_impressions'];
}

function arrayOrderByFacebookImpressions($array1, $array2) {
    return $array1['total_facebook_impressions'] < $array2['total_facebook_impressions'];
}

function arrayOrderByTiktokViewCount($array1, $array2) {
    return $array1['total_tiktok_viewCount'] < $array2['total_tiktok_viewCount'];
}

function obInstagramPostList($array1, $array2) {
	return $array1['impressionsOrReach'] < $array2['impressionsOrReach'];
}

function obFacebookPostList($array1, $array2) {
	return $array1['impressions'] < $array2['impressions'];
}

function obTiktokPostList($array1, $array2) {
	return $array1['viewCount'] < $array2['viewCount'];
}

function get_reporte($programs_db, $date_from, $date_to){

	$cache_name = md5('reporte.v1.0.'.$date_from.$date_to);
	$programs = hoypy_get_manual_cache_by_time($cache_name, 'reports', 10);
	if(!$programs) {

		$username = 'data@gruponacion.com.py';
		$userToken = 'userToken';
		//$blogId = '658349'; //GEN
		//$blogId = '658341'; //LN
		//$blogId = '658439'; //HOY
		//$blogId = '659691'; //POPULAR

		$medios = getMedios();
		$fields = getReporteFields();

		$userId = '587713';

		$programs = [];
		foreach ($programs_db as $program) {
			$programs[] = [
				'id' => $program['id'],
				'title' => $program['title'],
				'hashtag' => $program['hashtag'],
				'type' => $program['type'],
				'color' => $program['color'],
				'analytics' => []
			];
		}

		//if(FALSE){

		foreach ($medios as $medioKey => $medio) {

			$blogId = $medio['blogId'];

			/*** FACEBOOK ***/
			$url = 'https://app.metricool.com/api/v2/analytics/posts/facebook?blogId='.$blogId.'&userId='.$userId.'&username='.$username.'&userToken='.$userToken.'&from='.$date_from.'T00:00:00-04:00&to='.$date_to.'T23:59:59-04:00&timezone=America/Asuncion';
			$posts_facebook = get_url_content($url);
			
			/*** INSTAGRAM POSTS ***/
			$url = 'https://app.metricool.com/api/v2/analytics/posts/instagram?blogId='.$blogId.'&userId='.$userId.'&username='.$username.'&userToken='.$userToken.'&from='.$date_from.'T00:00:00-04:00&to='.$date_to.'T23:59:59-04:00&timezone=America/Asuncion';
			$posts_instagram_posts = get_url_content($url);
			
			/*** INSTAGRAM REELS ***/
			$url = 'https://app.metricool.com/api/v2/analytics/reels/instagram?blogId='.$blogId.'&userId='.$userId.'&username='.$username.'&userToken='.$userToken.'&from='.$date_from.'T00:00:00-04:00&to='.$date_to.'T23:59:59-04:00&timezone=America/Asuncion';
			$posts_instagram_reels = get_url_content($url);
			/*** TWITTER ***/
			//$url = 'https://app.metricool.com/api/stats/twitter/posts?blogId='.$blogId.'&userId='.$userId.'&username='.$username.'&userToken='.$userToken.'&start='.(str_replace('-', '', $date_from)).'&end='.(str_replace('-', '', $date_to)).'&timezone=America/Asuncion';
			//$posts_twitter = get_url_content($url, false);
			
			/*** TIKTOK ***/
			$url = 'https://app.metricool.com/api/v2/analytics/posts/tiktok?blogId='.$blogId.'&userId='.$userId.'&username='.$username.'&userToken='.$userToken.'&from='.$date_from.'T00:00:00-04:00&to='.$date_to.'T23:59:59-04:00&timezone=America/Asuncion';
			$posts_tiktok = get_url_content($url);
			
			/*** YOUTUBE ***/
			/*
			$url = 'https://app.metricool.com/api/v2/analytics/posts/youtube?blogId='.$blogId.'&userId='.$userId.'&username='.$username.'&userToken='.$userToken.'&from='.$date_from.'T00:00:00-04:00&to='.$date_to.'T23:59:59-04:00&timezone=America/Asuncion&postsType=all';
			$posts_youtube = get_url_content($url);
			*/


			foreach ($programs as $key => $program) {

				$hashtag = $program['hashtag'];
				$analytics = [];

				/*** INSTAGRAM POSTS ***/
				$data_total = $fields['instagram'];
				$data_total['post_list'] = [];

				foreach ($posts_instagram_posts as $post) {
					if (isset($post->content) && mb_stripos($post->content, $hashtag) !== false) {
						//echo $post->text."<br><br>";
						if(isset($post->impressions)) $data_total['impressions'] += $post->impressions;
						if(isset($post->likes)) $data_total['likes'] += $post->likes;
						if(isset($post->comments)) $data_total['comments'] += $post->comments;
						if(isset($post->interactions)) $data_total['interactions'] += $post->interactions;
						if(isset($post->reach)) $data_total['reach'] += $post->reach;
						if(isset($post->saved)) $data_total['saved'] += $post->saved;
						if(isset($post->videoViews)) $data_total['videoViews'] += $post->videoViews;
						$data_total['posts'] += 1;

						$post_array = (array) $post;
						$post_array['medio'] = $medioKey;
						$post_array['instagram_type'] = 'post';
						$post_array['impressionsOrReach'] = isset($post->impressions) ? $post->impressions : 0;
						$data_total['post_list'][] = $post_array;
					}
				}

				//echo 'INSTAGRAM POSTS'."\n";
				//print_r($data_total);
				//$analytics['instagram_posts'] = $data_total;


				/*** INSTAGRAM REELS ***/
				//$data_total = $fields['instagram_reels'];

				foreach ($posts_instagram_reels as $post) {
					if (isset($post->content) && mb_stripos($post->content, $hashtag) !== false) {
						//echo $post->text."<br><br>";
						$post_impressions = 0;
						if(isset($post->impressions)) {
							$data_total['impressions'] += $post->impressions;
							$post_impressions = $post->impressions;
						}
						if($post_impressions == 0 && isset($post->reach)) {
							$data_total['impressions'] += $post->reach;
							$post_impressions = $post->reach;
						}

						if(isset($post->likes)) $data_total['likes'] += $post->likes;
						if(isset($post->comments)) $data_total['comments'] += $post->comments;
						if(isset($post->interactions)) $data_total['interactions'] += $post->interactions;
						if(isset($post->reach)) $data_total['reach'] += $post->reach;
						if(isset($post->saved)) $data_total['saved'] += $post->saved;
						if(isset($post->videoViews)) $data_total['videoViews'] += $post->videoViews;
						$data_total['posts'] += 1;

						$post_array = (array) $post;
						$post_array['medio'] = $medioKey;
						$post_array['instagram_type'] = 'reel';
						$post_array['impressionsOrReach'] = $post_impressions;
						$data_total['post_list'][] = $post_array;
					}
				}

				//echo 'INSTAGRAM REELS'."\n";
				//print_r($data_total);
				$analytics['instagram'] = $data_total;



				if(isset($programs[$key]['analytics']['instagram'])) {

					$programs[$key]['analytics']['instagram']['impressions'] += $analytics['instagram']['impressions'];
					$programs[$key]['analytics']['instagram']['likes'] += $analytics['instagram']['likes'];
					$programs[$key]['analytics']['instagram']['comments'] += $analytics['instagram']['comments'];
					$programs[$key]['analytics']['instagram']['interactions'] += $analytics['instagram']['interactions'];
					$programs[$key]['analytics']['instagram']['reach'] += $analytics['instagram']['reach'];
					$programs[$key]['analytics']['instagram']['saved'] += $analytics['instagram']['saved'];
					$programs[$key]['analytics']['instagram']['videoViews'] += $analytics['instagram']['videoViews'];
					$programs[$key]['analytics']['instagram']['posts'] += $analytics['instagram']['posts'];
					$programs[$key]['analytics']['instagram']['post_list'] = array_merge($programs[$key]['analytics']['instagram']['post_list'], $analytics['instagram']['post_list']);
					$programs[$key]['analytics']['instagram']['medios_posts'][] = [$medioKey => $analytics['instagram']['posts']];

					$programs[$key]['total_instagram_impressions'] += $analytics['instagram']['impressions']; //to order by

				} else {
					$programs[$key]['analytics']['instagram'] = $analytics['instagram'];
					$programs[$key]['analytics']['instagram']['medios_posts'][] = [$medioKey => $analytics['instagram']['posts']];
					$programs[$key]['total_instagram_impressions'] = $analytics['instagram']['impressions']; //to order by
				}



				/*** FACEBOOK ***/
				$data_total = $fields['facebook'];
				$data_total['post_list'] = [];

				foreach ($posts_facebook as $post) {
					if (isset($post->text) && mb_stripos($post->text, $hashtag) !== false) {
						//echo $post->text."<br><br>";
						if(isset($post->impressions)) $data_total['impressions'] += $post->impressions;
						if(isset($post->impressionsUnique)) $data_total['impressionsUnique'] += $post->impressionsUnique;
						if(isset($post->comments)) $data_total['comments'] += $post->comments;
						if(isset($post->reactions)) $data_total['reactions'] += $post->reactions;
						if(isset($post->shares)) $data_total['shares'] += $post->shares;
						if(isset($post->clicks)) $data_total['clicks'] += $post->clicks;
						if(isset($post->videoViews)) $data_total['videoViews'] += $post->videoViews;
						$data_total['posts'] += 1;

						$post_array = (array) $post;
						$post_array['medio'] = $medioKey;
						$data_total['post_list'][] = $post_array;
					}
				}

				//echo 'FACEBOOK'."\n";
				//print_r($data_total);
				$analytics['facebook'] = $data_total;


				if(isset($programs[$key]['analytics']['facebook'])) {

					$programs[$key]['analytics']['facebook']['impressions'] += $analytics['facebook']['impressions'];
					$programs[$key]['analytics']['facebook']['impressionsUnique'] += $analytics['facebook']['impressionsUnique'];
					$programs[$key]['analytics']['facebook']['comments'] += $analytics['facebook']['comments'];
					$programs[$key]['analytics']['facebook']['reactions'] += $analytics['facebook']['reactions'];
					$programs[$key]['analytics']['facebook']['shares'] += $analytics['facebook']['shares'];
					$programs[$key]['analytics']['facebook']['clicks'] += $analytics['facebook']['clicks'];
					$programs[$key]['analytics']['facebook']['videoViews'] += $analytics['facebook']['videoViews'];
					$programs[$key]['analytics']['facebook']['posts'] += $analytics['facebook']['posts'];
					$programs[$key]['analytics']['facebook']['post_list'] = array_merge($programs[$key]['analytics']['facebook']['post_list'] ,$analytics['facebook']['post_list']);
					$programs[$key]['analytics']['facebook']['medios_posts'][] = [$medioKey => $analytics['facebook']['posts']];

					$programs[$key]['total_facebook_impressions'] += $analytics['facebook']['impressions']; //to order by

				} else {
					$programs[$key]['analytics']['facebook'] = $analytics['facebook'];
					$programs[$key]['analytics']['facebook']['medios_posts'][] = [$medioKey => $analytics['facebook']['posts']];
					$programs[$key]['total_facebook_impressions'] = $analytics['facebook']['impressions']; //to order by
				}


				/*** TWITTER ***/
				/*
				$data_total = $fields['twitter'];
				$data_total['post_list'] = [];

				foreach ($posts_twitter as $post) {
					if (isset($post->text) && mb_stripos($post->text, $hashtag) !== false) {
						//echo $post->text."<br><br>";
						if(isset($post->metricsV2->total_impressions))$data_total['total_impressions'] += $post->metricsV2->total_impressions;
						if(isset($post->metricsV2->total_retweets)) $data_total['total_retweets'] += $post->metricsV2->total_retweets;
						if(isset($post->metricsV2->total_likes))$data_total['total_likes'] += $post->metricsV2->total_likes;
						if(isset($post->metricsV2->total_replies))$data_total['total_replies'] += $post->metricsV2->total_replies;
						if(isset($post->metricsV2->total_videoviews))$data_total['total_videoviews'] += $post->metricsV2->total_videoviews;
						$data_total['posts'] += 1;

					}
				}

				//echo 'TWITTER'."\n";
				//print_r($data_total);
				$analytics['twitter'] = $data_total;
				*/


				/*** TIKTOK ***/
				$data_total = $fields['tiktok'];
				$data_total['post_list'] = [];

				foreach ($posts_tiktok as $post) {
					if (isset($post->title) && mb_stripos($post->title, $hashtag) !== false) {
						//echo $post->text."<br><br>";
						if(isset($post->viewCount)) $data_total['viewCount'] += $post->viewCount;
						if(isset($post->likeCount)) $data_total['likeCount'] += $post->likeCount;
						if(isset($post->commentCount)) $data_total['commentCount'] += $post->commentCount;
						if(isset($post->shareCount)) $data_total['shareCount'] += $post->shareCount;
						$data_total['posts'] += 1;

						$post_array = (array) $post;
						$post_array['medio'] = $medioKey;
						$data_total['post_list'][] = $post_array;
					}
				}

				//echo 'TIKTOK'."\n";
				//print_r($data_total);
				$analytics['tiktok'] = $data_total;


				if(isset($programs[$key]['analytics']['tiktok'])) {

					$programs[$key]['analytics']['tiktok']['viewCount'] += $analytics['tiktok']['viewCount'];
					$programs[$key]['analytics']['tiktok']['likeCount'] += $analytics['tiktok']['likeCount'];
					$programs[$key]['analytics']['tiktok']['commentCount'] += $analytics['tiktok']['commentCount'];
					$programs[$key]['analytics']['tiktok']['shareCount'] += $analytics['tiktok']['shareCount'];
					$programs[$key]['analytics']['tiktok']['posts'] += $analytics['tiktok']['posts'];
					$programs[$key]['analytics']['tiktok']['post_list'] = array_merge($programs[$key]['analytics']['tiktok']['post_list'] ,$analytics['tiktok']['post_list']);
					$programs[$key]['analytics']['tiktok']['medios_posts'][] = [$medioKey => $analytics['tiktok']['posts']];

					$programs[$key]['total_tiktok_viewCount'] += $analytics['tiktok']['viewCount']; //to order by

				} else {
					$programs[$key]['analytics']['tiktok'] = $analytics['tiktok'];
					$programs[$key]['analytics']['tiktok']['medios_posts'][] = [$medioKey => $analytics['tiktok']['posts']];
					$programs[$key]['total_tiktok_viewCount'] = $analytics['tiktok']['viewCount']; //to order by
				}				


				/*** YOUTUBE ***/
				/*
				$data_total = $fields['youtube'];
				$data_total['post_list'] = [];

				foreach ($posts_youtube as $post) {
					if (isset($post->title) && mb_stripos($post->title, $hashtag) !== false) {
						//echo $post->text."<br><br>";
						if(isset($post->views)) $data_total['views'] += $post->views;
						if(isset($post->likes)) $data_total['likes'] += $post->likes;
						if(isset($post->dislikes)) $data_total['dislikes'] += $post->dislikes;
						if(isset($post->comments)) $data_total['comments'] += $post->comments;
						if(isset($post->shares)) $data_total['shares'] += $post->shares;
						$data_total['posts'] += 1;

					}
				}

				//echo 'YOUTUBE'."\n";
				//print_r($data_total);
				$analytics['youtube'] = $data_total;
				*/

			}
		}
		//}

		//Order [post_list]
		for ($i=0; $i < count($programs); $i++) {
			$list = $programs[$i]['analytics']['instagram']['post_list'];
			usort($list, 'obInstagramPostList');
			$post_list_cache_name = md5('in'.$date_from.$date_to.$programs[$i]['id'].getCode(10));
			hoypy_set_manual_cache_by_time($post_list_cache_name, 'reports/post_list', json_encode($list));
			$programs[$i]['analytics']['instagram']['post_list'] = $post_list_cache_name;

			$list = $programs[$i]['analytics']['facebook']['post_list'];
			usort($list, 'obFacebookPostList');
			$post_list_cache_name = md5('fb'.$date_from.$date_to.$programs[$i]['id'].getCode(10));
			hoypy_set_manual_cache_by_time($post_list_cache_name, 'reports/post_list', json_encode($list));
			$programs[$i]['analytics']['facebook']['post_list'] = $post_list_cache_name;
			
			$list = $programs[$i]['analytics']['tiktok']['post_list'];
			usort($list, 'obTiktokPostList');
			$post_list_cache_name = md5('tt'.$date_from.$date_to.$programs[$i]['id'].getCode(10));
			hoypy_set_manual_cache_by_time($post_list_cache_name, 'reports/post_list', json_encode($list));
			$programs[$i]['analytics']['tiktok']['post_list'] = $post_list_cache_name;
		}

		usort($programs, 'arrayOrderByInstagramImpressions');

		$programs = json_encode($programs);
	  
	  	hoypy_set_manual_cache_by_time($cache_name, 'reports', $programs);
	}

	return $programs;
}