# Astryx design system assets

NovaTube ships the compiled Astryx theme layer so the WordPress front end and
the block editor use the same design tokens, typography, colors, spacing,
radii, and elevation as [Astryx](https://astryx.atmeta.com/).

| File | Source |
| --- | --- |
| `astryx.css` | `@astryxdesign/core@0.7.17/dist/astryx.css` |
| `theme.css` | `@astryxdesign/theme-neutral@0.7.17/dist/theme.css` |
| `fonts.css` + `fonts/*.woff2` | `@astryxdesign/theme-neutral@0.7.17` |

Astryx is Copyright (c) Meta Platforms, Inc. and affiliates and is released
under the MIT license.

The WordPress front end is rendered by PHP rather than React. NovaTube's
`assets/css/base.css`, `components.css`, and `templates.css` layers follow the
Astryx component anatomy (Button, Badge, Card, Field, Dialog, Drawer, Menu,
SegmentedControl, Breadcrumbs, Pagination, MetadataList, Toast, and so on) and
consume the tokens from these files directly. The root element carries
`data-astryx-theme="neutral"` so the scoped neutral theme applies, and the
NovaTube layers bridge the Astryx token names to the site colour scheme. No
JavaScript build step is required.

To update Astryx, replace these files with the matching files from the same
`@astryxdesign/core` and `@astryxdesign/theme-neutral` versions.
