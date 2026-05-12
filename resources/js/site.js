import Splide from '@splidejs/splide';
import collapse from '@alpinejs/collapse';
import focus from '@alpinejs/focus';
import intersect from '@alpinejs/intersect';
import tippy from 'tippy.js';
import simpleLikes from './components/simple-likes.js';
import tourMap from './components/tour-map.js';
import tourSubnav from './components/tour-subnav.js';
import precognition from 'laravel-precognition-alpine';
import officeMap from './components/office-map.js';

window.Splide = Splide;
window.simpleLikes = simpleLikes;

document.addEventListener('alpine:init', () => {
    window.Alpine.plugin(collapse);
    window.Alpine.plugin(focus);
    window.Alpine.plugin(intersect);

    /* Sticky tour sub-nav state.
       `active`   — id of the currently-on section, read by the nav buttons
                    for their active underline.
       `stuck`    — true once the nav has pinned to the top of the viewport;
                    flipped by a sentinel above the nav, read by the nav
                    for its drop-shadow.
       `select()` — called from a nav-button click. Sets `active`
                    immediately and locks `spy()` for ~800ms so intersect
                    events firing during the smooth scroll can't overwrite
                    the clicked target. The clicked section's own sentinel
                    won't necessarily land inside the spy band after the
                    scroll settles (the band is just below the nav, but
                    the sentinel is at content-top below the section's
                    own padding) — without this lock, active would snap
                    to whichever sentinel last passed through the band
                    during transit (typically the section just before).
       `spy()`    — called from per-section `x-intersect` directives.
                    No-op while the click lock is in effect. */
    window.Alpine.store('tourSubnav', {
        active: 'overview',
        stuck: false,
        _lockUntil: 0,
        select(id) {
            this.active = id;
            this._lockUntil = Date.now() + 800;
        },
        spy(id) {
            if (Date.now() < this._lockUntil) return;
            this.active = id;
        },
    });
    window.Alpine.plugin(precognition);

    window.Alpine.store('wishlist', {
        count: 0,
        popping: false,

        async refresh(animate = false) {
            const res = await fetch('/!/simple-likes/wishlist');
            const data = await res.json();
            this.count = data.count;

            if (animate) {
                this.popping = true;
                setTimeout(() => {
                    this.popping = false;
                }, 200);
            }
        }
    });

    window.Alpine.store('nav', {
        mobileOpen: false,
        toggle() { this.mobileOpen = !this.mobileOpen; },
        close() { this.mobileOpen = false; },
    });

    window.Alpine.data('simpleLikes', simpleLikes);
    window.Alpine.data('tourMap', tourMap);
    window.Alpine.data('tourSubnav', tourSubnav);
    window.Alpine.data('officeMap', officeMap);

    /* Form handler for Peak Tools' Alpine + Precognition form pattern.

       Peak ships its own `Alpine.data('formHandler', …)` registration in
       `vendor/studio1902/statamic-peak-tools/resources/views/snippets/
       _form_handler.antlers.html`, but it attaches inside an
       `alpine:initializing` listener emitted inline on each form page.
       That event only fires once per browser session — on the initial
       page load — so visitors who reach a form page via
       `wire:navigate` (any nav click, the tour CTA into `/booking`)
       attach the listener too late and `formHandler` never registers.
       Alpine then evaluates `x-data="formHandler()"` against
       `undefined`, the subtree fails to hydrate, and only the static
       submit button remains.

       Registering here, in the persistent `alpine:init` listener loaded
       by `site.js`, means the factory survives every navigation —
       Alpine's data registry persists, so the new x-data finds it on
       arrival. We intentionally don't override Peak's vendor partial so
       upstream upgrades flow through untouched; on cold loads Peak's
       inline `alpine:initializing` registration fires after ours and
       overwrites the factory, but its body is functionally equivalent
       for this project (empty `success_hook`, Turnstile captcha covered
       by the multi-provider selector below). */
    window.Alpine.data('formHandler', () => ({
        success: false,
        submitted: false,
        form: null,
        init() {
            this.form = this.$form(
                'post',
                this.$refs.form.getAttribute('action'),
                JSON.parse(this.$refs.form.getAttribute('x-data')).form,
                {
                    headers: {
                        'X-CSRF-Token': {
                            toString: () => this.$refs.form.querySelector('[name="_token"]').value,
                        },
                    },
                },
            );
        },
        successHook() {
            setTimeout(() => {
                this.success = false;
            }, 10000);
        },
        submit() {
            if (Object.keys(this.form).includes('captcha-response')) {
                const captchaInput = this.$refs.form.querySelector(
                    '[name="cf-turnstile-response"], [name="g-recaptcha-response"], [name="h-captcha-response"], [name="altcha"]'
                );
                if (captchaInput) {
                    this.form['captcha-response'] = captchaInput.value;
                }
            }

            this.submitted = true;
            this.form.submit()
                .then(response => {
                    this.form.reset();
                    this.form.setErrors([]);
                    this.$refs.form.reset();
                    this.success = true;
                    this.submitted = false;
                    this.successHook(response);
                })
                .then(this.$refs.form.scrollIntoView())
                .catch(error => {
                    const summary = document.querySelector('#summary');
                    if (summary) {
                        this.$focus.focus(summary.querySelector('a'));
                    } else {
                        console.log(error);
                    }
                });
        },
    }));

    window.Alpine.directive('tooltip', (el, { expression }) => {
        const content = expression || el.getAttribute('data-tooltip') || '';
        if (!content) return;
        const theme = el.getAttribute('data-tooltip-theme') || undefined;
        tippy(el, { content, allowHTML: false, theme });
    });

    window.Alpine.magic('tooltip', el => message => {
        const instance = tippy(el, { content: message, trigger: 'manual' });
        instance.show();
        setTimeout(() => {
            instance.hide();
            setTimeout(() => instance.destroy(), 150);
        }, 2000);
    });
});

document.addEventListener('alpine:initialized', () => {
    window.Alpine.store('wishlist').refresh();
});

document.addEventListener('livewire:morph', () => {
    if (window.SimpleLikesBatch) {
        window.SimpleLikesBatch.cache = {};
    }
});
