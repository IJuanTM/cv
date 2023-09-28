export const
  navMenu = document.querySelector('nav.menu'),
  navItems = navMenu.querySelectorAll('*.nav-item'),
  menuHamburger = document.querySelector('button.hamburger');

/**
 * Function to toggle the navigation menu.
 *
 * @returns {void}
 */
export const toggleMenu = () => {
  let menuHeight = 0;

  navItems.forEach(item => {
    // Get the height of the menu items.
    menuHeight += item.offsetHeight;

    // Set the tabindex of the menu items.
    if (menuHamburger.classList.contains('is-active')) item.setAttribute('tabindex', '-1');
    else item.setAttribute('tabindex', '0');
  });

  // Set the height of the navigation menu.
  navMenu.style.maxHeight = navMenu.style.maxHeight ? null : `${menuHeight}px`;

  // Toggle the hamburger menu.
  menuHamburger.classList.toggle('is-active');
  menuHamburger.toggleAttribute('aria-expanded');
}

/**
 * Function to set the tabindex of the navigation menu items.
 *
 * @returns {void}
 */
export const setNavItems = () => {
  // Set the tabindex of the menu items.
  if (window.innerWidth > 768) navItems.forEach(item => item.setAttribute('tabindex', '0'));
  else navItems.forEach(item => item.setAttribute('tabindex', '-1'));
}
