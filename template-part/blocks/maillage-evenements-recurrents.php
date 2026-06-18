<?php
/**
 * Bloc maillage interne vers les 4 pages d'événements récurrents.
 * Utilisé sur la home et la page programmation.
 */
?>
<section class="maillage-events" aria-labelledby="maillage-events-title">
    <div class="maillage-events__inner">
        <h2 id="maillage-events-title" class="maillage-events__title">Nos rendez-vous récurrents</h2>
        <p class="maillage-events__intro">Toute l'année, des soirées et ateliers qui rythment la vie de la péniche. Choisissez votre format préféré&nbsp;:</p>

        <div class="maillage-events__grid">
            <a class="maillage-card maillage-card--red" href="<?php echo esc_url(home_url('/evenements/blind-test-lille/')); ?>">
                <span class="maillage-card__kicker">Une fois par mois</span>
                <h3 class="maillage-card__title">Blind test</h3>
                <p class="maillage-card__text">Quiz musical en équipe à Lille, sur la péniche. Entrée libre, ambiance bistrot.</p>
                <span class="maillage-card__link">En savoir plus</span>
            </a>

            <a class="maillage-card maillage-card--green" href="<?php echo esc_url(home_url('/evenements/jam-session-lille/')); ?>">
                <span class="maillage-card__kicker">Une fois par mois</span>
                <h3 class="maillage-card__title">Jam session</h3>
                <p class="maillage-card__text">Scène ouverte aux musiciennes et musiciens. Venez jouer ou simplement écouter.</p>
                <span class="maillage-card__link">En savoir plus</span>
            </a>

            <a class="maillage-card maillage-card--yellow" href="<?php echo esc_url(home_url('/evenements/scene-ouverte-lille/')); ?>">
                <span class="maillage-card__kicker">Une fois par mois</span>
                <h3 class="maillage-card__title">Scène ouverte</h3>
                <p class="maillage-card__text">Poésie, slam, drag, stand-up, musique&nbsp;: la scène est à vous. Entrée libre.</p>
                <span class="maillage-card__link">En savoir plus</span>
            </a>

            <a class="maillage-card maillage-card--blue" href="<?php echo esc_url(home_url('/evenements/ateliers-lille/')); ?>">
                <span class="maillage-card__kicker">Programmation régulière</span>
                <h3 class="maillage-card__title">Ateliers</h3>
                <p class="maillage-card__text">Écriture, linogravure, café philo, broderie, punch needle, fleurs de Bach…</p>
                <span class="maillage-card__link">En savoir plus</span>
            </a>
        </div>
    </div>
</section>
