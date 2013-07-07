function Flights () {
	// empty
}

/**
 * Fetch and return a list of flights given the date and the campus of departure.
 * @param {String} campus - one of "UTSG", "UTM"
 * @param {Date} date
 * @returns {Array} Return an array of Flight objects
 */
Flights.fetch = function (campus, date) {
	"use strict";
	var flights = [], flightDate;
	var times = [8, 10, 14, 17];
	
	for (var heli = 0; heli < 2; heli++) {
		for (var i = 0; i < times.length; i++) {
			flightDate = new Date(date);
			flightDate.setHours(times[i]);
			
			flights.push(new Flight(campus, flightDate, heli + 1));
		}
	}
	
	return flights;
};

/**
 * Constructor for flight object
 * @param {String} from - Departure campus
 * @param {Date} when - date and time of departure
 * @param {Number} heli - The helicopter tail # for the flight.
 */
function Flight (from, when, heli) {
	this.from = from;
	this.when = when;
	this.heli = heli;
}

/**
 * Return the destination of this flight.
 * @returns {String}
 */
Flight.prototype.getDestination = function () {
	return this.from === "UTM" ? "UTSG" : "UTM";
};

// add some missing functionality to built-in Date object
/**
 * Days of the week (in full)
 */
Date.prototype.longDays = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
Date.prototype.longMonths = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

/**
 * Return the name of the day of the week.
 * @returns {String}
 */
Date.prototype.getNamedDay = function () {
	return this.longDays[this.getDay()];
};

/**
 * Returns the name of the month;
 * @returns {String}
 */
Date.prototype.getNamedMonth = function () {
	return this.longMonths[this.getMonth()];
};

/**
 * Return the string representing the full date in the format <day of week>, <month> <date>, <full year>
 * @returns {String}
 */
Date.prototype.getFullDate = function () {
	var d = String(this.getDate());
	
	
	return [this.getNamedDay(), this.getNamedMonth() + " " + d, this.getFullYear()].join(", ");
};

/**
 * Return the time in the format <hour> < PM / AM >
 * @returns {String}
 */
Date.prototype.getLongHour = function () {
	var h = this.getHours();
	
	if (h == 0) {
		return "12 AM"
	} else if (h < 12) {
		return h + " AM";
	} else if (h == 12) {
		return h + " PM"
	} else {
		return (h - 12) + " PM";
	}
};

/**
 * Return string representation.
 * @returns {String}
 */
Flight.prototype.toString = function () {
	return "Flight from " + this.from + " to " + this.getDestination() + " on " + this.when.getFullDate() + " at " + this.when.getLongHour() + " on helicopter " + this.heli;
};
