/**
 * Given that a given field has changed value, alter the other campus field to the opposite value.
 */
function changeOtherCampus(field, value) {
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
}

/**
 * Set up the campus choosers on this page.
 * @param {String} selector A selector to choose all the campus choosers on the page.
 */
function setupCampusChooser(selector) {
	// couldn't figure out how to do this in CI, doing it in JS instead
	$(selector).find("option[value='']").attr("disabled", "disabled");
	
	$(selector).change(function() {
		changeOtherCampus($(this).attr("id"), $(this).val());
	});
}
