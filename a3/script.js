/*nav bar active links */
window.addEventListener("scroll", event => {
    let navLinks = document.querySelectorAll('nav a');
    let viewTop = window.scrollY;

    navLinks.forEach(link => {

        let section = document.querySelector(link.hash);

        if (section.offsetTop - 75 <= viewTop &&
            section.offsetTop + section.offsetHeight - 100 > viewTop) {
            console.log(link.hash);
            link.classList.add('active');
        } else {
            link.classList.remove('active');
        }
    });
});