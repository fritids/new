jQuery(function($){
	var $day = $("#dob_d");
	var selected_day = $day.val(); 
	var $month = $("#dob_m");
	var $year = $("#dob_y");
	var days_arr = range(1,31);
	
	$day.change(function(){
		selected_day = $(this).val();
	});
	
	$month.change(function(){
		Datepicker.mapDaysToMonth($(this).val());
	});
	
	$year.change(function(){
		Datepicker.mapDaysToMonth($month.val());
	});
	
	var Datepicker = {
		mapDaysToMonth: function (monthName){
			if(monthName == "February")
			{
				days_arr = range(1, 28);
				if(this.checkLeapYear()) { days_arr = range(1, 29); } 
			}
			
			if(monthName == "April" || monthName == "June" || monthName == "September" || monthName == "November")
			{
				days_arr = range(1, 30); 
			}
			this.setDayValues();
		},
		checkLeapYear: function(){
			var year = $year.val();
			if(year % 4 == 0)
			{
				if(year % 100 != 0)
				{
					return true;
				}
				else if(year == 0)
				{
					return true;
				}
				
			}
			return false;
		},
		setDayValues: function(){
			var opt = "";
			for(var i = 1, len = days_arr.length; i <= len; i++)
			{
				var selected = "";
				if(selected_day == i) { selected = "selected='selected'";} 
				opt += "<option value='"+ i +"' "+ selected +" >" + i + "</option>";
			}
			$day.html(opt);
		}
	}
	
	function range ( low, high, step ) {
	    // Create an array containing the range of integers or characters from low to high (inclusive)  
	    // 
	    // version: 1008.1718
	    // discuss at: http://phpjs.org/functions/range    // +   original by: Waldo Malqui Silva
	    // *     example 1: range ( 0, 12 );
	    // *     returns 1: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]
	    // *     example 2: range( 0, 100, 10 );
	    // *     returns 2: [0, 10, 20, 30, 40, 50, 60, 70, 80, 90, 100]    // *     example 3: range( 'a', 'i' );
	    // *     returns 3: ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i']
	    // *     example 4: range( 'c', 'a' );
	    // *     returns 4: ['c', 'b', 'a']
	    var matrix = [];    var inival, endval, plus;
	    var walker = step || 1;
	    var chars  = false;
	 
	    if ( !isNaN( low ) && !isNaN( high ) ) {        inival = low;
	        endval = high;
	    } else if ( isNaN( low ) && isNaN( high ) ) {
	        chars = true;
	        inival = low.charCodeAt( 0 );        endval = high.charCodeAt( 0 );
	    } else {
	        inival = ( isNaN( low ) ? 0 : low );
	        endval = ( isNaN( high ) ? 0 : high );
	    } 
	    plus = ( ( inival > endval ) ? false : true );
	    if ( plus ) {
	        while ( inival <= endval ) {
	            matrix.push( ( ( chars ) ? String.fromCharCode( inival ) : inival ) );            inival += walker;
	        }
	    } else {
	        while ( inival >= endval ) {
	            matrix.push( ( ( chars ) ? String.fromCharCode( inival ) : inival ) );            inival -= walker;
	        }
	    }
	 
	    return matrix;
	}
})