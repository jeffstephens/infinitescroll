<?php
/*
 * Infinite Scroll + Usability
 * author: Jeff Stephens <jefftheman45@gmail.com>
 *
 * This is the backend for an example webapp that has "infinite scroll"
 * capability - as you scroll down the page, more content automatically loads
 * in - yet doesn't break basic browser navigation like the back button and
 * address bar.
 *
 * This implementation is meant to be as readable as possible. It repeats
 * itself a little bit, but hopefully it's quite clear to follow how the
 * logic works.
 */

define('FEED_URI', 'https://medium.com/feed/design-ux');
define('POSTS_PER_PAGE', 3);

function getGuidFromPermalink($permalink) {
    $guidParts = explode('/', $permalink);
    $guid = trim($guidParts[(sizeof($guidParts) - 1)]);
    file_put_contents('log', "\nfound guid to be " . $guid, FILE_APPEND);
    return $guid;
}

if($response = file_get_contents(FEED_URI)) {

    if(strlen($_GET['oldestPost'])) {
        $oldestPost = $_GET['oldestPost'];
    }

    else {
        $oldestPost = '';
    }

    if(strlen($_GET['fetchUntil'])) {
        $fetchUntil = $_GET['fetchUntil'];
    }

    else {
        $fetchUntil = '';
    }

    $data = new SimpleXMLElement($response);
    $returnPosts = array(); // this will hold post objects to be returned
    $i = 0;

    // Case 1: We're returning an initial set of posts (POSTS_PER_PAGE of them)
    if(!strlen($oldestPost) && !strlen($fetchUntil)) {
        foreach($data->channel->item as $post) {

            // do this to avoid iterating more times than there are items in the RSS feed:
            if($i >= POSTS_PER_PAGE) {
                break;
            }

            $returnPosts[] = $post;
            $i++;
        }
    }

    // Case 2: We're fetching an additional chunk of posts given the oldest post the client has
    else if(strlen($oldestPost)) {
        $startedSaving = false;

        foreach($data->channel->item as $post) {

            if($startedSaving) {

                // Do this to avoid iterating more times than there are items in the RSS feed:
                if($i >= POSTS_PER_PAGE) {
                    break;
                }

                $returnPosts[] = $post;
                $i++;
            }

            // Iterate until we find the oldest post the client has. Begin saving posts after that.
            if(getGuidFromPermalink($post->guid) == $oldestPost) {
                $startedSaving = true;
            }
        }
    }

    // Case 3: We're fetching all posts since and including a given post.
    // This is used for restoring autoscrolled content after a browser navigation.
    else if(strlen($fetchUntil)) {
        foreach($data->channel->item as $post) {

            $returnPosts[] = $post;

            // After we've added the post specified in fetchUntil, we're done
            if(getGuidFromPermalink($post->guid) == $fetchUntil) {
                break;
            }

        }
    }

    // Generate HTML and output JSON response
    $output = array();
    $output['jsonCode'] = 200;
    $output['html'] = '';

    foreach($returnPosts as $selectedPost) {
        $output['html'] .= "<h3 id=\"" . getGuidFromPermalink($selectedPost->guid) . "\"><a href=\"$selectedPost->link\">$selectedPost->title</a></h3>";
        $output['html'] .= "<blockquote><p>" . strip_tags($selectedPost->description) . "</p></blockquote>";
        $output['oldestPost'] = getGuidFromPermalink($selectedPost->guid);
    }

    echo json_encode($output);
}
?>
