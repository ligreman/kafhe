<html>
<head>
    <style type="text/css">
        *, html { margin: 0; padding: 0;}

        #traitTree { border-collapse: collapse; }
        #traitTree td { width: 100px; height: 100px; }
        #traitTree td.c1 { background: url(cells/c1.png) center center no-repeat;}
        #traitTree td.c2 { background: url(cells/c2.png) center center no-repeat;}
        #traitTree td.c3 { background: url(cells/c3.png) center center no-repeat;}
        #traitTree td.c4 { background: url(cells/c4.png) center center no-repeat;}
        #traitTree td.c5 { background: url(cells/c5.png) center center no-repeat;}
        #traitTree td.c6 { background: url(cells/c6.png) center center no-repeat;}
        #traitTree td.c7 { background: url(cells/c7.png) center center no-repeat;}
        #traitTree td.c8 { background: url(cells/c8.png) center center no-repeat;}
        #traitTree td.c9 { background: url(cells/c9.png) center center no-repeat;}
        #traitTree td.c10 { background: url(cells/c10.png) center center no-repeat;}
        #traitTree td.c11 { background: url(cells/c11.png) center center no-repeat;}
        #traitTree td.c12 { background: url(cells/c12.png) center center no-repeat;}
        #traitTree td.c13 { background: url(cells/c13.png) center center no-repeat;}
        #traitTree td.c14 { background: url(cells/c14.png) center center no-repeat;}
        #traitTree td.c15 { background: url(cells/c15.png) center center no-repeat;}

        #traitTree td.rotulo {
            padding-left: 10px;
            font-size: 1.2em;
            font-weight: bold;
        }
        #traitTree td img {
           border: 4px solid transparent;
           width: 66px;
           margin: 13px;
        }
        #traitTree td img:hover {
            cursor: pointer;
            border-color: gold;
        }
        #traitTree td img.active {
            border-color: gold;
            border-style: groove;
            cursor: default;
        }

        #traitTree tr.experto {
            background: #C6E5FF;
        }
        #traitTree tr.maestro {
            background: #a9c790;
        }
    </style>
</head>
<body>


<table id="traitTree">
    <tr>
        <td class="rotulo">APRENDIZ</td>
        <td></td>
        <td class="c7"></td>
        <td class="c5"><img class="active" src="chemical-drop.png" /></td>
        <td class="c9"></td>
        <td></td>
    </tr>
    <tr class="">
        <td></td>
        <td class=""></td>
        <td class="c6"><img class="" src="chemical-drop.png" /></td>
        <td class=""></td>
        <td class="c12"><img class="" src="chemical-drop.png" /></td>
        <td class="c9"></td>
    </tr>
    <tr class="experto">
        <td class="rotulo">EXPERTO</td>
        <td class=""></td>
        <td class="c6"><img class="" src="chemical-drop.png" /></td>
        <td class=""></td>
        <td class="c12"><img class="" src="chemical-drop.png" /></td>
        <td class="c9"></td>
    </tr>
    <tr class="experto">
        <td></td>
        <td class=""></td>
        <td class="c6"><img class="" src="chemical-drop.png" /></td>
        <td class=""></td>
        <td class="c12"><img class="" src="chemical-drop.png" /></td>
        <td class="c9"></td>
    </tr>
    <tr class="maestro">
        <td class="rotulo">MAESTRO</td>
        <td class=""></td>
        <td class="c6"><img class="" src="chemical-drop.png" /></td>
        <td class=""></td>
        <td class="c12"><img class="" src="chemical-drop.png" /></td>
        <td class="c9"></td>
    </tr>
</table>
<?php

?>






</body>

</html>