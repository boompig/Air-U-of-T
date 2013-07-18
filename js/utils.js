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
}
