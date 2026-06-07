(function () {
  'use strict';

  const passwordInput = document.getElementById('password');
  const bar = document.getElementById('strength-bar');
  const label = document.getElementById('strength-label');

  if (!passwordInput || !bar || !label) {
    return;
  }

  const colors = ['#e74c3c', '#e67e22', '#f1c40f', '#2ecc71', '#27ae60'];
  const labels = ['Tres faible', 'Faible', 'Moyen', 'Fort', 'Tres fort'];

  function computeScore(password) {
    let score = 0;
    if (password.length >= 10) score += 1;
    if (password.length >= 14) score += 1;
    if (/[A-Z]/.test(password) && /[a-z]/.test(password)) score += 1;
    if (/[0-9]/.test(password)) score += 1;
    if (/[\W_]/.test(password)) score += 1;
    return Math.min(score, 4);
  }

  function render(score) {
    const percent = ((score + 1) / 5) * 100;
    bar.style.width = `${percent}%`;
    bar.style.background = colors[score];
    label.textContent = labels[score];
    label.style.color = colors[score];
  }

  passwordInput.addEventListener('input', () => {
    if (passwordInput.value.length === 0) {
      bar.style.width = '0';
      label.textContent = '';
      return;
    }

    render(computeScore(passwordInput.value));
  });
})();
