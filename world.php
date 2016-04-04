<?php
// Start the session
session_start();
$dir    = './data/';
$files = glob('./data/*.{csv}', GLOB_BRACE);
// print_r($files);
?>
<!DOCTYPE html>
<meta charset="utf-8">
<script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.5/d3.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/topojson/1.1.0/topojson.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="js/jscolor.js"></script>
<link rel="stylesheet" href="https://bootswatch.com/superhero/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

<style>
  body {
    font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
  }

  #border-t, #border-b, #border-l, #border-r {
      position: fixed;
      background: #4e5d6c;
      z-index: 9999;
    }

    #border-t {
      left: 0;
      right: 0;
      top: 0;
      height: 2px;
    }

    #border-b {
      left: 0;
      right: 0;
      bottom: 0;
      height: 2px;
    }

    #border-l {
      left: 0;
      top: 0;
      bottom: 0;
      width: 2px;
    }

    #border-r {
      right: 0;
      top: 0;
      bottom: 0;
      width: 2px;
    }
  

  #canvas {
  }

  #canvas-svg {
  }


  .land {
    fill: #222;
  }

  .boundary {
    fill: none;
    stroke: #fff;
    stroke-width: 1px;
  }

  #tooltip-container {
    font-family: "Gill Sans", Impact, sans-serif;
    position: absolute;
    background-color: #fff;
    color: #000;
    padding: 10px;
    /*border: 1px solid;*/
    display: none;
    box-shadow: 5px 5px 5px #222;
  }

  .tooltip_key {
    font-weight: bold;
  }

  .tooltip_value {
    margin-left: 20px;
    float: right;
  }

  .topleft{
    position: fixed;
    top: 5px;
    left: 5px;
  }

  .botleft{
    position: fixed;
    bottom: 5px;
    left: 5px;
  }

  .dropdown-menu{
    padding: 5px;
  }

  .dropdown-menu>li
  { position:relative;
    -webkit-user-select: none; /* Chrome/Safari */        
    -moz-user-select: none; /* Firefox */
    -ms-user-select: none; /* IE10+ */
    /* Rules below not implemented in browsers yet */
    -o-user-select: none;
    user-select: none;
    cursor:pointer;
  }
  .dropdown-menu .sub-menu {
      left: 100%;
      position: absolute;
      top: 0;
      display:none;
      margin-top: -1px;
    border-top-left-radius:0;
    border-bottom-left-radius:0;
    border-left-color:#fff;
    box-shadow:none;
  }
  .right-caret:after
   {  content:"";
      border-bottom: 4px solid transparent;
      border-top: 4px solid transparent;
      border-left: 4px solid orange;
      display: inline-block;
      height: 0;
      opacity: 0.8;
      vertical-align: middle;
      width: 0;
    margin-left:5px;
  }
  .left-caret:after
  { content:"";
      border-bottom: 4px solid transparent;
      border-top: 4px solid transparent;
      border-right: 4px solid orange;
      display: inline-block;
      height: 0;
      opacity: 0.8;
      vertical-align: middle;
      width: 0;
    margin-left:5px;
  }
  .blur {
    -webkit-filter: blur(10px);
    -moz-filter: blur(10px);
    -o-filter: blur(10px);
    -ms-filter: blur(10px);
    filter: blur(10px);
  }
  .rotate {

  /* Safari */
  -webkit-transform: rotate(-90deg);

  /* Firefox */
  -moz-transform: rotate(-90deg);

  /* IE */
  -ms-transform: rotate(-90deg);

  /* Opera */
  -o-transform: rotate(-90deg);

  /* Internet Explorer */
  filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=3);

  }
  select, option { width: 160px !important; }
  label{
    padding-top: 5px;
  }
</style>
<body>
<div id="border-t"></div><div id="border-b"></div>
  <div id="border-l"></div><div id="border-r"></div>
  <div class="botleft">
    <a class="btn btn-primary btn-sm" style="z-index: 6;" href="vertical.html"><i class="fa fa-pause"></i> Vertical</a>
    <a class="btn btn-primary btn-sm" style="z-index: 6;" href="horizontal.html"><i class="fa fa-pause rotate"></i> Horizontal</a>
  </div>
  <div class="dropdown topleft" style="z-index: 6;">
        <div class="hideNseek">
          <p><span class="label label-info title" style="font-size: 15px;"></span></p>
          <button class="menuButton btn btn-primary btn-sm"><i class="fa fa-bars"></i> Menu</button>
        </div>
        <br>
        <div id="div-with-content" class="controls" style=" margin-left: 5px;" >
              <div class="panel panel-primary" style="width: 200px;float: left;;margin-right: 20px;">
                <div class="panel-heading">
                  <h3 class="panel-title"><i class="fa fa-bar-chart"></i> Data Control</h3>
                </div>
                <div class="panel-body">
                  <div class="form-group">
                    <label for="select" class="control-label">Csv file</label>
                    <div>
                      <select class="csv form-control" onchange="changeCsv()">
                      </select>
                    </div>
                    <label for="select" class="control-label">Country Name</label>
                    <div>
                      <select class="nation form-control">
                      </select>
                    </div>
                    <label for="select" class="control-label">Data Column</label>
                    <div>
                      <select class="data form-control">
                      </select>
                    </div>
                    <p>
                      <label id="amount" class="control-label" for="amount"><div class="alert alert-dismissible alert-warning">
                      <button type="button" class="close" data-dismiss="alert">&times;</button>
                      <p>Select data to show slider</p>
                    </div></label>
                    </p>
                    <div id="slider-range"></div>
                  </div>
                </div>
              </div>
              <div class="panel panel-primary" style="width: 200px;float: left;margin-right: 20px;">
                <div class="panel-heading">
                  <h3 class="panel-title"><i class="fa fa-paint-brush"></i> Color Control</h3>
                </div>
                <div class="panel-body">
                  <p>Color1: <input id="color1" class="form-control input-sm jscolor" value="99ccff" style="text-align: center"></p>
                  <p>Color2: <input id="color2" class="form-control input-sm jscolor" value="0050A1" style="text-align: center"></p>
                  <p>Color Counts: <input id="color3" class="form-control input-sm" style="text-align: center" value="9"></p>
                </div>
              </div>
              <div class="panel panel-primary" style="width: 200px;float: left;margin-right: 20px;">
                <div class="panel-heading">
                  <h3 class="panel-title"><i class="fa fa-wrench"></i> Actions</h3>
                </div>
                <div class="panel-body">
                  <a href="#" class="draw btn btn-success" style="width: 100%;margin-bottom: 5px;">Draw</a>
                  <a href="#" class="stop btn btn-danger" style="width: 100%;margin-bottom: 5px;">Stop</a>
                  <!-- <a href="#" class="redraw btn btn-default" style="width: 100%;margin-bottom: 5px;">Redraw</a> -->
                </div>
              </div>
        </div>

  </div>
  <div id="tooltip-container" style="z-index: 2"></div>
  <div class="controls cover" style="position: fixed;width: 100%; height: 100%;z-index: 3;"></div>
  <div id="canvas-svg" style="z-index: 1;"></div>
</body>
<script>
// config attributes, global
var config = {"label0":"label 0","label1":"label 1"}

var dataSource = "<?php echo substr($files[0],2); ?>";


var width = window.innerWidth-10, height = window.innerHeight-20; 
var dataMin , dataMax;
var selectMin, selectMax;

// whole drawing function
function d3run(){

  //get controller values from user
  var MAP_KEY = $( ".nation option:selected" ).text();
  var MAP_VALUE = $( ".data option:selected" ).text();
  var color1 = '#'+$('#color1').val();
  var color2 = '#'+$('#color2').val();
  var COLOR_COUNTS = $('#color3').val();

  //set title
  $('.title').text($( ".data option:selected" ).text());

  console.log("Key ="+MAP_KEY+", Value ="+MAP_VALUE);

  // start d3 drawing
  d3.csv(dataSource, function(err, data) {
    // console.log(d3.keys(data[0])[0]);
    dataMin = d3.min(data, function(d){return (+d[MAP_VALUE]) });
    dataMax = d3.max(data, function(d){return (+d[MAP_VALUE]) })+1;
    if((typeof selectMin === "undefined")||(selectMin<dataMin)||(selectMax>dataMax)){
      selectMin = dataMin;
      selectMax = dataMax;
    }
    console.log(dataMin+"|"+dataMax);
    
    function Interpolate(start, end, steps, count) {
        var s = start,
            e = end,
            final = s + (((e - s) / steps) * count);
        return Math.floor(final);
    }
    
    function Color(_r, _g, _b) {
        var r, g, b;
        var setColors = function(_r, _g, _b) {
            r = _r;
            g = _g;
            b = _b;
        };
    
        setColors(_r, _g, _b);
        this.getColors = function() {
            var colors = {
                r: r,
                g: g,
                b: b
            };
            return colors;
        };
    }
    
    function hexToRgb(hex) {
        var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
        return result ? {
            r: parseInt(result[1], 16),
            g: parseInt(result[2], 16),
            b: parseInt(result[3], 16)
        } : null;
    }
    
    function valueFormat(d) {
      if (d > 1000000000) {
        return Math.round(d / 1000000000 * 10) / 10 + "B";
      } else if (d > 1000000) {
        return Math.round(d / 1000000 * 10) / 10 + "M";
      } else if (d > 1000) {
        return Math.round(d / 1000 * 10) / 10 + "K";
      } else {
        return d;
      }
    }
    
    var COLOR_FIRST = color1, COLOR_LAST = color2;
    
    var rgb = hexToRgb(COLOR_FIRST);
    
    var COLOR_START = new Color(rgb.r, rgb.g, rgb.b);
    
    rgb = hexToRgb(COLOR_LAST);
    var COLOR_END = new Color(rgb.r, rgb.g, rgb.b);
    
    var startColors = COLOR_START.getColors(),
        endColors = COLOR_END.getColors();
    
    var colors = [];
    
    for (var i = 0; i < COLOR_COUNTS; i++) {
      var r = Interpolate(startColors.r, endColors.r, COLOR_COUNTS, i);
      var g = Interpolate(startColors.g, endColors.g, COLOR_COUNTS, i);
      var b = Interpolate(startColors.b, endColors.b, COLOR_COUNTS, i);
      colors.push(new Color(r, g, b));
    }
  
    
    var projection = d3.geo.mercator()
        .scale((width + 1) / 2 / Math.PI)
        .translate([width / 2, height / 2])
        .precision(.1);
    
    var path = d3.geo.path()
        .projection(projection);
    
    var graticule = d3.geo.graticule();
    
    var svg = d3.select("#canvas-svg").append("svg")
        .attr("class", "svgMain")
        .attr("width", width)
        .attr("height", height)
        .append("g")
          .call(d3.behavior.zoom().scaleExtent([0.5, 8]).on("zoom", zoom))
        .append("g");
    
    svg.append("rect")
        .attr("class", "overlay")
        .attr("x",-999999)
        .attr("y",-999999)
        .attr("width", 999999*2)
        .attr("height", 999999*2)
        .style("fill", "#2b3e50");

    svg.append("path")
        .datum(graticule)
        .attr("class", "graticule")
        .attr("d", path);
    
    var valueHash = {};
    
    function zoom() {
      svg.attr("transform", "translate(" + d3.event.translate + ")scale(" + d3.event.scale + ")");
    }

    function log10(val) {
      return Math.log(val);
    }
    
    data.forEach(function(d) {
        valueHash[d[MAP_KEY]] = +d[MAP_VALUE];
    });
    


    var quantize = d3.scale.quantize()
        .domain([0, 1.0])
        .range(d3.range(COLOR_COUNTS).map(function(i) { return i }));

    quantize.domain([selectMin,selectMax]);
    
    d3.json("world.json", function(error, world) {
      var countries = topojson.feature(world, world.objects.countries).features;
    
      svg.append("path")
         .datum(graticule)
         .attr("class", "choropleth")
         .attr("d", path);
    
      var g = svg.append("g");
    
      g.append("path")
       .datum({type: "LineString", coordinates: [[-180, 0], [-90, 0], [0, 0], [90, 0], [180, 0]]})
       .attr("class", "equator")
       .attr("d", path);
    
      var country = g.selectAll(".country").data(countries);
    
      country.enter().insert("path")
          .attr("class", "country")
          .attr("d", path)
          .attr("id", function(d,i) { return d.id; })
          .attr("title", function(d) { return d.properties.name; })
          .style("fill", function(d) {
            // console.log(valueHash[d.properties.name]);
            if (valueHash[d.properties.name]) {
              if ((valueHash[d.properties.name]>selectMin)&&(valueHash[d.properties.name]<selectMax)) {
                // console.log(valueHash[d.properties.name]);
                var c = quantize((valueHash[d.properties.name]));
                var color = colors[c].getColors();
                return "rgb(" + color.r + "," + color.g +
                    "," + color.b + ")";
              }else{
                return "#333";
              }

            } else {
              // console.log('\"'+d.properties.name+'\" is not in data');
              return "#333";
            }
          })
          .on("mousemove", function(d) {
              var html = "";
    
              html += "<div class=\"tooltip_kv\">";
              html += "<span class=\"tooltip_key\">";
              html += d.properties.name;
              html += "</span><br>";
              html += "<span class=\"tooltip_value\">";
              html += (valueHash[d.properties.name] ? valueFormat(valueHash[d.properties.name]) : "");
              html += "";
              html += "</span>";
              html += "</div>";
              
              $("#tooltip-container").html(html);
              $(this).attr("fill-opacity", "0.8");
              $("#tooltip-container").show();
              
              var coordinates = d3.mouse(this);
              
              var map_width = $('.choropleth')[0].getBoundingClientRect().width;
              
              if (d3.event.pageX < map_width / 2) {
                d3.select("#tooltip-container")
                  .style("top", (d3.event.layerY + 15) + "px")
                  .style("left", (d3.event.layerX + 15) + "px");
              } else {
                var tooltip_width = $("#tooltip-container").width();
                d3.select("#tooltip-container")
                  .style("top", (d3.event.layerY + 15) + "px")
                  .style("left", (d3.event.layerX - tooltip_width - 30) + "px");
              }
          })
          .on("mouseout", function() {
                  $(this).attr("fill-opacity", "1.0");
                  $("#tooltip-container").hide();
              });
      
      g.selectAll(".country").transition().call(endall, function() { 
        resetSlider();
      });

      g.append("path")
          .datum(topojson.mesh(world, world.objects.countries, function(a, b) { return a !== b; }))
          .attr("class", "boundary")
          .attr("d", path);
      
      // svg.attr("height", config.height * 2.2 / 3);
    });
    
    // d3.select(self.frameElement).style("height", (height * 2.3 / 3) + "px");
  });

 function endall(transition, callback) { 
    if (transition.size() === 0) { callback() }
    var n = 0; 
    transition 
        .each(function() { ++n; }) 
        .each("end", function() { if (!--n) callback.apply(this, arguments); }); 
  }   


    
}
// d3run();

function draw(){
  $('.svgMain').remove();
  d3run();
}

function redraw(){
  $('.svgMain').remove();
  d3run();
}
function stop(){
  $('.svgMain').remove();
}

// Actions
$( ".redraw" ).click(function() {
  $(".controls").hide();
  $("#canvas-svg").removeClass("blur");
  hide = true;
  redraw();
});
$( ".draw" ).click(function() {
  $(".controls").hide();
  $("#canvas-svg").removeClass("blur");
  hide = true;
  draw();
});
$( ".stop" ).click(function() {
  stop();
});

// color change
$('.color1label').click(function(){
  $('#color1').click();
});

// hide control menu
var hide = true;
$(".controls").hide();

$(".menuButton").click(function(){
  if (hide) {
    $(".controls").show();
    $("#canvas-svg").addClass("blur");
  }else{
    $(".controls").hide();
    $("#canvas-svg").removeClass("blur");
  }
  hide = !hide;
})

// header select
var headers=[];
$(document).ready(function() {
    $.ajax({
        type: "GET",
        url: dataSource,
        dataType: "text",
        success: function(data) {processData(data);}
     });
});

function processData(allText) {
    var allTextLines = allText.split(/\r\n|\n/);
    headers = allTextLines[0].split(',');
    // console.log(headers);
    for(var x = 0;x<headers.length;x++){
      $('.nation').append($('<option>', {
          value: x,
          text: headers[x]
      }));
      $('.data').append($('<option>', {
          value: x,
          text: headers[x]
      }));
    }
}

// change csv source
function changeCsv(){
  dataSource = 'data/'+$( ".csv option:selected" ).text();
  $('.nation').find('option').remove();
  $('.data').find('option').remove();
  $.ajax({
    type: "GET",
    url: dataSource,
    dataType: "text",
    success: function(data) {processData(data);}
 });
}

// file scan
<?php
for($x = 0; $x < count($files); $x++) {
  // echo $files[$x];
  // echo "<br>";
  ?>
  $('.csv').append($('<option>', {
    value: <?php echo $x ?>,
    text: "<?php echo substr($files[$x],7); ?>"
  }));
  // console.log("<?php echo substr($files[$x],7); ?>");
  <?php
}
?>

//slider
function resetSlider() {
    
    try {
        $('#slider-range').slider("destroy");
    }
    catch(err) {
        // document.getElementById("demo").innerHTML = err.message;
    }
    try {
        $('.alert-dismissible').remove();
    }
    catch(err) {
        // document.getElementById("demo").innerHTML = err.message;
    }
    $( "#slider-range" ).slider({
      range: true,
      min: dataMin,
      max: dataMax,
      values: [ selectMin, selectMax ],
      slide: function( event, ui ) {
        $( "#amount" ).text( "Data range: " + ui.values[ 0 ] + " - " + ui.values[ 1 ] );
        selectMin = ui.values[ 0 ];
        selectMax = ui.values[ 1 ];
      }
    });
    $( "#amount" ).text( "Data range: " + $( "#slider-range" ).slider( "values", 0 ) +
      " - " + $( "#slider-range" ).slider( "values", 1 ) );
    selectMin = $( "#slider-range" ).slider( "values", 0 );
    selectMax = $( "#slider-range" ).slider( "values", 1 );
  };



</script>