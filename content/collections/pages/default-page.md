---
id: 4f8a2d91-1c5e-4b6a-9f3d-default00001
blueprint: page
title: 'Default Page'
headline: 'Default Page'
description: 'Every main page-builder set rendered in a single page so we can review the catalogue end-to-end. Tour-only sets live on the guided / self-guided tour blueprints — open an existing tour entry to see those.'
header_variant: large
seo_noindex: true
seo_nofollow: true
seo_canonical_type: entry
sitemap_change_frequency: weekly
sitemap_priority: 0.5
page_builder:
  -
    id: dp_content_with_image
    type: content_with_image
    enabled: true
    heading: 'Content with image set'
    image_position: right
    image: tours/tuscany-tuscany-2-chianti-florence-to-siena/rich-martello-yqcvybdd4y-unsplash-1774331143.jpg
    body:
      -
        type: paragraph
        content:
          -
            type: text
            text: 'The Content-with-image set is a two-column layout on a parchment card surface. Heading and body copy on one side, a single supporting photo on the other. On mobile the image stacks above the text. Pick the side from the editor — left or right.'
      -
        type: paragraph
        content:
          -
            type: text
            text: 'It is functionally near-identical to the Image-and-text set below. The audit on /page-builder-sections recommends merging them.'
  -
    id: dp_testimonial_overlap
    type: testimonial_overlap
    enabled: true
    image: people/434666225_440666958320787_3022049181184737557_n.jpg
    quote: "For me there are still few greater pleasures than entering the walls of an ancient Italian town after a good day's walk, with the prospect of a fine meal and a warm bed in front of me. That's what Hidden Italy was always about."
    name: 'Simon Tancred'
    role: 'Director, Hidden Italy'
  -
    id: dp_image_and_text
    type: image_and_text
    enabled: true
    heading: 'Image and text set'
    image_position: left
    image: tours/italian-alps-trails-to-freedom/dscf1836.jpg
    body:
      -
        type: paragraph
        content:
          -
            type: text
            text: 'The Image-and-text set is a heading and body copy beside a 4:3 image, with the image side configurable so rows can alternate. Rounded corners with a subtle ring around the image. Plain background — no parchment surface.'
  -
    id: dp_form
    type: form
    enabled: true
    heading: 'Form set'
    text: 'Render any Statamic form. Live Precognition validation, honeypot, error notifications and grid field layouts come for free.'
    form: contact
  -
    id: dp_contact
    type: contact
    enabled: true
    form: contact
  -
    id: dp_cards
    type: cards
    enabled: true
    heading: 'Cards set'
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
    heading: 'Tour banner set'
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
    type: intro
    enabled: true
    heading: 'Benefits set'
    columns: '3'
    intro:
      -
        type: paragraph
        content:
          -
            type: text
            text: 'The Benefits set takes a heading, intro paragraph and a grid of Benefit entries. Pick a 3- or 4-column layout and optionally drop in one or two CTA buttons.'
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
    heading: 'Stats set'
    top_border: true
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
    heading: 'People grid set'
    intro: 'A centred heading and a 4-column grid of profile cards (2 on tablet/mobile). Each card opens a flyout bio.'
    people:
      - 8e21b5fb-a972-4a2e-a81c-2e6f8425841f
      - 656b56c6-ff12-45de-86a3-c6c480d7c141
      - 469290c8-2f8b-4d10-b639-574b4f4f1b8a
      - 699bd6c8-377d-46a8-bd97-234a3720b9f1
      - 63fec771-0f91-4c4d-a4c1-9106f35c0670
      - 2e1d4ebf-08b8-4191-86bd-c52f27304023
      - 436787ff-89d4-438e-bf50-81c3672256cc
  -
    id: dp_tour_types
    type: tour_types
    enabled: true
    heading: 'Tour types set'
    tour_types:
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
    id: dp_featured_tours
    type: featured_tours
    enabled: true
    heading: 'Featured tours set'
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
    enabled: true
    heading: 'Testimonials set'
    testimonials:
      - c4dad366-cfdd-443a-8db6-c29df33df79a
      - 966a5684-0228-4341-8790-8ef4f90f52dc
      - 5e20d0e8-847b-4371-95a7-a7c859735fee
  -
    id: dp_travel_tips
    type: travel_tips
    enabled: true
    heading: 'Travel tips set'
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
    heading: 'CTA banner set'
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
    heading: 'FAQs set'
    show_common_questions: false
    questions:
      -
        question: 'How fit do I need to be?'
        answer:
          -
            type: paragraph
            content:
              -
                type: text
                text: 'Most of our tours suit walkers with moderate fitness — typically 4–6 hours of walking a day with rest stops. Each tour page lists distance, elevation and grade so you can match the route to your fitness.'
      -
        question: 'Do you cater for dietary requirements?'
        answer:
          -
            type: paragraph
            content:
              -
                type: text
                text: 'Yes — vegetarian, gluten-free and most common allergens are easy to accommodate when restaurants are notified in advance. Let us know at booking and we will brief our partners along the route.'
      -
        question: 'What happens in bad weather?'
        answer:
          -
            type: paragraph
            content:
              -
                type: text
                text: 'Walks run rain or shine within reason. If conditions are unsafe, our guides re-route or switch to an indoor itinerary for the day — included cellar visits, kitchen experiences and museum walks. Refunds for weather are not offered.'
---
