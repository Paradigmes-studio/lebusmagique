<?php

/**
 * Template Name: Privatisation — Séminaires entreprise
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
        'serviceType' => "Séminaire d'entreprise sur péniche à Lille",
        'name' => "Séminaires, réunions et team building sur péniche à Lille — Le Bus Magique",
        'description' => "Lieu de séminaire atypique à Lille : péniche privatisable pour réunions d'entreprise, séminaires, team building, afterwork. Salle équipée, terrasse, restauration, jusqu'à 100 personnes.",
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
        'audience' => [
            '@type' => 'BusinessAudience',
            'name' => 'Entreprises',
        ],
    ];
    $service_schema = array_filter($service_schema);

    $faq_schema = [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => [
            [
                '@type' => 'Question',
                'name' => "La péniche est-elle adaptée pour un séminaire d'entreprise ?",
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => "Oui. La péniche dispose d'une salle principale aménagée, adaptée aux séminaires, réunions et workshops pour des groupes de 50 à 100 personnes. Les équipements techniques (sonorisation, vidéoprojecteur, écran, wifi) sont à préciser avec vous lors du devis en fonction de vos besoins.",
                ],
            ],
            [
                '@type' => 'Question',
                'name' => "Quelles sont les disponibilités pour un séminaire en journée ?",
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => "Les séminaires en journée sont organisés les lundi, mardi, mercredi, et les samedis de 9h à 16h. Pour les autres créneaux, contactez-nous pour étudier votre demande.",
                ],
            ],
            [
                '@type' => 'Question',
                'name' => "Quels équipements sont disponibles ?",
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => "Mobilier modulable (tables, chaises), terrasse extérieure, bar opéré par notre équipe. Options : son & lumière (150 €), DJ (250 €), artiste live (450 €), service à table (120 €), tonnelle (40 €). Équipement technique (sonorisation, vidéoprojecteur, écran, wifi) à préciser au devis selon vos besoins.",
                ],
            ],
            [
                '@type' => 'Question',
                'name' => "Propose-t-on des formules repas pour les séminaires ?",
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => "Oui : petit-déjeuner d'accueil, pauses café, déjeuner 2 plats (25 €/pers) ou 3 plats (30 €/pers), cocktail dinatoire, afterwork. Le bar est opéré par notre équipe toute la durée de l'événement.",
                ],
            ],
            [
                '@type' => 'Question',
                'name' => "Pourquoi choisir une péniche pour un séminaire à Lille ?",
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => "Un cadre atypique qui change des hôtels classiques : cadre bucolique sur la Deûle, bien-être au bord de l'eau, originalité pour marquer vos équipes, proximité immédiate du centre-ville de Lille et accès transports.",
                ],
            ],
            [
                '@type' => 'Question',
                'name' => "Comment accéder à la péniche pour un séminaire ?",
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => "La péniche est amarrée quai de l'Esplanade à Lille (59800). Accès : métro Cormontaigne (ligne 2), tram Bois Blancs, parking Esplanade à proximité, 10 min à pied de la gare Lille Flandres.",
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
        <h2>Un lieu de séminaire original à Lille, sur une péniche</h2>
        <p>Marquez vos équipes avec un séminaire d'entreprise, une réunion stratégique, un team building ou un afterwork hors des sentiers battus. Le Bus Magique, péniche amarrée quai de l'Esplanade à Lille, propose un lieu atypique au bord de la Deûle, pour des événements professionnels de 50 à 100 personnes (groupes plus petits sur demande).</p>
      </div>

    </section>

    <section class="event-recurrent">

      <div class="event-recurrent__grid">

        <article class="event-card event-card--red">
          <h2>Séminaire d'entreprise à Lille</h2>
          <p>Organisez votre séminaire annuel, vos journées stratégiques, vos onboarding ou vos workshops sur une péniche au cœur de Lille. La salle principale aménagée accueille groupes assis ou cocktail ; les équipements techniques (sonorisation, vidéoprojecteur, écran, wifi) sont à préciser au devis selon vos besoins.</p>
          <p>Format modulable : plénière, sous-groupes, conférence, ateliers. La terrasse extérieure offre un espace de pause unique au bord de l'eau.</p>
        </article>

        <article class="event-card event-card--green">
          <h2>Team building & afterwork</h2>
          <p>Renforcez la cohésion de vos équipes avec un team building original sur la Deûle. Apéro dinatoire, cocktail, soirée DJ, jam session privée : la péniche s'adapte à votre programme. L'originalité du lieu facilite les échanges informels et rompt avec la routine du bureau.</p>
          <p>Formule afterwork : location sur créneau soirée, bar opéré par notre équipe, planches apéro, cocktails signature.</p>
        </article>

        <article class="event-card event-card--yellow">
          <h2>Réunions & journées d'études</h2>
          <p>Pour une journée de formation, une réunion client importante, un comité de direction ou une journée d'étude, bénéficiez d'un cadre calme et inspirant. Privatisation en journée possible les lundi, mardi, mercredi, et samedis matin.</p>
          <p>Formules disponibles : petit-déjeuner d'accueil, pauses café, déjeuner sur place, afterwork en fin de journée pour clôturer.</p>
        </article>

        <article class="event-card event-card--blue">
          <h2>Équipement & prestations</h2>
          <ul>
            <li>Salle principale chauffée + terrasse extérieure</li>
            <li>Capacité : groupes de 50 à 100 personnes (plus petits groupes sur demande)</li>
            <li>Mobilier modulable (tables, chaises, mange-debout)</li>
            <li>Bar opéré par notre équipe + formules boissons (3,50 €/boisson)</li>
            <li>Repas 2 plats (25 €/pers) ou 3 plats (30 €/pers)</li>
            <li>Options : son & lumière (150 €), DJ (250 €), artiste live (450 €), service à table (120 €), tonnelle (40 €)</li>
            <li>Équipement technique (sonorisation, vidéoprojecteur, écran, wifi…) : à préciser lors du devis selon vos besoins</li>
          </ul>
        </article>

      </div>

      <div class="event-recurrent__faq">
        <h2>Questions fréquentes — séminaires & réunions</h2>

        <details class="event-faq-item">
          <summary><h3>La péniche est-elle adaptée pour un séminaire d'entreprise ?</h3></summary>
          <p>Oui. La péniche dispose d'une salle principale aménagée, adaptée aux séminaires, réunions et workshops pour des groupes de 50 à 100 personnes. Les équipements techniques (sonorisation, vidéoprojecteur, écran, wifi) sont à préciser au devis en fonction de vos besoins.</p>
          <p>À noter : au-delà de 60 convives, le service à table n'est pas disponible ; au-delà de 80, le devis n'est pas automatique et la jauge est validée selon le type d'événement.</p>
        </details>

        <details class="event-faq-item">
          <summary><h3>Quelles sont les disponibilités pour un séminaire en journée ?</h3></summary>
          <p>Les séminaires en journée sont organisés les lundi, mardi, mercredi, et les samedis de 9h à 16h. Pour les autres créneaux, <a href="/contact/">contactez-nous</a> pour étudier votre demande au cas par cas.</p>
        </details>

        <details class="event-faq-item">
          <summary><h3>Quels équipements sont disponibles ?</h3></summary>
          <p>Mobilier modulable (tables, chaises, mange-debout), terrasse extérieure, bar opéré par notre équipe. Options tarifées : son & lumière (150 €), DJ (250 €), artiste live (450 €), service à table (120 €), tonnelle terrasse (40 €). L'équipement technique (sonorisation, vidéoprojecteur, écran, wifi) est à préciser avec vous lors du devis selon les besoins de votre événement.</p>
        </details>

        <details class="event-faq-item">
          <summary><h3>Propose-t-on des formules repas pour les séminaires ?</h3></summary>
          <p>Oui : petit-déjeuner d'accueil, pauses café, déjeuner 2 plats (25 €/pers) ou 3 plats (30 €/pers), cocktail dinatoire, afterwork. Le bar est opéré par notre équipe toute la durée de l'événement.</p>
        </details>

        <details class="event-faq-item">
          <summary><h3>Pourquoi choisir une péniche pour un séminaire à Lille ?</h3></summary>
          <p>Un cadre atypique qui change des hôtels et salles classiques : cadre bucolique sur la Deûle, bien-être au bord de l'eau, originalité qui marque les équipes et les clients, proximité immédiate du centre-ville de Lille et accès transports.</p>
        </details>

        <details class="event-faq-item">
          <summary><h3>Comment accéder à la péniche pour un séminaire ?</h3></summary>
          <p>La péniche est amarrée quai de l'Esplanade à Lille (59800). Métro Cormontaigne (ligne 2), tram Bois Blancs, parking Esplanade à proximité, 10 min à pied de la gare Lille Flandres. Pratique pour les équipes venant en train.</p>
        </details>
      </div>

      <div class="event-recurrent__cta">
        <h2>Organiser votre séminaire</h2>
        <p>Sélectionnez une date disponible dans le calendrier ci-dessous et obtenez votre devis personnalisé en ligne en quelques minutes.</p>
      </div>

    </section>

    <section class="section-privatisation">
      <?php include(locate_template('template-part/privatisation/calendar.php')); ?>
      <?php include(locate_template('template-part/privatisation/form-wizard.php')); ?>
    </section>

  <?php endwhile; ?>
<?php endif; ?>


<?php get_footer();
