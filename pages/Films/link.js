var series = [
    '5447/files/847137?source=adjaranet',
    '5452/files/847156?source=adjaranet',
    '5454/files/847148?source=adjaranet',
    '5458/files/847158?source=adjaranet',
    '5465/files/847150?source=adjaranet',
    '5470/files/847145?source=adjaranet',
    '458/files/847142?source=adjaranet',
    '39498/files/847130?source=adjaranet'
]

$(document).ready(function(){
   var btnClick = function(e){
        $('#video-player').attr('src','https://api.adjaranet.com/api/v1/movies/' + series[e.currentTarget.id.slice(2)-1]);
        $( "#play-pause" ).load( "./SVG/play.svg" );  
   }

    $('#sr1').on({'click': btnClick});
    $('#sr2').on({'click': btnClick});
    $('#sr3').on({'click': btnClick});
    $('#sr4').on({'click': btnClick});
    $('#sr5').on({'click': btnClick});
    $('#sr6').on({'click': btnClick});
    $('#sr7').on({'click': btnClick});
    $('#sr8').on({'click': btnClick});
});

