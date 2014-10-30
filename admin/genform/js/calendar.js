         // how reliable is this test?
         isIE = (document.all ? true : false);
	 isDOM = (document.getElementById ? true : false);

         // Initialize arrays.
         var months = new Array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul",
	 "Aug", "Sep", "Oct", "Nov", "Dec");
         var daysInMonth = new Array(31, 28, 31, 30, 31, 30, 31, 31,
            30, 31, 30, 31);
	 var displayMonth = new Date().getMonth();
	 var displayYear = new Date().getFullYear();
	 var displayDivName;
	 var displayElement;
	 var displayElementMonth;
	 var displayElementYear;

         function getDays(month, year) {
            // Test for leap year when February is selected.
            if (1 == month)
               return ((0 == year % 4) && (0 != (year % 100))) ||
                  (0 == year % 400) ? 29 : 28;
            else
               return daysInMonth[month];
         }

         function getToday() {
            // Generate today's date.
            this.now = new Date();
            this.year = this.now.getFullYear();
            this.month = this.now.getMonth();
            this.day = this.now.getDate();
         }

         // Start with a calendar for today.
         today = new getToday();

         function newCalendar(eltName,attachedElementDay,attachedElementMonth,attachedElementYear) {
	    if (attachedElementDay) {
	       if (displayDivName && displayDivName != eltName) hideElement(displayDivName);
	       displayElementDay = attachedElementDay;
		   displayElementMonth = attachedElementMonth;
		   displayElementYear = attachedElementYear;
	    }
	    displayDivName = eltName;
            today = new getToday();
            var parseYear = parseInt(displayYear + '');
            var newCal = new Date(parseYear,displayMonth,1);
            var day = -1;
            var startDayOfWeek = newCal.getDay();
            if ((today.year == newCal.getFullYear()) &&
                  (today.month == newCal.getMonth()))
	    {
               day = today.day;
            }
            var intDaysInMonth =
               getDays(newCal.getMonth(), newCal.getFullYear());
            var daysGrid = makeDaysGrid(startDayOfWeek,day,intDaysInMonth,newCal,eltName)
	    if (isIE) {
	       var elt = document.all[eltName];
	       elt.innerHTML = daysGrid;
            } else if (isDOM) {
	       var elt = document.getElementById(eltName);
	       elt.innerHTML = daysGrid;
	    } else {
	       var elt = document.layers[eltName].document;
	       elt.open();
	       elt.write(daysGrid);
	       elt.close();
	    }
	 }

	 function incMonth(delta,eltName) {
	   displayMonth += delta;
	   if (displayMonth >= 12) {
	     displayMonth = 0;
	     incYear(1,eltName);
	   } else if (displayMonth <= -1) {
	     displayMonth = 11;
	     incYear(-1,eltName);
	   } else {
	     newCalendar(eltName);
	   }
	 }

	 function incYear(delta,eltName) {
	   displayYear = parseInt(displayYear + '') + delta;
	   newCalendar(eltName);
	 }

	 function makeDaysGrid(startDay,day,intDaysInMonth,newCal,eltName) {
	    var daysGrid;
	    var month = newCal.getMonth();
	    var year = newCal.getFullYear();
	    var isThisYear = (year == new Date().getFullYear());
	    var isThisMonth = (day > -1)
	    daysGrid = '<table border=0 cellspacing=2 cellpadding=2 width=160 class="genform_cal"><tr>';
		daysGrid += '<td class="genform_cal_td">';
	    daysGrid += '<a href="javascript:incMonth(-1,\'' + eltName + '\')">&laquo; </a>';

	    if (isThisMonth) { daysGrid += '<span>' + months[month] + '</span>'; }
	    else { daysGrid += '' + months[month] + ''; }

	    daysGrid += '<a href="javascript:incMonth(1,\'' + eltName + '\')"> &raquo;</a>';
		daysGrid += '</td><td  class="genform_cal_td">';

	    daysGrid += '<a href="javascript:incYear(-1,\'' + eltName + '\')">&laquo; </a>';

	    if (isThisYear) { daysGrid += '<span>' + year + '</span>'; }
	    else { daysGrid += ''+year; }


	    daysGrid += '<a href="javascript:incYear(1,\'' + eltName + '\')"> &raquo;</a>';

		daysGrid += '</td><td  class="genform_cal_td">';

	    daysGrid += '<a href="javascript:hideElement(\'' + eltName + '\')">x</a>';

		daysGrid += '</td></tr></table>';

		daysGrid += '<table border=0 cellspacing=2 cellpadding=2 width=160 class="genform_cal"><tr>';

	    daysGrid += '<td>Lu</td><td>Ma</td><td>Me</td><td>Je</td><td>Ve</td><td>Sa</td><td>Di</td>';
		daysGrid += '</tr>';
	    var dayOfMonthOfFirstSunday = (7 - startDay + 1);
	    for (var intWeek = 0; intWeek < 6; intWeek++) {
	       var dayOfMonth;
		   daysGrid += "<tr>";
	       for (var intDay = 1; intDay <= 7; intDay++) {

		  	 daysGrid += "<td class='genform_cal_td2'>";
	         dayOfMonth = (intWeek * 7) + intDay + dayOfMonthOfFirstSunday - 7;
			 if (dayOfMonth <= 0) {

			 } else if (dayOfMonth <= intDaysInMonth) {

		 		  daysGrid += '<a href="javascript:setDay(';
		 		  daysGrid += dayOfMonth + ',\'' + eltName + '\')"> ';

				  if (day > 0 && day == dayOfMonth)
				  		daysGrid += "<b>";

				  var dayString = dayOfMonth;

		 		  if (dayString.length == 6) dayString = '0' + dayString;
		 		  daysGrid += dayString;
		  		 daysGrid += "</a>";
				 			 if (day > 0 && day == dayOfMonth)  daysGrid += "</b>";
				 daysGrid += "</td>";
			}

	       }
			daysGrid += "</tr>";

	    }
	    return daysGrid + "</table>";
	 }

	 function setDay(day,eltName) {
	   //displayElement.value = (displayMonth + 1) + "/" + day + "/" + displayYear;
		displayElementDay.value = day;
		displayElementMonth.value = (displayMonth + 1);
		displayElementYear.value = displayYear;
	   hideElement(eltName);
	 }
