<?php

namespace App\Console\Commands;

use App\Models\DiscountRule;
use App\Models\Package;
use App\Models\PackagePrice;
use App\Models\Provider;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class CalculateDiscount extends Command
{
    const FILE_PATH = 'input.txt';
    const SAVE_FILE_PATH = 'output.txt';
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calculate:discount {--file=}';
    private array $data;
    private array $discount;
    private array $count;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'calculate discount';

    public function __construct(Provider $provider, Package $package, PackagePrice $price, DiscountRule $rule)
    {
        parent::__construct();

        $this->provider = $provider;
        $this->package = $package;
        $this->price = $price;
        $this->rule = $rule;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $this->getFile();

        $this->info('result:');

        if (!$this->data) {
            $this->info('dont found data');
        }

        $providers = $this->provider->get()->pluck('slug')->toArray();
        $packages = $this->package->get()->pluck('slug')->toArray();
        $rules = $this->rule->get();
        $prices = $this->getPrices();

        if (empty($providers) || empty($packages) || empty($rules) || empty($prices)) {
            $this->info('dont found data, please run seeder');

            return Command::FAILURE;
        }

        $result = collect();

        foreach ($this->data as $item) {
            if (count($item) < 3 || !isset($prices[$item[1]])) {
                $result->push([
                    $item[0],
                    trim($item[1]),
                    'Ignored',
                ]);
                continue;
            }

            $discount = $this->getPrice($item, $prices, $rules);
            $price = $prices[$item[1]][trim($item[2])] - $discount;

            $result->push([
                $item[0],
                trim($item[2]),
                number_format($price, 2),
                $discount > 0 ? number_format($discount, 2) : '--',
            ]);
        }

        $this->saveFile($result);

        return Command::SUCCESS;
    }

    protected function getFile(): void
    {
        $file = $this->option('file') ?? self::FILE_PATH;

        $url = Storage::disk('local')->url($file);
        $conn = Storage::disk('local');
        $stream = $conn->readStream($url);

        while (($line = fgets($stream, 4096)) !== false) {
            $data = explode(" ", $line);
            $this->data[] = $data;
        }
    }

    protected function saveFile(Collection $result): void
    {
        $result = $result->map(function (array $data) {
            return collect($data)->implode(' ');
        })->each(function (string $data) {
            $this->info($data);
        })->implode("\n");

        Storage::disk('local')->put(self::SAVE_FILE_PATH, $result);
    }

    protected function getPrices(): array
    {
        return $this->price
            ->with('package', 'provider')
            ->get()
            ->map(function (PackagePrice $packagePrice) {
                return [
                    'slug' => $packagePrice->package->slug,
                    'provider' => $packagePrice->provider->slug,
                    'price' => $packagePrice->price,
                ];
            })
            ->groupBy('slug')
            ->map(function (Collection $item) {
                return $item->pluck('price', 'provider');
            })
            ->toArray();
    }

    protected function getPrice(array $item, array $prices, Collection $rules): float
    {
        $discount = 0;

        $month = Carbon::parse($item[0])->month;
        $years = Carbon::parse($item[0])->year;
        $rules->each(function (DiscountRule $rule) use ($item, $prices, &$discount, $month, $years) {
            if (isset($rule->rule['size']) && !empty($rule->rule['lower']) && $rule->rule['size'] == $item[1]) {
                $min = collect($prices[$item[1]])->min();
                $discount = $prices[$item[1]][trim($item[2])] - $min;
            } elseif (isset($rule->rule['max']) && $rule->rule['calendar'] == 'month') {
                $oldDiscount = (isset($this->discount[$years . '-' . $month])) ? $this->discount[$years . '-' . $month] : 0;
                $discount = $rule->rule['max'] > ($discount + $oldDiscount)
                    ? $discount : $rule->rule['max'] - $oldDiscount;
            } elseif (
                !empty($rule->rule['first']) &&
                isset($rule->rule['size']) &&
                $rule->rule['size'] == $item[1] &&
                isset($rule->rule['provider']) &&
                $rule->rule['provider'] == trim($item[2]) &&
                $rule->rule['calendar'] == 'month'
            ) {
                $time = (isset($this->count[$item[1] . '-' . trim($item[2]) . '-' . $years . '-' . $month]))
                    ? $this->count[$item[1] . '-' . trim($item[2]) . '-' . $years . '-' . $month] : 0;
                $time++;
                if ($time == (int)$rule->rule['after']) {
                    $discount = (float)$prices[$item[1]][trim($item[2])];
                }
            }
        });

        if (isset($this->count[$item[1] . '-' . trim($item[2]) . '-' . $years . '-' . $month])) {
            $this->count[$item[1] . '-' . trim($item[2]) . '-' . $years . '-' . $month] += 1;
        } else {
            $this->count[$item[1] . '-' . trim($item[2]) . '-' . $years . '-' . $month] = 1;
        }

        if (isset($this->discount[$years . '-' . $month])) {
            $this->discount[$years . '-' . $month] += $discount;
        } else {
            $this->discount[$years . '-' . $month] = $discount;
        }

        return $discount;
    }
}
