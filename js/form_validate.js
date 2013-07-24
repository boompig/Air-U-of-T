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
 * Return true iff date is in the future.
 * In the future means at least tomorrow.
 */
function checkFutureDate (val, elem) {
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
