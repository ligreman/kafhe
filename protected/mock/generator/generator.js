const fs = require('fs'),
    cmd = require('node-cmd');

let ahora = new Date();
logg('-------------------  ' + ahora.toLocaleString() + '  -------------------');

// Lo ejecuto 1 de cada 5 veces
if (!rand(5)) {
    logg('Ahora no toca');
    process.exit(0);
}

/*************************************************************************/
/*************************************************************************/
/*************************************************************************/
/*************************************************************************/
/*************************************************************************/
/*************************************************************************/
var MAX_LIST_SIZE = 3;
var idxIds = ["i", "j", "k", "l", "m", "x"];
var quotes = ['"Fact, all alphabet precipitate, pay to from"', '"you of the off was world regulatory upper then twists need"',
    '" to her is never myself it to seemed both felt hazardous almost"', '" dresses never great decided a founding ahead that for now think, to"',
    '" the tuned her answering he mellower"', '" those texts. Timing although forget belong, "', '"display, friends bit explains advantage at"',
    '" that quite sleep seen their horn of with had offers"', '" forwards, as noting legs the temple shine."', '"I drew the even the transactions least,"',
    '"by the lowest offers influenced concepts stand in she"', '" narrow and to oh, definitely the changes"'];
var binaryOpsWithNonAssoc = ["+", "-", "*", "/", "<", ">", "<=", ">=", "==", "!=", "/\\", "\\/"];
var assignOps = ["+=", "-=", "*=", "/="];

/* terminals used */
var num, id_var, id_func, id_idx, binaryOP, quote, assignOP;

/* non-terminals used */
var Program, Assertion, Expr, TableExpr, Constant, FunctionDef, ColumnDef, Stmt, Assign, Condition, Call, Loop;

// non terminal class
function NonTerminal(name) {
    this.name = name;
    this.rules = [];
    this.addRule = function (rule) {
        this.rules.push(rule);
    };
    this.getRandomRule = function () {
        if (this.rules.length == 0) {
            return "";
        }
        return getRandomArrayElement(this.rules);
    };
    this.derive = function () {
        // console.log("derive: " + this.name);
        return (this.getRandomRule())();
    };
}

// terminal class
function Terminal(name, deriveFunc) {
    this.name = name;
    this.derive = deriveFunc;
}

/* --------- TERMINALS --------- */
/* Number */
num = new Terminal("Num", function () {
    return Math.round(Math.random() * 10);
});

/* ID */
id_var = new Terminal("ID", function () {
    return genVarName();
});

id_func = new Terminal("ID", function () {
    return genFunctionName();
});

id_idx = new Terminal("ID", function () {
    return getRandomArrayElement(idxIds);
});

/* OP */
binaryOP = new Terminal("OP", function () {
    return getRandomArrayElement(binaryOpsWithNonAssoc);
});

/* QUOTE */
quote = new Terminal("QUOTE", function () {
    return getRandomArrayElement(quotes);
});

/* ASSIGN OP */
assignOP = new Terminal("assignOP", function () {
    return getRandomArrayElement(assignOps);
});

/* --------- NON-TERMINALS --------- */

/* Program */
Program = new NonTerminal("Program");
Program.addRule(function () {
    return Assertion.derive();
});
Program.addRule(function () {
    return Constant.derive();
});
Program.addRule(function () {
    return FunctionDef.derive();
});
Program.addRule(function () {
    return ColumnDef.derive();
});

/* Assertion */
Assertion = new NonTerminal("Assertion");
Assertion.addRule(function () {
    return "assert " + Expr.derive() + " : " + quote.derive();
});


/* TableExpr */
TableExpr = new NonTerminal("TableExpr");
TableExpr.addRule(function () {
    return "COLS";
});
TableExpr.addRule(function () {
    return "ROWS";
});
TableExpr.addRule(function () {
    return "TABLE[" + Expr.derive() + "][" + Expr.derive() + "]";
});

/* Expr */
Expr = new NonTerminal("Expr");
Expr.addRule(function () {
    return num.derive();
});
Expr.addRule(function () {
    return id_var.derive();
});
Expr.addRule(function () {
    return Expr.derive() + " " + binaryOP.derive() + " " + Expr.derive();
});
Expr.addRule(function () {
    return "-" + Expr.derive();
});
Expr.addRule(function () {
    return "( " + Expr.derive() + " )";
});
Expr.addRule(function () {
    return TableExpr.derive();
});
Expr.addRule(function () {
    return Call.derive();
});

/* Stmt */
Stmt = new NonTerminal("Stmt");
Stmt.addRule(function () {
    return Expr.derive()
});
Stmt.addRule(function () {
    return Assign.derive()
});
Stmt.addRule(function () {
    return Condition.derive()
});
//Stmt.addRule(function () { return Loop.derive() });

/* Constant */
Constant = new NonTerminal("Constant");
Constant.addRule(function () {
    return "var " + id_var.derive() + " = " + Expr.derive();
});

/* FunctionDef */
FunctionDef = new NonTerminal("FunctionDef");
FunctionDef.addRule(function () {
    return "def " + id_func.derive() + "(" + makeList(id_var, ",", false) + "){\n" + makeList(Stmt, ";", true) + "\n}"
});

/* ColumnDef */
ColumnDef = new NonTerminal("ColumnDef");
ColumnDef.addRule(function () {
    return "def TABLE[" + Expr.derive() + "][" + id_idx.derive() + "] {\n" + makeList(Stmt, ";", true) + "\n}"
});

/* Assign */
Assign = new NonTerminal("Assign");
Assign.addRule(function () {
    return id_var.derive() + " " + assignOP.derive() + " " + Expr.derive();
});

/* Condition */
Condition = new NonTerminal("Condition");
Condition.addRule(function () {
    return "if(" + Expr.derive() + "){\n" + makeList(Stmt, ";", true) + "\n}";
});
Condition.addRule(function () {
    return "if(" + Expr.derive() + "){\n" + makeList(Stmt, ";", true) + "\n} else {\n" + makeList(Stmt, ";", true) + "\n}";
});

/* Call */
Call = new NonTerminal("Call");
Call.addRule(function () {
    return id_func.derive() + "(" + makeList(Expr, ",", false) + ")";
});

/* Loop */
Loop = new NonTerminal("Loop");
Loop.addRule(function () {
    return "for ( " + id_idx.derive() + " = " + Expr.derive() + ".." + Expr.derive() + " ) {\n" + makeList(Stmt, ";", true) + "\n}";
});

/* Utils */

// get a random element from an array
function getRandomArrayElement(arr) {
    return arr[Math.floor(Math.random() * arr.length)];
}

// create a list of 0+ elements
function makeList(item, seperator, isBody) {
    var listSize = Math.round(Math.random() * MAX_LIST_SIZE);
    var res = "";

    for (var i = 0; i < listSize; i++) {
        if (isBody) {
            res += "\t";
        }
        res += item.derive();
        if (i != listSize - 1) {
            res += seperator;
            if (isBody) {
                res += "\n";
            }
        }
    }

    return res;
}

/*************************************************************************/
/*************************************************************************/
/*************************************************************************/
/*************************************************************************/
/*************************************************************************/
/*************************************************************************/


// Variables
const directories = ['components', 'controllers', 'models'];
const extensions = ['.php', '.js', '.py', '.java'];

// Launcher
// Directorio de trabajo
const dir = '../../' + getRandom(directories);

// Contenido
let content = genFunction();
content = content.join('\n');
content += '\n\n' + Program.derive();

logg('Ahora si toca');

// Decido si creo fichero nuevo o edito uno
if (rand(4)) {
    //Nuevo
    const file = genFunctionName() + getRandom(extensions);

    fs.writeFileSync(dir + '/' + file, content);

    logg('Escrito el fichero nuevo: ' + dir + '/' + file);

    uploadToGit(dir + '/' + file);
} else {
    // Busco uno de los ficheros
    let fileList = [];
    fs.readdirSync(dir).forEach(function (file) {
        extensions.forEach(function (ext) {
            if (file.indexOf(ext) > -1) {
                // El fichero es de extensión aceptable
                fileList.push(file);
            }
        });
    });

    // Escojo uno al azar
    const file = getRandom(fileList);

    // Edito
    fs.appendFileSync(dir + '/' + file, content);
    logg('Editado el fichero: ' + dir + '/' + file);

    uploadToGit(dir + '/' + file);
}

// Generators
function genFunction() {
    let res = [], returnVar = genVarName();

    const veces = randomNum(10);

    res.push('function ' + genFunctionName() + '() {');
    res.push('  ' + returnVar + ' = null;');
    res = res.concat(genContent(returnVar, veces));
    res.push('  return ' + returnVar + ';');

    res.push('}');

    return res;
}

function genContent(returnVar, count) {
    let res = [], myVar = genVarName();

    for (let i = count; i > 0; i--) {
        // Statement
        if (rand(2)) {
            res = res.concat(genStatement(myVar));
        }

        // If
        if (rand(2)) {
            res = res.concat(genIf(myVar));
        }

        // For
        if (rand(4)) {
            res = res.concat(genFor(myVar));
        }

        // While
        if (rand(5)) {
            res = res.concat(genWhile(myVar));
        }

        // Other
        if (rand(3)) {
            res.push(Program.derive());
        }
    }

    res.push('  ' + returnVar + ' = ' + myVar + ';');

    return res;
}

function genStatement(variable) {
    let res = [];

    if (!variable) {
        variable = genVarName();
    }

    if (rand(3)) {
        const aux = genVarName();
        res.push('  ' + aux + ' = ' + genValue() + ';');
        res.push('  ' + variable + ' = ' + aux + ' + ' + genValue() + ';');
    } else {
        res.push('  ' + variable + '=' + genValue() + ';');
    }

    res.push(Program.derive());

    return res;
}

function genComparison(variable) {
    const operators = ['!=', '==', '>', '>=', '<', '<='];

    if (!variable) {
        variable = genVarName();
    }

    return variable + ' ' + getRandom(operators) + ' "' + genValue() + '"';
}

function genIf(variable) {
    let res = [];

    if (!variable) {
        variable = genVarName();
    }

    res.push(' if (' + genComparison(variable) + ') {');
    res = res.concat(genStatement());
    res = res.concat(genStatement(variable));
    res.push(' }');

    return res;
}

function genFor(variable) {
    let res = [];

    if (!variable) {
        variable = genVarName();
    }

    res.push(' for (' + variable + '=0; ' + variable + '<=5; ' + variable + '++) {');
    res = res.concat(genStatement(variable));

    if (rand(4)) {
        res = res.concat(genIf());
    }

    res = res.concat(genStatement());

    res.push(' }');

    return res;
}

function genWhile(variable) {
    let res = [];

    if (!variable) {
        variable = genVarName();
    }

    res.push(' while (' + genComparison(variable) + ') {');
    res = res.concat(genStatement(variable));

    if (rand(4)) {
        res = res.concat(genIf());
    }

    res = res.concat(genStatement());

    res.push(' }');

    return res;
}

function genValue() {
    let val = '';

    if (rand(2)) {
        val = randomNum(9999);
    } else {
        val = randomString(10);
    }

    return val;
}

function genVarName() {
    let name = '$';

    const varPrefix = ['my', 'var', 'aux', 'first', 'last', 'one', 'random', 'second', 'simplified', 'this', 'the'];
    const varBody = ['Char', 'String', 'Value', 'Item', 'Element', 'Position', 'Array', 'Stat', 'Number', 'Integer', 'Name', 'Url', 'File', 'Boolean'];

    if (rand(5)) {
        name += getRandom(varPrefix);
        name += getRandom(varBody);
    } else {
        name += getRandom(varBody).toLowerCase();
    }


    return name;
}

function genFunctionName() {
    let name = '';

    const functPrefix = ['get', 'set', 'process', 'call', 'do', 'remove', 'add', 'calc', 'update', 'select', 'insert', 'generate', 'download', 'upload'];
    const functBody = ['Data', 'Num', 'String', 'Boolean', 'Info', 'Element', 'Integer', 'Long', 'Float', 'Number', 'Name', 'Url', 'File', 'Content', 'Array', 'Enum', 'Collection', 'Id', 'Status', 'Response', 'Request', 'Error', 'Message', 'JSON', 'XML', 'TXT', 'YML', 'Log', 'Config', 'Dataset', 'Module', 'Library', 'Plugin', 'Dependency'];
    const functSufix = ['Completely', 'Partially', 'Fast', 'Securely', 'Santitize', 'Recursive', 'First', 'Again', 'Callback', 'Server', 'Client', 'Error', 'Callback'];

    name += getRandom(functPrefix) + getRandom(functBody);

    if (rand(5)) {
        name += getRandom(functSufix);
    }

    return name;
}

// Utils
function getRandom(array) {
    return array[Math.floor(Math.random() * array.length)];
}

function randomString() {
    let text = "";
    let possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    let max = randomNum(10);

    for (let i = 0; i < max; i++)
        text += possible.charAt(Math.floor(Math.random() * possible.length));

    return text;
}

function randomNum(max) {
    return Math.floor(Math.random() * max);
}

function rand(num) {
    return (Math.floor(Math.random() * num) + 1) === 1;
}


function uploadToGit(file) {
    const date = new Date();

    cmd.get(
        'git status',
        function (err, data, stderr) {
            console.log(err);
            console.log(data);
            console.log(stderr);

            cmd.get(
                'git add ' + file,
                function (err, data, stderr) {
                    console.log(err);
                    console.log(data);
                    console.log(stderr);

                    cmd.get(
                        'git commit -a -m "Updates ' + date.toLocaleString().replace(/\//g, '-') + '"',
                        function (err, data, stderr) {
                            console.log(err);
                            console.log(data);
                            console.log(stderr);

                            cmd.get(
                                'git push',
                                function (err, data, stderr) {
                                    console.log(err);
                                    console.log(data);
                                    console.log(stderr);
                                    logg('Subido a git');
                                }
                            );
                        }
                    );
                }
            );
        }
    );


    /*cmd.run('git add *');
    cmd.run('git commit -a -m "Updates ' + date.toLocaleString().replace(/\//g, '-') + '"');
    cmd.run('git push');*/
    // console.log('Subido a git');
}

function logg(msg) {
	console.log(msg);
	fs.appendFileSync('output.txt', msg + '\n');
}