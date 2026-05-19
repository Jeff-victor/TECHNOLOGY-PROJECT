/**
 * strength.js
 * Password strength scorer (0–100) and live strength-bar helper.
 *
 * No dependencies — load before auth.js and vault.js.
 */

/**
 * Top-100 most common passwords (rockyou subset).
 * Extend for better coverage.
 */
const COMMON_PASSWORDS = [
  '123456','password','123456789','12345678','12345','1234567','1234567890',
  'qwerty','abc123','111111','123123','admin','letmein','welcome','monkey',
  'dragon','master','sunshine','princess','shadow','superman','michael',
  'football','iloveyou','trustno1','batman','passw0rd','qwerty123','password1',
  '1q2w3e4r','solo','starwars','hello','charlie','donald','password123',
  'qwertyuiop','987654321','zxcvbnm','121212','000000','696969','777777',
  'mustang','access','corvette','jessica','yankees','dallas','rangers',
  'harley','hockey','killer','baseball','george','hunter','jordan','jordan23',
  'tigger','buster','thunder','ranger','silver','daniel','golfer','chelsea',
  'angel','cookie','cheese','orange','computer','pepper','alexis','thomas',
  'test','123','pass','god','sex','love','jesus','money','pokemon',
];

/**
 * Score a password from 0 to 100.
 *
 * Breakdown:
 *   - Length             → up to 40 pts  (6/8/12/16+ chars)
 *   - Character variety  → up to 40 pts  (lower/upper/digit/symbol)
 *   - Uniqueness ratio   → up to 20 pts
 *   - Common password    → hard-cap at 15 regardless of other scores
 *
 * Returns { score, label, color }
 */
function scorePassword(pw) {
  if (!pw) return { score: 0, label: 'No password', color: 'var(--muted)' };

  let score = 0;

  // Length
  if (pw.length >= 6)  score += 10;
  if (pw.length >= 8)  score += 10;
  if (pw.length >= 12) score += 10;
  if (pw.length >= 16) score += 10;

  // Character variety
  if (/[a-z]/.test(pw))        score += 10;
  if (/[A-Z]/.test(pw))        score += 10;
  if (/[0-9]/.test(pw))        score += 10;
  if (/[^a-zA-Z0-9]/.test(pw)) score += 10;

  // Uniqueness bonus
  score += Math.round((new Set(pw).size / pw.length) * 20);

  // Common-password penalty
  if (COMMON_PASSWORDS.includes(pw.toLowerCase())) score = Math.min(score, 15);

  score = Math.min(100, Math.max(0, score));

  let label, color;
  if (score < 40)      { label = 'Weak';   color = 'var(--weak,   #ef4444)'; }
  else if (score < 70) { label = 'Medium'; color = 'var(--medium, #f59e0b)'; }
  else                 { label = 'Strong'; color = 'var(--strong, #22c55e)'; }

  return { score, label, color };
}

/**
 * Wire a live strength bar to a password <input>.
 *
 * @param {string} inputId   - id of the <input type="password">
 * @param {string} fillId    - id of the bar fill element
 * @param {string} labelId   - id of the text label (nullable)
 * @param {string} scoreId   - id of the score display (nullable)
 */
function attachStrengthMeter(inputId, fillId, labelId, scoreId) {
  const input   = document.getElementById(inputId);
  const fill    = document.getElementById(fillId);
  const labelEl = document.getElementById(labelId);
  const scoreEl = document.getElementById(scoreId);
  if (!input || !fill) return;

  input.addEventListener('input', () => {
    const { score, label, color } = scorePassword(input.value);
    fill.style.width      = score + '%';
    fill.style.background = color;
    if (labelEl) labelEl.textContent = input.value ? label          : 'Enter a password';
    if (scoreEl) scoreEl.textContent = input.value ? score + '/100' : '';
  });
}