<!DOCTYPE html>
<html lang="en">
<head>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
	<script src="bootstrap/js/bootstrap.min.js"></script>
	<script src="script.js"></script>
	<link type="text/css" rel="stylesheet" href="bootstrap/css/bootstrap.min.css" />
	<title>Infinite Scroll + Usability</title>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-3172541-3']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</head>
<body>
	<a href="https://github.com/jeffstephens/infinitescroll">
		<img style="position: absolute; top: 0; right: 0; border: 0;" src="https://s3.amazonaws.com/github/ribbons/forkme_right_darkblue_121621.png" alt="Fork me on GitHub">
	</a>
	<div class="container">
		<br />
		<h1>Demo: Infinite Scroll + Usability</h1>
		<br />
		<p class="lead">Infinite Scroll is great, <span class="text-error">but it often breaks the back button</span>.
		(See <a href="https://medium.com/design-ux/51b224e42926">my post on Medium</a> to read more.)</p>
		<p class="lead">This page uses <code>window.location.hash</code> to maintain the back button's functionality. Give it a try!</p>
		<br />
		<h2>Medium: Design/UX</h2>
		<div id="feed"></div>
		<p><a href="javascript:void(0);" onclick="fetchPosts();" class="btn btn-primary">fetch more</a> <span id="loading"><span class="icon-download"></span> <span class="muted">fetching...</span></span></p>
	</div>
</body>
</html>
