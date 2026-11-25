# NovaTube

A fast, dark, video-first WordPress theme for tube sites. NovaTube adds a dedicated video post type, taxonomy-driven discovery, HD badges, ratings, AJAX loading, advertisement slots, and a configurable 18+ age gate, all wrapped in a responsive, media-first layout.

[![NovaTube homepage screenshot](novatube/screenshot.png)](novatube/screenshot.png)

*The NovaTube homepage: featured hero, category chips, and the latest video grid. [View full size](novatube/screenshot.png).*

## Contents

- [Overview](#overview)
- [Highlights](#highlights)
- [Requirements](#requirements)
- [Installation](#installation)
- [Setting up videos](#setting-up-videos)
- [Customizer reference](#customizer-reference)
- [Theme structure](#theme-structure)
- [Development](#development)
- [Packaging](#packaging)
- [Frequently asked questions](#frequently-asked-questions)
- [Credits](#credits)
- [Changelog](#changelog)
- [License](#license)

## Overview

NovaTube is built for sites where video is the product. It registers a `video` post type with its own categories, tags, and models, presents promoted content in a three-card hero, and keeps browsing fast with a responsive grid and AJAX “Load more”.

Everything is managed from the WordPress dashboard. The theme has no build step, no page-builder dependency, and no external JavaScript framework.

## Highlights

### Video-first content

- Dedicated `video` post type with categories, tags, and models.
- Featured hero for up to three promoted videos.
- Responsive video grid with AJAX “Load more”.
- Video pages with direct file, YouTube, Vimeo, and iframe player support.
- HD badges, duration labels, view counts, and five-star ratings.
- Taxonomy chips, related videos, and threaded comments.
- VideoObject structured data for search engines.

### Discovery and archives

- Video archive with Latest, Most viewed, Top rated, and Oldest sorting.
- Category, tag, model, author, date, search, and standard blog archives.
- Category chips beneath the header for quick filtering.
- Breadcrumbs, numbered pagination, and a random-video link.

### Engagement

- Five-star rating widget with one vote per browser, enforced by cookie.
- One-view-per-browser counter; editors do not inflate their own counts.
- Copy-link, X, and email sharing actions.
- Accessible navigation, skip link, focus states, and keyboard support.

### Monetization and compliance

- Sanitized sidebar and below-player advertisement slots.
- Configurable 18+ age gate with custom copy and exit URL.
- Footer areas for legal pages, disclaimers, and social profiles.

### Customization

- Brand colors for the primary and secondary accents.
- Homepage controls for the hero, headings, top-rated section, chips, and videos per page.
- Age-gate copy and destination controls.
- Advertisement HTML fields for two placements.
- Footer about text, disclaimer, copyright line, and social links.

## Requirements

| Requirement | Minimum |
| --- | --- |
| WordPress | 6.2 |
| PHP | 7.4 |
| Tested up to | WordPress 7.1 |

## Installation

1. Build or download `dist/novatube-1.0.0.zip`.
2. In WordPress, go to **Appearance → Themes → Add New → Upload Theme**.
3. Upload the ZIP, install it, and activate NovaTube.
4. Go to **Settings → Permalinks** and save once so the video archive and taxonomy URLs are registered.
5. Create a menu under **Appearance → Menus** and assign it to the **Primary Menu** location. A sensible fallback menu is shown until you do.
6. Optional: add widgets to the **Main Sidebar** location.

## Setting up videos

1. Go to **Videos → Add New**.
2. Add a title and, optionally, a description.
3. In the **Video Details** panel, paste one of the following into **Video embed URL or code**:

   | Source | What to paste |
   | --- | --- |
   | Direct video file | `https://example.com/clip.mp4` |
   | YouTube or Vimeo | The normal watch URL |
   | Player host | An iframe embed code |

   Scripts inside pasted embed codes are removed for security. If your player requires additional tags, extend the allowed HTML with the `novatube_embed_allowed_html` filter.

4. Set a featured image. It is used as the card thumbnail and video poster.
5. Add a duration as `12:34`, `1:02:03`, or a number of seconds.
6. Choose video categories, tags, and models in the side panels.
7. Optionally tick **HD video** and **Feature in the homepage hero**.

Videos appear automatically on the homepage, the `/videos/` archive, and their taxonomy pages.

## Customizer reference

Open **Appearance → Customize → NovaTube Options**.

| Section | What you can change |
| --- | --- |
| Brand Colors | Primary and secondary accent colors |
| Homepage | Hero visibility and label, latest heading, top-rated section, category chips, videos per page |
| Age Gate | Enable or disable, heading, description, enter button label, exit URL |
| Advertisement Slots | Sidebar and below-player HTML |
| Footer & Social | About text, legal disclaimer, copyright line, X, Instagram, and Telegram links |

## Theme structure

```text
novatube/
├── assets/
│   ├── css/
│   │   ├── editor.css
│   │   └── main.css
│   ├── img/
│   │   └── placeholder.svg
│   └── js/
│       └── main.js
├── inc/
│   ├── ajax.php
│   ├── customizer.php
│   ├── meta-boxes.php
│   ├── post-types.php
│   ├── setup.php
│   └── template-tags.php
├── languages/
│   └── novatube.pot
├── template-parts/
│   ├── age-gate.php
│   ├── content-none.php
│   ├── hero.php
│   ├── post-card.php
│   └── video-card.php
├── 404.php
├── archive.php
├── comments.php
├── footer.php
├── front-page.php
├── functions.php
├── header.php
├── index.php
├── page.php
├── screenshot.png
├── search.php
├── searchform.php
├── sidebar.php
├── single-video.php
├── single.php
└── style.css
```

## Development

The theme has no build step. Edit PHP, CSS, and JavaScript directly and refresh the browser.

Useful checks:

```bash
# Lint every PHP file
find novatube -name '*.php' -print0 | xargs -0 -n1 php -l

# Validate the translation template
msgfmt --check --output-file=/dev/null novatube/languages/novatube.pot
```

All user-facing strings use the `novatube` text domain. The translation template lives at `novatube/languages/novatube.pot`.

## Packaging

Build an installable ZIP from the repository root:

```bash
mkdir -p dist
zip -r dist/novatube-1.0.0.zip novatube -x '*.DS_Store' -x '__MACOSX/*'
```

The archive contains a top-level `novatube/` directory, which is the structure WordPress expects for theme uploads.

## Frequently asked questions

### Why is the video archive returning a 404?

Visit **Settings → Permalinks** and save once. The theme flushes rewrite rules on activation, but saving permalinks also repairs an existing install.

### How do I change the player size?

The player fills its container, which is capped by the theme content width. Use Additional CSS to adjust `.player-frame` or `.novatube-player` if needed.

### How do I add an ad network script?

Scripts are removed from ad fields on purpose. Load the network’s JavaScript through a small plugin or a child theme, then place the ad markup, such as an `<ins>` tag, in the Customizer field.

### How do I use a child theme?

Create a child theme that enqueues the parent stylesheet, then override templates and styles there. Because NovaTube has no build step, every CSS and JavaScript file is editable directly.

### Are ratings and views cached?

No. Ratings and view counts are stored as post meta. Views are counted once per browser per video using a cookie, and logged-in editors do not inflate their own counts.

## Credits

- Icons: inline SVG drawn for this theme, based on common interface metaphors.
- Fonts: the visitor’s system font stack by default, with Inter used when available.
- Placeholder artwork: `assets/img/placeholder.svg`, original for this theme.

## Changelog

### 1.0.0

- Initial release.

## License

NovaTube is licensed under the GNU General Public License v2 or later. See <https://www.gnu.org/licenses/gpl-2.0.html>.
