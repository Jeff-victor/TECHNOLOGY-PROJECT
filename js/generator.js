/**
 * generator.js
 * Password generator — character-class selection, length slider,
 * Generate button, "Use this →" button, and click-to-copy output.
 *
 * Depends on: strength.js  (to trigger the strength meter after "Use this →")
 */

const CHAR_SETS = {
  upper:   'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
  lower:   'abcdefghijklmnopqrstuvwxyz',
  numbers: '0123456789',
  symbols: '!@#$%^&*()-_=+[]{}|;:,.<>?',
};

/**
 * Generate a random password.
 *
 * @param {{ upper, lower, numbers, symbols, length }} opts
 * @returns {string}
 */
function generatePassword({ upper, lower, numbers, symbols, length }) {
  let pool = '';
  const required = [];

  if (upper)   { pool += CHAR_SETS.upper;   required.push(randomChar(CHAR_SETS.upper));   }
  if (lower)   { pool += CHAR_SETS.lower;   required.push(randomChar(CHAR_SETS.lower));   }
  if (numbers) { pool += CHAR_SETS.numbers; required.push(randomChar(CHAR_SETS.numbers)); }
  if (symbols) { pool += CHAR_SETS.symbols; required.push(randomChar(CHAR_SETS.symbols)); }

  // Fallback: at least lowercase
  if (!pool) { pool = CHAR_SETS.lower; required.push(randomChar(CHAR_SETS.lower)); }

  // Fill to desired length
  const arr = Array.from({ length }, () => randomChar(pool));

  // Guarantee one char from each required set
  required.forEach((ch, i) => { arr[i % arr.length] = ch; });

  // Fisher-Yates shuffle
  for (let i = arr.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    [arr[i], arr[j]] = [arr[j], arr[i]];
  }

  return arr.join('');
}

function randomChar(str) {
  return str[Math.floor(Math.random() * str.length)];
}

/**
 * Initialise the generator widget inside .generator-box.
 * Call this on add.html and edit.html after DOMContentLoaded.
 */
function initGenerator() {
  const outputEl   = document.getElementById('gen-output');
  const lengthEl   = document.getElementById('gen-length');
  const lengthDisp = document.getElementById('gen-length-display');
  const btnGen     = document.querySelector('.gen-length-row .btn-primary');
  const btnUse     = document.querySelector('.gen-length-row .btn-secondary');
  const pwInput    = document.getElementById('password');

  if (!outputEl) return;

  // Read current checkbox/slider state
  function getOpts() {
    return {
      upper:   document.getElementById('gen-upper')  ?.checked ?? true,
      lower:   document.getElementById('gen-lower')  ?.checked ?? true,
      numbers: document.getElementById('gen-numbers')?.checked ?? true,
      symbols: document.getElementById('gen-symbols')?.checked ?? true,
      length:  parseInt(lengthEl?.value || '16', 10),
    };
  }

  function refreshOutput() {
    outputEl.textContent = generatePassword(getOpts());
  }

  // Length slider → update display label
  lengthEl?.addEventListener('input', () => {
    if (lengthDisp) lengthDisp.textContent = lengthEl.value;
  });

  // ↻ Generate button
  btnGen?.addEventListener('click', refreshOutput);

  // "Use this →" → paste into password field and trigger strength meter
  btnUse?.addEventListener('click', () => {
    if (!pwInput) return;
    pwInput.value = outputEl.textContent.trim();
    pwInput.dispatchEvent(new Event('input')); // triggers attachStrengthMeter
  });

  // Click on output → copy to clipboard
  outputEl.style.cursor = 'pointer';
  outputEl.title = 'Click to copy';
  outputEl.addEventListener('click', () => {
    navigator.clipboard.writeText(outputEl.textContent.trim()).then(() => {
      const prev = outputEl.textContent;
      outputEl.textContent = '✅ Copied!';
      setTimeout(() => { outputEl.textContent = prev; }, 1200);
    });
  });

  // Checkbox changes → regenerate immediately
  ['gen-upper', 'gen-lower', 'gen-numbers', 'gen-symbols'].forEach(id => {
    document.getElementById(id)?.addEventListener('change', refreshOutput);
  });

  // Generate on load
  refreshOutput();
}