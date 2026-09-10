import { Controller } from '@hotwired/stimulus';

/*
 * Plain, static Web Mercator world map (single SVG silhouette, no borders,
 * no country names, no third-party logo, no interaction) with small
 * text-only activity tooltips popped up on top of it (avatar added,
 * signup, new post, ...), each anchored to its exact location by a dot.
 *
 * Feed an activity in from anywhere with:
 *   document.dispatchEvent(new CustomEvent('world-map:feed', {
 *       detail: { lat: 48.8566, lng: 2.3522, label: 'Camille added a photo' },
 *   }));
 *
 * Set data-world-map-demo-value="true" on the controller element to preview
 * it with fake activities until the real event source is wired in.
 */
export default class extends Controller {
    static targets = ['layer'];
    static values = {
        demo: Boolean,
        itemLifetime: { type: Number, default: 5000 },
    };

    // Matches templates/components/_world_map.svg.twig's viewBox
    // (a Web Mercator crop from lat 83.5° to -58°).
    #latTop = 83.5;
    #latBottom = -58;

    connect() {
        this.onFeed = (event) => this.addFeedItem(event.detail);
        document.addEventListener('world-map:feed', this.onFeed);

        if (this.demoValue) {
            this.#startDemo();
        }
    }

    disconnect() {
        document.removeEventListener('world-map:feed', this.onFeed);
        clearInterval(this.demoTimer);
    }

    /**
     * @param {{lat: number, lng: number, label: string}} activity
     */
    addFeedItem({ lat, lng, label }) {
        const position = this.#project(lat, lng);
        if (!position) {
            return; // outside of the visible latitude band, skip it
        }

        const item = document.createElement('div');
        item.className = 'world-feed-item';
        item.style.left = `${position.xPercent}%`;
        item.style.top = `${position.yPercent}%`;
        item.innerHTML = `
            <div class="world-feed-item__bubble rounded-box border border-base-300 bg-base-100 px-3 py-1.5 text-xs font-medium text-base-content shadow-sm">${label}</div>
            <div class="world-feed-item__dot bg-primary"></div>
        `;

        this.layerTarget.appendChild(item);

        requestAnimationFrame(() => item.classList.add('world-feed-item--show'));

        setTimeout(() => {
            item.classList.remove('world-feed-item--show');
            item.classList.add('world-feed-item--leave');
            item.addEventListener('transitionend', () => item.remove(), { once: true });
        }, this.itemLifetimeValue);
    }

    /** Web Mercator projection (matches the SVG's projection): lat/lng -> % position. */
    #project(lat, lng) {
        if (lat > this.#latTop || lat < this.#latBottom) {
            return null;
        }

        const mercatorY = (value) => Math.log(Math.tan(Math.PI / 4 + (value * Math.PI) / 360)) * (180 / Math.PI);
        const yTop = -mercatorY(this.#latTop);
        const yBottom = -mercatorY(this.#latBottom);

        const xPercent = ((lng + 180) / 360) * 100;
        const yPercent = ((-mercatorY(lat) - yTop) / (yBottom - yTop)) * 100;

        return { xPercent, yPercent };
    }

    #startDemo() {
        const activities = [
            { lat: 48.8566, lng: 2.3522, label: 'Camille added a photo' },
            { lat: 40.7128, lng: -74.006, label: 'Alex just signed up' },
            { lat: 35.6762, lng: 139.6503, label: 'Yuki published a post' },
            { lat: -23.5505, lng: -46.6333, label: 'Bianca just signed up' },
            { lat: 51.5074, lng: -0.1278, label: 'Oliver added a photo' },
            { lat: -33.8688, lng: 151.2093, label: 'Mia published a post' },
        ];

        let i = 0;
        this.demoTimer = setInterval(() => {
            this.addFeedItem(activities[i % activities.length]);
            i += 1;
        }, 2200);
    }
}
