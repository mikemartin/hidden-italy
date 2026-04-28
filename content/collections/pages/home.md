---
id: home
blueprint: page
title: Home
template: home
updated_by: 6e2cc1d1-1470-4391-963d-37aed038e27a
updated_at: 1777127590
seo_noindex: inherit
seo_nofollow: false
seo_canonical_type: entry
sitemap_change_frequency: weekly
sitemap_priority: 1
page_builder:
  -
    id: home_hero
    type: hero
    enabled: true
    heading: |-
      Explore Italy,
      one step at a time
    buttons:
      -
        id: home_hero_btn
        label: 'Discover tours'
        link_type: entry
        entry: 2d1f3903-3248-4037-8e09-e76e8d62781c
        target_blank: false
        button_type: button
        type: button
        enabled: true
    tour_photos:
      - d6a3ceb1-2eac-4860-b7c0-b70500e53345
      - 0d717011-26fe-4d13-89f4-a0739f966e3d
      - fe7d8f05-4262-4ff2-9fa6-af18f7825ec2
  -
    id: home_intro
    type: intro
    enabled: true
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
        id: home_intro_btn
        label: 'Read our story'
        link_type: url
        url: /about
        target_blank: false
        button_type: button
        type: button
        enabled: true
  -
    id: home_tour_types
    type: tour_types
    enabled: true
    heading: 'Choose your adventure'
    tour_types:
      - guided
      - self-guided
    buttons:
      -
        id: home_types_btn
        label: 'Browse all tours'
        link_type: entry
        entry: 2d1f3903-3248-4037-8e09-e76e8d62781c
        target_blank: false
        button_type: button
        type: button
        enabled: true
  -
    id: home_featured_tours
    type: featured_tours
    enabled: true
    heading: 'Featured walking tours'
    link_text: 'View all'
    link_entry: 2d1f3903-3248-4037-8e09-e76e8d62781c
    tours:
      - 0d717011-26fe-4d13-89f4-a0739f966e3d
      - fe7d8f05-4262-4ff2-9fa6-af18f7825ec2
      - 65354be1-c899-4486-82f9-2883d090c270
      - 3d10a0a0-5538-48e1-a7ad-e8e84b633ed9
      - 543d98ec-f3a9-42a3-9f7b-0ad11cfca5a9
      - 5cbc2124-bf9e-49cf-af90-b2ef7d32c0d9
  -
    id: home_testimonials
    type: testimonials
    enabled: true
    heading: 'What our travellers are saying'
    testimonials:
      - c4dad366-cfdd-443a-8db6-c29df33df79a
      - abed777c-bcf3-4f03-a324-027c876335ee
      - a3519272-9f93-46df-90cf-8c28d7a03683
  -
    id: home_travel_tips
    type: travel_tips
    enabled: true
    heading: 'Travel tips and inspiration'
    link_text: 'View all'
    articles:
      - 20f6a148-63b8-47a8-9b0e-d57095d78c21
      - 20f6a148-63b8-47a8-9b0e-d57095d78c21
      - 20f6a148-63b8-47a8-9b0e-d57095d78c21
  -
    id: home_cta_banner
    type: cta_banner
    enabled: true
    heading: 'Ready to take the first step?'
    image: tours/trentino-alto-adige-dolomites/p9171981.jpg
    overlay: true
    buttons:
      -
        id: home_cta_btn
        label: 'Discover tours'
        link_type: entry
        entry: 2d1f3903-3248-4037-8e09-e76e8d62781c
        target_blank: false
        button_type: button
        type: button
        enabled: true
---
