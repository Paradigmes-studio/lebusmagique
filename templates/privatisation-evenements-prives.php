<?php

/**
 * Template Name: Privatisation — Événements privés
 */
?>
<?php get_header(); ?>

<?php if (have_posts()) : ?>
  <?php while (have_posts()) : the_post(); ?>

    <?php
    $page_url = get_permalink();
    $thumbnail_id = get_post_thumbnail_id();
    $image_url = $thumbnail_id ? wp_get_attachment_image_url($thumbnail_id, 'full') : '';
    $location_url = get_permalink(get_page_by_path('location')) ?: '/location/';

    $service_schema = [
        '@context' => 'https://schema.org',
        '@type' => 'Service',
        'serviceType' => "Privatisation de péniche pour événements privés à Lille",
        'name' => "Privatiser une péniche à Lille — anniversaires, EVG, EVJF, mariages",
        'description' => "Location de péniche à Lille pour vos événements privés : anniversaires, enterrements de vie de garçon et de jeune fille, petits mariages, fêtes entre amis. Jusqu'à 100 personnes, quai de l'Esplanade.",
        'url' => $page_url,
        'image' => $image_url ?: null,
        'provider' => [
            '@type' => 'Organization',
            'name' => 'Le Bus Magique',
            'url' => home_url('/'),
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => 'Avenue Cuvier',
                'addressLocality' => 'Lille',
                'postalCode' => '59800',
                'addressCountry' => 'FR',
            ],
        ],
        'areaServed' => [
            '@type' => 'City',
            'name' => 'Lille',
        ],
        'offers' => [
            '@type' => 'Offer',
            'url' => $location_url,
            'priceCurrency' => 'EUR',
            'priceSpecification' => [
                '@type' => 'PriceSpecification',
                'priceCurrency' => 'EUR',
                'description' => 'Tarifs sur devis selon créneau, taille du groupe et prestations',
            ],
        ],
    ];
    $service_schema = array_filter($service_schema);

    $faq_schema = [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => [
            [
                '@type' => 'Question',
                'name' => "Peut-on privatiser la péniche pour un anniversaire à Lille ?",
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => "Oui. Le Bus Magique accueille vos anniversaires dans un cadre unique sur la Deûle, jusqu'à 100 personnes. Créneaux disponibles en soirée les lundi, mardi, mercredi, les dimanches à partir de 20h, et les samedis de 9h à 16h.",
                ],
            ],
            [
                '@type' => 'Question',
                'name' => "La péniche peut-elle accueillir un EVG ou un EVJF ?",
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => "Absolument. Notre péniche est un lieu parfait pour un enterrement de vie de garçon ou de jeune fille à Lille : ambiance festive, bar à cocktails, sonorisation, possibilité de DJ, décoration personnalisable.",
                ],
            ],
            [
                '@type' => 'Question',
                'name' => "Combien de personnes peut-on accueillir ?",
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => "La péniche accueille confortablement des groupes de 50 à 100 personnes. Pour des groupes plus petits (moins de 50 personnes), contactez-nous pour étudier une formule adaptée.",
                ],
            ],
            [
                '@type' => 'Question',
                'name' => "Quels sont les créneaux de privatisation ?",
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => "Privatisation possible les lundi, mardi et mercredi (journée et soirée), les dimanches à partir de 20h, et les samedis de 9h à 16h. Les autres créneaux peuvent être étudiés au cas par cas.",
                ],
            ],
            [
                '@type' => 'Question',
                'name' => "Le bar et la restauration sont-ils inclus ?",
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => "Notre bar est opéré par notre équipe pendant votre privatisation. Plusieurs formules restauration sont disponibles : planches apéro, repas 2 ou 3 plats, service à table, boissons. Tarifs indicatifs à partir de 25 €/personne pour un repas.",
                ],
            ],
            [
                '@type' => 'Question',
                'name' => "Comment obtenir un devis de privatisation ?",
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => "Depuis notre page Privatisation, sélectionnez une date disponible dans le calendrier et remplissez le formulaire en ligne. Vous recevrez un devis automatique détaillé selon vos prestations choisies.",
                ],
            ],
        ],
    ];
    ?>
    <script type="application/ld+json"><?php echo wp_json_encode($service_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?></script>
    <script type="application/ld+json"><?php echo wp_json_encode($faq_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?></script>

    <section class="section-landing-standard">

      <?php include(locate_template('template-part/blocks/page-head.php')); ?>

      <div class="text-yellow-background bottom priv-intro">
        <h2>Louez une péniche à Lille pour vos événements privés</h2>
        <p>Anniversaire, EVG, EVJF, mariage intime, fête entre amis : Le Bus Magique, péniche amarrée quai de l'Esplanade à Lille, vous ouvre ses portes pour une privatisation complète. Un lieu atypique en bord de Deûle, salle intérieure et terrasse, bar opéré par notre équipe, prestations sur mesure. Jusqu'à 100 personnes.</p>
      </div>

    </section>

    <section class="event-recurrent">

      <div class="event-recurrent__grid">

        <article class="event-card event-card--red">
          <h2>Anniversaire sur une péniche à Lille</h2>
          <p>Organisez un anniversaire mémorable dans un cadre unique sur la Deûle, à deux pas de la Citadelle. La péniche Le Bus Magique vous accueille avec sa salle intérieure chaleureuse, sa terrasse extérieure, sa sonorisation et son bar.</p>
          <p>Formule sur mesure : décoration personnalisée, gâteau d'anniversaire, DJ ou playlist maison, formules repas à la carte. Idéal pour un anniversaire 30, 40, 50 ans ou plus.</p>
        </article>

        <article class="event-card event-card--green">
          <h2>EVG / EVJF à Lille sur péniche</h2>
          <p>Marquez le coup avec un enterrement de vie de garçon ou de jeune fille original à Lille. La péniche offre une ambiance festive et intime, avec bar à cocktails, possibilité de DJ, et des formules adaptées aux groupes d'amis.</p>
          <p>Bonus lillois : vous êtes à côté du Vieux-Lille, parfait pour enchaîner avec une sortie en ville après la soirée sur l'eau.</p>
        </article>

        <article class="event-card event-card--yellow">
          <h2>Mariage intime & petites cérémonies</h2>
          <p>Pour un mariage civil intime, une fête d'union, un PACS ou un anniversaire de mariage, la péniche offre un cadre original et bucolique. Capacité de 50 à 100 convives assis ou cocktail.</p>
          <p>Restauration sur place possible : repas servi à table (25 à 30 €/pers), cocktail dinatoire, planches apéro. Bar ouvert avec formule boissons (vin, bière, softs à 3,50 € en moyenne).</p>
        </article>

        <article class="event-card event-card--blue">
          <h2>Ce qui est inclus</h2>
          <ul>
            <li>Espace intérieur chauffé + terrasse extérieure</li>
            <li>Bar opéré par notre équipe</li>
            <li>Sonorisation et éclairage (150 € option son & lumière)</li>
            <li>Mise à disposition du mobilier (tables, chaises)</li>
            <li>Options : DJ (250 €), artiste en live (450 €), tonnelle (40 €), service à table (120 €)</li>
            <li>Formules repas : 2 plats (25 €/pers) ou 3 plats (30 €/pers)</li>
          </ul>
        </article>

      </div>

      <div class="event-recurrent__faq">
        <h2>Questions fréquentes sur la privatisation</h2>

        <details class="event-faq-item">
          <summary><h3>Peut-on privatiser la péniche pour un anniversaire à Lille ?</h3></summary>
          <p>Oui. Le Bus Magique accueille vos anniversaires dans un cadre unique sur la Deûle, jusqu'à 100 personnes. Créneaux disponibles en soirée les lundi, mardi, mercredi, les dimanches à partir de 20h, et les samedis de 9h à 16h.</p>
        </details>

        <details class="event-faq-item">
          <summary><h3>La péniche peut-elle accueillir un EVG ou un EVJF ?</h3></summary>
          <p>Absolument. Notre péniche est un lieu parfait pour un enterrement de vie de garçon ou de jeune fille à Lille : ambiance festive, bar à cocktails, sonorisation, DJ possible, décoration personnalisable. Parking à proximité.</p>
        </details>

        <details class="event-faq-item">
          <summary><h3>Combien de personnes peut-on accueillir ?</h3></summary>
          <p>La péniche accueille confortablement des groupes de 50 à 100 personnes. À noter : au-delà de 60 convives, le service à table n'est pas disponible ; au-delà de 80, le devis n'est pas automatique et la jauge est validée selon le type d'événement. Pour des groupes plus petits (moins de 50 personnes), <a href="/contact/">contactez-nous</a> pour étudier une formule adaptée.</p>
        </details>

        <details class="event-faq-item">
          <summary><h3>Quels sont les créneaux de privatisation ?</h3></summary>
          <p>Privatisation possible les lundi, mardi et mercredi (journée et soirée), les dimanches à partir de 20h, et les samedis de 9h à 16h. Les autres créneaux peuvent être étudiés au cas par cas.</p>
        </details>

        <details class="event-faq-item">
          <summary><h3>Le bar et la restauration sont-ils inclus ?</h3></summary>
          <p>Le bar est opéré par notre équipe pendant toute votre privatisation. Plusieurs formules restauration sont disponibles : planches apéro à partager, repas 2 ou 3 plats (25-30 €/pers), service à table en option (120 €), formules boissons.</p>
        </details>

        <details class="event-faq-item">
          <summary><h3>Comment obtenir un devis de privatisation ?</h3></summary>
          <p>Depuis notre <a href="<?php echo esc_url($location_url); ?>">page Privatisation</a>, sélectionnez une date disponible dans le calendrier et remplissez le formulaire en ligne. Vous recevrez un devis automatique détaillé selon vos prestations choisies.</p>
        </details>
      </div>

      <div class="event-recurrent__cta">
        <h2>Réserver votre privatisation</h2>
        <p>Choisissez une date disponible dans le calendrier ci-dessous et obtenez votre devis en ligne en quelques minutes. Des frais de dossier de 100 € sont appliqués à toute réservation confirmée.</p>
      </div>

    </section>

    <section class="section-privatisation">
      <?php include(locate_template('template-part/privatisation/calendar.php')); ?>
      <?php include(locate_template('template-part/privatisation/form-wizard.php')); ?>
    </section>

  <?php endwhile; ?>
<?php endif; ?>


<?php get_footer();
