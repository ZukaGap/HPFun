function play() {
    if($('#video-player').get(0).paused) {
        $('#video-player').get(0).play();
        $( "#play-pause" ).load( "./SVG/pause.svg" );
    } else {
        $('#video-player').get(0).pause();
        $( "#play-pause" ).load( "./SVG/play.svg" );
    }
}

function full() {
    $('#video-player').get(0).requestFullscreen();
}


$("#video-player").on('webkitfullscreenchange mozfullscreenchange fullscreenchange', function(e)
{
    if($('#video-player').get(0).paused) {
        $( "#play-pause" ).load( "./SVG/play.svg" );
    } else {
        $( "#play-pause" ).load( "./SVG/pause.svg" );
    }    
});

$( "#play-pause" ).load( "./SVG/play.svg" );
$( "#speaker" ).load( "./SVG/speaker.svg" );
$( "#fscreen" ).load( "./SVG/full-screen.svg" );

$( "#play-pause" ).click(play);
$( "#fscreen" ).click(full);

$("#speaker").click( function (){
    if( $("#video-player").prop('muted') ) {
        $("#video-player").prop('muted', false);
        $('#speaker').removeClass("muted");
    } else {
    $("#video-player").prop('muted', true);
    $('#speaker').addClass("muted");
    }
    
});

$("#video-player").on('volumechange', function() {
    if( $("#video-player").prop('muted') ) {
        $('#speaker').addClass("muted");
    } else {
        $('#speaker').removeClass("muted");
    }
})