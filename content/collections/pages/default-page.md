---
id: 4f8a2d91-1c5e-4b6a-9f3d-default00001
blueprint: page
title: 'Default Page'
headline: 'Default Page'
description: 'Every main page-builder set rendered in a single page so we can review the catalogue end-to-end. Tour-only sets live on the guided / self-guided tour blueprints — open an existing tour entry to see those.'
header_variant: large
# Picks up the Self-guided global so the FAQs block on this demo page
# loads the same common questions a self-guided tour-listing page does.
tour_collections: self-guided
seo_noindex: true
seo_nofollow: true
seo_canonical_type: entry
sitemap_change_frequency: weekly
sitemap_priority: 0.5
page_builder:
  -
    id: dp_image_and_text_card
    type: image_and_text
    enabled: true
    heading: 'It started with a walk'
    image_position: right
    image_aspect: tall
    section_background: light
    image: tours/tuscany-tuscany-2-chianti-florence-to-siena/rich-martello-yqcvybdd4y-unsplash-1774331143.jpg
    body:
      -
        type: paragraph
        content:
          -
            type: text
            text: 'The idea for Hidden Italy came during the six years I spent living and working in Milano in the 1980s when, to escape the bustle and smog of that wonderful city, I went walking in the surrounding mountains, along the rivers on the Po plain and along the coastal paths of nearby Liguria.'
      -
        type: paragraph
        content:
          -
            type: text
            text: 'Our first guided walking tour was based in Montalcino in southern Tuscany in 1994, and we introduced our first self-guided walks in 2000, also in Tuscany. Since then, we have taken people walking to many different parts of Italy, from the Dolomites and the Lakes in the north to Puglia and Sicily in the south, and lots of places in between, including Liguria, Tuscany, Lazio, Umbria, Abruzzo, Sardinia, Campania and the Amalfi Coast.'
      -
        type: paragraph
        content:
          -
            type: text
            text: 'We hope you will join us in our adventures!'
      -
        type: paragraph
        content:
          -
            type: text
            marks:
              -
                type: bold
            text: 'Simon Tancred'
          -
            type: hardBreak
          -
            type: text
            text: 'Founder & Director'
  -
    id: dp_testimonial_overlap
    type: testimonial_overlap
    enabled: true
    section_background: dark
    show_topography: true
    image: people/434666225_440666958320787_3022049181184737557_n.jpg
    quote: "For me there are still few greater pleasures than entering the walls of an ancient Italian town after a good day's walk, with the prospect of a fine meal and a warm bed in front of me. That's what Hidden Italy was always about."
    name: 'Simon Tancred'
    role: 'Director, Hidden Italy'
  -
    id: dp_form
    type: form
    enabled: true
    heading: 'Form'
    form: contact
    section_background: light
  -
    id: dp_contact
    type: contact
    enabled: true
    form: contact
    section_background: light
  -
    id: dp_cards
    type: cards
    enabled: true
    heading: Cards
    section_background: light
    cards:
      -
        id: dp_card_1
        type: card
        enabled: true
        image: tours/tuscany-tuscany-2-chianti-florence-to-siena/rich-martello-yqcvybdd4y-unsplash-1774331143.jpg
        heading: 'Guided tours'
        text: 'Small-group walking tours led by a local guide, with transfers, accommodation and dinners arranged for you.'
        button:
          -
            id: dp_card_1_btn
            label: 'Browse guided tours'
            link_type: entry
            entry:
              - 46b6b124-89cf-4efb-b06a-cdb4d28312ba
            target_blank: false
            button_type: button
            type: button
            enabled: true
            show_controls: true
      -
        id: dp_card_2
        type: card
        enabled: true
        image: tours/sicily-sicily-2-western-sicily-and-the-egadi-islands/g.jpg
        heading: 'Self-guided tours'
        text: 'Walk at your own pace with detailed notes, pre-booked accommodation and luggage transfers.'
        button:
          -
            id: dp_card_2_btn
            label: 'Browse self-guided tours'
            link_type: entry
            entry:
              - fff5d1d0-e05d-4088-9a54-1f2e7f630c28
            target_blank: false
            button_type: button
            type: button
            enabled: true
            show_controls: true
      -
        id: dp_card_3
        type: card
        enabled: true
        image: people/434666225_440666958320787_3022049181184737557_n.jpg
        heading: 'About Hidden Italy'
        text: 'Three decades of organising small-group walks in Italy. Meet the team and read our story.'
        button:
          -
            id: dp_card_3_btn
            label: 'About us'
            link_type: entry
            entry:
              - 60f29dc3-3636-4ae8-95a4-e508c713936a
            target_blank: false
            button_type: button
            type: button
            enabled: true
            show_controls: true
  -
    id: dp_tour_banner
    type: tour_banner
    enabled: true
    region: Tuscany
    heading: 'Tour banner'
    location: 'Montalcino, Tuscany'
    image: tours/tuscany-tuscany-2-chianti-florence-to-siena/rich-martello-yqcvybdd4y-unsplash-1774331143.jpg
    buttons:
      -
        id: dp_tour_banner_btn
        label: 'Discover the tour'
        link_type: entry
        entry:
          - 2d1f3903-3248-4037-8e09-e76e8d62781c
        target_blank: false
        button_type: button
        type: button
        enabled: true
        show_controls: true
  -
    id: dp_intro
    type: benefits
    enabled: true
    columns: '3'
    section_background: light
    heading: 'Walking Italy since 1993'
    intro:
      -
        type: paragraph
        content:
          -
            type: text
            text: "For over 30 years, we've helped inquisitive travellers explore Italy on walking tours. You'll experience the country at your own pace, with meaningful encounters, local flavour, and the comfort of knowing everything is taken care of."
    benefits:
      - d98e45f6-28fa-4018-ab67-51d82701a881
      - 987b0947-3003-41c1-86f6-c3456e1429da
      - 1473fd92-2f3e-4025-8d25-66111aef1fa0
    buttons:
      -
        id: dp_intro_btn
        label: 'Read our story'
        link_type: entry
        entry:
          - 60f29dc3-3636-4ae8-95a4-e508c713936a
        target_blank: false
        button_type: button
        type: button
        enabled: true
        show_controls: true
  -
    id: dp_stats
    type: stats
    enabled: true
    heading: Since then, we have covered some ground.
    top_border: true
    section_background: light
    stats:
      -
        id: dp_stat_years
        type: stat
        enabled: true
        value: 30+
        label: 'Years walking Italy'
        icon: dates
      -
        id: dp_stat_tours
        type: stat
        enabled: true
        value: '32'
        label: 'Walking tours'
        icon: route
      -
        id: dp_stat_guests
        type: stat
        enabled: true
        value: 10K+
        label: 'Travellers guided'
        icon: backpack
      -
        id: dp_stat_km
        type: stat
        enabled: true
        value: 500K+
        label: 'km walked together'
        icon: walking
  -
    id: dp_people_grid
    type: people_grid
    enabled: true
    heading: 'Our people'
    section_background: light
    intro: 'Meet the people who make Hidden Italy Walking Tours happen.'
    people:
      - 8e21b5fb-a972-4a2e-a81c-2e6f8425841f
      - dba23280-7df4-45c8-abd2-aabb9dee3b06
      - fa3be588-b0dd-4d37-9213-bafbd1992434
      - 656b56c6-ff12-45de-86a3-c6c480d7c141
  -
    id: dp_photo_cards_none
    type: photo_cards
    enabled: true
    heading: 'Photo cards — no background'
    section_background: none
    photo_cards:
      - a5eb2e54-ad28-467a-9e7a-b32fdce0c6d7
      - 5300daa4-c274-4a6e-8715-fbd320d02144
  -
    id: dp_tour_types
    type: photo_cards
    enabled: true
    heading: 'Choose your adventure'
    section_background: dark
    show_topography: true
    photo_cards:
      - 46b6b124-89cf-4efb-b06a-cdb4d28312ba
      - fff5d1d0-e05d-4088-9a54-1f2e7f630c28
    buttons:
      -
        id: dp_tour_types_btn
        label: 'Browse all tours'
        link_type: entry
        entry:
          - 2d1f3903-3248-4037-8e09-e76e8d62781c
        target_blank: false
        button_type: button
        type: button
        enabled: true
        show_controls: true
  -
    id: dp_photo_cards_mixed
    type: photo_cards
    enabled: true
    heading: 'Photo cards — mixed sources'
    section_background: light
    show_topography: true
    photo_cards:
      - a5eb2e54-ad28-467a-9e7a-b32fdce0c6d7
      - 8c969301-fa7c-418d-b306-3d50f60c142a
      - 5300daa4-c274-4a6e-8715-fbd320d02144
      - 885f3bc4-e91d-444f-a666-44cc0eda6277
    buttons:
      -
        id: dp_photo_cards_mixed_btn
        label: 'Browse all tours'
        link_type: entry
        entry:
          - 2d1f3903-3248-4037-8e09-e76e8d62781c
        target_blank: false
        button_type: button
        type: button
        enabled: true
        show_controls: true
  -
    id: dp_featured_tours
    type: featured_tours
    enabled: true
    heading: 'Featured tours'
    section_background: light
    link_text: 'View all'
    link_entry:
      - 2d1f3903-3248-4037-8e09-e76e8d62781c
    tours:
      - 0d717011-26fe-4d13-89f4-a0739f966e3d
      - fe7d8f05-4262-4ff2-9fa6-af18f7825ec2
      - 65354be1-c899-4486-82f9-2883d090c270
      - 3d10a0a0-5538-48e1-a7ad-e8e84b633ed9
      - 543d98ec-f3a9-42a3-9f7b-0ad11cfca5a9
      - 5cbc2124-bf9e-49cf-af90-b2ef7d32c0d9
  -
    id: dp_testimonials
    type: testimonials
    section_background: light
    enabled: true
    heading: Testimonials
    testimonials:
      - c4dad366-cfdd-443a-8db6-c29df33df79a
      - 966a5684-0228-4341-8790-8ef4f90f52dc
      - 5e20d0e8-847b-4371-95a7-a7c859735fee
  -
    id: dp_travel_tips
    type: travel_tips
    enabled: true
    heading: 'Travel tips'
    section_background: light
    link_text: 'View all'
    link_entry:
      - 6522c57c-2a9d-4ec7-a8eb-26035629e36a
    articles:
      - 7d2a4c91-3e85-4f17-91b3-8c1e0f5a7d62
      - 8a3b7d54-9e21-4f68-b193-5c4a0e6d2f87
      - 1e4d7a93-8c52-4f16-b384-7a2e0d6f9c41
      - 820b1ab2-9480-4b02-8575-1df125ab3966
      - f829bb65-b523-4183-bd65-1fc700837a0f
      - 6f8a2c47-3b91-4e58-a672-9d4e0f1c5b83
  -
    id: dp_cta_banner
    type: cta_banner
    enabled: true
    heading: 'CTA banner'
    image: tours/italian-alps-trails-to-freedom/dscf1836.jpg
    overlay: true
    buttons:
      -
        id: dp_cta_btn_1
        label: 'Browse tours'
        link_type: entry
        entry:
          - 2d1f3903-3248-4037-8e09-e76e8d62781c
        target_blank: false
        button_type: button
        type: button
        enabled: true
        show_controls: true
  -
    id: dp_faqs
    type: faqs
    enabled: true
    section_background: light
    heading: 'Frequently Asked Questions'
    show_common_questions: true
  -
    id: dp_icon_grid
    type: icon_grid
    enabled: true
    heading: 'Trails to Freedom highlights'
    section_background: light
    columns: 4
    icons:
      -
        id: mjflez0m
        title: 'Explore Biella'
        summary: 'Settle into a 4-star hotel and share a welcome dinner with your group in this historic alpine town.'
        icon: ski-alpine-hotel.svg
      -
        id: mjflfcs4
        title: 'Walk historic trails'
        summary: 'Follow ancient trails through the European Alps in the footsteps of 4 Aussie POWs who escaped Fascist Italy in 1943.'
        icon: trekking-trekking.svg
      -
        id: mjflflyr
        title: 'Sleep in ancient monasteries'
        summary: 'Stay in marvellous baroque monasteries nestled in the heart of the mountains, enjoying their history and hearty dinners.'
        icon: housing-house.svg
      -
        id: mjflft3i
        title: 'Explore the spectacular scenery'
        summary: 'This hikes takes you through some of the most spectacular scenery in Europe: mountains and forests; lakes and glaciers.'
        icon: switzerland-mountain.svg
    buttons:
      -
        id: dp_cta_btn_1
        label: 'Browse tours'
        link_type: entry
        entry:
          - 2d1f3903-3248-4037-8e09-e76e8d62781c
        target_blank: false
        button_type: button
        type: button
        enabled: true
        show_controls: true
  -
    id: dp_intro_light
    type: benefits
    enabled: true
    section_background: light
    show_topography: true
    heading: 'Benefits — light panel + topography'
    columns: '4'
    intro:
      -
        type: paragraph
        content:
          -
            type: text
            text: 'Same set, wrapped in a parchment panel with the topographic decoration on. Useful for breaking the rhythm of stacked plain sections.'
    benefits:
      - d98e45f6-28fa-4018-ab67-51d82701a881
      - 987b0947-3003-41c1-86f6-c3456e1429da
      - 1473fd92-2f3e-4025-8d25-66111aef1fa0
      - f8883fec-9a6f-42fc-937e-5034deb911f4
  -
    id: dp_stats_light
    type: stats
    enabled: true
    section_background: light
    heading: 'Stats — light panel'
    top_border: false
    stats:
      -
        id: dp_stat_l_years
        type: stat
        enabled: true
        value: 30+
        label: 'Years walking Italy'
        icon: dates
      -
        id: dp_stat_l_tours
        type: stat
        enabled: true
        value: '32'
        label: 'Walking tours'
        icon: route
  -
    id: dp_cards_light
    type: cards
    enabled: true
    section_background: light
    heading: 'Cards — light panel'
    text: 'Card surface flips to bg-background so they don''t disappear into the panel.'
    cards:
      -
        id: dp_cards_l_1
        type: card
        enabled: true
        heading: 'Guided tours'
        text: 'Small-group walks led by a local guide.'
        button:
          -
            id: dp_cards_l_1_btn
            label: 'Browse'
            link_type: entry
            entry:
              - 46b6b124-89cf-4efb-b06a-cdb4d28312ba
            target_blank: false
            button_type: button
            type: button
            enabled: true
            show_controls: true
      -
        id: dp_cards_l_2
        type: card
        enabled: true
        heading: 'Self-guided tours'
        text: 'Walk at your own pace with everything pre-arranged.'
        button:
          -
            id: dp_cards_l_2_btn
            label: 'Browse'
            link_type: entry
            entry:
              - fff5d1d0-e05d-4088-9a54-1f2e7f630c28
            target_blank: false
            button_type: button
            type: button
            enabled: true
            show_controls: true
  -
    id: dp_people_grid_light
    type: people_grid
    enabled: true
    section_background: light
    heading: 'People grid — light panel'
    people:
      - 8e21b5fb-a972-4a2e-a81c-2e6f8425841f
      - 656b56c6-ff12-45de-86a3-c6c480d7c141
      - 469290c8-2f8b-4d10-b639-574b4f4f1b8a
      - 699bd6c8-377d-46a8-bd97-234a3720b9f1
  -
    id: dp_featured_tours_light
    type: featured_tours
    enabled: true
    section_background: light
    heading: 'Featured tours — light panel'
    tours:
      - a5eb2e54-ad28-467a-9e7a-b32fdce0c6d7
      - 8c969301-fa7c-418d-b306-3d50f60c142a
      - 5300daa4-c274-4a6e-8715-fbd320d02144
  -
    id: dp_testimonials_light
    type: testimonials
    enabled: true
    section_background: light
    heading: 'Testimonials — light panel'
    testimonials:
      - c4dad366-cfdd-443a-8db6-c29df33df79a
      - 966a5684-0228-4341-8790-8ef4f90f52dc
      - 5e20d0e8-847b-4371-95a7-a7c859735fee
  -
    id: dp_travel_tips_light
    type: travel_tips
    enabled: true
    section_background: light
    heading: 'Travel tips — light panel'
    articles:
      - 7d2a4c91-3e85-4f17-91b3-8c1e0f5a7d62
      - 8a3b7d54-9e21-4f68-b193-5c4a0e6d2f87
      - 1e4d7a93-8c52-4f16-b384-7a2e0d6f9c41
  -
    id: dp_testimonial_overlap_none
    type: testimonial_overlap
    enabled: true
    section_background: none
    image: people/434666225_440666958320787_3022049181184737557_n.jpg
    quote: "No background — the section sits in the natural page-builder stack flow with no panel. Card surface stays parchment, body text stays foreground."
    name: 'Simon Tancred'
    role: 'Director, Hidden Italy'
  -
    id: dp_testimonial_overlap_light
    type: testimonial_overlap
    enabled: true
    section_background: light
    image: people/434666225_440666958320787_3022049181184737557_n.jpg
    quote: "Light panel — the band turns parchment-50 so the inset card needs no `.light` reset (it already inherits the page palette)."
    name: 'Simon Tancred'
    role: 'Director, Hidden Italy'
  -
    id: dp_testimonial_overlap_light_topo
    type: testimonial_overlap
    enabled: true
    section_background: light
    show_topography: true
    image: people/434666225_440666958320787_3022049181184737557_n.jpg
    quote: "Light panel + topography — same as above with the topographic line pattern tinted parchment-400."
    name: 'Simon Tancred'
    role: 'Director, Hidden Italy'
  -
    id: dp_testimonial_overlap_dark_notopo
    type: testimonial_overlap
    enabled: true
    section_background: dark
    image: people/434666225_440666958320787_3022049181184737557_n.jpg
    quote: "Dark panel without topography — the inset card carries `.light` so `bg-card` stays parchment and body copy stays dark against the deep-blue band."
    name: 'Simon Tancred'
    role: 'Director, Hidden Italy'
  -
    id: dp_faqs_light
    type: faqs
    enabled: true
    section_background: light
    show_topography: true
    heading: 'FAQs — light panel + topography'
    show_common_questions: true
  -
    id: dp_walking_benefits
    type: walking_benefits
    enabled: true
    section_background: light
    tour_type: guided
---
