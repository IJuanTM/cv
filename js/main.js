import {menuHamburger, navMenu, setNavItems, toggleMenu} from './menu';

// -------------------------------------------------------------------------------------------------------------------------------- //

// Simpl attribution
console.info('This website is made using the Simpl framework. Read more about Simpl here: https://www.github.com/IJuanTM/simpl');

// -------------------------------------------------------------------------------------------------------------------------------- //

// Navigation menu event listeners
if (navMenu) {
  // Hamburger menu button event listener
  menuHamburger.addEventListener('click', toggleMenu);

  // Menu item event listeners
  document.querySelectorAll('a.nav-link').forEach(link => link.addEventListener('click', toggleMenu));
}

// -------------------------------------------------------------------------------------------------------------------------------- //

// Set navigation items on resize
window.addEventListener('resize', setNavItems);

const timeoutItems = document.querySelectorAll('[data-timeout]');

window.addEventListener('load', () => {
  // Set navigation items on load
  setNavItems();

  if (timeoutItems) timeoutItems.forEach(item => setTimeout(() => {
    // If item is an alert, hide it
    // Else, remove the inert attribute from the item
    if (item.classList.contains('alert')) item.classList.add('invisible');
    else item.removeAttribute('inert');
  }, parseInt(item.dataset.timeout)));
});

// -------------------------------------------------------------------------------------------------------------------------------- //

const toTop = document.querySelector('div.to-top');

toTop.addEventListener('click', () => {
  window.scrollTo({
    top: 0,
    behavior: 'smooth'
  });
});

window.onscroll = () => {
  if (window.scrollY > 256) toTop.classList.remove('invisible');
  else toTop.classList.add('invisible');
}

// -------------------------------------------------------------------------------------------------------------------------------- //

document.querySelector('div.print').onclick = () => window.print();
