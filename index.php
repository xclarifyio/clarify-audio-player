<?php

if (!file_exists('credentials.php')) {
    die("To get started, you need to rename credentials-dist.php to credentials.php and add your API key.");
}
if (!file_exists('vendor/autoload.php')) {
    die("To get started, you need to use Composer to install the required PHP libraries.");
}

include 'credentials.php';
include 'vendor/autoload.php';

$terms = $_GET['terms'];
$terms = preg_replace("/[^A-Za-z0-9|]/", "", $terms);

$bundle = new Clarify\Bundle($apikey);
$items = $bundle->search($terms);

$total = (int) $items['total'];
$search_terms = json_encode($items['search_terms']);
$item_results = $items['item_results'];

$bundles = $items['_links']['items'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=9"/>
        <title>Clarify Player Demo</title>
        <style type="text/css">
            body { font-family: sans-serif; }
        </style>

        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
        <script src="scripts/jquery/jquery-ui-1.9.2.custom.min.js" type="text/javascript"></script>
        <script src="scripts/jquery/jquery.jplayer-2.2.0.min.js" type="text/javascript"></script>
        <script src="scripts/o3v_player.js" type="text/javascript"></script>

        <link rel="stylesheet" href="css/jquery-ui.custom.css"/>
        <link rel="stylesheet" href="css/o3v-player.css"/>

        <script type="text/javascript">
            $(document).ready(function() {

                // Set the path to the jplayer swf file.
                o3vPlayer.jPlayerOptions.swfPath = 'scripts/jquery';

                ////////////////////////////////////////////////////////
                // This is a sample search_terms array from a SearchCollection
                var searchTerms = <?php echo $search_terms; ?>;

                <?php foreach ($bundles as $key => $_bundle) {
                    $bundlekey = $_bundle['href'];
                    $tracks = $bundle->tracks->load($bundlekey)['tracks'];

                    $mediaUrl = $tracks[0]['media_url'];
                    $duration = $tracks[0]['duration'];
                    ?>

                    // Set to the playback URL for the audio file(s).
                    var mediaURLs<?php echo $key; ?> = '<?php echo $mediaUrl; ?>';
                    // This is a sample "ItemResult" object from a SearchCollection JSON
                    // object. It is one item in the item_results array.
                    var itemResult<?php echo $key; ?> =  <?php echo json_encode($item_results[$key]); ?>;
                    ////////////////////////////////////////////////////////

                    // Create a player and add in search results marks
                    var player = o3vPlayer.createPlayer("#player_instance_<?php echo $key; ?>", mediaURLs<?php echo $key; ?>, <?php echo $duration; ?>, {volume:0.5});
                    o3vPlayer.addItemResultMarkers(player, <?php echo $duration; ?>,itemResult<?php echo $key; ?>, searchTerms);

                    ////////////////////////////////////////////////////////
                    // Create words tags for SearchCollection.

                    for (var i=0,c=searchTerms.length;i<c;i++) {
                        var term = searchTerms[i].term;
                        var dtag = document.createElement('div');
                        $(dtag).addClass("o3v-search-tag o3v-search-color-"+i);
                        $(dtag).text(term);
                        $("#player_<?php echo $key; ?>_search_tags").append(dtag);
                    }
                    dtag = document.createElement('div');
                    $(dtag).addClass("o3v-clear");
                    $("#player_<?php echo $key; ?>_search_tags").append(dtag);
                    ////////////////////////////////////////////////////////
                <?php } ?>
            });
        </script>
    
    </head>
    <body>
        <h3>Clarify JPlayer Audio Demo</h3>
        <form action="" method="GET">
            Search terms: <input name="terms" value="<?php print $terms; ?>" />
            <input type="submit" />
        </form>
        <br>
        Player Example:
        <br>
        <em>If no audio player appears, there was not a search result found.</em>
        <br>
        <?php foreach ($bundles as $key => $_bundle) { ?>
            <div id="player_<?php echo $key; ?>_search_tags" class="o3v-search-tag-box"></div>
            <div id="player_instance_<?php echo $key; ?>"></div>
        <?php } ?>
    </body>
</html>