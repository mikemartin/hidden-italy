import { animate } from 'motion/mini';

/*
 * Alpine `x-reveal` directive — one-shot entrance animation for hero / banner
 * text. Animates direct/descendant `[data-reveal-item]` elements with a small
 * staggered fade-and-rise.
 *
 * `motion/mini` is a thin WAAPI wrapper — animations run on the compositor
 * thread off the main thread. We only animate `opacity` + `transform`, which
 * are GPU-composited (no layout, no paint), so the page never blocks the
 * render thread.
 *
 * Trigger uses a native IntersectionObserver so the animation only fires
 * when the host enters the viewport (fires immediately if already in view).
 * Hidden initial state is set in CSS (`[data-reveal-item]`) to avoid a flash
 * of fully-visible content before JS boots. `prefers-reduced-motion` skips
 * the animation entirely; no-JS users see content immediately via a
 * `<noscript>` override in the layout head.
 */
export default function registerReveal(Alpine) {
    const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    Alpine.directive('reveal', (root, _, { cleanup }) => {
        const items = Array.from(root.querySelectorAll('[data-reveal-item]'));
        if (items.length === 0) {
            return;
        }

        if (reduce) {
            items.forEach((el) => {
                el.style.opacity = '';
                el.style.transform = '';
                el.style.willChange = '';
            });
            return;
        }

        const step = parseFloat(root.dataset.revealStagger ?? '0.08') || 0.08;
        const startDelay = parseFloat(root.dataset.revealDelay ?? '0') || 0;
        const amount = parseFloat(root.dataset.revealAmount ?? '0.2') || 0.2;

        const play = () => {
            items.forEach((el, index) => {
                const controls = animate(
                    el,
                    { opacity: [0, 1], transform: ['translateY(16px)', 'translateY(0)'] },
                    {
                        duration: 0.7,
                        delay: startDelay + index * step,
                        ease: [0.22, 1, 0.36, 1],
                    },
                );
                controls.finished.then(() => {
                    el.style.willChange = '';
                }).catch(() => {});
            });
        };

        const observer = new IntersectionObserver((entries) => {
            for (const entry of entries) {
                if (entry.isIntersecting) {
                    play();
                    observer.disconnect();
                    break;
                }
            }
        }, { threshold: amount });

        observer.observe(root);
        cleanup(() => observer.disconnect());
    });
}
