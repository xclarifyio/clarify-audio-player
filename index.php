<?php

$terms = $_GET['terms'];
$terms = preg_replace("/[^A-Za-z0-9|]/", "", $terms);

//todo: initialize object
//todo: perform search
//todo: get search results
//todo: json decode things
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=9"/>
        <title>OP3Nvoice Player Demo</title>
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

                // Set to the playback URL for the audio file.
                var mediaURL = 'SET_URL_TO_RECORDING_FOR_createPlayer_CALL';

                ////////////////////////////////////////////////////////

                // This is a sample search_terms array from a SearchCollection
                var searchTerms = [{"term":"pizza"},{"term":"beer"},{"term":"party"}];

                // This is a sample "ItemResult" object from a SearchCollection JSON
                // object. It is one item in the item_results array.
                var itemResult = {"score":0.054899603,"term_results":[{"score":1,"matches":[{"type":"text","field":"description","hits":[{"start":10,"end":14}]},{"type":"audio","track":0,"hits":[{"start":12.12,"end":12.23}]}]},{"score":1,"matches":[{"type":"audio","track":0,"hits":[{"start":4.56,"end":4.66}]}]},{"score":1,"matches":[{"type":"audio","track":0,"hits":[{"start":6.18,"end":6.54}]}]}]};

                ////////////////////////////////////////////////////////
                // Create a player and add in search results marks
                var convDuration = 55;
                var player = o3vPlayer.createPlayer("#player_instance_1", mediaURL, convDuration,{volume:0.5});
                o3vPlayer.addItemResultMarkers(player,convDuration,itemResult,searchTerms);

                ////////////////////////////////////////////////////////
                // Create words tags for SearchCollection.
                for (var i=0,c=searchTerms.length;i<c;i++) {
                    var term = searchTerms[i].term;
                    var dtag = document.createElement('div');
                    $(dtag).addClass("o3v-search-tag o3v-search-color-"+i);
                    $(dtag).text(term);
                    $("#player_1_search_tags").append(dtag);
                }
                dtag = document.createElement('div');
                $(dtag).addClass("o3v-clear");
                $("#player_1_search_tags").append(dtag);
                ////////////////////////////////////////////////////////

            });
        </script>
    
    </head>
    <body>
        <h3>OP3Nvoice JPlayer Demo</h3>
        <br>
        Player Example:
        <br>
        <br>
        <div id="player_1_search_tags" class="o3v-search-tag-box"></div>
        <div id="player_instance_1"></div>
    </body>
</html>