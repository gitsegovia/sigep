/*
Script: Common.js
	Contains common implementations for custom classes. In Mootools is implemented in <Ajax> and <Fx>.

Author:
	Valerio Proietti, <http://mad4milk.net>

License:
	MIT-style license.
*/

/*
Class: Chain
	An "Utility" Class. Its methods can be implemented with <Class.implement> into any <Class>.
	Currently implemented in <Fx> and <Ajax>. In <Fx> for example, is used to execute a list of function, one after another, once the effect is completed.
	The functions will not be fired all togheter, but one every completion, to create custom complex animations.

Example:
	(start code)
	var myFx = new Fx.Style('element', 'opacity');

	myFx.start(1,0).chain(function(){
		myFx.start(0,1);
	}).chain(function(){
		myFx.start(1,0);
	}).chain(function(){
		myFx.start(0,1);
	});
	//the element will appear and disappear three times
	(end)
*/

var Chain = new Class({

	/*
	Property: chain
		adds a function to the Chain instance stack.

	Arguments:
		fn - the function to append.
	*/

	chain: function(fn){
		this.chains = this.chains || [];
		this.chains.push(fn);
		return this;
	},

	/*
	Property: callChain
		Executes the first function of the Chain instance stack, then removes it. The first function will then become the second.
	*/

	callChain: function(){
		if (this.chains && this.chains.length) this.chains.splice(0, 1)[0].delay(10, this);
	},

	/*
	Property: clearChain
		Clears the stack of a Chain instance.
	*/

	clearChain: function(){
		this.chains = [];
	}

});

/*
Class: Events
	An "Utility" Class. Its methods can be implemented with <Class.implement> into any <Class>.
	In <Fx> Class, for example, is used to give the possibility add any number of functions to the Effects events, like onComplete, onStart, onCancel

Example:
	(start code)
	var myFx = new Fx.Style('element', 'opacity').addEvent('onComplete', function(){
		alert('the effect is completed');
	}).addEvent('onComplete', function(){
		alert('I told you the effect is completed');
	});

	myFx.start(0,1);
	//upon completion it will display the 2 alerts, in order.
	(end)
*/

var Events = new Class({

	/*
	Property: addEvent
		adds an event to the stack of events of the Class instance.
	*/

	addEvent: function(type, fn){
		this.events = this.events || {};
		this.events[type] = this.events[type] || [];
		if (fn != Class.empty && !this.events[type].test(fn)) this.events[type].push(fn);
		return this;
	},

	/*
	Property: fireEvent
		fires all events of the specified type in the Class instance.
	*/

	fireEvent: function(type, args, delay){
		if (!this.events || !this.events[type]) return this;
		this.events[type].each(function(fn){
			fn.create({'bind': this, 'delay': delay, 'arguments': args})();
		}, this);
		return this;
	},

	/*
	Property: removeEvent
		removes an event from the stack of events of the Class instance.
	*/

	removeEvent: function(type, fn){
		if (!this.events || !this.events[type]) return this;
		this.events[type].remove(fn);
		return this;
	}

});

/*
Class: Options
	An "Utility" Class. Its methods can be implemented with <Class.implement> into any <Class>.
	Used to automate the options settings, also adding Class <Events> when the option begins with on.
*/

var Options = new Class({

	/*
	Property: fireEvent
		sets this.options

	Arguments:
		defaults - the default set of options
		options - the user entered options. can be empty too.

	Note:
		if your Class has <Events> implemented, every option beginning with on, foloowed by a capital letter (onComplete) becomes an Class instance event.
	*/

	setOptions: function(defaults, options){
		this.options = Object.extend(defaults, options);
		if (!this.addEvent) return this;
		for (var option in this.options){
			if (option.test('^on[A-Z]') && $type(this.options[option]) == 'function') this.addEvent(option, this.options[option]);
		}
		return this;
	}

});