(function () {
  var CALENDAR = '.hebergement__calendar';
  var checkIn = null;
  var checkOut = null;
  var offset = 0;

  function calendar() {
    return document.querySelector(CALENDAR);
  }

  function summary() {
    return document.querySelector('.hebergement__summary');
  }

  function booking() {
    return document.querySelector('.hebergement__booking');
  }

  function months() {
    var root = calendar();
    return root ? Array.prototype.slice.call(root.querySelectorAll('.hebergement__month')) : [];
  }

  function windowSize() {
    var root = calendar();
    return (root && parseInt(root.dataset.window, 10)) || 2;
  }

  function isAvailable(date) {
    var root = calendar();
    return !!(root && root.querySelector('button[data-date="' + date + '"]'));
  }

  function toDate(value) {
    var parts = value.split('-');
    return new Date(+parts[0], +parts[1] - 1, +parts[2]);
  }

  function toKey(date) {
    return date.getFullYear() + '-' +
      String(date.getMonth() + 1).padStart(2, '0') + '-' +
      String(date.getDate()).padStart(2, '0');
  }

  function nightsBetween(from, to) {
    return Math.round((toDate(to) - toDate(from)) / 86400000);
  }

  function everyNightFree(from, to) {
    var cursor = toDate(from);
    var last = toDate(to);
    while (cursor < last) {
      if (!isAvailable(toKey(cursor))) {
        return false;
      }
      cursor.setDate(cursor.getDate() + 1);
    }
    return true;
  }

  function track(event, data) {
    if (window.umami && typeof window.umami.track === 'function') {
      window.umami.track(event, data);
    }
  }

  function label(value) {
    return toDate(value).toLocaleDateString('fr-FR', { day: 'numeric', month: 'long' });
  }

  function ensureNav() {
    var root = calendar();
    if (!root || months().length <= windowSize()) {
      return;
    }

    var nav = document.querySelector('.hebergement__nav');
    if (nav && nav.parentNode === root.parentNode) {
      return;
    }

    var chevron = '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">' +
      '<path d="M15 4 L7 12 L15 20" fill="none" stroke="currentColor" stroke-width="3" ' +
      'stroke-linecap="round" stroke-linejoin="round"/></svg>';

    nav = document.createElement('div');
    nav.className = 'hebergement__nav';
    nav.innerHTML =
      '<button type="button" class="hebergement__nav-button" data-step="-1" aria-label="Mois précédents">' + chevron + '</button>' +
      '<p class="hebergement__period" aria-live="polite"></p>' +
      '<button type="button" class="hebergement__nav-button hebergement__nav-button--next" data-step="1" aria-label="Mois suivants">' + chevron + '</button>';
    root.parentNode.insertBefore(nav, root);
  }

  function showWindow() {
    var all = months();
    var size = windowSize();
    var max = Math.max(0, all.length - size);
    offset = Math.min(Math.max(0, offset), max);

    all.forEach(function (month, index) {
      month.hidden = index < offset || index >= offset + size;
    });

    var buttons = document.querySelectorAll('.hebergement__nav-button');
    if (buttons.length === 2) {
      buttons[0].disabled = offset === 0;
      buttons[1].disabled = offset >= max;
    }

    var period = document.querySelector('.hebergement__period');
    if (period) {
      var shown = all.slice(offset, offset + size).map(function (month) {
        var title = month.querySelector('.hebergement__month-title');
        return title ? title.textContent : '';
      });
      var text = shown.length > 1
        ? shown[0].replace(/\s\d{4}$/, '') + ' – ' + shown[shown.length - 1]
        : shown[0] || '';
      if (period.textContent !== text) {
        period.textContent = text;
      }
    }
  }

  function paint() {
    var root = calendar();
    if (!root) {
      return;
    }

    Array.prototype.forEach.call(root.querySelectorAll('button[data-date]'), function (day) {
      var date = day.dataset.date;
      day.classList.toggle('is-selected', date === checkIn || date === checkOut);
      day.classList.toggle('is-in-range', !!(checkIn && checkOut && date > checkIn && date < checkOut));
    });
  }

  function render() {
    paint();

    var text = summary();
    var slot = booking();
    if (!text || !slot) {
      return;
    }

    if (!slot.dataset.fallback) {
      slot.dataset.fallback = slot.innerHTML;
    }

    if (!checkIn) {
      text.textContent = text.dataset.empty;
      slot.innerHTML = slot.dataset.fallback;
      return;
    }

    if (!checkOut) {
      text.textContent = 'Arrivée le ' + label(checkIn) + '. Choisissez votre date de départ.';
      slot.innerHTML = slot.dataset.fallback;
      return;
    }

    var nights = nightsBetween(checkIn, checkOut);
    text.textContent = 'Du ' + label(checkIn) + ' au ' + label(checkOut) + ', ' +
      nights + (nights > 1 ? ' nuits.' : ' nuit.');

    var link = document.createElement('a');
    link.className = 'cta cta-decoration';
    link.href = slot.dataset.listing + '?check_in=' + checkIn + '&check_out=' + checkOut + '&adults=2';
    link.target = '_blank';
    link.rel = 'noopener';
    link.textContent = 'Réserver ces dates';
    link.setAttribute('data-umami-event', 'hebergement-airbnb');
    link.setAttribute('data-umami-event-source', 'calendrier');
    link.setAttribute('data-umami-event-nights', String(nights));

    slot.innerHTML = '';
    slot.appendChild(link);
  }

  function sync() {
    if (!calendar()) {
      return;
    }
    ensureNav();
    showWindow();
    paint();
  }

  document.addEventListener('click', function (event) {
    var step = event.target.closest('.hebergement__nav-button');
    if (step) {
      offset += parseInt(step.dataset.step, 10);
      showWindow();
      return;
    }

    var day = event.target.closest(CALENDAR + ' button[data-date]');
    if (!day) {
      return;
    }

    var date = day.dataset.date;

    if (!checkIn || checkOut || date <= checkIn) {
      var first = !checkIn;
      checkIn = date;
      checkOut = null;
      render();
      if (first) {
        track('hebergement-arrivee', { date: date });
      }
      return;
    }

    if (!everyNightFree(checkIn, date)) {
      track('hebergement-indisponible', { arrivee: checkIn, depart: date });
      checkIn = date;
      checkOut = null;
      render();
      summary().textContent = 'Ce séjour croisait des nuits réservées. Nouvelle arrivée le ' +
        label(date) + '. Choisissez votre date de départ.';
      return;
    }

    checkOut = date;
    render();
    track('hebergement-dates', {
      arrivee: checkIn,
      depart: checkOut,
      nuits: nightsBetween(checkIn, checkOut)
    });
  });

  sync();

  var observer = new MutationObserver(function () {
    observer.disconnect();
    sync();
    observer.observe(document.body, { childList: true, subtree: true });
  });
  observer.observe(document.body, { childList: true, subtree: true });
})();
