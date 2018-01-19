<!DOCTYPE html>
<html>
    <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width">
    <title> {{trans('wzoj.tags')}} </title>
    <link rel="stylesheet" href="/include/css/treant-js/Treant.css">
    <link rel="stylesheet" href="/include/css/treant-js/collapsable.css">
    
</head>
<body>
    <div class="chart" id="tags-chart"></div>
    <script src="/include/js/treant-js/raphael.js"></script>
    <script src="/include/js/treant-js/Treant.js"></script>
    
    <script src="/include/js/treant-js/jquery.min.js"></script>
    <script src="/include/js/treant-js/jquery.easing.js"></script>
    
 
    <script>
    var chart_config = {
        chart: {
            container: "#tags-chart",

            animateOnInit: false,
            
            node: {
                collapsable: true
            },
            animation: {
                nodeAnimation: "easeOutBounce",
                nodeSpeed: 700,
                connectorsAnimation: "bounce",
                connectorsSpeed: 700
            },
            connectors: {
                style: {
                    "stroke-width": 1,
                    "stroke": "#76EAF7"
                }
            }
        },
        nodeStructure: {
            innerHTML: "<div class='tags-chart-node'></div>",
            @include ('problem_tags_chart_recursive', ['tags' => $tags->filter(function($value){
                return $value->parent_id == 0;
            })->sortBy('index')])

        }
    };
    tree = new Treant( chart_config );

    if(window.location.hash != ''){
	    $('#tags-chart').scrollLeft($(window.location.hash).offset().left - $('#tags-chart').width()/2);
	    $('#tags-chart').scrollTop($(window.location.hash).offset().top - $('#tags-chart').height()/2);
    }
    </script>
</body>
</html>
