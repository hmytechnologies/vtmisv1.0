<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Demo of How to Open Bootstrap Modal Popup on Ajax Click Function</title>
<meta content="This tutorial will explain about to open Bootstrap Modal Popup on Ajax Click Function. When user clicks on button, A ajax request comes and load the another page content." name="description" />
<meta content="demo of bootstrap modal popup, demo of bootstrap modal" name="keywords" />
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="css/bootstrap.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script type="text/javascript" language="javascript" src="js/bootstrap.js"></script>

<script type="text/javascript">
$(document).ready(function(){
var $modal = $('#load_popup_modal_show_id');
$('#click_to_load_modal_popup').on('click', function(){
$modal.load('load-modal.php',{'id1': '1', 'id2': '2'},
function(){
$modal.modal('show');
});

});
});

</script>

</head>

<body>
<!--===========================  -->
<!-- For More Info Visit : http://www.discussdesk.com/how-to-open-bootstrap-modal-popup-on-ajax-click-function.htm-->
<!-- ============================ -->
<div id="load_popup_modal_show_id" class="modal fade" tabindex="-1"></div>

<div style="padding:10px;">
<button type="button" class="btn btn-danger btn-lg" id="click_to_load_modal_popup">
Open Popup Modal</button>
<div>


<script type="text/javascript">

  /*var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-38304687-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();*/

</script>

</body>
</html>
