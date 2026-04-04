<div class="priv-wizard" id="priv-form" style="display: none;">

    <div class="priv-wizard__mode-choice" id="priv-mode-choice" style="display: none;">
        <p class="priv-wizard__mode-question">Souhaitez-vous réserver un espace lorsque nous sommes ouverts ou privatiser complètement ?</p>
        <div class="priv-wizard__mode-options">
            <label class="priv-wizard__mode-option">
                <input type="radio" name="mode_choice" value="reservation">
                <span class="priv-wizard__mode-card">
                    <strong>Réserver un espace</strong>
                    <small>Pour un groupe jusqu'à 40 personnes, pendant nos heures d'ouverture</small>
                </span>
            </label>
            <label class="priv-wizard__mode-option">
                <input type="radio" name="mode_choice" value="privatisation">
                <span class="priv-wizard__mode-card">
                    <strong>Privatiser la péniche</strong>
                    <small>Accès exclusif à l'ensemble du bateau pour votre évènement</small>
                </span>
            </label>
        </div>
    </div>

    <div class="priv-wizard__steps">
        <span class="priv-wizard__step-indicator priv-wizard__step-indicator--active" data-step="1">1. Profil</span>
        <span class="priv-wizard__step-indicator" data-step="2">2. Évènement</span>
        <span class="priv-wizard__step-indicator" data-step="3">3. Options & devis</span>
    </div>

    <form id="priv-wizard-form" novalidate>
        <input type="hidden" name="mode" id="priv-mode" value="privatisation">

        <!-- Step 1: Profil -->
        <div class="priv-wizard__panel priv-wizard__panel--active" data-step="1">
            <h3>Votre profil</h3>

            <div class="priv-wizard__field">
                <label>Type de demandeur <span class="required">*</span></label>
                <div class="priv-wizard__radios">
                    <label><input type="radio" name="type" value="particulier" checked> Particulier</label>
                    <label><input type="radio" name="type" value="entreprise"> Entreprise</label>
                    <label><input type="radio" name="type" value="association"> Association</label>
                </div>
            </div>

            <div class="priv-wizard__field">
                <label for="priv-nom">Nom <span class="required">*</span></label>
                <input type="text" id="priv-nom" name="nom" required class="a-input">
            </div>

            <div class="priv-wizard__field">
                <label for="priv-prenom">Prénom <span class="required">*</span></label>
                <input type="text" id="priv-prenom" name="prenom" required class="a-input">
            </div>

            <div class="priv-wizard__field">
                <label for="priv-email">Email <span class="required">*</span></label>
                <input type="email" id="priv-email" name="email" required class="a-input">
            </div>

            <div class="priv-wizard__field">
                <label for="priv-telephone">Téléphone <span class="required">*</span></label>
                <input type="tel" id="priv-telephone" name="telephone" required class="a-input">
            </div>

            <div class="priv-wizard__field priv-wizard__field--org" id="priv-field-org" style="display: none;">
                <label for="priv-organisation">Organisation <span class="required">*</span></label>
                <input type="text" id="priv-organisation" name="organisation" class="a-input">
            </div>

            <div class="priv-wizard__field priv-wizard__field--checkbox">
                <label>
                    <input type="checkbox" name="rgpd_consent" id="priv-rgpd" required>
                    J'accepte que mes données personnelles soient traitées dans le cadre de ma demande de privatisation.
                </label>
            </div>
        </div>

        <!-- Step 2: Evenement -->
        <div class="priv-wizard__panel" data-step="2" style="display: none;">
            <h3>Votre évènement</h3>

            <div class="priv-wizard__field">
                <label>Date sélectionnée</label>
                <div class="priv-wizard__date-display" id="priv-date-display"></div>
                <input type="hidden" name="date" id="priv-date">
                <input type="hidden" name="date_status" id="priv-date-status">
            </div>

            <div class="priv-wizard__row">
                <div class="priv-wizard__field">
                    <label for="priv-heure-debut">Heure de début <span class="required">*</span></label>
                    <select id="priv-heure-debut" name="heure_debut" required class="a-select">
                        <option value="">—</option>
                        <option value="9">9h</option>
                        <option value="10">10h</option>
                        <option value="11">11h</option>
                        <option value="12">12h</option>
                        <option value="13">13h</option>
                        <option value="14">14h</option>
                        <option value="15">15h</option>
                        <option value="16">16h</option>
                        <option value="17">17h</option>
                        <option value="18">18h</option>
                        <option value="19">19h</option>
                        <option value="20">20h</option>
                        <option value="21">21h</option>
                        <option value="22">22h</option>
                        <option value="23">23h</option>
                        <option value="0">Minuit</option>
                    </select>
                </div>

                <div class="priv-wizard__field">
                    <label for="priv-heure-fin">Heure de fin <span class="required">*</span></label>
                    <select id="priv-heure-fin" name="heure_fin" required class="a-select">
                        <option value="">—</option>
                        <option value="10">10h</option>
                        <option value="11">11h</option>
                        <option value="12">12h</option>
                        <option value="13">13h</option>
                        <option value="14">14h</option>
                        <option value="15">15h</option>
                        <option value="16">16h</option>
                        <option value="17">17h</option>
                        <option value="18">18h</option>
                        <option value="19">19h</option>
                        <option value="20">20h</option>
                        <option value="21">21h</option>
                        <option value="22">22h</option>
                        <option value="23">23h</option>
                        <option value="0">Minuit</option>
                        <option value="1">1h</option>
                        <option value="2">2h</option>
                    </select>
                </div>
            </div>
            <p class="priv-wizard__hint" id="priv-hint-minuit" style="display: none;">Les heures au-delà de minuit sont facturées au tarif majoré (nuit).</p>

            <div class="priv-wizard__field">
                <label for="priv-nb-personnes">Nombre de personnes <span class="required">*</span></label>
                <input type="number" id="priv-nb-personnes" name="nb_personnes" min="1" max="100" required class="a-input">
                <p class="priv-wizard__hint" id="priv-hint-no-service" style="display: none;">Au-delà de 60 personnes, le service à table n'est pas disponible.</p>
                <p class="priv-wizard__hint priv-wizard__hint--warning" id="priv-hint-jauge" style="display: none;">Au-delà de 80 personnes, le devis n'est pas automatique. Votre demande sera étudiée afin de valider la jauge en fonction du type d'évènement prévu.</p>
                <p class="priv-wizard__hint" id="priv-hint-reservation-max" style="display: none;">Au-delà de 40 personnes, une privatisation complète est nécessaire.</p>
            </div>

            <div class="priv-wizard__field">
                <label for="priv-description">Description / objet de l'évènement <span class="required">*</span></label>
                <textarea id="priv-description" name="description" rows="3" class="a-input" required></textarea>
            </div>
        </div>

        <!-- Step 3: Options -->
        <div class="priv-wizard__panel" data-step="3" style="display: none;">
            <h3>Options & services</h3>

            <?php $td = mkwvs_priv_get_tarifs_for_display(); ?>
            <div class="priv-wizard__options">
                <div class="priv-wizard__field priv-wizard__field--checkbox">
                    <label><input type="checkbox" name="opt_son_lumiere"> Son et lumière — <?php echo $td['son_lumiere']; ?> € HT</label>
                </div>
                <div class="priv-wizard__field priv-wizard__field--checkbox">
                    <label><input type="checkbox" name="opt_tonnelle"> Tonnelle — <?php echo $td['tonnelle']; ?> € HT</label>
                </div>
                <div class="priv-wizard__field priv-wizard__field--checkbox">
                    <label><input type="checkbox" name="opt_artiste"> Artiste — <?php echo $td['artiste']; ?> € HT</label>
                </div>
                <div class="priv-wizard__field priv-wizard__field--checkbox">
                    <label><input type="checkbox" name="opt_dj"> DJ set / Blindtest / Karaoké — <?php echo $td['dj']; ?> € HT</label>
                </div>

                <div class="priv-wizard__field">
                    <label for="priv-opt-autre">Autre service que vous souhaiteriez</label>
                    <textarea id="priv-opt-autre" name="opt_autre" rows="2" class="a-input" placeholder="Précisez ici tout autre service souhaité"></textarea>
                </div>

                <div class="priv-wizard__field">
                    <label for="priv-opt-repas">Formule repas</label>
                    <select id="priv-opt-repas" name="opt_repas" class="a-select">
                        <option value="aucun">Aucune</option>
                        <option value="2_plats">Entrée-plat ou plat-dessert — <?php echo $td['repas_2_plats']; ?> € HT/pers</option>
                        <option value="3_plats">Entrée-plat-dessert — <?php echo $td['repas_3_plats']; ?> € HT/pers</option>
                    </select>
                </div>

                <div class="priv-wizard__field priv-wizard__field--checkbox" id="priv-field-service-table" style="display: none;">
                    <label><input type="checkbox" name="opt_service_table"> Service à table (3h) — <?php echo $td['service_table']; ?> € HT</label>
                </div>

                <div class="priv-wizard__field priv-wizard__field--checkbox">
                    <label><input type="checkbox" name="opt_boissons" id="priv-opt-boissons"> Boissons (vins, bières, softs) — <?php echo $td['boisson']; ?> € HT/boisson</label>
                </div>

                <div class="priv-wizard__field" id="priv-field-boissons-qty" style="display: none;">
                    <label for="priv-opt-boissons-qty">Nombre de boissons</label>
                    <input type="number" id="priv-opt-boissons-qty" name="opt_boissons_quantite" min="1" class="a-input">
                </div>
            </div>

            <div class="priv-wizard__recap" id="priv-recap">
                <h4>Récapitulatif estimatif</h4>
                <p class="priv-wizard__recap-intro">Voici une première proposition tarifaire, et comme nous sommes magiques, elle pourra être adaptée ultérieurement selon vos besoins et contraintes !</p>
                <table id="priv-recap-table">
                    <thead>
                        <tr>
                            <th>Désignation</th>
                            <th>Qté</th>
                            <th>P.U. HT</th>
                            <th>Total HT</th>
                        </tr>
                    </thead>
                    <tbody id="priv-recap-body"></tbody>
                </table>
                <div class="priv-wizard__recap-totals" id="priv-recap-totals"></div>
            </div>
        </div>

        <!-- Navigation -->
        <div class="priv-wizard__nav">
            <button type="button" class="priv-wizard__btn priv-wizard__btn--prev" id="priv-btn-prev" style="display: none;">Précédent</button>
            <button type="button" class="priv-wizard__btn priv-wizard__btn--next" id="priv-btn-next">Suivant</button>
            <button type="submit" class="priv-wizard__btn priv-wizard__btn--submit" id="priv-btn-submit" style="display: none;">Envoyer ma demande</button>
        </div>

    </form>

    <div class="priv-wizard__message" id="priv-message" style="display: none;"></div>
    <div class="priv-wizard__new-request" id="priv-new-request" style="display: none; text-align: center; margin-top: 20px;">
        <a href="#priv-calendar" class="priv-wizard__btn priv-wizard__btn--next" id="priv-btn-new">Faire une nouvelle demande</a>
    </div>
</div>
