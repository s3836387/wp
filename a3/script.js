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