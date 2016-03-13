<!doctype html>
<html lang="de">
<head>
  	<title>Schlosser-Stubenwagen-Vermietung</title>
  	<meta name="description" content="Schlosser-Stubenwagen-Vermietung">
  	<meta name="author" content="Simon Isenschmid">
    <meta name="keywords" content="claudia schlosser stubenwagen baby kleinkinder mieten vermietung reservation webshop stuben wagen bett thun schweiz bern thunersee beratung berner oberland" />

  	<link rel="stylesheet" href="css/style.css">

  	<!--[if lt IE 9]>
  	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  	<![endif]-->

    <link rel="stylesheet" href="css/jquery-ui.css">
    <script src="scripts/jquery-1.10.2.js"></script>
    <script src="scripts/jquery-ui.js"></script>


    <script type="text/javascript">
        $(function() {
            $( "#date" ).datepicker();
        });


        $(function() {
            $( "#from" ).datepicker({
                defaultDate: "+0d",
                minDate: -0,
                changeMonth: true,
                numberOfMonths: 2,
                onClose: function( selectedDate ) {
                    $( "#to" ).datepicker( "option", "minDate", selectedDate );
                }
            });
            $( "#to" ).datepicker({
                defaultDate: "+3m",
                minDate: "+3m",
                changeMonth: true,
                numberOfMonths: 2,
                onClose: function( selectedDate ) {
                    $( "#from" ).datepicker( "option", "maxDate", selectedDate );
                }
            });
        });


    </script>
</head>
