/**
 * A bunch of utility methods for validating dates and such.
 */

/**
 * Return True iff dateStr has correct regex format.
 * This corresponds to YYYY-MM-DD
 */
function validDateFormat (val, elem) {
	return val.match(/^\d{4}\-\d{2}\-\d{2}$/);
}

/**
 * Assume dateStr has correct format. Return true iff it's a valid date.
 */
function validDate (val, elem) {
	"use strict";
	
	var match = val.match(/^(\d{4})\-(\d{2})\-(\d{2})$/);
	
	if ((! match) || match.length < 4) {
		return false;
	} else if (match[2] < 1 || match[2] > 12) {
		return false;
	} else if (match[3] < 1 || match[3] > Date.daysInMonth(match[2], match[1])) {
		return false;
	} else {
		return true;
	}
}

/**
 * Return true iff val contains valid credit card number.
 */
function validCreditCardNumber (val, elem) {
	return val.match(/^\d{16}$/);
}

function checkCCExpMonth (val, elem) {
	var pattern = /^\d{2}$/;
	return val.match(pattern) && val > 0 && val <= 12;
}

/**
 * This is cheating function
 */
function checkCCExpYear (val, elem) {
	var pattern = /^\d{2}$/;
	return val.match(pattern);
}

function checkFutureExpiryDate (val, elem, options) {
	"use strict";
	
	var year = $("#expYear").val();
	var month = $("#expMonth").val();
	
	// if year or month is not set, return true
	// this may seem counter-intuitive, but I allow other methods to catch this
	
	if ((! year) || (! month) || isNaN(month) || isNaN(year)) {
		return true;
	} else {
		year = "20" + String(year);
		month = String(Number(month) + 1).pad(2, "0");
		
		console.log(year + "-" + month + "-00");
		return checkFutureDate(year + "-" + month + "-00");
	}
}

/**
 * Return true iff date is in the future.
 * In the future means at least tomorrow.
 */
function checkFutureDate (val, elem) {
	"use strict";
	
	var match = val.match(/^(\d{4})\-(\d{2})\-(\d{2})$/);

	if ((! match) || match.length < 4) {
		return false;
	}
	
	var date = new Date(match[1], match[2] - 1, match[3], 0, 0, 0);
	// console.log(date);
	
	var today = new Date();
	var tomorrow = new Date(today.getFullYear(), today.getMonth(), today.getDate(), 0, 0, 0);
	// console.log(tomorrow);
	
	tomorrow.setDate(tomorrow.getDate() + 1); // do this here to make sure month increases if relevant
		
	return date >= tomorrow;
}

function validCampus (val, elem) {
	var campuses = ["UTSG", "UTM"];
	return campuses.indexOf(val) !== -1;
}
