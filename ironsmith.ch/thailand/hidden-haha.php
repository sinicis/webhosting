<html lang="de">
<head>
    <style media="screen" type="text/css">
        @import "../style/style.css";
    </style>

    <meta name="author" content="Simon Isenschmid" />
    <meta name='description' content='Ironsmith' />
    <meta http-equiv="Content-Type" content="text/html; charset=uft-8" />
    <meta name="keywords" content="Schweiz, Suisse, Swizzera, Switzerland, Verwaltung, Administration, Amministrazione, Administration, design, logo, image, identity" />
</head>
<body>
<div class='allinone' id='allinone'>
    <h1 align='center'>Thailand Aufenthalt 2015</h1>

    <?php
        echo "<center>";
            echo "<table width='700px'>";

        
            #include('functions.php');
            // get images            
            $dir    = '/home/ironsmit/public_html/thailand/img/';
            $images = array_diff(scandir($dir), array('..', '.'));

            #print_r($images);
            #echo count($images);

#            galBuilder($images);

           include('filelist.php');

            echo "</table>";
        echo "</center>";

    ?>
</div>
