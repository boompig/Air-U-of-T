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