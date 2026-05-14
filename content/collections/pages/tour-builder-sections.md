---
id: tbs-00000000-0000-4000-9000-tourbuildersect
blueprint: page
title: 'Tour builder sections'
headline: 'Tour builder sections'
description: 'Every tour-builder set rendered with the real content from Italian Alps — Trails to Freedom. Renders via `default.antlers.html`, so the layout matches `/default-page`. Use this page to stage screenshots for Statamic''s set picker.'
header_variant: large
seo_noindex: true
seo_nofollow: true
seo_canonical_type: entry
sitemap_change_frequency: weekly
sitemap_priority: 0.1
# Picks the Guided global for FAQ common questions.
leader: 469290c8-2f8b-4d10-b639-574b4f4f1b8a
tour_collections: guided
start_location:
  label: 'Biella, Piedmont, Italy'
  lat: '45.5664109'
  lon: '8.0542758'
  postalCode: '13900'
  locality: Biella
  adminLevels:
    1:
      name: Piedmont
      code: null
      level: 1
    2:
      name: Biella
      code: null
      level: 2
  country: Italy
finish_location:
  label: 'Macugnaga, Piedmont, Italy'
  lat: '45.9683785'
  lon: '7.9672651'
  postalCode: '28876'
  locality: Macugnaga
  adminLevels:
    1:
      name: Piedmont
      code: null
      level: 1
    2:
      name: Verbano-Cusio-Ossola
      code: null
      level: 2
  country: Italy
tour_builder:
  -
    id: tbs_highlights
    type: highlights
    enabled: true
    title: 'Trails to Freedom highlights'
    section_background: light
    activities:
      -
        id: tbs_h1
        title: 'Explore Biella'
        summary: 'Settle into a 4-star hotel and share a welcome dinner with your group in this historic alpine town.'
        icon: ski-alpine-hotel.svg
      -
        id: tbs_h2
        title: 'Walk historic trails'
        summary: 'Follow ancient trails through the European Alps in the footsteps of 4 Aussie POWs who escaped Fascist Italy in 1943.'
        icon: trekking-trekking.svg
      -
        id: tbs_h3
        title: 'Sleep in ancient monasteries'
        summary: 'Stay in marvellous baroque monasteries nestled in the heart of the mountains, enjoying their history and hearty dinners.'
        icon: housing-house.svg
      -
        id: tbs_h4
        title: 'Explore the spectacular scenery'
        summary: 'This hike takes you through some of the most spectacular scenery in Europe: mountains and forests; lakes and glaciers.'
        icon: switzerland-mountain.svg
      -
        id: tbs_h5
        title: 'Enjoy mountain hospitality'
        summary: 'Enjoy the generous hospitality of the mountain people in this little-visited part of Italy: warm welcomes, fine food.'
        icon: ski-alpine-Chalet.svg
      -
        id: tbs_h6
        title: 'Celebrate with a farewell dinner'
        summary: 'Mark the end of your alpine adventure with a festive group meal in a charming mountain village near the Swiss border.'
        icon: culture-champagne.svg
  -
    id: tbs_itinerary
    type: itinerary
    enabled: true
    title: 'Trails to Freedom itinerary'
    section_background: light
    summary:
      -
        type: paragraph
        content:
          -
            type: text
            text: "This magnificent tour takes you hiking in Piedmont from the Po River plains through the Italian Alps (skirting around Mt Rosa, the second highest peak in Europe) to the Swiss border following an escape route used by Allied POW's in the Second World War."
    days:
      -
        id: tbs_day1
        title: 'Arrival in Biella'
        description: "Your first night is in Biella, a pleasant town north of Turin with a rail link. Located where the Po River plain meets the foothills of the Alps, Biella has long been a trade and commercial centre. The Oropa Valley has connected Italy to Switzerland and France since Roman times. Its centuries-old textile tradition continues today, producing high-quality wool and housing fashion brands like Ermenegildo Zegna. We'll have dinner at an innovative local restaurant."
        meals_included:
          - dinner
        photo: tours/italian-alps-trails-to-freedom/img_0975.jpg
      -
        id: tbs_day2
        title: 'Hike to a baroque monastery'
        description: "The path we take today leaves Biella, following a route first mentioned in 1207 that once served as a trade route to Lyon in central France. It climbs through forests and hamlets, offering spectacular views over the plains. We'll stop for a delicious picnic lunch. The hike ends at one of the most unexpected and striking sites on the walk: a vast baroque monastery that has been a popular pilgrimage destination for centuries and where we'll stay the night."
        duration: 5
        distance: 17
        elevation: 768
        meals_included:
          - breakfast
          - lunch
          - dinner
        photo: tours/italian-alps-trails-to-freedom/img_0986.jpg
      -
        id: tbs_day3
        title: 'Sanctuary tour and ridge walk'
        description: "Today is a short walk. In the morning we'll be taken on a guided tour of this fascinating sanctuary. After another delicious picnic lunch, we take a small trail that climbs up through pine forest to the ridge that divides the two valleys. We then descend to the second monastery. We'll have time to relax, before having dinner on the veranda of the monastery."
        duration: 4
        distance: 11
        elevation: 393
        meals_included:
          - breakfast
          - lunch
          - dinner
        photo: tours/italian-alps-trails-to-freedom/57.png
      -
        id: tbs_day4
        title: 'The first big climb'
        description: 'We follow the trail into a quirky town once home to the benefactor who built the road and tunnel connecting two monasteries in the late 1800s. The path then leads north by the river to another village in the head of the valley where we have lunch. Afterward, we begin a challenging three-hour climb to a mountain hut beneath the peaks. The effort is rewarded with stunning views across the Alps and a convivial spot for dinner and rest.'
        duration: 6
        distance: 12
        elevation: 1406
        meals_included:
          - breakfast
          - lunch
          - dinner
        photo: tours/italian-alps-trails-to-freedom/10.jpg
      -
        id: tbs_day5
        title: 'A long walk to Alagna'
        description: "Today's walk winds through the Alps with the first views of Monte Rosa, passing alpine lakes, glaciers, and mountain pastures still used for grazing. Only trails connect this area; there are no roads. After a picnic lunch, we descend a glacial valley that leads into forest and pasture, passing distinctive wooden 'Walser' farmhouses. Tonight's stay is at a very comfortable hotel in Alagna."
        duration: 8
        distance: 17
        elevation: 540
        meals_included:
          - breakfast
          - lunch
        photo: tours/italian-alps-trails-to-freedom/dscf0143.jpg
  -
    id: tbs_overview
    type: overview
    enabled: true
    section_background: dark
    show_topography: true
    title: "Follow the escape route used\Lby Allied POW's"
    column_left:
      -
        type: paragraph
        content:
          -
            type: text
            text: 'On the 3rd of October 1943, four young Australian soldiers crossed from Italy to Switzerland at the border on Monte Moro Pass, thus ending a three-week odyssey that had started in a Fascist prison camp on the Po Plain near Biella in Piedmont (north of Turin). The route they took followed a network of medieval paths that had been re-opened by the partisans and came to be known as the ‘sentieri della liberta’ or the trails to freedom.'
    column_right:
      -
        type: paragraph
        content:
          -
            type: text
            text: 'Based on several years'' research, this magnificent hike follows the same route, starting in Biella and finishing on Mt Moro on the Swiss border. It passes through some of the most spectacular scenery in the European Alps, staying in a mixture of hotels, pilgrim monasteries and mountain huts along the way. Although we will walk through uncontaminated mountain landscapes, this is an ancient land: traders, pilgrims and graziers have been passing through here for millennia, in fact, since before the Bronze Age.'
    images:
      - tours/italian-alps-trails-to-freedom/dscf0276.jpg
      - tours/italian-alps-trails-to-freedom/dscf1836.jpg
      - tours/italian-alps-trails-to-freedom/10.jpg
  -
    id: tbs_experience
    type: experience
    enabled: true
    section_background: dark
    show_topography: true
    title: 'Trails to Freedom Experience'
    tabs:
      -
        id: tbs_exp_walk
        type: walk
        enabled: true
        title: 'Exceptional walking'
        total_distance: 96
        total_elevation: 5000
        grade: 5
        description:
          -
            type: paragraph
            content:
              -
                type: text
                text: "The trails are well-marked, starting with a pilgrim route that takes us up into the hills above Biella in northern Piedmont before picking up sections of two long distance trails: the Grande Traversata degli Alpi (GTA) and the final section of the Tour de Mt Rosa (TMR) that take us to the Swiss border. A large part of the route follows ancient pack routes, variously narrow, wide, cobbled and paved. You will cross some long stretches of skree and boulders, so you'll need to be sure-footed and fit."
          -
            type: paragraph
            content:
              -
                type: text
                text: "Technically the route is simple, however, it is a challenge. You will be required to climb more than a thousand metres on several days, there are some exposed sections and you will be crossing high altitude country where weather conditions can change dramatically so you will require a high level of fitness. You must also be prepared to walk in all conditions rain, hail or shine! This is a tough but very satisfying hike."
        image: tours/5.5.jpg
      -
        id: tbs_exp_acc
        type: accommodation
        enabled: true
        title: 'Boutique accommodation'
        description:
          -
            type: paragraph
            content:
              -
                type: text
                text: "The accommodation is one of the highlights of the tour. We will start with the first night in a 4-star hotel in Biella in Piedmont. For the next two nights we will stay in very comfortable pilgrim accommodation in magnificent monasteries that date from the Middle Ages. The next night is in a ‘rifugio’, or mountain hut, where we will sleep overnight in shared rooms (with shared bathrooms). The following two nights are in a lovely 3-star hotel in a town at the head of the Sesia Valley. The next night is in a gorgeous rifugio at the foot of Mont Rosa, with shared rooms (with shared bathrooms). The last two nights we spend in a family-run 3-star hotel in a village below the Mt Moro Pass and the Swiss border."
        media:
          - tours/b96f4a2a-dec1-40ff-b7cb-9c99a23a7e4f.jpg
      -
        id: tbs_exp_food
        type: food
        enabled: true
        title: 'Authentic food'
        description:
          -
            type: paragraph
            content:
              -
                type: text
                text: 'The walk passes through the region of Piemonte (Piedmont), home of the Slow Food movement and one of Italy''s premier food and wine regions, so, although sometimes simple, the food on the tour will always be local, hearty and delicious.'
          -
            type: paragraph
            content:
              -
                type: text
                text: 'Dinner each night will usually consist of three courses: a first course which may consist of risotto, ravioli or polenta; mains such as veal or rabbit stew; and a dessert such as bonet (a thick chocolate pudding) or a slice of excellent mature local cheese, chased down with coffee and/or a shot of grappa. Wine with the meals is included.'
          -
            type: paragraph
            content:
              -
                type: text
                text: 'Lunches on the track will be a mixture of picnic lunches and cut lunches prepared by our accommodation.'
        media:
          - tours/44.jpg
  -
    id: tbs_leader
    type: leader
    enabled: true
    title: 'Tour Leader'
    section_background: light
    person: 469290c8-2f8b-4d10-b639-574b4f4f1b8a
  -
    id: tbs_essentials
    type: essentials
    enabled: true
    title: 'What you should know'
    section_background: light
    sections:
      -
        id: tbs_ess_equipment
        type: equipment
        enabled: true
        body:
          -
            type: paragraph
            content:
              -
                type: text
                text: "Because several of the legs do not have car access, you will need to carry your own gear for the week. We will provide you with a comprehensive (and prescriptive) list of what is required (nothing more and nothing less!). The first requirement is a good quality 35 to 40 litre backpack. The total loaded weight should be no more than 12 kilos."
          -
            type: paragraph
            content:
              -
                type: text
                marks:
                  -
                    type: italic
                text: 'Please note that the rest of your luggage will be stored in Biella for the duration of the walk and then forwarded to Macugnaga, waiting for you at the end of the walk.'
      -
        id: tbs_ess_cancel
        type: booking_cancellation
        enabled: true
        body:
          -
            type: paragraph
            content:
              -
                type: text
                text: "To confirm the booking, a deposit of 25% of the total booking cost is required within 7 days of the issue of the invoice. Please note that in the event of customer cancellation, deposits are refunded, less a cancellation fee of $500 per person per tour."
      -
        id: tbs_ess_insurance
        type: travel_insurance
        enabled: true
        body:
          -
            type: paragraph
            content:
              -
                type: text
                text: "It is a condition of travel that you are covered by comprehensive travel insurance. When arranging your insurance, you must provide no later than 60 days prior to commencement of travel: a copy of your travel insurance policy (or details of master policy), the emergency telephone number of your insurance company; and next of kin emergency contact."
  -
    id: tbs_testimonials
    type: testimonials
    section_background: light
    enabled: true
    heading: 'What our travellers are saying'
    testimonials:
      - c4dad366-cfdd-443a-8db6-c29df33df79a
      - 5e20d0e8-847b-4371-95a7-a7c859735fee
      - 966a5684-0228-4341-8790-8ef4f90f52dc
  -
    id: tbs_faqs
    type: faqs
    section_background: light
    enabled: true
    heading: 'Frequently Asked Questions'
    show_common_questions: true
---
