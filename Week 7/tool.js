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

var synoposis = document.getElementsByClassName("synop");
console.log(synoposis[0].id);

function myFunction(movieid) {
  let x = document.getElementById(movieid);
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