# Brand filter LUTs (HALD CLUT)

These are the brand colour grades, stored as 512×512, level-8, 8-bit sRGB
HALD CLUT PNGs (e.g. `hald_nordic.png`). They are version-controlled with the
code — never author-managed assets — so each look stays byte-exact and can't be
deleted or tinted by accident. They're pinned `binary` in `.gitattributes`.

## How a filter is applied

Opt-in per image, at the tag level only:

    {{ glide:img filter="nordic" }}

The friendly `filter` param maps to Glide's `filt` key; the manipulator in
`app/Imaging/` applies the matching LUT via Imagick at the locked intensity from
config. Requires the Imagick driver (`IMAGE_MANIPULATION_DRIVER=imagick`); under
GD it skips cleanly and logs a warning rather than failing the render.

## Changing a look later

1. Replace the LUT PNG in this folder (same filename, 512×512 level-8).
2. Optionally tune the locked `intensity` in config with the designer.
3. Clear cached variants: `php please glide:clear`
4. Done — Glide regenerates each variant lazily on the next request (no
   cache-warming step needed).
