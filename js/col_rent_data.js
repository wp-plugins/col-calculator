  jQuery(function() {
	for(place in places){
		jQuery("#col_calculator_teach")
			.append(jQuery("<option value='" + places[place] + "'>" + place + "</option>"));
	}
  });