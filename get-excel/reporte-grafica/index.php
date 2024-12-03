<?php 

require_once('../../config.php');
if(!isLogin()) {
    header("Location: ".$base_url);
    exit();
}

$programs_db = getPrograms();
$medios = getMedios();

$mysqli->close();

require_once __DIR__.'/../inc/functions.php';

$date_from = $_GET['date-from'];
$date_to = $_GET['date-to'];
$rrss = $_GET['rrss'];

$metricas  = [];

switch ($rrss) {
    case 'instagram':
        $metricas = [
            'm1' => [
                'title' => 'Impresiones',
                'field' => 'impressions'
            ],
            'm2' => [
                'title' => 'Alcance',
                'field' => 'reach'
            ],
            'm3' => [
                'title' => 'Likes',
                'field' => 'likes'
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

?><html lang="es"><head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Aldea">
    <meta name="robots" content="noindex, nofollow">
    <title>Talentos | Nación Media</title>
    <link type="image/x-icon" rel="shortcut icon" href="https://apps.nacionmedia.com/metrica/audiencia-tv-digital/assets/img/icon.png">
    <link type="image/png" rel="icon" href="https://apps.nacionmedia.com/metrica/audiencia-tv-digital/assets/img/icon.png">
    <!--Core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <!-- Custom styles -->
    <link href="https://phplaravel-249050-1103411.cloudwaysapps.com/css/style.css?v=1.0.25" rel="stylesheet">
    <link href="https://phplaravel-249050-1103411.cloudwaysapps.com/css/style-responsive.css" rel="stylesheet">

    <style type="text/css">
        body.monitor-page {
            background-color: #2C2F33;
        }

        #carouselRanking .list-column {
            float: left;
            width: 50%;
            padding: 0 0.8%;
        }

        .gdh-main {
            float: left;
            width: 100%;
            margin: 1.2% 0;
            height: 0;
            padding-top: 15.2%;
            cursor: pointer;
            box-shadow: 4px 4px 10px rgb(16 16 18 / 75%), -7px -7px 30px #262e32;
        }

        .gdh-main .vm-gc2 {
            float: none;
            position: initial;
        }

        .sgsdh {
            padding: 0.8%;
        }

        .carousel-inner {
            margin-bottom: 0;
        }

        .gdh-main .picture {
            position: absolute;
            left: 1.5%;
            top: 7.5%;
            width: 13%;
            border-radius: 50%;
            background: linear-gradient(to right, red, orange);
            padding: 2px;
        }

        .gdh-main .picture img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        .gdh-main .vm-gc1 {
            padding-left: 14.25%;
        }

        .gdh-main .vm-gc1 .now {
            font-weight: bold;
            font-size: 1.9vw;
            line-height: 1.9vw;
            color: rgba(136, 160, 183, 0.46);
            margin: 2% 16px 0.5%;
        }

        .gdh-main .vm-gc1 .now.username {
            font-weight: 400;
            font-size: 1.6vw;
            line-height: 1.6vw;
            margin-top: -0.5%;
            margin-bottom: 1%;
        }

        .gdh-main .vm-gc1 .details {
            padding: 0 16px;
            font-size: 1.5vw;
            line-height: 1.5vw;
            color: #a6c5e3;
        }

        .gdh-main .vm-gc1 .details .item {
            display: inline-block;
            vertical-align: middle;
        }

        .gdh-main .vm-gc1 .details .item + .item {
            margin-left: 12px;
        }

        .gdh-main .vm-gc1 .details .item .number {
            font-family: 'Baloo 2', sans-serif;
            font-weight: bold;
            color: #FFF;
        }

        .gdh-main .vm-gc2 .num {
            font-weight: bold;
            font-size: 2.8vw;
            line-height: 2.8vw;
            align-items: center;
            margin-right: 16px;
            position: absolute;
            right: 0;
            top: 55%;
        }

        .gdh-main .vm-gc2 .position {
            align-items: center;
            margin-right: 16px;
            position: absolute;
            right: 0px;
            top: 5%;
            font-weight: bold;
            font-size: 2.4vw;
            line-height: 2.4vw;
            color: rgba(136, 160, 183, 0.46);
        }

        .gdh-main .vm-gc2 .numtitle {
            align-items: center;
            margin-right: 16px;
            position: absolute;
            right: 0;
            top: 39%;
            font-size: 1.2vw;
            line-height: 1.2vw;
            color: #a6c5e3;
        }

        .gdh-main .vm-gc2 .logo {
            float: none;
            background: #2C2F33;
            position: absolute;
            right: 0;
            top: 0;
        }

        .gdh-main .vm-gc2 .logo img {
            height: 44px;
            border-radius: 0 8px 0 8px;
        }

        @media (max-width: 1400px) {
            .gdh-main .vm-gc2 .logo img {
                height: 32px;
            }
        }

        h2.title {
            font-weight: bold;
            font-size: 2.5vw;
            line-height: 2.5vw;
            color: rgba(136, 160, 183, 0.46);
            margin: 0 12px 0.25%;
        }

        .gdh-main .vm-gc1 .now:before {
            display: none;
        }

        .list-medios {
            overflow: hidden;
            display: inline-block;
            margin-left: 24px;
            vertical-align: middle;
        }

        .list-medios .item {
          float: left;
          width: 30px;
        }

        .list-medios .item img {
          width: 100%;
          border-radius: 50%;
        }

        .list-medios .item.isp {
          margin-right: 16px;
          position: relative;
        }

        .list-medios .item.isp:after {
          content: "";
          position: absolute;
          right: -8px;
          top: 0;
          width: 1px;
          height: 100%;
          background: #CCC;
        }

        p.rdate {
            padding: 0 12px;
            font-size: 18px;
            color: #88a0b7;
            margin: 0;
            position: relative;
            z-index: 1;
        }

        .modal-backdrop.fade.show {
            opacity: 0.5 !important;
        }

        .gpp-post .gpp-text {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-left: 56px;
    text-align: left;
    font-size: 13px;
    line-height: 1.23;
}

.gpp-post .gpp-img {
    border-radius: 4px;
    display: block;
    justify-content: center;
    position: relative;
    overflow: hidden;
    height: 48px;
    width: 48px;
    background-color: rgb(216, 219, 221);
    border-color: rgb(216, 219, 221);
    float: left;
}

.gpp-post .gpp-img img {
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

#modal-posts-content table {
    font-size: 13px;
    text-align: right;
    vertical-align: middle;
}

#modal-posts-content table tr {
    cursor: pointer;
}

.gpp-post {
    overflow: hidden;
}

.gpp-medio {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: 1px solid #EEE;
    max-width: none;
    object-fit: cover;
}

.modal-title {
    color: rgb(33, 37, 41);
}

.carousel-ranking-controls {
    position: absolute;
    right: 2%;
    top: 16px;
    z-index: 2;
}

.carousel-ranking-controls .btn {
    border-radius: 4px;
    padding: 8px 12px;
    cursor: pointer;
}

.carousel-ranking-controls .btn svg {
    width: 20px;
    height: 20px;
}

.carousel-ranking-controls .btn + .btn {
    margin-left: 4px;
}

.carousel-loader .spinner-border {
    width: 5rem;
    height: 5rem;
}

.carousel-loader {
    position: absolute;
    left: 0;
    top: 16%;
    width: 100%;
    z-index: 5;
}

.posts-by-type {
    color: #212529;
    font-size: 16px;
    overflow: hidden;
    max-width: 450px;
}

.posts-by-type .item {
    float: left;
    margin-bottom: 4px;
}

.posts-by-type .item + .item {
    margin-left: 16px;
}

.posts-by-type .item b {
    text-transform: uppercase;
}

.ct_i {
    display: inline-block;
    width: 14px;
    height: 14px;
    border-radius: 50%;
    position: relative;
    top: 1px;
    background: #EEE;
}

.posts-by-type .ct_i {
    margin-right: 2px;
}

.ct_reels_video,
.ct_video,
.ct_VIDEO {
    background: #833AB4;
}

.ct_feed_carousel_album,
.ct_album {
    background: #405DE6;
}

.ct_feed_image,
.ct_photo {
    background: #F56040;
}

.posts-by-type + .btn {
    font-size: 14px;
    padding: 6px 12px;
    position: absolute;
    right: 12px;
    margin-top: -51px;
    font-weight: 600;
}

.posts-by-type + .btn svg {
    margin-left: 8px;
}

@media (max-width: 1299px) {
    .modal-title {
        font-size: 14px;
    }

    .posts-by-type + .btn {
        margin-top: -43px;
    }
}

@media (max-width: 991px) {
    .sgsdh {
        padding: 15px;
    }

    #carouselRanking .list-column {
        width: 100%;
        padding: 0;
    }

    .carousel-inner {
        padding: 0 15px;
        margin: 0 -15px;
        width: auto;
    }

    .carousel-ranking-controls {
        right: 15px;
    }

    h2.title {
        font-size: 36px;
        line-height: 36px;
        margin: 0;
    }

    p.rdate {
        padding: 0;
    }

    .gdh-main {
        width: 100%;
        margin: 8px 0;
        height: auto;
        padding-top: 0;
        overflow: hidden;
    }

    .gdh-main .picture {
        position: relative;
        width: 102px;
        height: 102px;
        float: left;
        left: 0;
        top: 0;
        margin: 12px;
    }

    .gdh-main .vm-gc1 {
        padding: 16px 0 16px 130px;
        position: relative;
        left: 0;
        top: 0;
        height: auto;
        width: 100%;
    }

    .gdh-main .vm-gc1 .now {
        font-size: 32px;
        line-height: 32px;
        margin: 0;
    }

    .gdh-main .vm-gc1 .now.username {
        font-size: 26px;
        line-height: 26px;
        margin: 0 0 8px;
    }

    .gdh-main .vm-gc1 .details {
        padding: 0;
        font-size: 24px;
        line-height: 24px;
    }

    .gdh-main .vm-gc2 .position {
        margin-right: 16px;
        position: absolute;
        right: 0px;
        top: 9px;
        font-size: 42px;
        line-height: 42px;
    }

    .gdh-main .vm-gc2 .num {
        font-size: 50px;
        line-height: 50px;
        margin-right: 16px;
        position: absolute;
        right: 0;
        top: 66px;
    }

    .gdh-main .vm-gc2 .numtitle {
        margin-right: 16px;
        position: absolute;
        right: 0;
        top: 48px;
        font-size: 20px;
        line-height: 20px;
    }

    .modal-title {
        font-size: 14px;
    }

    .modal-header,
    .modal-body,
    .modal-footer {
        padding: 12px;
    }

    .modal-footer .btn {
        font-size: 16px;
    }

    .posts-by-type {
        font-size: 14px;
    }

    .ct_i {
        width: 12px;
        height: 12px;
    }
}

.show-767 {
    display: none;
}

@media (max-width: 767px) {
    .gdh-main .vm-gc1 {
        padding-left: 107px;
    }

    .gdh-main .picture {
        width: 83px;
        height: 83px;
        margin: 12px;
    }

    .gdh-main .vm-gc1 .now {
        font-size: 26px;
        line-height: 26px;
        margin: 0;
    }

    .gdh-main .vm-gc1 .now.username {
        font-size: 22px;
        line-height: 22px;
        margin: 0 0 6px;
    }

    .gdh-main .vm-gc1 .details {
        padding: 0;
        font-size: 20px;
        line-height: 20px;
        letter-spacing: -1px;
    }

    .gdh-main .vm-gc2 .position {
        top: 8px;
        font-size: 32px;
        line-height: 32px;
    }

    .gdh-main .vm-gc2 .num {
        font-size: 44px;
        line-height: 44px;
        top: 56px;
    }

    .gdh-main .vm-gc2 .numtitle {
        top: 40px;
        font-size: 18px;
        line-height: 18px;
    }

    .show-767 {
        display: inline-block;
    }

    .hidden-767 {
        display: none;
    }
}

@media (max-width: 659px) {
    .posts-by-type + .btn {
        position: relative;
        right: 0;
        margin: 8px 0 8px auto;
        display: block;
        max-width: 162px;
    }
}

@media (max-width: 609px) {
    .gdh-main .vm-gc1 .details .item + .item {
        margin-left: 8px;
    }

    .gdh-main .picture {
        width: 64px;
        height: 64px;
        margin: 8px;
    }

    .gdh-main .vm-gc1 {
        padding: 10px 0 10px 80px;
    }

    .gdh-main .vm-gc1 .now {
        font-size: 24px;
        line-height: 24px;
        margin: 0;
    }

    .gdh-main .vm-gc1 .now.username {
        font-size: 16px;
        line-height: 16px;
        margin: 0 0 4px;
    }

    .gdh-main .vm-gc1 .details {
        padding: 0;
        font-size: 16px;
        line-height: 16px;
        letter-spacing: -.5px;
    }

    .gdh-main .vm-gc2 .position {
        top: 7px;
        font-size: 28px;
        line-height: 28px;
    }

    .gdh-main .vm-gc2 .num {
        font-size: 30px;
        line-height: 30px;
        top: 45px;
        letter-spacing: -1px;
    }

    .gdh-main .vm-gc2 .numtitle {
        top: 33px;
        font-size: 14px;
        line-height: 14px;
    }

    .list-medios {
        display: block;
        margin: 8px 0;
    }

    .carousel-loader {
        top: 180px;
    }
}

@media (max-width: 499px) {
    .gdh-main .vm-gc1 {
        padding: 10px 0 10px 78px;
    }

    .gdh-main .vm-gc1 .now {
        font-size: 22px;
        line-height: 22px;
        margin: 0;
    }

    .gdh-main .picture {
        width: 62px;
        height: 62px;
        margin: 8px;
    }

    .carousel-ranking-controls {
        top: 12px;
    }

    .gdh-main .vm-gc1 .now.username {
        display: none;
    }

    .gdh-main .vm-gc1 .details {
        margin-top: 2px;
        letter-spacing: -.75px;
    }

    .gdh-main .vm-gc1 .details .item {
        display: block;
    }

    .gdh-main .vm-gc1 .details .item + .item {
        margin-left: 0;
        margin-top: 2px;
    }

    .gdh-main .vm-gc2 .num {
        font-size: 24px;
        line-height: 24px;
        top: 46px;
        margin-right: 12px;
    }

    .gdh-main .vm-gc2 .position {
        top: 7px;
        font-size: 26px;
        line-height: 26px;
    }

    .gdh-main .vm-gc2 .numtitle {
        margin-right: 12px;
        top: 32px;
        font-size: 14px;
        line-height: 14px;
    }

    .gdh-main .vm-gc2 .position {
        margin-right: 12px;
    }

    p.rdate {
        letter-spacing: -.6px;
    }
}

    </style>
</head>
<body class="monitor-page ">
	<section id="container">

        <div class="carousel-loader">
            <div class="d-flex justify-content-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>
        </div>

		<!--main content start-->
		<section id="vmonitor">

	    	<div id="gn-st-minitor" class="container-fluid" style="padding: 0;">

                <div class="sgsdh" style="margin: 0;">
                    <!--mini statistics start-->

                    <div class="carousel-ranking-controls d-none">
                        <button type="button" class="btn btn-primary btn-lg" id="btn-cr-prev" disabled>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16">
                              <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0"/>
                            </svg>
                        </button>
                        <button type="button" class="btn btn-primary btn-lg" id="btn-cr-next">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-right" viewBox="0 0 16 16">
                              <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708"/>
                            </svg>
                        </button>
                    </div>

                    <h2 class="title">
                        Talentos | <span class="hidden-767">Nación Media</span><span class="show-767">NM</span>
                        <div class="list-medios">
                            <div class="item isp">
                              <img src="../images/<?php echo $rrss ?>.jpg" alt="<?php echo strtoupper($rrss) ?>" title="<?php echo $rrss ?>" />
                            </div>
                            <?php 
                            $medios = getMedios();
                            foreach ($medios as $medioKey => $medio) { ?>
                              <div class="item">
                                <img src="../images/<?php echo $medioKey ?>.jpg" alt="<?php echo $medio['name'] ?>" title="<?php echo $medio['name'] ?>" />
                              </div>
                            <?php } ?>
                        </div>
                    </h2>

                    <p class="rdate"><b>Rango de fechas:</b> <?php echo date("d/m/Y", strtotime($date_from)) ?> al <?php echo date("d/m/Y", strtotime($date_to)) ?></p>

                    <div class="vm-g1"></div>
                </div>

	    	</div>

	    </section>
		<!--main content end-->

	</section>
	<!--container end-->

    <!-- Modal -->
    <div class="modal fade" id="modalPosts" tabindex="-1" aria-labelledby="modalPostsLabel" aria-hidden="true">
      <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalPostsLabel">Publicaciones con menciones a <b><span id="modal-posts-username"></span></b></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div id="modal-posts-content"></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>

	<button class="btn-fullscreen hide-onmouse" onclick="openFullscreen();"><i class="fa fa-expand" aria-hidden="true"></i></button>
	<button class="btn-exit-fullscreen hide-onmouse" onclick="closeFullscreen();"><i class="fa fa-compress" aria-hidden="true"></i></button>

	<!--Core js-->
	<script src="https://phplaravel-249050-1103411.cloudwaysapps.com/vendors/jquery/js/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
	<script src="https://phplaravel-249050-1103411.cloudwaysapps.com/js/dashboard.js"></script>
	<!--common script init for all pages-->
	<script src="https://phplaravel-249050-1103411.cloudwaysapps.com/js/scripts.js"></script>
	<!--script for this page-->

	<script>

        var modalPosts = new bootstrap.Modal(document.getElementById("modalPosts"), {});

        let carouselRankingDiv = null;
        let carouselRanking = null;

        $(function() {

            function getCarouselContent() {
                var windowHeight = $(window).height();
                var windowWidth = $(window).width();
                var vmonitorHeight = $("#vmonitor").height();
                var eachItemHeight = null;
                if(windowWidth <= 991) {
                    if(windowWidth <= 499) {
                        eachItemHeight = 94;
                    } else if(windowWidth <= 609) {
                        eachItemHeight = 96.25;
                    } else if(windowWidth <= 767) {
                        eachItemHeight = 123.31;
                    } else {
                        eachItemHeight = 142;
                    }
                } else {
                    eachItemHeight = (windowWidth * 47.62625 / 100) * 17.6 / 100;
                }
                var items_per_page = (windowHeight - vmonitorHeight) / eachItemHeight;
                if(Math.ceil(items_per_page) - items_per_page < 0.3) {
                    items_per_page = Math.ceil(items_per_page);
                } else {
                    items_per_page = Math.floor(items_per_page);
                }

                if(windowWidth > 991) {
                    items_per_page = items_per_page * 2;
                }

                $.post("<?php echo $base_url ?>/get-excel/reporte-grafica/get-carousel.php", {date_from: '<?php echo $date_from ?>', date_to: '<?php echo $date_to ?>',  rrss: '<?php echo $rrss ?>', items_per_page: items_per_page}, function(result){
                    $('.carousel-loader').addClass('d-none');
                    if(result.status == 'success') {
                        $('.vm-g1').html(result.html);

                        $('.carousel-ranking-controls').removeClass('d-none');

                        carouselRankingDiv = document.querySelector('#carouselRanking');
                        carouselRanking = new bootstrap.Carousel(carouselRankingDiv, {
                          interval: 86400*1000,
                          wrap: false
                        });        

                        carouselRankingDiv.addEventListener('slide.bs.carousel', event => {
                            if(event.to == 0){
                                $('#btn-cr-prev').prop('disabled', true);
                            } else {
                                $('#btn-cr-prev').prop('disabled', false);
                            }

                            if(event.to == (result.carousel_item_count - 1)){
                                $('#btn-cr-next').prop('disabled', true);
                            } else {
                                $('#btn-cr-next').prop('disabled', false);
                            }
                        });

                    } else {
                        $('.vm-g1').html('<div class="alert alert-danger" role="alert">Ha ocurrido un error, inténtelo nuevamente.</div>');
                    }
                });
            }
            getCarouselContent();

            $( ".vm-g1" ).on( "click", ".gdh-btn-posts", function() {
                var posts_username = $(this).data('posts_username');
                var posts_rrss = $(this).data('posts_rrss');
                var posts_cache = $(this).data('posts_cache');
                var posts_title = $(this).data('posts_title');

                $('#modal-posts-username').html(posts_username);
                $('#modal-posts-content').html('<div class="d-flex justify-content-center"><div class="spinner-border text-dark" role="status"><span class="visually-hidden">Loading...</span></div></div>');
                modalPosts.show();

                var btn_html = '<a class="btn btn-lg btn-primary" href="<?php echo $base_url ?>/get-excel/reporte-grafica/download-posts.php?posts_rrss='+posts_rrss+'&posts_cache='+posts_cache+'&date-from=<?php echo $date_from ?>&date-to=<?php echo $date_to ?>&program='+encodeURIComponent(posts_title)+'&program_hashtag='+encodeURIComponent(posts_username)+'">Descargar Excel<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-download" viewBox="0 0 16 16"><path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5"></path><path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708z"></path></svg></a>';

                $.post("<?php echo $base_url ?>/get-excel/reporte-grafica/get-posts.php", {posts_rrss: posts_rrss, posts_cache: posts_cache}, function(result){
                    if(result.status == 'success') {
                        $('#modal-posts-content').html(result.posts_by_type+btn_html+result.html);
                    } else {
                        $('#modal-posts-content').html('<div class="alert alert-danger" role="alert">Ha ocurrido un error, inténtelo nuevamente.</div>');
                    }
                });
            });

            $('#btn-cr-prev').click(function() {
                carouselRanking.prev();
                
            });

            $('#btn-cr-next').click(function() {
                carouselRanking.next();
            });

        });



	    /* Get the element you want displayed in fullscreen */ 
	    var elem = document.documentElement;

	    function bodyFullscreen() {
	    	if($('body').hasClass('fullscreen')) {
	    		$('body').removeClass('fullscreen');
	    	} else {
	    		$('html, body').animate({
			        scrollTop: 0
			    }, 250);
		    	$('body').addClass('fullscreen');
	    	}
	    }

	    /* Function to open fullscreen mode */
	    function openFullscreen() {
	        if (elem.requestFullscreen) {
	            elem.requestFullscreen();
	        } else if (elem.mozRequestFullScreen) { /* Firefox */
	            elem.mozRequestFullScreen();
	        } else if (elem.webkitRequestFullscreen) { /* Chrome, Safari & Opera */
	            elem.webkitRequestFullscreen();
	        } else if (elem.msRequestFullscreen) { /* IE/Edge */
	            elem = window.top.document.body; //To break out of frame in IE
	            elem.msRequestFullscreen();
	        }
	    }

	    /* Function to close fullscreen mode */
	    function closeFullscreen() {
	        if (document.exitFullscreen) {
	            document.exitFullscreen();
	        } else if (document.mozCancelFullScreen) {
	            document.mozCancelFullScreen();
	        } else if (document.webkitExitFullscreen) {
	            document.webkitExitFullscreen();
	        } else if (document.msExitFullscreen) {
	            window.top.document.msExitFullscreen();
	        }
	    }

	    // Events
		var output = document.getElementById("myP");
		document.addEventListener("fullscreenchange", function() {
			bodyFullscreen();
		});
		document.addEventListener("mozfullscreenchange", function() {
			bodyFullscreen();
		});
		document.addEventListener("webkitfullscreenchange", function() {
			bodyFullscreen();
		});
		document.addEventListener("msfullscreenchange", function() {
			bodyFullscreen();
		});
	</script>


</body></html>