/*
Script: Json.js
	Simple Json parser and Stringyfier, See: <http://www.json.org/>

Author:
	Valerio Proietti, <http://mad4milk.net> & Christophe Beyls <http://www.digitalia.be>.

License:
	MIT-style license.
*/

/*
Class: Json
	Simple Json parser and Stringyfier, See: <http://www.json.org/>
*/

var Json = {

	/*
	Property: toString
		Converts an object to a string, to be passed in server-side scripts as a parameter. Although its not normal usage for this class, this method can also be used to convert functions and arrays to strings.

	Arguments:
		obj - the object to convert to string

	Returns:
		A json string

	Example:
		(start code)
		Json.toString({apple: 'red', lemon: 'yellow'}); "{"apple":"red","lemon":"yellow"}" //don't get hung up on the quotes; it's just a string.
		(end)
	*/

	toString: function(obj){
		var string = [];
		switch ($type(obj)){
			case 'string':
				return '"'+obj.replace(new RegExp('(["\\\\])', 'g'), '\\$1')+'"';
			case 'array':
				obj.each(function(ar){
					string.push(Json.toString(ar));
				});
				return '['+string.join(',')+']';
			case 'object':
				for (var property in obj) string.push('"'+property+'":'+Json.toString(obj[property]));
				return '{'+string.join(',')+'}';
		}
		return String(obj);
	},

	/*
	Function: evaluate
		converts a json string to an javascript Object.

	Arguments:
		str - the string to evaluate.

	Example:
		>var myObject = Json.evaluate('{"apple":"red","lemon":"yellow"}');
		>//myObject will become {apple: 'red', lemon: 'yellow'}
	*/

	evaluate: function(str){
		return eval('(' + str + ')');
	}

};