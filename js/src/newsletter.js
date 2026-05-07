(function () {
  document.addEventListener('DOMContentLoaded', function () {
    var form = document.querySelector('form.newsletter-form');
    if (!form) {
      return;
    }
    var messageEl = form.querySelector('.newsletter-message');
    var submitBtn = form.querySelector('input[type="submit"]');

    function showMessage(text, type) {
      if (!messageEl) {
        return;
      }
      messageEl.textContent = text;
      messageEl.classList.remove('error', 'success');
      if (type) {
        messageEl.classList.add(type);
      }
    }

    form.addEventListener('submit', function (e) {
      e.preventDefault();

      if (typeof window.mkwvsNewsletter === 'undefined') {
        showMessage('Configuration manquante.', 'error');
        return;
      }

      var emailInput = form.querySelector('input[name="email"]');
      var consentInput = form.querySelector('input[name="consent"]');
      var websiteInput = form.querySelector('input[name="website"]');

      var email = emailInput ? emailInput.value.trim() : '';
      var consent = consentInput && consentInput.checked ? '1' : '';
      var website = websiteInput ? websiteInput.value : '';

      if (!email) {
        showMessage('Adresse e-mail requise.', 'error');
        return;
      }
      if (!consent) {
        showMessage('Veuillez accepter les conditions.', 'error');
        return;
      }

      submitBtn.disabled = true;
      showMessage('', null);

      var body = new URLSearchParams();
      body.append('action', 'brevo_subscribe');
      body.append('nonce', window.mkwvsNewsletter.nonce);
      body.append('email', email);
      body.append('consent', consent);
      body.append('website', website);

      fetch(window.mkwvsNewsletter.ajaxUrl, {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: body.toString()
      })
        .then(function (r) {
          return r.json().catch(function () {
            return { success: false, data: { message: 'Réponse invalide.' } };
          });
        })
        .then(function (json) {
          if (json && json.success) {
            showMessage(
              json.data && json.data.message ? json.data.message : 'Merci pour votre inscription.',
              'success'
            );
            form.reset();
          } else {
            showMessage(
              json && json.data && json.data.message ? json.data.message : 'Une erreur est survenue.',
              'error'
            );
          }
        })
        .catch(function () {
          showMessage('Une erreur réseau est survenue.', 'error');
        })
        .then(function () {
          submitBtn.disabled = false;
        });
    });
  });
})();
