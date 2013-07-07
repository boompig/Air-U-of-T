function Verifier () {
	// nothing here
};

/**
 * Make sure the flight date is valid. If not valid
 * @param {String} date
 */
Verifier.verifyFlightDate = function (date) {
	"use strict";
	
	var selected = new Date(date);
	var errorPane = "#flightDateError";
	
	$(errorPane).hide();
	
	if (selected.getDate() == NaN) {
		$(errorPane).text("Invalid date format").show();
		return false;
	}
	
	var today = new Date();
	
	// make sure 'tomorrow' is set at midnight
	var tomorrow = new Date(today.getFullYear(), today.getMonth(), today.getDate());
	tomorrow.setDate(today.getDate() + 1)
	
	// two weeks from tomorrow
	var twoWeeks = new Date(today.getFullYear(), today.getMonth(), today.getDate());
	twoWeeks.setDate(today.getDate() + 14);
	
	if (selected < today) {
		$(errorPane).text("You cannot select days in the past").show();
		return false;
	} else if (selected < tomorrow) {
		$(errorPane).text("You cannot book a flight for today, you must book for tomorrow or later").show();
		return false;
	} else if (selected > twoWeeks) {
		$(errorPane).text("You cannot book more than 2 weeks into the future").show();
		return false;
	}
	
	return true;
};

/**
 * Verify that the given campus is a valid destination.
 * Display appropriate warning otherwise.
 * Should be one of: ["UTSG", "UTM"]
 * @param {String} campus
 */
Verifier.verifyCampus = function (campus) {
	"use strict";
	var campuses = ["UTSG", "UTM"];
	var errorPane = "#campusError"
	
	$(errorPane).hide();
	
	if(campus === null || campus.length === 0) {
		$(campusError).text("You must select a campus before proceeding").show();
		return false;
	} else if (campuses.indexOf(campus) == -1) {
		$(campusError).text("Invalid campus selection").show();
		return false;
	}
	
	return true;
};
