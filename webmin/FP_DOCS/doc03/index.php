
<?php
	/* Includes URL signing class and performs authentication check */
	require_once('UrlSigning.php');
	$lic_key = '#V2ZzfmBFWVpeSRhxAElFWVVfZg';
	$authenticated = false || strrpos("!flexpaperEVMWPlqhPJ","!flexpaper")>-1;
	if(isset($_POST['txt_flexpaper_password'])){$authenticated = $_POST['txt_flexpaper_password'] == '!flexpaperEVMWPlqhPJ';} $auth_tried=isset($_POST['txt_flexpaper_password']) && !$authenticated;
?>
<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="initial-scale=1,user-scalable=0,maximum-scale=1,width=device-width" />
    <meta content="IE=Edge" http-equiv="X-UA-Compatible"></meta>
    <title></title>
    <style type="text/css" media="screen">
        html, body	{ height:100%; }
        body { margin:0; padding:0; overflow:auto; }
        #flashContent { display:none; }
    </style>
    <link rel="stylesheet" type="text/css" href="css/flexpaper.css?1459494027779" />
    <link rel="stylesheet" type="text/css" href="css/popover.css?1459494027779" />
    <link rel="stylesheet" type="text/css" href="css/popover-theme.css?1459494027779" />
    <script type="text/javascript" src="js/jquery.min.js?1459494027779"></script>
    <script type="text/javascript" src="js/jquery.extensions.min.js?1459494027779"></script>
    <script type="text/javascript" src="js/popover.min.js?1459494027779"></script>
    <!--[if gte IE 10 | !IE ]><!-->
    <script type="text/javascript" src="js/three.min.js?1459494027779"></script>
    <!--<![endif]-->
    <script type="text/javascript" src="js/flexpaper.js?1459494027779"></script>
    <script type="text/javascript" src="js/flexpaper_handlers.js?1459494027779"></script>
</head>
<body>
<?php if($authenticated){ /* Uses this template if user has authenticated */ ?>
    <div id="documentViewer" class="flexpaper_viewer" style="position:absolute;;width:100%;height:100%;background-color:#222222;;"></div>
    <script type="text/javascript">
        $('#documentViewer').FlexPaperViewer(
                { config : {
                    DOC : escape(<?php echo UrlSigning::getSignedDOCURI('atlassian_git_cheatsheet',$lic_key,'+1 hour',10)?>),

                    Scale                   : 0.1,
                    ZoomTransition          : 'easeOut',
                    ZoomTime                : 0.4,
                    ZoomInterval            : 0.1,
                    FitPageOnLoad           : true,
                    FitWidthOnLoad          : false,
                    AutoAdjustPrintSize     : true,
                    PrintPaperAsBitmap      : false,
                    AutoDetectLinks         : true,
                    FullScreenAsMaxWindow   : false,
                    ProgressiveLoading      : false,
                    MinZoomSize             : 0.1,
                    MaxZoomSize             : 5,
                    SearchMatchAll          : true,
                    InitViewMode            : 'Flip-SinglePage',
                    RenderingOrder          : 'html5,html',
                    StartAtPage             : 1,
                    EnableWebGL             : true,
                    PreviewMode             : '',
                    PublicationTitle        : '',
                    MixedMode               : true,
                    ViewModeToolsVisible    : true,
                    ZoomToolsVisible        : true,
                    NavToolsVisible         : true,
                    CursorToolsVisible      : true,
                    SearchToolsVisible      : true,

                    UIConfig                : 'UI_Zine.xml',
                    WMode                   : 'transparent',

                    TrackingNumber          : '',
                    key                     : '#V2ZzfmBFWVpeSRhxAElFWVVfZg',
                    signature               : '8393d86e910a2e5e5041daa901de3705',
                    localeChain             : 'en_US'
        }}
        );

        var url = window.location.href.toString();
        if(location.length==0){
            url = document.URL.toString();
        }
        if(url.indexOf("file:")>=0){
            jQuery('#documentViewer').html("<div style='position:relative;background-color:#ffffff;width:420px;font-family:Verdana;font-size:10pt;left:22%;top:20%;padding: 10px 10px 10px 10px;border-style:solid;border-width:5px;'><img src='data:image/gif;base64,R0lGODlhEAAPAMQPAPS+GvXAIfrjnP/89vnZePrhlvS9F//+/PrfjfS/HfrgkPS+GP/9+YJiACAYAP////O3AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACH5BAEAAA8ALAAAAAAQAA8AAAVQ4COOD0GQKElA0JmSg7EsxvCOCMsi9xPrkNpNwXI0WIoXA1A8QgCMVEFn1BVQS6rzGR1NtcCriJEAVnWJrgDI1gkehwD7rAsc1u28QJ5vB0IAOw%3D%3D'>&nbsp;<b>You are trying to use FlexPaper from a local directory.</b><br/><br/> Use the 'View in browser' button in the Desktop Publisher publish & preview dialog window to preview your publication or copy the contents of your publication directory to a web server and access this html file through a http:// url.</div>");
        }
    </script>

<?php }else{ /* If the user has not authenticated, then show the log in form */ ?>
    <script type="text/javascript">
        FLEXPAPER.initLoginForm('docs/atlassian_git_cheatsheet.pdf_{page}_thumb.jpg',<?php if(!$auth_tried){echo 'true';}else{echo 'false';} /* Animate if the user has tried to authenticate */ ?>);
        <?php if($auth_tried){ /* If authentication has been attempted but failed, then shake the form */ ?>
            FLEXPAPER.animateDenyEffect ('#loginForm',25,500,7,'hor');
        <?php } /* End of form shaking effect condition */ ?>
    </script>
<?php } /* End of template condition */ ?>

</body>
</html>