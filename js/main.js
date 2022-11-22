// Simpl attribution
console.log('This website is made using the Simpl framework. Read more about Simpl here: https://www.github.com/IJuanTM/Simpl/');

// -------------------------------------------------------------------------------------------------------------------------------- //

/**
 * Below is code for showing and hiding the main navigation bar.
 * This is made for the default navigation bar that comes with the standard Simpl launch page.
 *
 * Uses the Hamburger menu from hamburgers.css
 */

// Define constants for the menu and hamburger menu.
const hamburger = document.querySelector('button.hamburger');

// Function to toggle the menu.
function toggleMenu() {
  // Toggle the class extended for the menu.
  document.querySelector('nav.menu').classList.toggle('extended');
  // Toggle the class is is-active for the hamburger menu.
  hamburger.classList.toggle('is-active');
}

// Listen for click on the menu items.
document.querySelectorAll('a.nav-link').forEach(link => link.onclick = toggleMenu);

// Listen for click on the hamburger menu.
hamburger.onclick = toggleMenu;

// -------------------------------------------------------------------------------------------------------------------------------- //

const toTop = document.querySelector('div.to-top');

toTop.onclick = () => {
  window.scrollTo({
    top: 0,
    behavior: 'smooth'
  });
}

window.onscroll = () => {
  if (window.scrollY > 256) toTop.classList.remove('invisible');
  else toTop.classList.add('invisible');
}

// -------------------------------------------------------------------------------------------------------------------------------- //

document.querySelector('div.print').onclick = () => window.print();
