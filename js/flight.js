/**
 * Wrapper for JS relating to picking flight.
 * Requires Utils static class.
 */
function Flight () {
	this.campuses = ["UTM", "UTSG"];
}

/**
 * Given that a given field has changed value, alter the other campus field to the opposite value.
 */
Flight.prototype.changeOtherCampus = function (field, value) {
	"use strict";
	
	var otherField = field == "to" ? "from" : "to", otherValue;
	
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
 * Set up the campus choosers on this page.
 * @param {String} selector A selector to choose all the campus choosers on the page.
 */
Flight.prototype.setupCampusChooser = function (selector) {
	"use strict";
	
	var that = this;
	
	// couldn't figure out how to do this in CI, doing it in JS instead
	$(selector).find("option[value='']").attr("disabled", "disabled");
	
	$(selector).change(function() {
		that.changeOtherCampus($(this).attr("id"), $(this).val());
	});
};

/**
 * Validate the input on submit. Return True iff all inputs are valids
 */
Flight.prototype.validate_inputs = function () {
	"use strict";
	
	var fields = ["to", "from"];
	
	for (var i = 0; i < fields.length; i++) {
		var val = $("#{0} option:selected".format(fields[i])).val();
		if (this.campuses.indexOf(val) == -1) {
			return false;
		}
	}
	
	if (! Utils.isValidDate($("#date").val())) {
		return false;
	}
	
	if ($("#time").length > 0) {
		var timePattern = /\d{2}\:00\:00/
		if (! $("#time").val().match(timePattern)) {
			return false;
		}
	}
	
	return true;
};
