import {menuHamburger, navMenu, setNavItems, toggleMenu} from './menu';
import {capsLockWarning, checkMessageLength, inputPassword, passwordToggleIcon, togglePassword} from './input.js';

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

if (toTop) {
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
}

// -------------------------------------------------------------------------------------------------------------------------------- //

const printButton = document.querySelector('div.print');

if (printButton) printButton.onclick = () => window.print();

// -------------------------------------------------------------------------------------------------------------------------------- //

const inputFields = document.querySelectorAll('input, textarea, select');

if (inputFields) inputFields.forEach(field => field.addEventListener('keydown', () => {
  const inputGroup = field.closest('div.input-group');

  // Remove error class from input group
  if (inputGroup) inputGroup.classList.remove('error');
}));

if (inputPassword) {
  // On click, toggle the password visibility
  passwordToggleIcon.addEventListener('click', togglePassword);

  // On keydown, check if the caps lock key is on
  inputPassword.addEventListener('keydown', event => capsLockWarning(event));
}

const
  messageTextarea = document.querySelector('textarea.message-field'),
  clearMessageButton = document.querySelector('p.clear-message');

if (messageTextarea && clearMessageButton) {
  messageTextarea.addEventListener('keyup', event => {
    // Check the message length
    checkMessageLength(event);

    // If the message length is greater than 0, remove the inert attribute from the clear message button
    // Else, add the inert attribute to the clear message button
    if (messageTextarea.value.length > 0) clearMessageButton.removeAttribute('inert');
    else clearMessageButton.setAttribute('inert', '');
  });

  clearMessageButton.addEventListener('click', () => {
    // Clear the message textarea
    messageTextarea.value = '';

    // Check the message length
    checkMessageLength({target: messageTextarea});

    // Add the inert attribute to the clear message button
    clearMessageButton.setAttribute('inert', '');
  });
}

// -------------------------------------------------------------------------------------------------------------------------------- //

const editLockButtons = document.querySelectorAll('button.edit-lock');

if (editLockButtons) editLockButtons.forEach(button => {
  // Get the input fields of the form
  const inputFields = button.closest('form').querySelectorAll('input, textarea, select');

  const currentValues = {};

  inputFields.forEach(field => {
    // Set the current values of the input fields
    currentValues[field.name] = field.value;

    ['keyup', 'change'].forEach(event => field.addEventListener(event, () => {
      let changed = false;

      // Check if the values of the input fields have changed
      inputFields.forEach(field => {
        if (field.value !== currentValues[field.name]) changed = true;
      });

      // If the values have changed, remove the inert attribute from the edit lock button
      // Else, add the inert attribute to the edit lock button
      if (changed) button.removeAttribute('inert');
      else button.setAttribute('inert', '');
    }));
  });
});

const
  deleteCheckbox = document.querySelector('input.delete-checkbox'),
  deleteAccessTokenButton = document.querySelector('button.delete-access-token');

// On change, set or remove the inert attribute from the delete access token button depending on whether the checkbox is checked
if (deleteCheckbox) deleteCheckbox.addEventListener('change', () => deleteCheckbox.checked
  ? deleteAccessTokenButton.removeAttribute('inert')
  : deleteAccessTokenButton.setAttribute('inert', ''));

// -------------------------------------------------------------------------------------------------------------------------------- //

const
  obfuscated = document.querySelectorAll('span.obfuscated'),
  eyeButtons = document.querySelectorAll('i.access-modal'),
  accessModal = document.querySelector('section.access-modal'),
  closeAccessModal = accessModal.querySelector('button.close-modal');

if (obfuscated && eyeButtons) [...obfuscated, ...eyeButtons].forEach(item => item.addEventListener('click', () => accessModal.classList.remove('hidden')));

if (accessModal && closeAccessModal) {
  closeAccessModal.addEventListener('click', () => accessModal.classList.add('hidden'));

  accessModal.addEventListener('click', event => {
    if (event.target === accessModal) accessModal.classList.add('hidden');
  });
}
