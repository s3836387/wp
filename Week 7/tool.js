window.addEventListener("scroll", event => {
    let navLinks = document.querySelectorAll('nav ul li a');
    let fromTop = window.scrollY;
    console.log(fromTop);
    navLinks.forEach(link =>{
        let section = document.querySelector(link.hash);
        if (section.offsetTop <= fromTop &&
            section.offsetTop + section.offsetHeight > fromTop){
            link.classList.add('active');
        }else {
            link.classList.remove('active');
        }
    });

});
/*function link(anchor, index) {
    let section = document.querySelector(anchor.hash);
    console.log(section.id)
}
/**/