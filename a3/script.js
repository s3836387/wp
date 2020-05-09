/*nav bar active links */
window.addEventListener("scroll", event => {
    let navLinks = document.querySelectorAll('nav a');
    let viewTop = window.scrollY;

    navLinks.forEach(link => {

        let section = document.querySelector(link.hash);

        if (section.offsetTop - 75 <= viewTop &&
            section.offsetTop + section.offsetHeight - 100 > viewTop) {
            link.classList.add('active');
        } else {
            link.classList.remove('active');
        }
    });
});

/* More detail synoposis*/


function moreDetailToggle(movieid) {
  let x = document.getElementById(movieid);
  let synoposis = document.getElementsByClassName("synopsis");
  for (i = 0 ; i < synoposis.length; i++){
    let y= synoposis[i];
    if (y.id != movieid){
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
function initializeSynop(id, day){
  let title=  document.getElementById('title'+id).innerHTML;
  let movieInfo =  document.getElementById(day+id).innerHTML.split(" ");
  document.getElementById("movieId").value= id;
  document.getElementById("movieHour").value=movieInfo[3].slice(1,4);
  document.getElementById("movieDay").value= day.toUpperCase();
  document.getElementById('formHeader').innerHTML ='';
  document.getElementById("formHeader").innerHTML = "<h3>"+ title +" "+ movieInfo[0]+ " " + movieInfo[2]+ "</h3>";
}
