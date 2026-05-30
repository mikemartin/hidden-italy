<?php

namespace App\Console\Commands;

use App\Imaging\ClutFilter;
use Illuminate\Console\Command;
use Imagick;
use Intervention\Image\ImageManager;

/**
 * Proof harness: exercises a named brand filter's imaging core OUTSIDE Glide.
 * Confirms (a) Intervention v3's core()->native() returns an \Imagick, (b) the
 * full-strength HALD CLUT applies, and (c) a partial blend produces a visibly
 * intermediate result. Writes samples to disk for eyeballing.
 *
 * Run on a host with the Imagick PHP extension + the LUT in place:
 *   php artisan clut:proof nordic public/img/sample.jpg --intensity=50
 */
class ClutFilterProof extends Command
{
    protected $signature = 'clut:proof
        {filter : Registered filter name (e.g. nordic, fresco)}
        {source : Path to a source image (absolute, or relative to the project root)}
        {--intensity=50 : Strength for the partial-blend sample (0-100)}
        {--out= : Output directory (defaults to storage/app/clut-proof)}';

    protected $description = 'Prove a brand HALD CLUT filter in isolation (outside Glide).';

    public function handle(): int
    {
        if (! extension_loaded('imagick')) {
            $this->error('Imagick PHP extension is not loaded — cannot run the proof here.');
            $this->line('Run this on a host with php-imagick (the production servers have it).');

            return self::FAILURE;
        }

        $name = $this->argument('filter');
        $config = (new ClutFilter)->filterConfig($name);

        if ($config === null) {
            $this->error("Unknown filter [{$name}]. Defined: ".implode(', ', array_keys(config('image_filters.filters', []))));

            return self::FAILURE;
        }

        $source = $this->argument('source');
        if (! is_file($source)) {
            $source = base_path($source);
        }

        if (! is_file($source)) {
            $this->error("Source image not found: {$this->argument('source')}");

            return self::FAILURE;
        }

        $lutPath = $config['lut_path'];
        if (! is_file($lutPath)) {
            $this->error("LUT for [{$name}] not found at {$lutPath}.");

            return self::FAILURE;
        }

        $out = $this->option('out') ?: storage_path('app/clut-proof');
        if (! is_dir($out)) {
            mkdir($out, 0755, true);
        }

        $partial = max(0, min(100, (int) $this->option('intensity')));
        $filter = new ClutFilter;
        $manager = ImageManager::imagick();

        // (a) Confirm Intervention v3 hands us a native \Imagick.
        $probe = $manager->read($source)->core()->native();
        if (! $probe instanceof Imagick) {
            $this->error('core()->native() did NOT return an \Imagick instance — wrong driver?');

            return self::FAILURE;
        }
        $this->info('(a) core()->native() returned '.$probe::class.' ✓');

        // (b) Full strength.
        $fullPath = $out."/{$name}-full.png";
        $full = $manager->read($source)->core()->native();
        $filter->applyClut($full, $lutPath, 100);
        $full->writeImage($fullPath);
        $this->info("(b) full-strength {$name} CLUT written to {$fullPath} ✓");

        // (c) Partial blend.
        $partialPath = $out."/{$name}-{$partial}.png";
        $blend = $manager->read($source)->core()->native();
        $filter->applyClut($blend, $lutPath, $partial);
        $blend->writeImage($partialPath);
        $this->info("(c) {$partial}% blend written to {$partialPath} ✓");

        $this->newLine();
        $this->table(
            ['Sample', 'Mean R', 'Mean G', 'Mean B'],
            [
                $this->meanRow('original', $manager->read($source)->core()->native()),
                $this->meanRow("{$partial}% blend", new Imagick($partialPath)),
                $this->meanRow('full grade', new Imagick($fullPath)),
            ],
        );

        $this->newLine();
        $this->info('Done. The blend row should sit between original and full on each channel.');

        return self::SUCCESS;
    }

    /**
     * @return array{0:string,1:string,2:string,3:string}
     */
    protected function meanRow(string $label, Imagick $image): array
    {
        $range = $image->getQuantumRange()['quantumRangeLong'];
        $r = $image->getImageChannelMean(Imagick::CHANNEL_RED)['mean'] / $range;
        $g = $image->getImageChannelMean(Imagick::CHANNEL_GREEN)['mean'] / $range;
        $b = $image->getImageChannelMean(Imagick::CHANNEL_BLUE)['mean'] / $range;

        return [$label, number_format($r, 4), number_format($g, 4), number_format($b, 4)];
    }
}
