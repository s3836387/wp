window.addEventListener("scroll", event => {
  let navLinks = document.querySelectorAll('nav ul li a');
  let fromTop = window.scrollY;
  navLinks.forEach(link => {
    let section = document.querySelector(link.hash);
    if (section.offsetTop <= fromTop &&
      section.offsetTop + section.offsetHeight > fromTop) {
      link.classList.add('active');
    } else {
      link.classList.remove('active');
    }
  });

});
/* Get element id */
function getid(x) {
  return document.getElementById(x);
}
/* Show/hide synoposis area */
var synoposis = document.getElementsByClassName("synop");
function myFunction(movieid) {
  let x = document.getElementById(movieid);
  for (i = 0; i < synoposis.length; i++) {
    let y = synoposis[i];
    if (y.id != movieid) {
      if (y.style.display === "block") {
        y.style.display = "none";
      }
    }
  }
  if (x.style.display === "none") {
    x.style.display = "block";
  } else {
    x.style.display = "none";
  }
}

/* Form functions */
var weekdays = ['MON', 'TUE', 'WED', 'THU', 'FRI'];
var discountdays = ['MON', 'TUE', 'WED'];
var seatcode = ['STA', 'STP', 'STC', 'FCA', 'FCP', 'FCC']; /* Use indexof to calculate price total */
var movie_id = document.getElementById("movieId");
var movie_hour = document.getElementById("movieHour");
var movie_day = document.getElementById("movieDay");
var funcYear = true;
var total = 0.00;

var pricerate;

function getYear() {
  if (funcYear) {
    let year = document.getElementById("expYear");
    let currentYear = new Date().getFullYear();
    for (i = currentYear; i < currentYear + 6; i++) {
      year.innerHTML += '<option value="' + i + '">' + i + '</option>';
    }
    funcYear = false;
  }
}
function getRate() {
  if ((discountdays.includes(movie_day.value)) || (movie_hour.value === "T12" && weekdays.includes(movie_day.value))) {
    pricerate = [14, 12.50, 11, 24, 22.50, 21];
  } else {
    pricerate = [19.80, 17.50, 15.30, 30, 27, 24];
  }
}

function updateTotal() {
  let seats = document.getElementsByClassName('seats');
  let index;
  let temp = 0;
  for (i of seats) {
    if (i.value.length != 0) {
      index = seatcode.indexOf(i.id);
      temp += i.value * pricerate[index];
    }

  }
  if (total != temp) {
    total = temp.toFixed(2);
    document.getElementById('Total').value = total;
  }
}
function initializeSynop(id, day) {
  let title = document.getElementById('title' + id).innerHTML;
  let movieInfo = document.getElementById(day + id).innerHTML.split(" ");
  movie_id.value = id;
  movie_hour.value = movieInfo[3].slice(1, 4);
  movie_day.value = movieInfo[0].slice(0, 3).toUpperCase();
  document.getElementById('formHeader').innerHTML = '';
  document.getElementById("formHeader").innerHTML = "<h3>" + title + " " + movieInfo[0] + " " + movieInfo[2] + "</h3>";
  if (funcYear) {
    getYear();
  }
  getRate();
  updateTotal();
  document.getElementById('booking').style.display = "block";
}

/* Clear error*/
function clearError(input, alert) {
  alert.innerHTML = '';
  input.classList.remove('inputError');
}
/* Check is blank */
function blankCheck(inputid, errorid) {
  let name = document.getElementById(inputid);
  let errorAlert = document.getElementById(errorid);
  if (name.validity.valueMissing) {
    errorAlert.innerHTML = "&#42;Please don't leave this blank.";
    name.classList.add('inputError');
    return false;
  } else {
    clearError(name, errorAlert);
    return true
  }
}
/* Check name */
function nameCheck() {
  let name = document.getElementById('inputName');
  let errorAlert = document.getElementById('nameError');
  if (!name.checkValidity()) {
    errorAlert.innerHTML = "&#42;Please don't use special character. We only accept latin character.";
    name.classList.add('inputError');
    return false;
  } else {
    clearError(name, errorAlert);
    return true;
  }
}
/* Check Phone number */
function phoneCheck() {
  let phone = document.getElementById("inputPhonenum");
  let errorAlert = document.getElementById('phoneError');
  if (!phone.checkValidity()) {
    errorAlert.innerHTML = "&#42;This must be Australia number. Ex: 04 1234 5678";
    phone.style.backgroundColor = '#fee';
    return false;
  } else {
    clearError(phone, errorAlert);
    return true;
  }
}
/* Check Email */
function emailCheck() {
  let email = document.getElementById("inputEmail");
  let errorAlert = document.getElementById('emailError');
  if (!email.checkValidity()) {
    errorAlert.innerHTML = "&#42;Please type in a valid email. Ex: exmaple@gmail.com";
    email.style.backgroundColor = '#fee';
    return false;
  } else {
    clearError(email, errorAlert);
    return true;
  }
}
/* Check credit card */
function ccCheck() {
  let creditcard = document.getElementById("inputccnum");
  let errorAlert = document.getElementById('ccError');
  if (!creditcard.checkValidity()) {
    errorAlert.innerHTML = "&#42;You must fill in your credit card number.";
    creditcard.style.backgroundColor = '#fee';
    return false;
  } else {
    clearError(creditcard, errorAlert);
    return true;
  }
}

/* Check expCheck */
function expCheck() {
  document.getElementById('expError').innerHTML = '';
  let year = document.getElementById('expYear').value;
  let month = document.getElementById('expMonth').value;
  if (month == 0 || year == 2000) {
    document.getElementById('expError').innerHTML = "&#42;Please choose card expiration date";
    return false;
  }
  let expDate = new Date(year, month - 1);
  let currentDate = new Date()
  if (expDate < currentDate) {
    document.getElementById('expError').innerHTML = "&#42;Your card has expired. Please enter a valid card.";
    return false;
  } else {
    return true
  }

}

function formCheck() {
  let errorCount = 0;
  if (total === 0)errorCount++;
  if (!expCheck()) { errorCount++; }
  return (errorCount == 0);
}