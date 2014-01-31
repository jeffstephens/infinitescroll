<?php
define('FEED_URI', 'https://medium.com/feed/design-ux');
define('POSTS_PER_PAGE', 3);

function getGuidFromPermalink($permalink) {
    $guidParts = explode('/', $permalink);
    $guid = trim($guidParts[(sizeof($guidParts) - 1)]);
    file_put_contents('log', "\nfound guid to be " . $guid, FILE_APPEND);
    return $guid;
}

if($response = file_get_contents(FEED_URI)) {
    $output = array();
    $output['jsonCode'] = 200;
    $output['html'] = ''; // we'll always append later

    if(strlen($_GET['oldestPost'])) {
        $oldestPost = $_GET['oldestPost'];
    }
    else {
        $oldestPost = '';
    }

    $fetchUntil = $_GET['fetchUntil'];

    file_put_contents('log', "\nfetching until guid " . $fetchUntil, FILE_APPEND);

    $data = new SimpleXMLElement( $response );
    $i = 0;
    $startPosts = false; // true when we've started outputting posts

    foreach($data->channel->item as $post) {
        // if we've started outputting posts, we've output POSTS_PER_PAGE, and we're not fetching posts until a specified one, we're done
        if($i >= POSTS_PER_PAGE && $startPosts === true && !strlen($fetchUntil)) {
            file_put_contents('log', "\ndone fetching posts, already output enough and no fetchUntil");
            break;
        }

        // if we're starting at a post older than the first one...
        if(strlen($oldestPost)) {

            // and we're not fetching all posts until the specified one...
            if(!strlen($fetchUntil)) {

                // and we haven't started outputting posts yet...
                if($startPosts === false) {

                    // if this is the oldest post we've previously returned...
                    if(getGuidFromPermalink($post->guid) == $oldestPost) {
                        $startPosts = true;
                        $i = 0;
                        continue; // the next post is where we want to actually start
                    }
                    else {
                        continue;
                    }
                }
            }
        }

        // otherwise, we're fetching the first pageful of posts. start with the first one in the feed.
        else {
            $startPosts = true;
        }

        $output['html'] .= "<h3 id=\"" . getGuidFromPermalink($post->guid) . "\"><a href=\"$post->link\">$post->title</a></h3>";
        $output['html'] .= "<blockquote><p>" . strip_tags($post->description) . "</p></blockquote>";

        // grab the GUID of the oldest post. this makes pagination easy.
        $output['oldestPost'] = getGuidFromPermalink($post->guid);

        $i++;

        // if we've reached the oldest post we're supposed to fetch, we're done
        if(strlen($fetchUntil) && getGuidFromPermalink($post->guid) == $fetchUntil) {
            file_put_contents('log', "\ndone fetching posts, reached fetchUntil");
            break;
        }
    }

    echo json_encode($output);
}
?>
