(function () {
  var root = document.querySelector('.hebergement__calendar');
  var summary = document.querySelector('.hebergement__summary');
  var booking = document.querySelector('.hebergement__booking');
  if (!root || !summary || !booking) {
    return;
  }

  var days = Array.prototype.slice.call(root.querySelectorAll('button[data-date]'));
  var available = {};
  days.forEach(function (day) {
    available[day.dataset.date] = true;
  });

  var checkIn = null;
  var checkOut = null;

  var months = Array.prototype.slice.call(root.querySelectorAll('.hebergement__month'));
  var windowSize = parseInt(root.dataset.window, 10) || 2;
  var offset = 0;
  var nav = null;
  var prevButton = null;
  var nextButton = null;

  function monthLabel(index) {
    var title = months[index] && months[index].querySelector('h3');
    return title ? title.textContent : '';
  }

  function showWindow() {
    months.forEach(function (month, index) {
      month.hidden = index < offset || index >= offset + windowSize;
    });

    if (!prevButton) {
      return;
    }

    prevButton.disabled = offset === 0;
    nextButton.disabled = offset + windowSize >= months.length;
    prevButton.setAttribute('aria-label', 'Mois précédents');
    nextButton.setAttribute('aria-label', 'Mois suivants');
  }

  function buildNav() {
    if (months.length <= windowSize) {
      return;
    }

    nav = document.createElement('div');
    nav.className = 'hebergement__nav';

    prevButton = document.createElement('button');
    prevButton.type = 'button';
    prevButton.className = 'hebergement__nav-button';
    prevButton.innerHTML = '&larr;';

    nextButton = document.createElement('button');
    nextButton.type = 'button';
    nextButton.className = 'hebergement__nav-button';
    nextButton.innerHTML = '&rarr;';

    prevButton.addEventListener('click', function () {
      offset = Math.max(0, offset - 1);
      showWindow();
    });

    nextButton.addEventListener('click', function () {
      offset = Math.min(months.length - windowSize, offset + 1);
      showWindow();
    });

    nav.appendChild(prevButton);
    nav.appendChild(nextButton);
    root.parentNode.insertBefore(nav, root);
  }

  buildNav();
  showWindow();

  function toDate(value) {
    var parts = value.split('-');
    return new Date(+parts[0], +parts[1] - 1, +parts[2]);
  }

  function nightsBetween(from, to) {
    return Math.round((toDate(to) - toDate(from)) / 86400000);
  }

  function toKey(date) {
    return date.getFullYear() + '-' +
      String(date.getMonth() + 1).padStart(2, '0') + '-' +
      String(date.getDate()).padStart(2, '0');
  }

  function everyNightFree(from, to) {
    var cursor = toDate(from);
    var last = toDate(to);
    while (cursor < last) {
      if (!available[toKey(cursor)]) {
        return false;
      }
      cursor.setDate(cursor.getDate() + 1);
    }
    return true;
  }

  function label(value) {
    return toDate(value).toLocaleDateString('fr-FR', { day: 'numeric', month: 'long' });
  }

  function paint() {
    days.forEach(function (day) {
      var date = day.dataset.date;
      var inRange = checkIn && checkOut && date > checkIn && date < checkOut;
      day.classList.toggle('is-selected', date === checkIn || date === checkOut);
      day.classList.toggle('is-in-range', !!inRange);
    });
  }

  function render() {
    paint();

    if (!checkIn) {
      summary.textContent = summary.dataset.empty;
      booking.innerHTML = '';
      return;
    }

    if (!checkOut) {
      summary.textContent = 'Arrivée le ' + label(checkIn) + '. Choisissez votre date de départ.';
      booking.innerHTML = '';
      return;
    }

    var nights = nightsBetween(checkIn, checkOut);
    summary.textContent = 'Du ' + label(checkIn) + ' au ' + label(checkOut) +
      ', ' + nights + (nights > 1 ? ' nuits.' : ' nuit.');

    var url = booking.dataset.listing +
      '?check_in=' + checkIn + '&check_out=' + checkOut + '&adults=2';

    var link = document.createElement('a');
    link.className = 'cta cta-decoration';
    link.href = url;
    link.target = '_blank';
    link.rel = 'noopener';
    link.textContent = 'Réserver ces dates';
    link.setAttribute('data-umami-event', 'hebergement-airbnb');
    link.setAttribute('data-umami-event-source', 'calendrier');
    link.setAttribute('data-umami-event-nights', String(nights));

    booking.innerHTML = '';
    booking.appendChild(link);
  }

  root.addEventListener('click', function (event) {
    var day = event.target.closest('button[data-date]');
    if (!day) {
      return;
    }

    var date = day.dataset.date;

    if (!checkIn || checkOut || date <= checkIn) {
      checkIn = date;
      checkOut = null;
      render();
      return;
    }

    if (!everyNightFree(checkIn, date)) {
      summary.textContent = 'Ce séjour croise des nuits déjà réservées. Choisissez une autre date de départ.';
      checkOut = null;
      paint();
      return;
    }

    checkOut = date;
    render();
  });
})();
