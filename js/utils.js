Date.dayNames = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
Date.monthNames = ['January','February','March','April','May','June','July','August','September','October','November','December']

/**
 * Created a String.format function, because I was sick of constantly concatenating strings.
 * Works similarly to Python's string format function.
 */
String.prototype.format = function () {
	var s = this;
	
	for (var i = 0; i < arguments.length; i++) {
		s = s.replace("{" + i + "}", arguments[i]);
	}
	
	return s;
};

/**
 * If a is specified, return a random number in the range [a, b].
 * If b is not specified, return a random number in the range [0, a].
 * 
 * @param {Number} a
 * @param {Number} b
 */
Math.randInt = function (a, b) {
	if (b === undefined) {
		b = a;
		a = 0;
	}
	
	return Math.floor(Math.random() * (b - a + 1)) + a;
};

/**
 * Wrapper for utility methods.
 */
function Utils () {
	// empty
}

/**
 * Return True iff given date is a valid date
 * The dateStr is in this format: yyyy-mm-dd
 * Inspired by this SOF question: http://stackoverflow.com/q/1353684/755934
 * @param {String} dateStr
 */
Utils.isValidDate = function (dateStr) {
	var datePattern = /(\d{4})\-(\d{2})\-(\d{2})/;
	var match = dateStr.match(datePattern);
	
	if (match === null) {
		return false;
	}
	
	var d = new Date(match[1], match[2], match[3]);
	
	if (Object.prototype.toString.call(d) !== "[object Date]") {
		return false;
	}
	
	return !isNaN(d.getTime());
};

/**
 * Generate a random credit-card number.
 * i.e. a random 16-digit number
 * 
 * @returns {Number}
 */
Utils.genRandomCC = function () {
	var n = 0, min;
	
	for (var i = 0; i < 16; i++) {
		// ensures that leading digit will not be 0 (so makes sure we have enough digits)
		min = i == 15 ? 1 : 0;
		
		n += Math.randInt(min, 9) * Math.pow(10, i);
	}
	
	return n;
};

/**
 * Return a random element from given array.
 * @param {Array} arr
 */
Utils.randomChoice = function (arr) {
	return arr[Math.randInt(0, arr.length - 1)];
};

Utils.getRandomName = function () {
	var firstNames = ["Alice", "Will", "Ross", "Elia", "Sergei", "Jackie", "Andrew", "Kevin", "Daniel", "Sasha"];
	var lastNames = ["Smith", "Liu", "Wu", "Slavovich", "Lee", "Jackson", "Snow", "Fierce"];
	
	return Utils.randomChoice(firstNames) + " " + Utils.randomChoice(lastNames);
};

/**
 * Return a random date in the near future.
 * Not uniformly random, but good enough for what I'm doing
 * 
 * @returns {Date}
 */
Date.randomFutureDate = function () {
	// get some kind of random interval in the next 2 years
	var dYear = Math.randInt(0, 1);
	var dMonth = Math.randInt(0, 11);
	var dDay = Math.randInt(1, 28);
	var today = new Date();
	
	return new Date(today.getFullYear() + dYear, today.getMonth() + dMonth, today.getDate() + dDay);
};

/**
 * Return a new string which is right-padded to `num` characters.
 * Default character is "0"
 * 
 * @param {Number} num
 * @param {String} c
 */
String.prototype.pad = function (num, c) {
	if (c === undefined) {
		c = "0";
	}
	
	var a = Array(num - this.length);
	for (var i = 0; i < num - this.length; i++) {
		a[i] = c;
	}
	
	return a.join("") + this;
};
