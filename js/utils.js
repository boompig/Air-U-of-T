Date.dayNames = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
Date.monthNames = ['January','February','March','April','May','June','July','August','September','October','November','December']

/**
 * Return number of days in the specified month.
 * From this question on SOF:
 * http://stackoverflow.com/q/315760/755934
 * 
 * @param {Number} month
 * @param {Number} year
 */
Date.daysInMonth = function (month, year) {
	return new Date(year, month, 0).getDate();
}

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
 * Return a random element from given array.
 * @param {Array} arr
 */
Utils.randomChoice = function (arr) {
	return arr[Math.randInt(0, arr.length - 1)];
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
