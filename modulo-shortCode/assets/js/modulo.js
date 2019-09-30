/*
Modulo Init Scripts

 */
function getInput(inputField){
  return parseFloat($('#modulaForm').find('input[name="'+inputField+'"]').val())
}
// init
var width = getInput("width");
var height = getInput("height");
var curf = getInput("curf");
var quantity = getInput("quantity");
var weight = getInput("weight");
var heightMaterial = getInput("heightMaterial");
var centerMesure = heightMaterial*3 + getInput("centerMesure");
var outerMesure = heightMaterial*2 + getInput("outerMesure");
// var surface
var myTab = {
  'quantity' : quantity,
  'weight' : weight,
  'curf' : curf,
  'heightMaterial' : heightMaterial,
  'centerMesure' : centerMesure,
  'outerMesure' : outerMesure,
}
// console.table( myTab )
/**/
// var unity = 5; // Epaisseur materiaux
// var weight = 5;
// var heightMaterial = unity*4;
// var centerMesure = unity*10;
// var outerMesure = unity*2;
// var curf = 1.1;
// var quantity = 10;

// var draw = SVG('modulaDrawing').size(1000, 1000)

// function that apply a central symetrie
function centralSym(model){
  // denominator for symetrie
  var denominator = [['-',''], ['-','-'], ['','-'] ]
  var newLine = [];
  for (var i = 0; i < denominator.length; i++) {
    if(i%2 == 0){
      for (var j = model.length-2; j > -1; j--) {
        if(i==2 && j==0){
          newLine.push([model[j][0],model[j][1]])
        } else {
          newLine.push([parseFloat(denominator[i][0] + model[j][0])  , parseFloat(denominator[i][1] + model[j][1])])
        }
      }
    } else {
      for (var j = 1; j < model.length; j++) {
        newLine.push([parseFloat(denominator[i][0] + model[j][0])  , parseFloat(denominator[i][1] + model[j][1])])
      }
    }
  }
  model = model.concat(newLine)
  return model;
}
//var newSquarre = centralSym(dots)
var dots, squarre;
var draw = SVG('modulaDrawing').size(500, 500)

function drawModula(nb) {
  // inserte the models property
  dots = [
    [heightMaterial*3+weight, 0],
    [heightMaterial*3+weight, centerMesure + heightMaterial*2 + outerMesure],
    [heightMaterial, centerMesure + heightMaterial*2 +outerMesure],
    [heightMaterial, centerMesure + heightMaterial*2 +outerMesure + heightMaterial*4],
    [0, centerMesure + heightMaterial*2 + outerMesure + heightMaterial*4]
  ]
  squarre = [
    [heightMaterial-curf/2, 0],
    [heightMaterial-curf/2, heightMaterial*2 ],
    [0, heightMaterial*2],
  ]

  var spaceModula = heightMaterial*3+weight*2
  var heightModula = centerMesure + heightMaterial*7 +outerMesure
  for (var i = 0; i < nb; i++) {
    line = draw.polyline( centralSym(dots) ).stroke({ width: 1 })
    line.fill('none').dmove(spaceModula,heightModula)
    line = draw.polyline( centralSym(squarre) ).stroke({ width: 1 })
    line.fill('none').dmove(spaceModula,heightModula+centerMesure)
    line = draw.polyline( centralSym(squarre) ).stroke({ width: 1 })
    line.fill('none').dmove(spaceModula,heightModula-centerMesure)
    if(spaceModula + heightMaterial*7 + weight*2+heightMaterial*3+weight*2 < width ){
      spaceModula += heightMaterial*7+weight*2
    } else {
      spaceModula = heightMaterial*3+weight*2
      heightModula += (centerMesure + heightMaterial*7 + outerMesure)*2
    }
  }
}
drawModula(quantity)

$( "#modulaForm" ).change(function() {
  // Clean the drawing
  SVG.get('SvgjsSvg1001').clear()
  width = getInput("width");
  height = getInput("height");
  curf = getInput("curf");
  quantity = getInput("quantity");
  weight = getInput("weight");
  heightMaterial = getInput("heightMaterial");
  centerMesure = heightMaterial*2 + heightMaterial + getInput("centerMesure");
  outerMesure = heightMaterial*2 + getInput("outerMesure");
  // Redraw all
  SVG.get('SvgjsSvg1001').size(width, height)
  drawModula(quantity)
});

// Download Svg
function download(filename, drawing) {

  // Setting unit of the drawing
  drawing[0].firstChild.setAttribute('width', drawing[0].firstChild.getAttribute('width')+'mm' )
  drawing[0].firstChild.setAttribute('height', drawing[0].firstChild.getAttribute('height')+'mm' )

  // Generating download window
  var downloadContent = document.createElement('a');
  downloadContent.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(drawing.html()));
  downloadContent.setAttribute('download', filename);
  downloadContent.style.display = 'none';
  document.body.appendChild(downloadContent);
  downloadContent.click();

  // document.body.removeChild(element);
}
