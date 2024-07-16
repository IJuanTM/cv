export const
  inputPassword = document.querySelector('input.input-password'),
  passwordToggleIcon = document.querySelector('i.password-toggle');

/**
 * Function to toggle the password visibility
 *
 * @returns {void}
 */
export const togglePassword = () => {
  // Toggle the password visibility.
  inputPassword.setAttribute('type', inputPassword.getAttribute('type') === 'password' ? 'text' : 'password');

  // Toggle the password icon.
  passwordToggleIcon.classList.toggle('fa-eye');
  passwordToggleIcon.classList.toggle('fa-eye-slash');
}

const passwordWarning = document.querySelector('div.password-warning');

/**
 * Function to show a warning if the caps lock key is on
 *
 * @param event
 */
export const capsLockWarning = event => {
  if (event.getModifierState('CapsLock')) passwordWarning.classList.remove('hidden');
  else passwordWarning.classList.add('hidden');
}

// -------------------------------------------------------------------------------------------------------------------------------- //

const messageWarning = document.querySelector('p.message-warning');

/**
 * Function to check the message length
 *
 * @param event
 */
export const checkMessageLength = event => {
  // Set the message length.
  document.querySelector('span.message-length').textContent = event.target.value.length;

  // Show a warning if the message is less than 50 characters from the maximum length.
  if (event.target.value.length >= event.target.maxLength - 50) messageWarning.classList.add('warning');
  else messageWarning.classList.remove('warning');

  // Show an error if the message is at the maximum length.
  if (event.target.value.length === event.target.maxLength) messageWarning.classList.add('error');
  else messageWarning.classList.remove('error');
}
