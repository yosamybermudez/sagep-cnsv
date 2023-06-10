<?php


namespace App\Controller;


use App\Entity\Encuentro;
use App\Entity\EncuentroAcuerdo;
use App\Entity\Rol;
use App\Entity\SolicitudMateriales;
use App\Repository\SistemaModuloRepository;
use App\Repository\SistemaRutaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class DefaultController extends BaseController
{

    private $sistemaRutaRepository;

    private $sistemaModuloRepository;

    public function __construct(SistemaRutaRepository $sistemaRutaRepository, SistemaModuloRepository $sistemaModuloRepository)
    {
        $this->sistemaRutaRepository = $sistemaRutaRepository;
        $this->sistemaModuloRepository = $sistemaModuloRepository;
    }

    /**
     * @Route("/",name="app_index")
     */
    public function index(ChartBuilderInterface $chartBuilder){
        $acuerdos = $this->getDoctrine()->getRepository(EncuentroAcuerdo::class)->findAll();
        $encuentros = $this->getDoctrine()->getRepository(Encuentro::class)->findAll();
        $solicitudesMateriales = $this->getDoctrine()->getRepository(SolicitudMateriales::class)->findAll();
        $acuerdosPorTipo = array();
        $encuentrosPorTipo = array();


        foreach ($acuerdos as $item){
            if(isset($acuerdosPorTipo[$item->getUltimoEstado()]))  {
                $acuerdosPorTipo[$item->getUltimoEstado()] ++;
            } else {
                $acuerdosPorTipo[$item->getUltimoEstado()] = 1;
            }
        }

        foreach ($encuentros as $item){
            if(isset($encuentrosPorTipo[$item->getEstado()]))  {
                $encuentrosPorTipo[$item->getEstado()] ++;
            } else {
                $encuentrosPorTipo[$item->getEstado()] = 1;
            }
        }

        $chartjs = $chartBuilder->createChart(Chart::TYPE_LINE);
        $chartjs->setData([
            'labels' => ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            'datasets' => [
                [
                    'label' => 'My First dataset',
                    'backgroundColor' => '[rgb(255, 99, 132), rgb(0, 99, 100)]',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => [0, 10, 5, 2, 20, 30, 45],
                ],
            ],
        ]);

        $chart = $chartBuilder->createChart(Chart::TYPE_PIE);
        $chart->setData([
           
            'datasets' => [
                [
                    'label' => 'My First dataset',
                    'backgroundColor' => [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    'data' => [0, 10, 5, 2, 20, 30, 45],
                ],
            ],
        ]);



        return $this->render("default/main.html.twig", array(
            'encuentros' => $encuentros,
            'acuerdos' => $acuerdos,
            'acuerdos_tipos' => $acuerdosPorTipo,
            'encuentros_tipos' => $encuentrosPorTipo,
            'solicitudes_materiales' => $solicitudesMateriales,
            'chartjs' => $chartjs,
            'chart' => $chart
        ));
    }

    /**
     * @Route("/assets/iconos",name="app_iconos")
     */
    public function iconos(){
        $iconsFA = array("fa-500px","fa-adjust","fa-adn","fa-align-center","fa-align-justify","fa-align-left","fa-align-right",
            "fa-amazon","fa-ambulance","fa-american-sign-language-interpreting","fa-anchor","fa-android","fa-angellist","fa-angle-double-down","fa-angle-double-left","fa-angle-double-right","fa-angle-double-up",
            "fa-angle-down","fa-angle-left","fa-angle-right","fa-angle-up","fa-apple","fa-archive","fa-area-chart","fa-arrow-circle-down","fa-arrow-circle-left","fa-arrow-circle-o-down","fa-arrow-circle-o-left",
            "fa-arrow-circle-o-right","fa-arrow-circle-o-up","fa-arrow-circle-right","fa-arrow-circle-up","fa-arrow-down","fa-arrow-left","fa-arrow-right","fa-arrow-up","fa-arrows","fa-arrows-alt","fa-arrows-h",
            "fa-arrows-v","fa-assistive-listening-systems","fa-asterisk","fa-at","fa-audio-description","fa-backward","fa-balance-scale","fa-ban","fa-bar-chart","fa-barcode","fa-bars",
            "fa-battery-empty","fa-battery-full","fa-battery-half","fa-battery-quarter","fa-battery-three-quarters","fa-bed","fa-beer","fa-behance","fa-behance-square","fa-bell","fa-bell-o","fa-bell-slash",
            "fa-bell-slash-o","fa-bicycle","fa-binoculars","fa-birthday-cake","fa-bitbucket","fa-bitbucket-square","fa-black-tie","fa-blind","fa-bluetooth","fa-bluetooth-b","fa-bold","fa-bolt","fa-bomb","fa-book",
            "fa-bookmark","fa-bookmark-o","fa-braille","fa-briefcase","fa-btc","fa-bug","fa-building","fa-building-o","fa-bullhorn","fa-bullseye","fa-bus","fa-buysellads","fa-calculator","fa-calendar",
            "fa-calendar-check-o","fa-calendar-minus-o","fa-calendar-o","fa-calendar-plus-o","fa-calendar-times-o","fa-camera","fa-camera-retro","fa-car","fa-caret-down","fa-caret-left","fa-caret-right",
            "fa-caret-square-o-down","fa-caret-square-o-left","fa-caret-square-o-right","fa-caret-square-o-up","fa-caret-up","fa-cart-arrow-down","fa-cart-plus","fa-cc","fa-cc-amex","fa-cc-diners-club",
            "fa-cc-discover","fa-cc-jcb","fa-cc-mastercard","fa-cc-paypal","fa-cc-stripe","fa-cc-visa","fa-certificate","fa-chain-broken","fa-check","fa-check-circle","fa-check-circle-o","fa-check-square",
            "fa-check-square-o","fa-chevron-circle-down","fa-chevron-circle-left","fa-chevron-circle-right","fa-chevron-circle-up","fa-chevron-down","fa-chevron-left","fa-chevron-right","fa-chevron-up","fa-child",
            "fa-chrome","fa-circle","fa-circle-o","fa-circle-o-notch","fa-circle-thin","fa-clipboard","fa-clock-o","fa-clone","fa-cloud","fa-cloud-download","fa-cloud-upload","fa-code","fa-code-fork","fa-codepen",
            "fa-codiepie","fa-coffee","fa-cog","fa-cogs","fa-columns","fa-comment","fa-comment-o","fa-commenting","fa-commenting-o","fa-comments","fa-comments-o","fa-compass","fa-compress","fa-connectdevelop",
            "fa-contao","fa-copyright","fa-creative-commons","fa-credit-card","fa-credit-card-alt","fa-crop","fa-crosshairs","fa-css3","fa-cube","fa-cubes","fa-cutlery","fa-dashcube","fa-database","fa-deaf",
            "fa-delicious","fa-desktop","fa-deviantart","fa-diamond","fa-digg","fa-dot-circle-o","fa-download","fa-dribbble","fa-dropbox","fa-drupal","fa-edge","fa-eject","fa-ellipsis-h","fa-ellipsis-v",
            "fa-empire","fa-envelope","fa-envelope-o","fa-envelope-square","fa-envira","fa-eraser","fa-eur","fa-exchange","fa-exclamation","fa-exclamation-circle",
            "fa-exclamation-triangle","fa-expand","fa-expeditedssl","fa-external-link","fa-external-link-square","fa-eye","fa-eye-slash","fa-eyedropper","fa-facebook","fa-facebook-official","fa-facebook-square",
            "fa-fast-backward","fa-fast-forward","fa-fax","fa-female","fa-fighter-jet","fa-file","fa-file-archive-o","fa-file-audio-o","fa-file-code-o","fa-file-excel-o","fa-file-image-o","fa-file-o","fa-file-pdf-o",
            "fa-file-powerpoint-o","fa-file-text","fa-file-text-o","fa-file-video-o","fa-file-word-o","fa-files-o","fa-film","fa-filter","fa-fire","fa-fire-extinguisher","fa-firefox","fa-first-order","fa-flag",
            "fa-flag-checkered","fa-flag-o","fa-flask","fa-flickr","fa-floppy-o","fa-folder","fa-folder-o","fa-folder-open","fa-folder-open-o","fa-font","fa-font-awesome","fa-fonticons","fa-fort-awesome","fa-forumbee",
            "fa-forward","fa-foursquare","fa-frown-o","fa-futbol-o","fa-gamepad","fa-gavel","fa-gbp","fa-genderless","fa-get-pocket","fa-gg","fa-gg-circle","fa-gift","fa-git","fa-git-square",
            "fa-github","fa-github-alt","fa-github-square","fa-gitlab","fa-glass","fa-glide","fa-glide-g","fa-globe","fa-google","fa-google-plus","fa-google-plus-official","fa-google-plus-square","fa-google-wallet",
            "fa-graduation-cap","fa-gratipay","fa-h-square","fa-hacker-news","fa-hand-lizard-o","fa-hand-o-down","fa-hand-o-left","fa-hand-o-right","fa-hand-o-up","fa-hand-paper-o","fa-hand-peace-o",
            "fa-hand-pointer-o","fa-hand-rock-o","fa-hand-scissors-o","fa-hand-spock-o","fa-hashtag","fa-hdd-o","fa-header","fa-headphones","fa-heart","fa-heart-o","fa-heartbeat","fa-history",
            "fa-home","fa-hospital-o","fa-hourglass","fa-hourglass-end","fa-hourglass-half","fa-hourglass-o","fa-hourglass-start","fa-houzz","fa-html5","fa-i-cursor",
            "fa-ils","fa-inbox","fa-indent","fa-industry","fa-info","fa-info-circle","fa-inr","fa-instagram","fa-internet-explorer","fa-ioxhost","fa-italic","fa-joomla","fa-jpy","fa-jsfiddle","fa-key",
            "fa-keyboard-o","fa-krw","fa-language","fa-laptop","fa-lastfm","fa-lastfm-square","fa-leaf","fa-leanpub","fa-lemon-o","fa-level-down","fa-level-up","fa-life-ring","fa-lightbulb-o","fa-line-chart",
            "fa-link","fa-linkedin","fa-linkedin-square","fa-linux","fa-list","fa-list-alt","fa-list-ol","fa-list-ul","fa-location-arrow","fa-lock","fa-long-arrow-down","fa-long-arrow-left",
            "fa-long-arrow-right","fa-long-arrow-up","fa-low-vision","fa-magic","fa-magnet","fa-male","fa-map","fa-map-marker","fa-map-o","fa-map-pin","fa-map-signs","fa-mars","fa-mars-double","fa-mars-stroke",
            "fa-mars-stroke-h","fa-mars-stroke-v","fa-maxcdn","fa-meanpath","fa-medium","fa-medkit","fa-meh-o","fa-mercury","fa-microphone","fa-microphone-slash","fa-minus",
            "fa-minus-circle","fa-minus-square","fa-minus-square-o","fa-mixcloud","fa-mobile","fa-modx","fa-money","fa-moon-o","fa-motorcycle","fa-mouse-pointer","fa-music","fa-neuter","fa-newspaper-o",
            "fa-object-group","fa-object-ungroup","fa-odnoklassniki","fa-odnoklassniki-square","fa-opencart","fa-openid","fa-opera","fa-optin-monster","fa-outdent","fa-pagelines","fa-paint-brush","fa-paper-plane",
            "fa-paper-plane-o","fa-paperclip","fa-paragraph","fa-pause","fa-pause-circle","fa-pause-circle-o","fa-paw","fa-paypal","fa-pencil","fa-pencil-square","fa-pencil-square-o","fa-percent","fa-phone",
            "fa-phone-square","fa-picture-o","fa-pie-chart","fa-pied-piper","fa-pied-piper-alt","fa-pied-piper-pp","fa-pinterest","fa-pinterest-p","fa-pinterest-square","fa-plane","fa-play","fa-play-circle",
            "fa-play-circle-o","fa-plug","fa-plus","fa-plus-circle","fa-plus-square","fa-plus-square-o","fa-power-off","fa-print","fa-product-hunt","fa-puzzle-piece","fa-qq","fa-qrcode","fa-question",
            "fa-question-circle","fa-question-circle-o","fa-quote-left","fa-quote-right","fa-random","fa-rebel","fa-recycle","fa-reddit","fa-reddit-alien","fa-reddit-square","fa-refresh",
            "fa-registered","fa-renren","fa-repeat","fa-reply","fa-reply-all","fa-retweet","fa-road","fa-rocket","fa-rss","fa-rss-square","fa-rub","fa-safari","fa-scissors","fa-scribd","fa-search","fa-search-minus",
            "fa-search-plus","fa-sellsy","fa-server","fa-share","fa-share-alt","fa-share-alt-square","fa-share-square","fa-share-square-o","fa-shield","fa-ship","fa-shirtsinbulk","fa-shopping-bag","fa-shopping-basket",
            "fa-shopping-cart","fa-sign-in","fa-sign-language","fa-sign-out","fa-signal","fa-simplybuilt","fa-sitemap","fa-skyatlas","fa-skype","fa-slack","fa-sliders","fa-slideshare","fa-smile-o",
            "fa-snapchat","fa-snapchat-ghost","fa-snapchat-square","fa-sort","fa-sort-alpha-asc","fa-sort-alpha-desc","fa-sort-amount-asc","fa-sort-amount-desc","fa-sort-asc","fa-sort-desc",
            "fa-sort-numeric-asc","fa-sort-numeric-desc","fa-soundcloud","fa-space-shuttle","fa-spinner","fa-spoon","fa-spotify","fa-square","fa-square-o","fa-stack-exchange","fa-stack-overflow","fa-star",
            "fa-star-half","fa-star-half-o","fa-star-o","fa-steam","fa-steam-square","fa-step-backward","fa-step-forward","fa-stethoscope","fa-sticky-note","fa-sticky-note-o","fa-stop","fa-stop-circle",
            "fa-stop-circle-o","fa-street-view","fa-strikethrough","fa-stumbleupon","fa-stumbleupon-circle","fa-subscript","fa-subway","fa-suitcase","fa-sun-o","fa-superscript","fa-table",
            "fa-tablet","fa-tachometer","fa-tag","fa-tags","fa-tasks","fa-taxi","fa-television","fa-tencent-weibo","fa-terminal","fa-text-height","fa-text-width","fa-th","fa-th-large","fa-th-list",
            "fa-themeisle","fa-thumb-tack","fa-thumbs-down","fa-thumbs-o-down",
            "fa-thumbs-o-up","fa-thumbs-up","fa-ticket","fa-times","fa-times-circle","fa-times-circle-o","fa-tint","fa-toggle-off","fa-toggle-on","fa-trademark","fa-train","fa-transgender","fa-transgender-alt",
            "fa-trash","fa-trash-o","fa-tree","fa-trello","fa-tripadvisor","fa-trophy","fa-truck","fa-try","fa-tty","fa-tumblr","fa-tumblr-square","fa-twitch","fa-twitter","fa-twitter-square","fa-umbrella",
            "fa-underline","fa-undo","fa-universal-access","fa-university","fa-unlock","fa-unlock-alt","fa-upload","fa-usb","fa-usd","fa-user","fa-user-md",
            "fa-user-plus","fa-user-secret","fa-user-times","fa-users","fa-venus","fa-venus-double","fa-venus-mars","fa-viacoin","fa-viadeo","fa-viadeo-square","fa-video-camera","fa-vimeo","fa-vimeo-square","fa-vine",
            "fa-vk","fa-volume-control-phone","fa-volume-down","fa-volume-off","fa-volume-up","fa-weibo","fa-weixin","fa-whatsapp","fa-wheelchair","fa-wheelchair-alt","fa-wifi","fa-wikipedia-w",
            "fa-windows","fa-wordpress","fa-wpbeginner","fa-wpforms","fa-wrench","fa-xing","fa-xing-square",
            "fa-y-combinator","fa-yahoo","fa-yelp","fa-yoast","fa-youtube","fa-youtube-play","fa-youtube-square");
        return $this->render("default/iconos.html.twig", array(
            'fas' => $iconsFA
        ));
    }

    public function convertStringRol(string $rol, EntityManagerInterface $entityManager){

        if($rol !== 'ROLE_USER'){
            $result = $entityManager->getRepository(Rol::class)->findOneByIdentificador($rol)->getNombre();
        } else {
            $result = 'Usuario';
        }

        return $this->render("default/render-controller-info.html.twig", array(
            'result' => $result
        ));
    }


    /**
     * @Route("/render", name="app_render_bars_menu")
     */
    public function renderBarsMenu(){

//        $mainMenu = $this->isGranted('ROLE_ADMINISTRADOR_SISTEMA') ? $this->todasLasfuncionalidades() : $this->funcionalidadesAutorizadas();
        $mainMenu = $this->todasLasfuncionalidades();
        return $this->render("default/bars-menu.html.twig", array(
            'main_menu' => $mainMenu
        ));
    }



    //----------------------------
    public function cargarModulosBaseDatos(){
        $mainMenu = $this->sistemaModuloRepository->findAll();
        return $mainMenu;
    }

    public function cargarModulos(){
        $modulos = array();

        //Encuentro
        $modulos[] = array(
            'nombre' => 'Encuentros',
            'icono' => 'clock-o',
            'enlace' => '',
            'rutas' => array(
                array(
                    'nombre' => 'Adicionar',
                    'icono' => 'plus',
                    'enlace' => $this->get('router')->generate('encuentro_nuevo')
                ),
                array(
                    'nombre' => 'Gestionar',
                    'icono' => 'folder',
                    'enlace' => $this->get('router')->generate('encuentro_listar')
                ),
            )
        );

        //Acuerdo
        $modulos[] = array(
            'nombre' => 'Acuerdos',
            'icono' => 'calendar-check-o',
            'enlace' => '',
            'rutas' => array(
                array(
                    'nombre' => 'Adicionar',
                    'icono' => 'plus',
                    'enlace' => $this->get('router')->generate('acuerdo_nuevos', array('idEncuentro' => 0))
                ),
                array(
                    'nombre' => 'Gestionar',
                    'icono' => 'folder',
                    'enlace' => $this->get('router')->generate('acuerdo_listar')
                ),
            )
        );

        //Solicitudes de materiales
        $modulos[] = array(
            'nombre' => 'Solicitud de materiales',
            'icono' => 'shopping-basket',
            'enlace' => '',
            'rutas' => array(
                array(
                    'nombre' => 'Adicionar',
                    'icono' => 'plus',
                    'enlace' => $this->get('router')->generate('solicitud_materiales_nuevo')
                ),
                array(
                    'nombre' => 'Gestionar',
                    'icono' => 'folder',
                    'enlace' => $this->get('router')->generate('solicitud_materiales_listar')
                ),
                array(
                    'nombre' => 'Productos registrados',
                    'icono' => 'cubes',
                    'enlace' => $this->get('router')->generate('almacen_producto_listar')
                ),
            )
        );

        //Gestión
        $modulos[] = array(
            'nombre' => 'Gestión',
            'icono' => 'briefcase',
            'enlace' => '',
            'rutas' => array(
                array(
                    'nombre' => 'Cargos',
                    'icono' => 'tags',
                    'enlace' => $this->get('router')->generate('cargo_listar')
                ),
                array(
                    'nombre' => 'Trabajadores',
                    'icono' => 'users',
                    'enlace' => $this->get('router')->generate('trabajador_listar')
                ),
            )
        );

        //Generales
        $modulos[] = array(
            'nombre' => 'Generales',
            'icono' => 'th-large',
            'enlace' => '',
            'rutas' => array(
                array(
                    'nombre' => 'Provincias',
                    'icono' => 'plus',
                    'enlace' => $this->get('router')->generate('provincia_listar')
                ),
                array(
                    'nombre' => 'Municipios',
                    'icono' => 'folder',
                    'enlace' => $this->get('router')->generate('municipio_listar')
                ),
            )
        );

        //Configuración
        $modulos[] = array(
            'nombre' => 'Configuración',
            'icono' => 'cogs',
            'enlace' => '',
            'rutas' => array(
                array(
                    'nombre' => 'Usuarios',
                    'icono' => 'user',
                    'enlace' => $this->get('router')->generate('usuario_listar')
                ),
                array(
                    'nombre' => 'Roles',
                    'icono' => 'users',
                    'enlace' => $this->get('router')->generate('rol_listar')
                ),
                array(
                    'nombre' => 'Tipos de encuentro',
                    'icono' => 'angle-double-down',
                    'enlace' => $this->get('router')->generate('encuentro_tipo_listar')
                ),
            )
        );


        return $modulos;
    }

    public function todasLasfuncionalidades(){
        $modulos = $this->cargarModulos();
        $cant_modulos = count($modulos);
        for($idM = 0; $idM < $cant_modulos; $idM++){
            $rutas = $modulos[$idM]['rutas'];
            $cant_rutas_by_modulo = count($rutas);
            if($cant_rutas_by_modulo === 0){
                $modulos[$idM]['mostrar'] = false;
            } else {
                for ($idR = 0; $idR < $cant_rutas_by_modulo; $idR++) {
                    $modulos[$idM]['rutas'][$idR]['mostrar'] = true;
                     $modulos[$idM]['mostrar'] = true;
                }
            }
        }
        return $modulos;

    }

    public function funcionalidadesAutorizadas(){
        $modulos = $this->cargarModulos();
        $cant_modulos = count($modulos);
        for($idM = 0; $idM < $cant_modulos; $idM++){
            $rutas = $modulos[$idM]['rutas'];
            $cant_rutas_by_modulo = count($rutas);
            if($cant_rutas_by_modulo === 0){
                $modulos[$idM]['mostrar'] = false;
            } else {
               $cant_r = 0;
//                for ($idR = 0; $idR < $cant_rutas_by_modulo; $idR++) {
////                    $cantidad_roles_by_ruta = $modulos[$idM]['rutas'][$idR]->getEntidad() !== null ? count($modulos[$idM]->getSistemaRutas()[$idR]->getEntidad()->getSistemaPermisos()) : 0;
////                    if($cantidad_roles_by_ruta === 0) {
////                        $modulos[$idM]->getSistemaRutas()[$idR]->setMostrar(false);
////                        $cant_r++;
////                    } else {
//                        $permisos = $modulos[$idM]->getSistemaRutas()[$idR]->getEntidad()->getSistemaPermisos();
//                        $rol_access_granted = $this->isGranted('ROLE_ADMINISTRADOR_SISTEMA') ? true : $this->isGrantedRole($permisos);
//                        $modulos[$idM]->getSistemaRutas()[$idR]->setMostrar($rol_access_granted);
//                        if(!$rol_access_granted){ $cant_r++; }
//                   // }
//                }
                if($cant_rutas_by_modulo === $cant_r){
                    $modulos[$idM]['mostrar'] = false;
                } else {
                    $modulos[$idM]['mostrar'] = true;
                }
            }
        }
        dd($modulos);
        return $modulos;
    }

    public function isGrantedRole($permisos)
    {
        foreach ($permisos as $permiso) {
            if ($this->isGranted($permiso->getRol()->getIdentificador())) {
                return true;
            }
        }
        return false;
    }

//    public function funcionalidadesAutorizadas(){
//        $modulos = $this->cargarModulos();
//        $cant_modulos = count($modulos);
//        for($idM = 0; $idM < $cant_modulos; $idM++){
//            $rutas = $modulos[$idM]->getSistemaRutas();
//            $cant_rutas_by_modulo = count($rutas);
//            if($cant_rutas_by_modulo === 0){
//                $modulos[$idM]->setMostrar(false);
//            } else {
//                $cant_r = 0;
//                for ($idR = 0; $idR < $cant_rutas_by_modulo; $idR++) {
//                    $cantidad_roles_by_ruta = $modulos[$idM]->getSistemaRutas()[$idR]->getEntidad() !== null ? count($modulos[$idM]->getSistemaRutas()[$idR]->getEntidad()->getSistemaPermisos()) : 0;
//                    if($cantidad_roles_by_ruta === 0) {
//                        $modulos[$idM]->getSistemaRutas()[$idR]->setMostrar(false);
//                        $cant_r++;
//                    } else {
//                        $permisos = $modulos[$idM]->getSistemaRutas()[$idR]->getEntidad()->getSistemaPermisos();
//                        $rol_access_granted = $this->isGranted('ROLE_ADMINISTRADOR_SISTEMA') ? true : $this->isGrantedRole($permisos);
//                        $modulos[$idM]->getSistemaRutas()[$idR]->setMostrar($rol_access_granted);
//                        if(!$rol_access_granted){ $cant_r++; }
//                    }
//                }
//                if($cant_rutas_by_modulo === $cant_r){
//                    $modulos[$idM]->setMostrar(false);
//                } else {
//                    $modulos[$idM]->setMostrar(true);
//                }
//            }
//        }
//        return $modulos;
//
//    }
}
