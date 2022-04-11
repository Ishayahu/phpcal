function jumpToMonth(month, year) {
  callingURL = location.href;

  questionmarkPos = callingURL.indexOf('?');
  if (questionmarkPos != -1) {
    currentDocument = callingURL.substring(0, questionmarkPos);
  } else {
    currentDocument = callingURL;
  }

  sharpPos = currentDocument.indexOf('#');
  if (sharpPos != -1)
    currentDocument = currentDocument.substring(0, sharpPos);

  activelocation = escape(document.forms.mainform.activelocation.value);
  location.href = currentDocument + "?Monate=" + month + "&jahr=" + year + "&activelocation=" + activelocation;
}

function jumpToPrevMonth(month, year) {
  prevMonth = month-1;
  prevYear = year;
  if (prevMonth < 1) {
    prevMonth = 12;
    prevYear--;
  }
  jumpToMonth(prevMonth, prevYear);
}

function jumpToNextMonth(month, year) {
  nextMonth = month+1;
  nextYear = year;
  if (nextMonth > 12) {
    nextMonth = 1;
    nextYear++;
  }
  jumpToMonth(nextMonth, nextYear);
}
