<?php

namespace App\Console\Commands;

use App\Imaging\NordicFilter;
use Illuminate\Console\Command;
use Imagick;
use Intervention\Image\ImageManager;

/**
 * Throwaway proof for Checkpoint A: exercises the Nordic manipulator's imaging
 * core OUTSIDE Glide. Confirms (a) Intervention v3's core()->native() returns
 * an \Imagick, (b) the full-strength HALD CLUT applies, and (c) a partial blend
 * produces a visibly intermediate result. Writes samples to disk for eyeballing.
 *
 * Run on a host with the Imagick PHP extension + the real LUT in place:
 *   php artisan nordic:proof public/img/sample.jpg --intensity=50
 */
class NordicFilterProof extends Command
{
    protected $signature = 'nordic:proof
        {source : Path to a source image (absolute, or relative to the project root)}
        {--intensity=50 : Strength for the partial-blend sample (0-100)}
        {--out= : Output directory (defaults to storage/app/nordic-proof)}';

    protected $description = 'Prove the Nordic HALD CLUT manipulator in isolation (outside Glide).';

    public function handle(): int
    {
        if (! extension_loaded('imagick')) {
            $this->error('Imagick PHP extension is not loaded — cannot run the proof here.');
            $this->line('Run this on a host with php-imagick (the production servers have it).');

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

        $lutPath = config('nordic.lut_path');
        if (! is_file($lutPath)) {
            $this->error("LUT not found at {$lutPath} — place hald_nordic.png there first.");

            return self::FAILURE;
        }

        $out = $this->option('out') ?: storage_path('app/nordic-proof');
        if (! is_dir($out)) {
            mkdir($out, 0755, true);
        }

        $partial = max(0, min(100, (int) $this->option('intensity')));
        $filter = new NordicFilter($lutPath);

        // (a) Confirm Intervention v3 hands us a native \Imagick.
        $manager = ImageManager::imagick();
        $probe = $manager->read($source)->core()->native();

        if (! $probe instanceof Imagick) {
            $this->error('core()->native() did NOT return an \Imagick instance — wrong driver?');

            return self::FAILURE;
        }
        $this->info('(a) core()->native() returned '.$probe::class.' ✓');

        // (b) Full strength.
        $fullPath = $out.'/nordic-full.png';
        $full = $manager->read($source)->core()->native();
        $filter->applyToNative($full, 100);
        $full->writeImage($fullPath);
        $this->info("(b) full-strength CLUT written to {$fullPath} ✓");

        // (c) Partial blend.
        $partialPath = $out."/nordic-{$partial}.png";
        $blend = $manager->read($source)->core()->native();
        $filter->applyToNative($blend, $partial);
        $blend->writeImage($partialPath);
        $this->info("(c) {$partial}% blend written to {$partialPath} ✓");

        // Report mean channel values so the intermediacy is verifiable, not just visual.
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
        $stats = $image->getImageChannelMean(Imagick::CHANNEL_RED);
        $r = $stats['mean'] / $image->getQuantumRange()['quantumRangeLong'];
        $g = $image->getImageChannelMean(Imagick::CHANNEL_GREEN)['mean'] / $image->getQuantumRange()['quantumRangeLong'];
        $b = $image->getImageChannelMean(Imagick::CHANNEL_BLUE)['mean'] / $image->getQuantumRange()['quantumRangeLong'];

        return [$label, number_format($r, 4), number_format($g, 4), number_format($b, 4)];
    }
}
