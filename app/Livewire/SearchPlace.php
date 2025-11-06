<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Enlace de reseñas')]
class SearchPlace extends Component
{
    // Google Places properties
    public $searchAddress = '';

    public $placeId = null;

    public $reviewUrl = '';

    public $placeName = '';

    // TripAdvisor properties
    public $tripAdvisorQuery = '';

    public $tripAdvisorLocationId = null;

    public $tripAdvisorReviewUrl = '';

    public $tripAdvisorPlaceName = '';

    public $tripAdvisorSuggestions = [];

    public $tripAdvisorLoading = false;

    protected $listeners = [
        'placeSelected' => 'handlePlaceSelected',
    ];

    // ==============================================
    // Google Places Methods
    // ==============================================

    public function updatedPlaceId($value)
    {
        if ($value) {
            $this->fetchPlaceDetailsAndBuildReviewUrl($value);
        }
    }

    public function handlePlaceSelected($placeId)
    {
        $this->placeId = $placeId;
        $this->fetchPlaceDetailsAndBuildReviewUrl($placeId);
    }

    protected function fetchPlaceDetailsAndBuildReviewUrl(string $placeId): void
    {
        $serverKey = config('services.google_maps')
                   ?? env('GOOGLE_MAPS_API_KEY');

        if (empty($serverKey)) {
            Log::warning('Google Maps server key missing; skipping place details fetch', ['placeId' => $placeId]);
            $this->reviewUrl = '';
            $this->placeName = '';

            return;
        }

        $endpoint = "https://places.googleapis.com/v1/places/{$placeId}";
        $fieldMask = 'placeId,name,formattedAddress,googleMapsLinks';

        try {
            $response = Http::timeout(5)->get($endpoint, [
                'key' => $serverKey,
                'fieldMask' => $fieldMask,
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching Google Place details', ['error' => $e->getMessage(), 'placeId' => $placeId]);
            $this->reviewUrl = '';
            $this->placeName = '';

            return;
        }

        if (! $response->successful()) {
            $this->reviewUrl = '';
            $this->placeName = '';

            return;
        }

        $result = $response->json() ?? [];

        $this->placeName = $result['name'] ?? '';

        $links = $result['googleMapsLinks'] ?? [];

        // Prefer writeAReviewUri, otherwise reviewsUri, otherwise fallback public URL
        if (! empty($links['writeAReviewUri'])) {
            $this->reviewUrl = $links['writeAReviewUri'];

            return;
        }

        if (! empty($links['reviewsUri'])) {
            $this->reviewUrl = $links['reviewsUri'];

            return;
        }

        $this->reviewUrl = "https://search.google.com/local/writereview?placeid={$placeId}";
    }

    // ==============================================
    // TripAdvisor Methods
    // ==============================================

    public function updatedTripAdvisorQuery(): void
    {
        $this->searchTripAdvisor();
    }

    public function searchTripAdvisor(): void
    {
        if (strlen($this->tripAdvisorQuery) < 3) {
            $this->tripAdvisorSuggestions = [];
            $this->tripAdvisorLocationId = null;
            $this->tripAdvisorReviewUrl = '';
            $this->tripAdvisorPlaceName = '';

            return;
        }

        $this->tripAdvisorLoading = true;

        try {
            $response = Http::withHeaders([
                'accept' => 'application/json',
            ])->get('https://api.content.tripadvisor.com/api/v1/location/search', [
                'key' => config('services.tripadvisor.api_key'),
                'searchQuery' => $this->tripAdvisorQuery,
                'language' => 'es',
            ]);

            if (! $response->successful()) {
                $this->tripAdvisorSuggestions = [];

                return;
            }

            $data = $response->json();

            $this->tripAdvisorSuggestions = $this->mapTripAdvisorResults($data);
        } catch (\Exception $e) {
            $this->tripAdvisorSuggestions = [];
            Log::error('Error en búsqueda TripAdvisor:', ['error' => $e->getMessage()]);
        } finally {
            $this->tripAdvisorLoading = false;
        }
    }

    public function selectTripAdvisorPlace(array $place): void
    {
        $this->tripAdvisorLocationId = $place['locationId'];
        $this->tripAdvisorPlaceName = $place['name'];
        $this->tripAdvisorQuery = $place['name'];
        $this->tripAdvisorSuggestions = [];

        $this->generateTripAdvisorReviewUrl();
    }

    public function clearTripAdvisor(): void
    {
        $this->tripAdvisorQuery = '';
        $this->tripAdvisorLocationId = null;
        $this->tripAdvisorReviewUrl = '';
        $this->tripAdvisorPlaceName = '';
        $this->tripAdvisorSuggestions = [];
        $this->tripAdvisorLoading = false;
    }

    // ==============================================
    // Private Helper Methods
    // ==============================================

    private function mapTripAdvisorResults(array $data): array
    {
        if (! isset($data['data']) || count($data['data']) === 0) {
            return [];
        }

        return collect($data['data'])
            ->map(fn ($item) => [
                'locationId' => $item['location_id'] ?? null,
                'name' => $item['name'] ?? '',
                'address' => $item['address_obj']['address_string'] ?? '',
            ])
            ->filter(fn ($item) => ! empty($item['locationId']))
            ->toArray();
    }

    private function generateTripAdvisorReviewUrl(): void
    {
        if (! $this->tripAdvisorLocationId) {
            $this->tripAdvisorReviewUrl = '';

            return;
        }

        try {
            // Usar endpoint de Location Details según documentación
            $response = Http::withHeaders([
                'accept' => 'application/json',
            ])->get("https://api.content.tripadvisor.com/api/v1/location/{$this->tripAdvisorLocationId}/details", [
                'key' => config('services.tripadvisor.api_key'),
                'language' => 'es',
            ]);

            if (! $response->successful()) {
                $this->tripAdvisorReviewUrl = $this->getFallbackTripAdvisorUrl();

                return;
            }

            $data = $response->json();

            // Intentar obtener write_review desde la respuesta de la API
            if (isset($data['write_review'])) {
                $this->tripAdvisorReviewUrl = $data['write_review'];

                return;
            }

            // Si no existe write_review, construir desde web_url
            if (isset($data['web_url'])) {
                $this->tripAdvisorReviewUrl = $this->buildTripAdvisorUrlFromWebUrl($data['web_url']);

                return;
            }

            // Fallback final
            $this->tripAdvisorReviewUrl = $this->getFallbackTripAdvisorUrl();
        } catch (\Exception $e) {
            Log::error('Error generando URL TripAdvisor:', [
                'error' => $e->getMessage(),
                'locationId' => $this->tripAdvisorLocationId,
            ]);
            $this->tripAdvisorReviewUrl = $this->getFallbackTripAdvisorUrl();
        }
    }

    private function buildTripAdvisorUrlFromWebUrl(string $webUrl): string
    {
        // Convertir la URL del lugar a URL de escritura de reseña
        // Ejemplo: https://www.tripadvisor.com/Hotel_Review-g123-d456.html
        // A: https://www.tripadvisor.com/UserReview-g123-d456
        $reviewUrl = preg_replace('/-[a-zA-Z_]+-/', '-', $webUrl);
        $reviewUrl = str_replace('.html', '', $reviewUrl);
        $reviewUrl = preg_replace('/\/(Hotel|Restaurant|Attraction)_Review/', '/UserReview', $reviewUrl);

        return $reviewUrl;
    }

    private function getFallbackTripAdvisorUrl(): string
    {
        return "https://www.tripadvisor.com/UserReview-d{$this->tripAdvisorLocationId}";
    }

    public function render()
    {
        return view('livewire.search-place');
    }
}
