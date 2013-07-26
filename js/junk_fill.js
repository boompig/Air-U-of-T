/**
 * Wrapper for methods in this file.
 * This will generate garbage info, which is valid and consistent
 */
function JunkFill () {
	
}

/**
 * Return a random combination of some sample first and last names, as a string.
 * Return them space-seperated.
 * 
 * @returns {String}
 */
JunkFill.getRandomName = function () {
	return JunkFill.getRandomFirstName() + " " + JunkFill.getRandomLastName();
};

/**
 * Return a randomly-chosen first name.
 * 
 * @returns {String}
 */
JunkFill.getRandomFirstName = function () {
	var firstNames = ["Alice", "Will", "Ross", "Elia", "Sergei", "Jackie", "Andrew", "Kevin", "Daniel", "Sasha", "Gru", "Doctor"];
	return Utils.randomChoice(firstNames);
};

/**
 * Return a randomly-chosen last name.
 * 
 * @returns {String}
 */
JunkFill.getRandomLastName = function () {
	var lastNames = ["Smith", "Liu", "Wu", "Slavovich", "Lee", "Jackson", "Snow", "Fierce", "vonEvil", "McAwesomePants"];
	return Utils.randomChoice(lastNames);
};

/**
 * Generate a random credit-card number, with 16 digits.
 * i.e. a random 16-digit number, with a leading non-zero digit.
 * 
 * @returns {Number}
 */
JunkFill.genRandomCC = function () {
	var n = 0, min;
	
	for (var i = 0; i < 16; i++) {
		// ensures that leading digit will not be 0 (so makes sure we have enough digits)
		min = i == 15 ? 1 : 0;
		
		n += Math.randInt(min, 9) * Math.pow(10, i);
	}
	
	return n;
};


/**
 * Return a random date within next 5 years.
 * Not uniformly random, but good enough for what I'm doing
 * 
 * @returns {Date}
 */
JunkFill.getRandomCCExp = function () {
	// these values refer to intervals
	var dYear = Math.randInt(0, 4);
	var dMonth = Math.randInt(0, 11);
	var dDay = Math.randInt(1, 28);
	
	var today = new Date();
	
	return new Date(today.getFullYear() + dYear, today.getMonth() + dMonth, today.getDate() + dDay);
};


/**
 * Get a random flight date in the next 2 weeks.
 * @returns {Date}
 */
JunkFill.getRandomFlightDate = function () {
	var day = Math.randInt(1, 14);
	var today = new Date();
	today.setDate(today.getDate() + day);
	return today;
};

/**
 * Return a random value from ['UTM', 'UTSG']
 */
JunkFill.getRandomCampus = function () {
	return Utils.randomChoice(["UTM", "UTSG"]);
};
