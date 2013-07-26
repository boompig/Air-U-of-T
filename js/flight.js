/**
 * Wrapper for JS relating to picking flight.
 * Requires Utils static class.
 */
function Flight () {
}

Flight.campuses = ["UTM", "UTSG"];

/**
 * Given that a given field has changed value, alter the other campus field to the opposite value.
 */
Flight.changeOtherCampus = function (field, value) {
	"use strict";
	
	var otherField, otherValue;
	otherField = Flight.otherCampusField(field);
	otherValue = Flight.otherCampus(value);
	
	$("#" + otherField).find("option[value='{0}']".format(otherValue)).prop("selected", true);
};

/**
 * Return the complimentary campus to this one.
 * @returns {String}
 */
Flight.otherCampus = function (thisCampus) {
	if (thisCampus == "UTSG") {
		return "UTM";
	} else if (thisCampus == "UTM") {
		return "UTSG";
	} else {
		return "";
	}
};

/**
 * Return the name of the complimentary campus field.
 * @param {String} thisField Name of this field
 * @returns {String}
 */
Flight.otherCampusField = function (thisField) {
	return thisField == "to" ? "from" : "to";
};

/**
 * Set up the campus choosers on this page.
 * @param {String} selector A selector to choose all the campus choosers on the page.
 */
Flight.setupCampusChooser = function (selector) {
	"use strict";
	
	// couldn't figure out how to do this in CI, doing it in JS instead
	$(selector).find("option[value='']").attr("disabled", "disabled");
	
	$(selector).change(function () {
		Flight.changeOtherCampus($(this).attr("id"), $(this).val());
		
		// revalidate complimentary field
		var otherField = Flight.otherCampusField($(this).attr("id"));
		if (! $("form").is("[novalidate]")) {
			$("#" + otherField).valid();
		}
	});
};
