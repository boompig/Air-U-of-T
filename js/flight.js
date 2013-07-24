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
	
	if (value == "UTSG") {
		otherValue = "UTM";
	} else if (value == "UTM") {
		otherValue = "UTSG";
	} else {
		otherValue = "";
	}
	
	$("#" + otherField).find("option[value='{0}']".format(otherValue)).prop("selected", true);
};

/**
 * Return the name of the complimentary campus field.
 * @param {String} thisField Name of this field
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
		var clicked = 
		
		Flight.changeOtherCampus($(this).attr("id"), $(this).val());
		
		// var form = $(this).closest("form");
		// var validator = $(form).validate();
		// validator.element(this);
		var otherField = Flight.otherCampusField($(this).attr("id"));
		if ($("#" + otherField).hasClass("invalid")) {
			$("#" + otherField).change();
		}
	});
};
