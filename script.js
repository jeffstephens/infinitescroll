oldestPost = '';
fetchUntil = '';

$(document).ready(function() {
	if( window.location.hash ){
		fetchUntil = window.location.hash.slice(1);
	}
	fetchPosts();
});

function fetchPosts(){
	$('#loading').fadeIn();
	$.get( 'backend.php', { 'oldestPost' : oldestPost, 'fetchUntil' : fetchUntil }, function(JSON){
		data = $.parseJSON(JSON);
		oldestPost = data.oldestPost;
		window.location.hash = oldestPost;
		$('#feed').append(data.html);
		$('#loading').fadeOut();
		fetchUntil = ''; // only used at first request

		// scroll to bottom-most element
		$("html, body").animate({ scrollTop: $('#' + oldestPost).offset().top}, 1000);
	});
}
