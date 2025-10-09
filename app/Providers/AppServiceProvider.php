<?php

namespace App\Providers;

use App\Events\SendMessage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Hanya jalankan jika aplikasi sudah ter-install dengan baik
        if ($this->app->runningInConsole() && !$this->isAppInstalled()) {
            return;
        }

        try {
            // Ambil kurs dari cache, jika tidak ada ambil dari API
            $euroRate = $this->getEuroRate();

            // Share ke semua view
            \Illuminate\Support\Facades\View::share('euroRate', $euroRate);
        } catch (\Exception $e) {
            // Jika terjadi error, gunakan fallback rate
            Log::warning('Failed to initialize euro rate in AppServiceProvider', [
                'error' => $e->getMessage()
            ]);
            \Illuminate\Support\Facades\View::share('euroRate', 18500);
        }

        // Event::listen(SendMessage::class);
    }

    /**
     * Get Euro to IDR exchange rate
     */
    private function getEuroRate(): float
    {
        // Cek jika cache driver tersedia
        if (!$this->isCacheAvailable()) {
            return $this->fetchEuroRateFromAPI();
        }

        return Cache::remember('eur_idr_rate', now()->addHours(12), function () {
            return $this->fetchEuroRateFromAPI();
        });
    }

    /**
     * Fetch Euro rate from API
     */
    private function fetchEuroRateFromAPI(): float
    {
        try {
            // Cek koneksi internet terlebih dahulu
            if (!$this->hasInternetConnection()) {
                Log::info('No internet connection, using fallback euro rate');
                return 18500;
            }

            $response = Http::timeout(10)
                ->retry(2, 1000) // Retry 2 kali dengan delay 1 detik
                ->get('https://api.exchangerate-api.com/v4/latest/EUR');

            if ($response->successful()) {
                $data = $response->json();

                // Validasi struktur response
                if (isset($data['rates']['IDR']) && is_numeric($data['rates']['IDR'])) {
                    $rate = (float) $data['rates']['IDR'];

                    // Validasi range rate yang masuk akal (10.000 - 25.000)
                    if ($rate >= 10000 && $rate <= 25000) {
                        Log::info('Successfully fetched euro rate', ['rate' => $rate]);
                        return $rate;
                    } else {
                        Log::warning('Euro rate out of expected range', ['rate' => $rate]);
                    }
                }

                Log::warning('Invalid response structure from exchange rate API', [
                    'response' => $data
                ]);
            } else {
                Log::warning('Exchange rate API returned non-successful response', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to fetch exchange rate from API', [
                'error' => $e->getMessage(),
                'type' => get_class($e)
            ]);
        }

        return 18500; // Fallback rate
    }

    /**
     * Check if cache is available
     */
    private function isCacheAvailable(): bool
    {
        try {
            Cache::put('test_cache_key', 'test', 1);
            Cache::forget('test_cache_key');
            return true;
        } catch (\Exception $e) {
            Log::warning('Cache not available, using direct API call', [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Check if app is properly installed
     */
    private function isAppInstalled(): bool
    {
        try {
            // Cek jika .env ada dan APP_KEY sudah di-set
            return config('app.key') !== null &&
                   config('app.key') !== '' &&
                   config('app.key') !== 'base64:';
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Simple internet connection check
     */
    private function hasInternetConnection(): bool
    {
        try {
            // Cek koneksi dengan ping sederhana ke DNS Google
            $connection = @fsockopen('8.8.8.8', 53, $errno, $errstr, 3);
            if ($connection) {
                fclose($connection);
                return true;
            }
        } catch (\Exception $e) {
            // Ignore connection errors
        }

        return false;
    }
}
