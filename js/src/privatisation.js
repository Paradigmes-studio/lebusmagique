(function($) {
    'use strict';

    var MONTHS = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];

    var currentYear, currentMonth, currentStep = 1;
    var selectedDate = null, selectedStatus = null;
    var currentBookings = {};
    var currentMinHours = {};
    var currentMaxHours = {};
    var currentReservable = {};
    var selectedDayBookings = [];
    var allDebutOptions = null;
    var currentMode = 'privatisation';

    // ============================================================
    // CALENDAR
    // ============================================================

    function initCalendar() {
        var now = new Date();
        currentYear = now.getFullYear();
        currentMonth = now.getMonth() + 1;
        loadMonth(currentYear, currentMonth);

        $('#priv-cal-prev').on('click', function() {
            currentMonth--;
            if (currentMonth < 1) { currentMonth = 12; currentYear--; }
            loadMonth(currentYear, currentMonth);
        });

        $('#priv-cal-next').on('click', function() {
            currentMonth++;
            if (currentMonth > 12) { currentMonth = 1; currentYear++; }
            loadMonth(currentYear, currentMonth);
        });
    }

    function loadMonth(year, month) {
        $('#priv-cal-title').text(MONTHS[month - 1] + ' ' + year);
        $('#priv-cal-body').html('<div class="priv-calendar__loading">Chargement...</div>');

        $.ajax({
            url: privData.ajaxurl,
            type: 'GET',
            data: { action: 'priv_get_calendar', year: year, month: month },
            success: function(response) {
                if (response.success) {
                    currentBookings = response.data.bookings || {};
                    currentMinHours = response.data.min_hours || {};
                    currentMaxHours = response.data.max_hours || {};
                    currentReservable = response.data.reservable || {};
                    renderMonth(year, month, response.data.statuses);
                }
            }
        });
    }

    function renderMonth(year, month, statuses) {
        var firstDay = new Date(year, month - 1, 1).getDay();
        // Convert Sunday=0 to 7 for Monday-first grid
        firstDay = firstDay === 0 ? 7 : firstDay;
        var daysInMonth = new Date(year, month, 0).getDate();

        var html = '';

        // Empty cells before first day
        for (var i = 1; i < firstDay; i++) {
            html += '<span class="priv-day priv-day--empty"></span>';
        }

        for (var day = 1; day <= daysInMonth; day++) {
            var status = statuses[day] || 'grey';
            var clickable = status !== 'grey';
            var dayBookings = currentBookings[day] || [];
            var hasBooking = dayBookings.length > 0 || currentMinHours[day] !== undefined || currentMaxHours[day] !== undefined;
            var dateStr = year + '-' + String(month).padStart(2, '0') + '-' + String(day).padStart(2, '0');

            // 2+ bookings = fully booked
            if (dayBookings.length >= 2) {
                status = 'grey';
                clickable = false;
                hasBooking = false;
            }

            var classes = 'priv-day priv-day--' + status;
            if (hasBooking && clickable) classes += ' priv-day--has-booking';
            if (clickable) classes += ' priv-day--clickable';

            html += '<span class="' + classes + '"';
            if (clickable) {
                html += ' data-date="' + dateStr + '" data-status="' + status + '" data-day="' + day + '"';
            }
            html += '>' + day + '</span>';
        }

        $('#priv-cal-body').html(html);

        // Click handler
        $('#priv-cal-body').off('click', '.priv-day--clickable').on('click', '.priv-day--clickable', function() {
            var $day = $(this);
            selectedDate = $day.data('date');
            selectedStatus = $day.data('status');
            var dayNum = $day.data('day');

            // Highlight selected
            $('.priv-day--selected').removeClass('priv-day--selected');
            $day.addClass('priv-day--selected');

            // Show form and set date
            showForm(dayNum);
        });
    }

    function showForm(dayNum) {
        var parts = selectedDate.split('-');
        var displayDate = parts[2] + '/' + parts[1] + '/' + parts[0];

        $('#priv-date').val(selectedDate);
        $('#priv-date-status').val(selectedStatus);
        $('#priv-date-display').text(displayDate);

        // Add status badge
        var statusLabels = { green: 'Devis automatique', orange: 'À étudier', red: 'Urgent' };
        var statusColors = { green: '#9bb909', orange: '#ec6620', red: '#e6534e' };
        $('#priv-date-display').append(
            ' <span style="display:inline-block;padding:2px 8px;border-radius:3px;font-size:11px;color:#fff;background:' +
            statusColors[selectedStatus] + ';">' + statusLabels[selectedStatus] + '</span>'
        );

        // Show booking info banner
        selectedDayBookings = currentBookings[dayNum] || [];
        showBookingBanner(selectedDayBookings);

        // Rebuild heure_debut from cloned options, applying Sunday + booking restrictions
        rebuildHeureDebut(selectedDayBookings);

        // Show mode choice if date allows reservation
        var isReservable = currentReservable[dayNum] || false;
        if (isReservable) {
            $('input[name="mode_choice"]').prop('checked', false);
            $('#priv-mode-choice').show();
            $('.priv-wizard__steps').hide();
            $('#priv-wizard-form').hide();
        } else {
            $('#priv-mode-choice').hide();
            setMode('privatisation');
            $('.priv-wizard__steps').show();
            $('#priv-wizard-form').show();
            goToStep(1);
        }

        $('#priv-form').slideDown(300);

        // Scroll to form
        $('html, body').animate({ scrollTop: $('#priv-form').offset().top - 50 }, 400);
    }

    function setMode(mode) {
        currentMode = mode;
        $('#priv-mode').val(mode);

        if (mode === 'reservation') {
            $('.priv-wizard__step-indicator[data-step="3"]').hide();
            $('#priv-nb-personnes').attr('max', 40);
        } else {
            $('.priv-wizard__step-indicator[data-step="3"]').show();
            $('#priv-nb-personnes').attr('max', 100);
        }

        updateCapacityWarnings();
    }

    function showBookingBanner(bookings) {
        $('#priv-booking-banner').remove();

        if (!bookings || bookings.length === 0) return;

        var message = '';
        var slots = [];
        for (var i = 0; i < bookings.length; i++) {
            var bk = bookings[i];
            var startLabel = (bk.heure_debut === 0) ? 'minuit' : bk.heure_debut + 'h';
            var endLabel = (bk.heure_fin === 0) ? 'minuit' : bk.heure_fin + 'h';
            slots.push(startLabel + '-' + endLabel);
        }

        if (bookings.length === 1) {
            message = 'Une privatisation est déjà prévue de ' + slots[0].replace('-', ' à ') + ' sur cette date.';
        } else {
            var slotsText = slots.slice(0, -1).join(', ') + ' et ' + slots[slots.length - 1];
            message = bookings.length + ' privatisations sont déjà prévues sur cette date : ' + slotsText + '.';
        }

        message += '<br><small>Un battement d\'une heure est appliqué entre chaque privatisation.</small>';

        $('#priv-form').prepend(
            '<div id="priv-booking-banner" class="priv-wizard__booking-banner">' + message + '</div>'
        );
    }

    function getOccupiedRanges(bookings) {
        var occupied = [];
        if (!bookings) return occupied;
        for (var i = 0; i < bookings.length; i++) {
            var bk = bookings[i];
            var startCalc = (bk.heure_debut === 0) ? 24 : bk.heure_debut;
            var endCalc = (bk.heure_fin <= 2) ? bk.heure_fin + 24 : bk.heure_fin;
            // 1h buffer between bookings
            occupied.push({ start: startCalc - 1, end: endCalc + 1 });
        }
        return occupied;
    }

    function isHourOccupied(hCalc, occupied) {
        for (var i = 0; i < occupied.length; i++) {
            if (hCalc >= occupied[i].start && hCalc < occupied[i].end) {
                return true;
            }
        }
        return false;
    }

    function rebuildHeureDebut(bookings) {
        var select = document.getElementById('priv-heure-debut');
        var dk = new Dropkick(select);
        dk.dispose();

        // Rebuild from cloned options
        while (select.options.length) select.remove(0);

        var parts = selectedDate.split('-');
        var dayNum = parseInt(parts[2]);
        var occupied = getOccupiedRanges(bookings);

        // Min/max hour from server
        var minHour = currentMinHours[dayNum] || null;
        var maxHour = currentMaxHours[dayNum] || null;

        allDebutOptions.each(function() {
            var val = $(this).val();
            if (val === '') {
                select.add(new Option($(this).text(), ''));
                return;
            }
            var h = parseInt(val);
            var hCalc = (h === 0) ? 24 : h;

            // Sunday restriction: min hour
            if (minHour && h !== 0 && h < minHour) return;

            // Saturday restriction: max hour (début must be before max)
            if (maxHour && (h === 0 || h >= maxHour)) return;

            // Booking conflict: skip occupied hours
            if (isHourOccupied(hCalc, occupied)) return;

            select.add(new Option($(this).text(), val));
        });

        new Dropkick(select, { mobile: true });

        // Trigger heure_fin rebuild to apply booking filters
        $('#priv-heure-debut').trigger('change');
    }

    // ============================================================
    // WIZARD NAVIGATION
    // ============================================================

    function goToStep(step) {
        currentStep = step;
        var maxStep = (currentMode === 'reservation') ? 2 : 3;

        $('.priv-wizard__panel').hide();
        $('.priv-wizard__panel[data-step="' + step + '"]').show();

        $('.priv-wizard__step-indicator').removeClass('priv-wizard__step-indicator--active priv-wizard__step-indicator--done');
        $('.priv-wizard__step-indicator').each(function() {
            var s = parseInt($(this).data('step'));
            if (currentMode === 'reservation' && s === 3) return;
            if (s < step) $(this).addClass('priv-wizard__step-indicator--done');
            if (s === step) $(this).addClass('priv-wizard__step-indicator--active');
        });

        $('#priv-btn-prev').toggle(step > 1);
        $('#priv-btn-next').toggle(step < maxStep);
        $('#priv-btn-submit').toggle(step === maxStep);

        if (step === 3 && currentMode === 'privatisation') {
            updateRecap();
        }
    }

    function initWizard() {
        // Mode choice handler
        $('input[name="mode_choice"]').on('change', function() {
            var mode = $(this).val();
            setMode(mode);
            $('#priv-mode-choice').slideUp(200, function() {
                $('.priv-wizard__steps').show();
                $('#priv-wizard-form').show();
                goToStep(1);
            });
        });

        $('#priv-btn-next').on('click', function() {
            if (!validateStep(currentStep)) return;

            if (currentStep === 1) {
                var $btn = $(this);
                var email = $('#priv-email').val().trim();
                $btn.prop('disabled', true).text('Vérification…');
                $.post(ajaxurl, {
                    action: 'priv_check_email',
                    email: email,
                    _ajax_nonce: privData.nonce
                }).done(function(response) {
                    if (response.success) {
                        goToStep(currentStep + 1);
                    } else {
                        showError('#priv-email', response.data);
                    }
                }).fail(function() {
                    goToStep(currentStep + 1);
                }).always(function() {
                    $btn.prop('disabled', false).text('Suivant');
                });
                return;
            }

            goToStep(currentStep + 1);
        });

        $('#priv-btn-prev').on('click', function() {
            goToStep(currentStep - 1);
        });

        // Conditional fields — step 1
        $('input[name="type"]').on('change', function() {
            var val = $(this).val();
            if (val === 'entreprise' || val === 'association') {
                $('#priv-field-org').show();
            } else {
                $('#priv-field-org').hide();
                $('#priv-organisation').val('');
            }
        });

        // Clone select options before any manipulation
        allDebutOptions = $('#priv-heure-debut option').clone();
        var allFinOptions = $('#priv-heure-fin option').clone();

        $('#priv-heure-debut').on('change', function() {
            var hd = parseInt($(this).val());

            if (isNaN(hd)) {
                var selectFin = document.getElementById('priv-heure-fin');
                var dkFin = new Dropkick(selectFin);
                dkFin.dispose();
                while (selectFin.options.length) selectFin.remove(0);
                selectFin.add(new Option('–', ''));
                new Dropkick(selectFin, { mobile: true });
                return;
            }

            var hdCalc = (hd === 0) ? 24 : hd;

            var select = document.getElementById('priv-heure-fin');

            // Dispose existing Dropkick
            var dk = new Dropkick(select);
            dk.dispose();

            // Compute occupied ranges from current bookings
            var occupied = getOccupiedRanges(selectedDayBookings);

            // Rebuild native select options
            while (select.options.length) select.remove(0);
            var parts = selectedDate.split('-');
            var dayNum = parseInt(parts[2]);
            var maxHour = currentMaxHours[dayNum] || null;

            allFinOptions.each(function() {
                var val = $(this).val();
                if (val === '') {
                    select.add(new Option($(this).text(), ''));
                    return;
                }
                var hf = parseInt(val);
                var hfCalc = (hf <= 2) ? hf + 24 : hf;

                // Must be after heure_debut
                if (hfCalc <= hdCalc) return;

                // Saturday restriction: fin must be <= maxHour
                if (maxHour && (hf === 0 || hf > maxHour)) return;

                // Check if range [hdCalc, hfCalc] overlaps any booking
                var overlaps = false;
                for (var i = 0; i < occupied.length; i++) {
                    if (hdCalc < occupied[i].end && occupied[i].start < hfCalc) {
                        overlaps = true;
                        break;
                    }
                }
                if (overlaps) return;

                select.add(new Option($(this).text(), val));
            });

            // Re-init Dropkick
            new Dropkick(select, { mobile: true });

            updateMinuitHint();
        });

        $('#priv-heure-fin').on('change', function() {
            updateMinuitHint();
        });

        // Conditional fields — step 3
        $('#priv-opt-repas').on('change', function() {
            var nb = parseInt($('#priv-nb-personnes').val()) || 0;
            var show = $(this).val() !== 'aucun' && nb <= 60;
            $('#priv-field-service-table').toggle(show);
            if (!show) $('input[name="opt_service_table"]').prop('checked', false);
            updateRecap();
        });

        $('#priv-opt-boissons').on('change', function() {
            var show = $(this).is(':checked');
            $('#priv-field-boissons-qty').toggle(show);
            if (!show) $('#priv-opt-boissons-qty').val('');
            updateRecap();
        });

        // Update recap on any option change
        $(document).on('change', '#priv-wizard-form input, #priv-wizard-form select', function() {
            if (currentStep === 3) updateRecap();
        });
        $(document).on('input', '#priv-opt-boissons-qty, #priv-nb-personnes', function() {
            updateCapacityWarnings();
            if (currentStep === 3) updateRecap();
        });

        // New request button
        $('#priv-btn-new').on('click', function(e) {
            e.preventDefault();
            $('#priv-wizard-form')[0].reset();
            $('#priv-field-org').hide();
            $('#priv-field-service-table').hide();
            $('#priv-field-boissons-qty').hide();
            $('#priv-message').hide();
            $('#priv-new-request').hide();
            $('#priv-mode-choice').hide();
            currentMode = 'privatisation';
            $('#priv-mode').val('privatisation');
            $('.priv-wizard__step-indicator[data-step="3"]').show();
            $('#priv-nb-personnes').attr('max', 100);
            $('#priv-form').slideUp(300);
            $('.priv-day--selected').removeClass('priv-day--selected');
            selectedDate = null;
            selectedStatus = null;
            $('html, body').animate({ scrollTop: $('#priv-calendar').offset().top - 50 }, 400);
        });

        // Form submit
        $('#priv-wizard-form').on('submit', function(e) {
            e.preventDefault();
            var maxStep = (currentMode === 'reservation') ? 2 : 3;
            // Ignore submit if not on the last step
            if (currentStep !== maxStep) return;
            if (!validateStep(maxStep)) return;
            submitForm();
        });
    }

    function updateMinuitHint() {
        var hf = parseInt($('#priv-heure-fin').val());
        var show = currentMode === 'privatisation' && !isNaN(hf) && hf >= 0 && hf <= 2;
        $('#priv-hint-minuit').toggle(show);
    }

    function updateCapacityWarnings() {
        var nb = parseInt($('#priv-nb-personnes').val()) || 0;

        if (currentMode === 'reservation') {
            // En mode réservation, masquer les hints privatisation
            $('#priv-hint-no-service').hide();
            $('#priv-hint-jauge').hide();
            $('#priv-hint-reservation-max').toggle(nb > 40);
            return;
        }

        $('#priv-hint-reservation-max').hide();

        // 61+ : pas de service à table
        $('#priv-hint-no-service').toggle(nb > 60);
        if (nb > 60) {
            $('input[name="opt_service_table"]').prop('checked', false);
            $('#priv-field-service-table').hide();
        }

        // 81+ : pas de devis automatique
        $('#priv-hint-jauge').toggle(nb > 80);
    }

    // ============================================================
    // VALIDATION
    // ============================================================

    function validateStep(step) {
        clearErrors();

        if (step === 1) {
            var valid = true;
            var type = $('input[name="type"]:checked').val();

            if (!$('#priv-nom').val().trim()) { showError('#priv-nom', 'Champ requis'); valid = false; }
            if (!$('#priv-prenom').val().trim()) { showError('#priv-prenom', 'Champ requis'); valid = false; }

            var email = $('#priv-email').val().trim();
            if (!email) { showError('#priv-email', 'Champ requis'); valid = false; }
            else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { showError('#priv-email', 'Email invalide'); valid = false; }

            var tel = $('#priv-telephone').val().trim().replace(/[\s.\-()]/g, '');
            if (!tel) { showError('#priv-telephone', 'Champ requis'); valid = false; }
            else if (!/^(0[1-9])\d{8}$/.test(tel) && !/^\+33[1-9]\d{8}$/.test(tel)) {
                showError('#priv-telephone', 'Numéro invalide (ex: 06 12 34 56 78)'); valid = false;
            }

            if ((type === 'entreprise' || type === 'association') && !$('#priv-organisation').val().trim()) {
                showError('#priv-organisation', 'Champ requis'); valid = false;
            }

            if (!$('#priv-rgpd').is(':checked')) {
                showError('#priv-rgpd', 'Vous devez accepter la politique de confidentialité');
                valid = false;
            }

            return valid;
        }

        if (step === 2) {
            var valid = true;
            var heureDebut = $('#priv-heure-debut').val();
            var heureFin = $('#priv-heure-fin').val();
            var nbPersonnes = parseInt($('#priv-nb-personnes').val());

            if (heureDebut === '') { showError('#priv-heure-debut', 'Champ requis'); valid = false; }
            if (heureFin === '') { showError('#priv-heure-fin', 'Champ requis'); valid = false; }

            if (heureDebut !== '' && heureFin !== '') {
                var hd = parseInt(heureDebut);
                var hf = parseInt(heureFin);
                var hfCalc = (hf <= 2) ? hf + 24 : hf;
                var hdCalc = (hd === 0) ? 24 : hd;

                if (hfCalc <= hdCalc) {
                    showError('#priv-heure-fin', 'L\'heure de fin doit être après l\'heure de début');
                    valid = false;
                }
            }

            var maxPersonnes = (currentMode === 'reservation') ? 40 : 100;
            if (isNaN(nbPersonnes) || nbPersonnes < 1 || nbPersonnes > maxPersonnes) {
                var msg = (currentMode === 'reservation')
                    ? 'La réservation d\'espace est limitée à 40 personnes. Au-delà, choisissez « Privatiser ».'
                    : 'Nombre de personnes : entre 1 et 100';
                showError('#priv-nb-personnes', msg);
                valid = false;
            }

            if (!$('#priv-description').val().trim()) {
                showError('#priv-description', 'Veuillez décrire votre évènement');
                valid = false;
            }

            return valid;
        }

        return true;
    }

    function showError(selector, message) {
        var $el = $(selector);
        $el.addClass('priv-wizard__input--error');
        $el.closest('.priv-wizard__field').append('<span class="priv-wizard__error">' + message + '</span>');
    }

    function clearErrors() {
        $('.priv-wizard__input--error').removeClass('priv-wizard__input--error');
        $('.priv-wizard__error').remove();
    }

    // ============================================================
    // RECAP CALCULATION (mirrors PHP mkwvs_priv_calculate_quote)
    // ============================================================

    function updateRecap() {
        var nbPersonnes = parseInt($('#priv-nb-personnes').val()) || 0;
        var heureDebut = parseInt($('#priv-heure-debut').val());
        var heureFin = parseInt($('#priv-heure-fin').val());

        if (isNaN(heureDebut) || isNaN(heureFin) || nbPersonnes < 1) {
            $('#priv-recap-body').html('<tr><td colspan="4" style="text-align:center;">Remplissez l\'étape 2 pour voir le récapitulatif</td></tr>');
            $('#priv-recap-totals').html('');
            return;
        }

        var isSmall = nbPersonnes < 50;
        var t = privData.tarifs;
        var tarifs = {
            location_9_minuit: isSmall ? t.location_9_minuit_small : t.location_9_minuit_large,
            location_minuit_2h: isSmall ? t.location_minuit_2h_small : t.location_minuit_2h_large,
            frais_dossier: t.frais_dossier,
            son_lumiere: t.son_lumiere,
            artiste: t.artiste,
            dj: t.dj,
            tonnelle: t.tonnelle,
            repas_2_plats: t.repas_2_plats,
            repas_3_plats: t.repas_3_plats,
            service_table: t.service_table,
            boisson: t.boisson
        };

        var minuit = 24;
        var heureDebutCalc = (heureDebut === 0) ? 24 : heureDebut;
        var heureFinCalc = (heureFin <= 2) ? heureFin + 24 : heureFin;

        var heuresAvantMinuit = 0;
        var heuresApresMinuit = 0;

        if (heureDebutCalc < minuit) {
            heuresAvantMinuit = Math.min(minuit, heureFinCalc) - heureDebutCalc;
        }
        if (heureFinCalc > minuit) {
            heuresApresMinuit = heureFinCalc - Math.max(minuit, heureDebutCalc);
        }

        var lines = [];
        var totalHT20 = 0;
        var totalHT10 = 0;

        if (heuresAvantMinuit > 0) {
            var finLabel = (heureFinCalc >= minuit) ? 'minuit' : heureFin + 'h';
            var total = heuresAvantMinuit * tarifs.location_9_minuit;
            lines.push({ designation: 'Location de ' + heureDebut + 'h à ' + finLabel, qty: heuresAvantMinuit, unitPrice: tarifs.location_9_minuit, total: total });
            totalHT20 += total;
        }

        if (heuresApresMinuit > 0) {
            var total = heuresApresMinuit * tarifs.location_minuit_2h;
            lines.push({ designation: 'Location de minuit à ' + heureFin + 'h', qty: heuresApresMinuit, unitPrice: tarifs.location_minuit_2h, total: total });
            totalHT20 += total;
        }

        lines.push({ designation: 'Frais de dossier - service - ménage', qty: 1, unitPrice: tarifs.frais_dossier, total: tarifs.frais_dossier });
        totalHT20 += tarifs.frais_dossier;

        var opts20 = [
            { name: 'opt_son_lumiere', label: 'Son et lumière', key: 'son_lumiere' },
            { name: 'opt_artiste', label: 'Artiste', key: 'artiste' },
            { name: 'opt_dj', label: 'DJ set / Blindtest / Karaoké', key: 'dj' },
            { name: 'opt_tonnelle', label: 'Tonnelle', key: 'tonnelle' }
        ];

        opts20.forEach(function(opt) {
            if ($('input[name="' + opt.name + '"]').is(':checked')) {
                lines.push({ designation: opt.label, qty: 1, unitPrice: tarifs[opt.key], total: tarifs[opt.key] });
                totalHT20 += tarifs[opt.key];
            }
        });

        var repas = $('#priv-opt-repas').val();
        if (repas && repas !== 'aucun') {
            var is3Plats = (repas === '3_plats');
            var prixRepas = is3Plats ? tarifs.repas_3_plats : tarifs.repas_2_plats;
            var labelRepas = is3Plats ? 'Repas (entrée-plat-dessert)' : 'Repas (entrée-plat ou plat-dessert)';
            var totalRepas = prixRepas * nbPersonnes;
            lines.push({ designation: labelRepas, qty: nbPersonnes, unitPrice: prixRepas, total: totalRepas });
            totalHT10 += totalRepas;

            if ($('input[name="opt_service_table"]').is(':checked') && nbPersonnes <= 60) {
                lines.push({ designation: 'Service à table (3h)', qty: 1, unitPrice: tarifs.service_table, total: tarifs.service_table });
                totalHT10 += tarifs.service_table;
            }
        }

        if ($('#priv-opt-boissons').is(':checked')) {
            var qtyBoissons = parseInt($('#priv-opt-boissons-qty').val()) || 0;
            if (qtyBoissons > 0) {
                var totalBoissons = qtyBoissons * tarifs.boisson;
                lines.push({ designation: 'Boissons (vins, bières, softs)', qty: qtyBoissons, unitPrice: tarifs.boisson, total: totalBoissons });
                totalHT10 += totalBoissons;
            }
        }

        var totalHT = totalHT20 + totalHT10;
        var tva20 = Math.round(totalHT20 * 0.20 * 100) / 100;
        var tva10 = Math.round(totalHT10 * 0.10 * 100) / 100;
        var totalTTC = totalHT + tva20 + tva10;

        // Render
        var html = '';
        lines.forEach(function(line) {
            html += '<tr>';
            html += '<td>' + line.designation + '</td>';
            html += '<td>' + line.qty + '</td>';
            html += '<td>' + formatPrice(line.unitPrice) + '</td>';
            html += '<td>' + formatPrice(line.total) + '</td>';
            html += '</tr>';
        });
        $('#priv-recap-body').html(html);

        var totalsHtml = '<div class="priv-wizard__recap-line"><span>Total HT</span><span>' + formatPrice(totalHT) + '</span></div>';
        if (tva10 > 0) totalsHtml += '<div class="priv-wizard__recap-line"><span>TVA 10%</span><span>' + formatPrice(tva10) + '</span></div>';
        if (tva20 > 0) totalsHtml += '<div class="priv-wizard__recap-line"><span>TVA 20%</span><span>' + formatPrice(tva20) + '</span></div>';
        totalsHtml += '<div class="priv-wizard__recap-line priv-wizard__recap-line--total"><span>Total TTC</span><span>' + formatPrice(totalTTC) + '</span></div>';
        if (nbPersonnes > 80) {
            totalsHtml += '<div class="priv-wizard__recap-notice">Ce récapitulatif est indicatif. Votre demande sera étudiée pour valider la jauge.</div>';
        }
        $('#priv-recap-totals').html(totalsHtml);
    }

    function formatPrice(amount) {
        return amount.toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, ' ') + ' €';
    }

    // ============================================================
    // FORM SUBMISSION
    // ============================================================

    function resetSubmitButton() {
        $('#priv-btn-submit').prop('disabled', false).text('Envoyer ma demande');
    }

    function submitForm() {
        var $btn = $('#priv-btn-submit');
        $btn.prop('disabled', true).text('Envoi en cours...');

        var formData = {
            action: 'priv_submit',
            nonce: privData.nonce,
            mode: currentMode,
            type: $('input[name="type"]:checked').val(),
            nom: $('#priv-nom').val(),
            prenom: $('#priv-prenom').val(),
            email: $('#priv-email').val(),
            telephone: $('#priv-telephone').val(),
            organisation: $('#priv-organisation').val(),
            date: $('#priv-date').val(),
            heure_debut: $('#priv-heure-debut').val(),
            heure_fin: $('#priv-heure-fin').val(),
            nb_personnes: $('#priv-nb-personnes').val(),
            description: $('#priv-description').val(),
            rgpd_consent: $('#priv-rgpd').is(':checked') ? '1' : '',
            opt_son_lumiere: $('input[name="opt_son_lumiere"]').is(':checked') ? '1' : '',
            opt_artiste: $('input[name="opt_artiste"]').is(':checked') ? '1' : '',
            opt_dj: $('input[name="opt_dj"]').is(':checked') ? '1' : '',
            opt_tonnelle: $('input[name="opt_tonnelle"]').is(':checked') ? '1' : '',
            opt_repas: $('#priv-opt-repas').val(),
            opt_service_table: $('input[name="opt_service_table"]').is(':checked') ? '1' : '',
            opt_boissons: $('#priv-opt-boissons').is(':checked') ? '1' : '',
            opt_boissons_quantite: $('#priv-opt-boissons-qty').val() || '0',
            opt_autre: $('#priv-opt-autre').val() || ''
        };

        $.ajax({
            url: privData.ajaxurl,
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    loadMonth(currentYear, currentMonth);

                    $('#priv-booking-banner').remove();
                    $('#priv-wizard-form').hide();
                    $('.priv-wizard__steps').hide();
                    showMessage(response.data.message, 'success');
                    $('#priv-new-request').show();
                    resetSubmitButton();
                } else {
                    showMessage(response.data || 'Une erreur est survenue.', 'error');
                    resetSubmitButton();
                }
            },
            error: function() {
                loadMonth(currentYear, currentMonth);
                showMessage('Erreur de connexion. Veuillez rafraîchir la page et vérifier si votre demande a bien été enregistrée avant de réessayer.', 'error');
                resetSubmitButton();
            }
        });
    }

    function showMessage(message, type) {
        var $msg = $('#priv-message');
        $msg.html(message)
            .removeClass('priv-wizard__message--success priv-wizard__message--error')
            .addClass('priv-wizard__message--' + type)
            .slideDown(300);
    }

    // ============================================================
    // INIT
    // ============================================================

    $(document).ready(function() {
        if ($('#priv-calendar').length) {
            initCalendar();
            initWizard();
        }
    });

})(jQuery);
