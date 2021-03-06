
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, viewport-fit=cover"/>
<meta name="description" content="An organization chart editor -- edit details and change relationships."/> 
<link rel="stylesheet" href="../assets/css/style.css"/> 
<!-- Copyright 1998-2021 by Northwoods Software Corporation. -->
<title>Org Chart Editor</title>
</head>

<body>
  <!-- This top nav is not part of the sample code -->
  <nav id="navTop" class="w-full z-30 top-0 text-white bg-nwoods-primary">
    <div class="w-full container max-w-screen-lg mx-auto flex flex-wrap sm:flex-nowrap items-center justify-between mt-0 py-2"  >
      <div class="md:pl-4">
        <a class="text-white hover:text-white no-underline hover:no-underline
        font-bold text-2xl lg:text-4xl rounded-lg hover:bg-nwoods-secondary " href="../">
          <h1 class="mb-0 p-1 ">ORGANIGRAMA DIRECCION REGISTRO DE ANTECEDENTES</h1>
        </a>
      </div>
   
    </div>
    <hr class="border-b border-gray-600 opacity-50 my-0 py-0" />
  </nav>
  <div class="md:flex flex-col md:flex-row md:min-h-screen w-full max-w-screen-xl mx-auto">
    <div id="navSide" class="flex flex-col w-full md:w-48 text-gray-700 bg-white flex-shrink-0"></div>
    <!-- * * * * * * * * * * * * * -->
    <!-- Start of GoJS sample code -->
    
    <script src="../release/go.js"></script>
    <div class="p-4 w-full">

  <link rel="stylesheet" href="../extensions/DataInspector.css" />
  <script src="../extensions/DataInspector.js"></script>

  <script id="code">
    function init() {
      var $ = go.GraphObject.make;  // for conciseness in defining templates

      myDiagram =
        $(go.Diagram, "myDiagramDiv", // must be the ID or reference to div
          {
            maxSelectionCount: 1, // users can select only one part at a time
            validCycle: go.Diagram.CycleDestinationTree, // make sure users can only create trees
            "clickCreatingTool.archetypeNodeData": { // allow double-click in background to create a new node
              name: "(new person)",
              title: "",
              comments: ""
            },
            "clickCreatingTool.insertPart": function(loc) {  // scroll to the new node
              var node = go.ClickCreatingTool.prototype.insertPart.call(this, loc);
              if (node !== null) {
                this.diagram.select(node);
                this.diagram.commandHandler.scrollToPart(node);
                this.diagram.commandHandler.editTextBlock(node.findObject("NAMETB"));
              }
              return node;
            },
            layout:
              $(go.TreeLayout,
                {
                  treeStyle: go.TreeLayout.StyleLastParents,
                  arrangement: go.TreeLayout.ArrangementHorizontal,
                  // properties for most of the tree:
                  angle: 90,
                  layerSpacing: 35,
                  // properties for the "last parents":
                  alternateAngle: 90,
                  alternateLayerSpacing: 35,
                  alternateAlignment: go.TreeLayout.AlignmentBus,
                  alternateNodeSpacing: 20
                }),
            "undoManager.isEnabled": true // enable undo & redo
          });

      // when the document is modified, add a "*" to the title and enable the "Save" button
      myDiagram.addDiagramListener("Modified", function(e) {
        var button = document.getElementById("SaveButton");
        if (button) button.disabled = !myDiagram.isModified;
        var idx = document.title.indexOf("*");
        if (myDiagram.isModified) {
          if (idx < 0) document.title += "*";
        } else {
          if (idx >= 0) document.title = document.title.substr(0, idx);
        }
      });

      // manage boss info manually when a node or link is deleted from the diagram
      myDiagram.addDiagramListener("SelectionDeleting", function(e) {
        var part = e.subject.first(); // e.subject is the myDiagram.selection collection,
        // so we'll get the first since we know we only have one selection
        myDiagram.startTransaction("clear boss");
        if (part instanceof go.Node) {
          var it = part.findTreeChildrenNodes(); // find all child nodes
          while (it.next()) { // now iterate through them and clear out the boss information
            var child = it.value;
            var bossText = child.findObject("boss"); // since the boss TextBlock is named, we can access it by name
            if (bossText === null) return;
            bossText.text = "";
          }
        } else if (part instanceof go.Link) {
          var child = part.toNode;
          var bossText = child.findObject("boss"); // since the boss TextBlock is named, we can access it by name
          if (bossText === null) return;
          bossText.text = "";
        }
        myDiagram.commitTransaction("clear boss");
      });

      var levelColors = ["#AC193D", "#2672EC", "#8C0095", "#5133AB",
        "#008299", "#D24726", "#008A00", "#094AB2"];

      // override TreeLayout.commitNodes to also modify the background brush based on the tree depth level
      myDiagram.layout.commitNodes = function() {
        go.TreeLayout.prototype.commitNodes.call(myDiagram.layout);  // do the standard behavior
        // then go through all of the vertexes and set their corresponding node's Shape.fill
        // to a brush dependent on the TreeVertex.level value
        myDiagram.layout.network.vertexes.each(function(v) {
          if (v.node) {
            var level = v.level % (levelColors.length);
            var color = levelColors[level];
            var shape = v.node.findObject("SHAPE");
            if (shape) shape.stroke = $(go.Brush, "Linear", { 0: color, 1: go.Brush.lightenBy(color, 0.05), start: go.Spot.Left, end: go.Spot.Right });
          }
        });
      };

      // when a node is double-clicked, add a child to it
      function nodeDoubleClick(e, obj) {
        var clicked = obj.part;
        if (clicked !== null) {
          var thisemp = clicked.data;
          myDiagram.startTransaction("add employee");
          var newemp = {
            name: "(new person)",
            title: "",
            comments: "",
            parent: thisemp.key
          };
          myDiagram.model.addNodeData(newemp);
          myDiagram.commitTransaction("add employee");
        }
      }

      // this is used to determine feedback during drags
      function mayWorkFor(node1, node2) {
        if (!(node1 instanceof go.Node)) return false;  // must be a Node
        if (node1 === node2) return false;  // cannot work for yourself
        if (node2.isInTreeOf(node1)) return false;  // cannot work for someone who works for you
        return true;
      }

      // This function provides a common style for most of the TextBlocks.
      // Some of these values may be overridden in a particular TextBlock.
      function textStyle() {
        return { font: "9pt  Segoe UI,sans-serif", stroke: "white" };
      }

      // This converter is used by the Picture.
      function findHeadShot(key) {
        if (key < 0 || key > 250) return "images/HSnopic.jpg"; // There are only 20 images on the server
        return "images/HS" + key + ".jpg"
      }

      // define the Node template
      myDiagram.nodeTemplate =
        $(go.Node, "Auto",
          { doubleClick: nodeDoubleClick },
          { // handle dragging a Node onto a Node to (maybe) change the reporting relationship
            mouseDragEnter: function(e, node, prev) {
              var diagram = node.diagram;
              var selnode = diagram.selection.first();
              if (!mayWorkFor(selnode, node)) return;
              var shape = node.findObject("SHAPE");
              if (shape) {
                shape._prevFill = shape.fill;  // remember the original brush
                shape.fill = "darkred";
              }
            },
            mouseDragLeave: function(e, node, next) {
              var shape = node.findObject("SHAPE");
              if (shape && shape._prevFill) {
                shape.fill = shape._prevFill;  // restore the original brush
              }
            },
            mouseDrop: function(e, node) {
              var diagram = node.diagram;
              var selnode = diagram.selection.first();  // assume just one Node in selection
              if (mayWorkFor(selnode, node)) {
                // find any existing link into the selected node
                var link = selnode.findTreeParentLink();
                if (link !== null) {  // reconnect any existing link
                  link.fromNode = node;
                } else {  // else create a new link
                  diagram.toolManager.linkingTool.insertLink(node, node.port, selnode, selnode.port);
                }
              }
            }
          },
          // for sorting, have the Node.text be the data.name
          new go.Binding("text", "name"),
          // bind the Part.layerName to control the Node's layer depending on whether it isSelected
          new go.Binding("layerName", "isSelected", function(sel) { return sel ? "Foreground" : ""; }).ofObject(),
          // define the node's outer shape
          $(go.Shape, "Rectangle",
            {
              name: "SHAPE", fill: "#333333", stroke: 'white', strokeWidth: 3.5,
              // set the port properties:
              portId: "", fromLinkable: true, toLinkable: true, cursor: "pointer"
            }),
          $(go.Panel, "Horizontal",
            $(go.Picture,
              {
                name: "Picture",
                desiredSize: new go.Size(70, 70),
                margin: 1.5,
              },
              new go.Binding("source", "key", findHeadShot)),
            // define the panel where the text will appear
            $(go.Panel, "Table",
              {
                minSize: new go.Size(130, NaN),
                maxSize: new go.Size(230, NaN),
                margin: new go.Margin(6, 10, 0, 6),
                defaultAlignment: go.Spot.Left
              },
              $(go.RowColumnDefinition, { column: 2, width: 4 }),
              $(go.TextBlock, textStyle(),  // the name
                {
                  row: 0, column: 0, columnSpan: 5,
                  font: "12pt Segoe UI,sans-serif",
                  editable: true, isMultiline: false,
                  minSize: new go.Size(10, 16)
                },
                new go.Binding("text", "name").makeTwoWay()),
              $(go.TextBlock, "Title: ", textStyle(),
                { row: 1, column: 0 }),
              $(go.TextBlock, textStyle(),
                {
                  row: 1, column: 1, columnSpan: 4,
                  editable: true, isMultiline: false,
                  minSize: new go.Size(10, 14),
                  margin: new go.Margin(0, 0, 0, 3)
                },

                new go.Binding("text", "title").makeTwoWay()),
              $(go.TextBlock, textStyle(),
                { row: 2, column: 0 },
                new go.Binding("text", "key", function(v) { return "ID: " + v; })),
              $(go.TextBlock, textStyle(),
                { name: "boss", row: 2, column: 3, }, // we include a name so we can access this TextBlock when deleting Nodes/Links
                new go.Binding("text", "parent", function(v) { return "Boss: " + v; })),
              $(go.TextBlock, textStyle(),  // the comments
                {
                  row: 3, column: 0, columnSpan: 5,
                  font: "italic 9pt sans-serif",
                  wrap: go.TextBlock.WrapFit,
                  editable: true,  // by default newlines are allowed
                  minSize: new go.Size(10, 14)
                },
                new go.Binding("text", "comments").makeTwoWay())
            )  // end Table Panel
          ) // end Horizontal Panel
        );  // end Node

      // the context menu allows users to make a position vacant,
      // remove a role and reassign the subtree, or remove a department
      myDiagram.nodeTemplate.contextMenu =
        $("ContextMenu",
          $("ContextMenuButton",
            $(go.TextBlock, "Vacate Position"),
            {
              click: function(e, obj) {
                var node = obj.part.adornedPart;
                if (node !== null) {
                  var thisemp = node.data;
                  myDiagram.startTransaction("vacate");
                  // update the key, name, and comments
                  myDiagram.model.setDataProperty(thisemp, "name", "(Vacant)");
                  myDiagram.model.setDataProperty(thisemp, "comments", "");
                  myDiagram.commitTransaction("vacate");
                }
              }
            }
          ),
          $("ContextMenuButton",
            $(go.TextBlock, "Remove Role"),
            {
              click: function(e, obj) {
                // reparent the subtree to this node's boss, then remove the node
                var node = obj.part.adornedPart;
                if (node !== null) {
                  myDiagram.startTransaction("reparent remove");
                  var chl = node.findTreeChildrenNodes();
                  // iterate through the children and set their parent key to our selected node's parent key
                  while (chl.next()) {
                    var emp = chl.value;
                    myDiagram.model.setParentKeyForNodeData(emp.data, node.findTreeParentNode().data.key);
                  }
                  // and now remove the selected node itself
                  myDiagram.model.removeNodeData(node.data);
                  myDiagram.commitTransaction("reparent remove");
                }
              }
            }
          ),
          $("ContextMenuButton",
            $(go.TextBlock, "Remove Department"),
            {
              click: function(e, obj) {
                // remove the whole subtree, including the node itself
                var node = obj.part.adornedPart;
                if (node !== null) {
                  myDiagram.startTransaction("remove dept");
                  myDiagram.removeParts(node.findTreeParts());
                  myDiagram.commitTransaction("remove dept");
                }
              }
            }
          )
        );

      // define the Link template
      myDiagram.linkTemplate =
        $(go.Link, go.Link.Orthogonal,
          { corner: 5, relinkableFrom: true, relinkableTo: true },
          $(go.Shape, { strokeWidth: 1.5, stroke: "#F5F5F5" }));  // the link shape

      // read in the JSON-format data from the "mySavedModel" element
      load();


      // support editing the properties of the selected person in HTML
      if (window.Inspector) myInspector = new Inspector("myInspector", myDiagram,
        {
          properties: {
            "key": { readOnly: true },
            "comments": {}
          }
        });

      // Setup zoom to fit button
      document.getElementById('zoomToFit').addEventListener('click', function() {
        myDiagram.commandHandler.zoomToFit();
      });

      document.getElementById('centerRoot').addEventListener('click', function() {
        myDiagram.scale = 1;
        myDiagram.commandHandler.scrollToPart(myDiagram.findNodeForKey(1));
      });

    } // end init

    // Show the diagram's model in JSON format
    function save() {
      document.getElementById("mySavedModel").value = myDiagram.model.toJson();
      myDiagram.isModified = false;
    }
    function load() {
      myDiagram.model = go.Model.fromJson(document.getElementById("mySavedModel").value);
      // make sure new data keys are unique positive integers
      var lastkey = 1;
      myDiagram.model.makeUniqueKeyFunction = function(model, data) {
        var k = data.key || lastkey;
        while (model.findNodeDataForKey(k)) k++;
        data.key = lastkey = k;
        return k;
      };
    }
    window.addEventListener('DOMContentLoaded', init);
  </script>

<div id="sample">
  <div id="myDiagramDiv" style="background-color: #34343C; border: solid 1px black; height: 570px;"></div>
  <p><button id="zoomToFit">Zoom to Fit</button> <button id="centerRoot">Center on root</button></p>

  <div>
    <div id="myInspector">
    </div>
  </div>
  
  <div>
    <div>
      <button id="SaveButton" onclick="save()">Save</button>
      <button onclick="load()">Load</button>
      Diagram Model saved in JSON format:
    </div>
    <textarea id="mySavedModel" style="width:100%; height:270px;">
      { "class": "TreeModel",
  "nodeDataArray": [

{"key":1,"name":"JUAN PEREZ DIAZ","title":"DIRECTOR"},


{"key":2,"name":"BOMBINI ANALIA","title":"SUBDIRECTORA","parent":1},


{"key":3,"name":"SAAVEDRA VANINA","title":"DIVISION RECEPTORIA Y PLANIFICACION","parent":2},
{"key":4,"name":"","title":"DIVISION INFORMES JUDICIALES Y POLICIALES","parent":2},
{"key":6,"name":"JAVIER RAMIRO","title":"DIVISION EXPEDICION DE ANTECEDENTES","parent":2},
{"key":7,"name":"CABRECICH EDUARDO","title":"DIVISION REGISTROS NOMINALES","parent":2},
{"key":8,"name":"MARIA INES FORTUNATTI","title":"DIVISION DACTILOSCOPIA","parent":2},


{"key":206,"name":"-","title":"-","parent":2},
{"key":9,"name":"URBE SEBASTIAN","title":"SECCI??N LOGISTICA Y ADMINISTRACION","parent":206},
{"key":207,"name":"SILVA MARCELO FABIAN","title":"SECCI??N LOGISTICA Y ADMINISTRACION","parent":9},


{"key":208,"name":"-","title":"-","parent":2},
{"key":10,"name":"LUCIANA LUGONES","title":"SECCI??N PERSONAL Y EXPEDIENTES","parent":208},
{"key":209,"name":"PELLIZA DIEGO ","title":"SECCI??N PERSONAL Y EXPEDIENTES","parent":10},
{"key":210,"name":"GAUNA SABRINA","title":"SECCI??N PERSONAL Y EXPEDIENTES","parent":10},
{"key":211,"name":"SILLES GIULIANO","title":"SECCI??N PERSONAL Y EXPEDIENTES","parent":10},

{"key":212,"name":"-","title":"","comments":"","parent":2},
{"key":216,"name":"NICOLINI GUILLERMO","title":"SECCI??N SOPORTE TECNICO","parent":212},
{"key":213,"name":"AFRIBO VIVIANA","title":"SECCI??N SOPORTE TECNICO","parent":216},
{"key":214,"name":"GATTI ESTEBAN","title":"SECCI??N SOPORTE TECNICO","parent":216},
{"key":215,"name":"ACU??A EDGARDO","title":"SECCI??N SOPORTE TECNICO","parent":216},


{"key":11,"name":"SORIANO FRANCO EMANUEL","title":"SECCI??N REGISTRO DE TRAMITES","parent":3},
{"key":12,"name":"DE VERA MARIANGELES","title":"SECCI??N ASUNTOS LEGALES","parent":3},
{"key":13,"name":"VANINA ALTUZARRA","title":"SECCI??N PROCESADOS","parent":4},
{"key":14,"name":"EDITH LOPEZ","title":"SECCI??N CONTRAVENTORES","parent":4},
{"key":15,"name":"OSCAR DIAZ","title":"SECCI??N ARCHIVO","parent":4},
{"key":16,"name":"NATALIA SARAVIA","title":"SECCI??N EXPEDIENTES","parent":6},
{"key":17,"name":"ANASTASIO MARINA","title":"SECCI??N CERTIFICADOS","parent":6},
{"key":18,"name":"FARIAS ","title":"SECCI??N CAPTURAS Y SECUESTROS","parent":7},
{"key":19,"name":"ORTIZ NOELIA","title":"SECCI??N INDICE INFORMATICO","parent":7},
{"key":20,"name":"VIVIANA NAVARRO","title":"SECCI??N DECADACTILAR","parent":8},
{"key":22,"name":"MARINA GALLETA","title":"SECCI??N AFIS","parent":8},


{"name":"CANTON NICOL??S RUB??N","title":"SEC. REGISTRO DE TRAMITES","comments":"","parent":11,"key":5},
{"name":"PELLIZA TAMARA ELIZABETH","title":"SEC. REGISTRO DE TRAMITES","comments":"","parent":11,"key":21},
{"name":"GIMENEZ MAURICIA","title":"SEC. REGISTRO DE TRAMITES","comments":"","parent":11,"key":199},

{"name":"VELOZO JUAN IGNACIO","title":"SECCI??N PROCESADOS","comments":"","parent":13,"key":23},
{"name":"SALINAS JUAN CARLOS","title":"SECCI??N PROCESADOS","comments":"","parent":13,"key":25},
{"name":"GOLDSZTEIN PABLO ","title":"SECCI??N PROCESADOS","comments":"","parent":13,"key":26},
{"name":"MEJIAS BETIANA ","title":"SECCI??N PROCESADOS","comments":"","parent":13,"key":27},
{"name":"TEPPA IRMA SUSANA","title":"SECCI??N PROCESADOS","comments":"","parent":13,"key":28},
{"name":"BRITEZ ANA MARISA","title":"SECCI??N PROCESADOS","comments":"","parent":13,"key":29},
{"name":"BASSO FEDERICO ","title":"SECCI??N PROCESADOS","comments":"","parent":13,"key":30},
{"name":"BEVERAGGI EUGENIA","title":"SECCI??N PROCESADOS    ","comments":"","parent":13,"key":31},
{"name":"FALCON YESICA ","title":"SECCI??N PROCESADOS","comments":"","parent":13,"key":32},
{"name":"FRIAS MARIA VICTORIA","title":"SECCI??N PROCESADOS","comments":"","parent":13,"key":33},
{"name":"ALBORNOZ  ORIETA","title":"SECCI??N PROCESADOS","comments":"","parent":13,"key":34},
{"name":"AYALA GUSTAVO ","title":"SECCI??N PROCESADOS","comments":"","parent":13,"key":35},
{"name":"VERA ROCIO SOLEDAD","title":"SECCI??N PROCESADOS","comments":"","parent":13,"key":46},
{"name":"CASAL JUAN LUCAS","title":"SECCI??N PROCESADOS","comments":"","parent":13,"key":37},
{"name":"D'ANGELO NATALIA ","title":"SECCI??N PROCESADOS","comments":"","parent":13,"key":38},
{"name":"DEFEIS RODRIGO ","title":"SECCI??N PROCESADOS","comments":"","parent":13,"key":39},
{"name":"FERNANDEZ DALMAU RODRIGO ","title":"SECCI??N PROCESADOS","comments":"","parent":13,"key":40},
{"name":"FERNANDEZ DALMAU SANTIAGO ","title":"SECCI??N PROCESADOS","comments":"","parent":13,"key":41},
{"name":"JAIME PABLO GABRIEL","title":"SECCI??N PROCESADOS","comments":"","parent":13,"key":42},
{"name":"PONCE NICOLAS ","title":"SECCI??N PROCESADOS","comments":"","parent":13,"key":43},
{"name":"ROCHA ANDREA ","title":"SECCI??N PROCESADOS","comments":"","parent":13,"key":44},
{"name":"CARABALLO VANESA ","title":"SECCI??N PROCESADOS","comments":"","parent":13,"key":45},
{"name":"BOMBARDELLI GIULIANO ","title":"SECCI??N PROCESADOS","comments":"","parent":13,"key":36},
{"name":"URBE EMMANUEL","title":"SECCI??N PROCESADOS","comments":"","parent":13,"key":47},


{"name":"LUNATI CALVO CECILIA","title":"SECCI??N CONTRAVENTORES","comments":"","parent":14,"key":144},
{"name":"PATERNO LIA HAIDE","title":"SECCI??N CONTRAVENTORES","comments":"","parent":14,"key":145},
{"name":"BERON MARIEL EDITH","title":"SECCI??N CONTRAVENTORES","comments":"","parent":14,"key":146},
{"name":"SENA SERGIO DANIEL","title":"SECCI??N CONTRAVENTORES","comments":"","parent":14,"key":147},
{"name":"ANDREATTA ROXANA","title":"SECCI??N CONTRAVENTORES","comments":"","parent":14,"key":148},
{"name":"FAJRE ADRIANA ISABEL","title":"SECCI??N CONTRAVENTORES","comments":"","parent":14,"key":149},
{"name":"OVIEDO ROSARIO ISABEL","title":"SECCI??N CONTRAVENTORES","comments":"","parent":14,"key":150},
{"name":"PEREZ STELLA MARIS","title":"SECCI??N CONTRAVENTORES","comments":"","parent":14,"key":151},
{"name":"CARRASCO GRACIELA EDITH","title":"SECCI??N CONTRAVENTORES","comments":"","parent":14,"key":152},
{"name":"PEREYRA RAMON ANTONIO","title":"SECCI??N CONTRAVENTORES","comments":"","parent":14,"key":153},


{"name":"GIGENA MARIA DEL CARMEN","title":"SEC. ARCHIVO PRONTUARIOS","comments":"","parent":15,"key":154},
{"name":"GERZEL JOSE LUIS","title":"SEC. ARCHIVO PRONTUARIOS","comments":"","parent":15,"key":155},
{"name":"MEJIAS SEBASTIAN","title":"SEC. ARCHIVO PRONTUARIOS","comments":"","parent":15,"key":156},
{"name":"GAZZOTTI PABLO","title":"SEC. ARCHIVO PRONTUARIOS","comments":"","parent":15,"key":157},
{"name":"GUARDA NICOLAS ROMAN","title":"SEC. ARCHIVO PRONTUARIOS","comments":"","parent":15,"key":158},
{"name":"SOSA LEANDRO","title":"SEC. ARCHIVO PRONTUARIOS","comments":"","parent":15,"key":159},
{"name":"BALBUENA ROBERTO","title":"SEC. ARCHIVO PRONTUARIOS","comments":"","parent":15,"key":160},
{"name":"SOSA LEONARDO","title":"SEC. ARCHIVO PRONTUARIOS","comments":"","parent":15,"key":161},
{"name":"DIONISIO NORBERTO","title":"SEC. ARCHIVO PRONTUARIOS","comments":"","parent":15,"key":162},
{"name":"LUENGO CARLOS","title":"SEC. ARCHIVO PRONTUARIOS","comments":"","parent":15,"key":163},
{"name":"NU??EZ EZEQUIEL","title":"SEC. ARCHIVO PRONTUARIOS","comments":"","parent":15,"key":164},
{"name":"CABERTA PEDRO ANTONIO","title":"SEC. ARCHIVO PRONTUARIOS","comments":"","parent":15,"key":165},


{"name":"GONZALEZ MORI BELEN","title":"SECCI??N CERTIFICADOS","comments":"","parent":16,"key":166},
{"name":"GOTELLI ANA CRISTINA","title":"SECCI??N CERTIFICADOS","comments":"","parent":16,"key":167},
{"name":"SPOSITO PATRICIA ","title":"SECCI??N CERTIFICADOS","comments":"","parent":16,"key":168},
{"name":"LASSALLE PATRICIA ","title":"SECCI??N CERTIFICADOS","comments":"","parent":16,"key":169},
{"name":"BASUALTO CARLA ","title":"SECCI??N CERTIFICADOS","comments":"","parent":16,"key":170},
{"name":"SANCHEZ FLAVIA","title":"SECCI??N CERTIFICADOS","comments":"","parent":16,"key":171},

{"name":"DA PIEVE LORENA","title":"SECCI??N CERTIFICADOS","comments":"","parent":17,"key":172},
{"name":"COLMENARES NESTOR","title":"SECCI??N CERTIFICADOS","comments":"","parent":17,"key":173},
{"name":"MOREIRA VANESA EDITH","title":"SECCI??N CERTIFICADOS","comments":"","parent":17,"key":174},
{"name":"SENOSIAIN SOFIA NAIR","title":"SECCI??N CERTIFICADOS","comments":"","parent":17,"key":175},
{"name":"MI??O ADRIANA ANDREA","title":"SECCI??N CERTIFICADOS","comments":"","parent":17,"key":176},
{"name":"MOLINA CAROLINA ","title":"SECCI??N CERTIFICADOS","comments":"","parent":17,"key":177},


{"name":"DA FONSECA GUILLERMO","title":"SECCI??N CAPTURAS Y SECUESTROS","comments":"","parent":18,"key":75},
{"name":"TOLEDANO ADRIANA EDITH ","title":"SECCI??N CAPTURAS Y SECUESTROS","comments":"","parent":18,"key":76},
{"name":"DICHIRO CLAUDIA KARINA","title":"SECCI??N CAPTURAS Y SECUESTROS","comments":"","parent":18,"key":77},
{"name":"GODOY LIDIA SUSANA","title":"SECCI??N CAPTURAS Y SECUESTROS","comments":"","parent":18,"key":78},
{"name":"GUTIERREZ MARIA EUGENIA","title":"SECCI??N CAPTURAS Y SECUESTROS","comments":"","parent":18,"key":79},
{"name":"MARTINEZ MARIA LAURA","title":"SECCI??N CAPTURAS Y SECUESTROS","comments":"","parent":18,"key":80},
{"name":"SENOSIAIN MONICA BEATRIZ ","title":"SECCI??N CAPTURAS Y SECUESTROS","comments":"","parent":18,"key":81},
{"name":"SUAREZ VIRGINIA ELIZABETH","title":"SECCI??N CAPTURAS Y SECUESTROS","comments":"","parent":18,"key":82},
{"name":"AZAR BORGARELLI MARIA CONSTANZA","title":"SECCI??N CAPTURAS Y SECUESTROS","comments":"","parent":18,"key":83},
{"name":"CIARALLO ROCIO BELEN","title":"SECCI??N CAPTURAS Y SECUESTROS","comments":"","parent":18,"key":84},
{"name":"IBARRA MARIA BELEN","title":"SECCI??N CAPTURAS Y SECUESTROS","comments":"","parent":18,"key":85},
{"name":"LEVANDOSKY CATALINA SOLEDAD ","title":"SECCI??N CAPTURAS Y SECUESTROS","comments":"","parent":18,"key":86},
{"name":"MANCIONI MARIA LAURA","title":"SECCI??N CAPTURAS Y SECUESTROS","comments":"","parent":18,"key":87},
{"name":"MARTINEZ CAROLINA MARIANA","title":"SECCI??N CAPTURAS Y SECUESTROS","comments":"","parent":18,"key":88},
{"name":"MIRICH JULIA","title":"","comments":"SECCI??N CAPTURAS Y SECUESTROS","parent":18,"key":89},
{"name":"VARELA JAZMIN ","title":"SECCI??N CAPTURAS Y SECUESTROS","comments":"","parent":18,"key":90},
{"name":"VILLAFA??E MARIA CELIA","title":"SECCI??N CAPTURAS Y SECUESTROS","comments":"","parent":18,"key":91},
{"name":"VECERICA GABRIELA ISABEL ","title":"SECCI??N CAPTURAS Y SECUESTROS","comments":"","parent":18,"key":92},
{"name":"MAGDALENA FABIO JAVIER","title":"SECCI??N CAPTURAS Y SECUESTROS","comments":"","parent":18,"key":93},
{"name":"MARCELLI VANINA ERIKA","title":"SECCI??N CAPTURAS Y SECUESTROS","comments":"","parent":18,"key":94},
{"name":"ALI JAVIER FERNANDO","title":"SECCI??N CAPTURAS Y SECUESTROS","comments":"","parent":18,"key":95},


{"name":"DIAZ ABACA AMORINA VANESA","title":"SECCI??N.INDICE INFORMATICO","comments":"","parent":19,"key":96},
{"name":"BURSICH ADRIANA SILVIA","title":"SECCI??N INDICE INFORMATICO","comments":"","parent":19,"key":97},
{"name":"GUERRIERI ANDREA VERONICA","title":"SECCI??N INDICE INFORMATICO","comments":"","parent":19,"key":98},
{"name":"PETROFF EZEQUIEL","title":"SECCI??N INDICE INFORMATICO","comments":"","parent":19,"key":99},
{"name":"TABERNABERRY JOSE MARIA","title":"SECCI??N INDICE INFORMATICO","comments":"","parent":19,"key":100},
{"name":"RODRIGUEZ GLORIA ESTHER","title":"SECCI??N INDICE INFORMATICO","comments":"","parent":19,"key":101},
{"name":"RODRIGUEZ MARCELA ESTELA","title":"SECCI??N INDICE INFORMATICO","comments":"","parent":19,"key":102},
{"name":"ALVAREZ VERONICA AMELIA","title":"SECCI??N INDICE INFORMATICO","comments":"","parent":19,"key":104},
{"name":"GONZALEZ VANESA ANABEL","title":"SECCI??N INDICE INFORMATICO","comments":"","parent":19,"key":106},
{"name":"TORRES LAURA RUT","title":"SECCI??N INDICE INFORMATICO","comments":"","parent":19,"key":105},
{"name":"NAVARRO OSCAR ANDRES","title":"SECCI??N INDICE INFORMATICO","comments":"","parent":19,"key":107},
{"name":"AGUIRRE AGUSTINA","title":"SECCI??N INDICE INFORMATICO","comments":"","parent":19,"key":108},
{"name":"ALFANO VALENTIN","title":"SECCI??N INDICE INFORMATICO","comments":"","parent":19,"key":109},
{"name":"ALONSO MATIAS EZEQUIEL","title":"SECCI??N INDICE INFORMATICO","comments":"","parent":19,"key":110},
{"name":"FERNANDEZ LEANDRO EZEQUIEL","title":"SECCI??N INDICE INFORMATICO","comments":"","parent":19,"key":111},
{"name":"GEREZ JUAN PABLO","title":"SECCI??N INDICE INFORMATICO","comments":"","parent":19,"key":64},
{"name":"GOMEZ MACARENA GUILLERMINA","title":"SECCI??N INDICE INFORMATICO","comments":"","parent":19,"key":65},
{"name":"LUCERO ROCIO MAGALI","title":"SECCI??N INDICE INFORMATICO","comments":"","parent":19,"key":66},
{"name":"MARTINEZ GISELLE ANAHI","title":"SECCI??N INDICE INFORMATICO","comments":"","parent":19,"key":67},
{"name":"NICOLINI DIEGO ANDRES","title":"SECCI??N INDICE INFORMATICO","comments":"","parent":19,"key":68},
{"name":"RAMOS SERGIO AGUSTIN","title":"SECCI??N INDICE INFORMATICO","comments":"","parent":19,"key":112},
{"name":"RAPOSO LUCIANA","title":"SECCI??N INDICE INFORMATICO","comments":"","parent":19,"key":128},
{"name":"CORDIDO CECILIA NOEMI","title":"SECCI??N INDICE INFORMATICO","comments":"","parent":19,"key":129},


{"name":"BARRIA MAGALI ROSA","title":"SECCI??N ASUNTOS LEGALES","comments":"","parent":12,"key":178},


{"name":"DE JESUS MYRIAM","title":"","comments":"","parent":20,"key":200},
{"name":"AVILA JUAN JOSE","title":"","comments":"","parent":20,"key":201},
{"name":"ROUAUX NICOLAS","title":"","comments":"","parent":20,"key":202},
{"name":"CATALINI MARIA","title":"","comments":"","parent":20,"key":203},
{"name":"MARTINEZ BRUNO","title":"","comments":"","parent":20,"key":204},
{"name":"ANTONINI EDITH ","title":"","comments":"","parent":20,"key":205},


{"name":"GIOTTA ELIZABETH ","title":" SECCI??N A.F.I.S ","comments":"","parent":22,"key":197},
{"name":"ROLON GABRIELA","title":" SECCI??N A.F.I.S","comments":"","parent":22,"key":198},
{"name":"VAZQUEZ LUIS","title":" SECCI??N A.F.I.S","comments":"","parent":22,"key":179},
{"name":"VALDES EVELYN","title":" SECCI??N A.F.I.S","comments":"","parent":22,"key":190},
{"name":"TABARES DANIEL ","title":" SECCI??N A.F.I.S","comments":"","parent":22,"key":181},
{"name":"MEDINA NOELIA ","title":" SECCI??N A.F.I.S","comments":"","parent":22,"key":182},
{"name":"GARCIA GABRIELA ","title":" SECCI??N A.F.I.S","comments":"","parent":22,"key":183},
{"name":"ZAVALA GABRIELA","title":" SECCI??N A.F.I.S","comments":"","parent":22,"key":184},
{"name":"POY CLAUDIA","title":" SECCI??N A.F.I.S","comments":"","parent":22,"key":185},
{"name":"GODOY ROCIO","title":" SECCI??N A.F.I.S","comments":"","parent":22,"key":192},
{"name":"GOMEZ ANALIA ","title":" SECCI??N A.F.I.S","comments":"","parent":22,"key":193},
{"name":"GAUTO BARBARA","title":" SECCI??N A.F.I.S","comments":"","parent":22,"key":188},
{"name":"RAFFA FLORENCIA","title":" SECCI??N A.F.I.S","comments":"","parent":22,"key":189},
{"name":"ZECCHIN GUSTAVO","title":" SECCI??N A.F.I.S","comments":"","parent":22,"key":191},
{"name":"CHAMORRO MARIA","title":" SECCI??N A.F.I.S","comments":"","parent":22,"key":180},
{"name":"CHIRUZZI EZEQUIEL","title":" SECCI??N A.F.I.S","comments":"","parent":22,"key":186},
{"name":"FERNANDEZ LEANDRO","title":" SECCI??N A.F.I.S","comments":"","parent":22,"key":187},
{"name":"DOMINGUEZ YEMINA ","title":" SECCI??N A.F.I.S","comments":"","parent":22,"key":194}
]}
    </textarea>
  </div>
</div>
    </div>
    <!-- * * * * * * * * * * * * * -->
    <!--  End of GoJS sample code  -->
  </div>
</body>
<!--  This script is part of the gojs.net website, and is not needed to run the sample -->
<script src="../assets/js/goSamples.js"></script>


<script type="text/javascript" src="go.js">
	
</script>
</html>











