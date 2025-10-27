<?php

namespace App\Livewire;

use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

#[Title('Enlace de reseñas')]
class SearchPlace extends Component
{
    public $searchAddress = '';

    public $placeId = null;

    public $reviewUrl = '';

    public $placeName = '';

    protected $listeners = [
        'placeSelected' => 'handlePlaceSelected',
    ];

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
        $serverKey = config('services.google.maps_server_key')
                   ?? env('GOOGLE_MAPS_SERVER_KEY');

        // Endpoint de la nueva API Places (New)
        $endpoint = "https://places.googleapis.com/v1/places/{$placeId}";

        // Definimos la máscara de campos (field mask) para obtener googleMapsLinks
        $fieldMask = 'placeId,name,formattedAddress,googleMapsLinks';

        $response = Http::get($endpoint, [
            'key' => $serverKey,
            'fieldMask' => $fieldMask,
        ]);

        if (! $response->successful()) {
            $this->reviewUrl = '';
            $this->placeName = '';

            return;
        }

        $data = $response->json();
        $result = $data; // en la versión v1, los campos están directamente en el objeto

        $this->placeName = $result['name'] ?? '';

        // Extraer el writeAReviewUri si existe
        if (isset($result['googleMapsLinks']['writeAReviewUri'])) {
            $this->reviewUrl = $result['googleMapsLinks']['writeAReviewUri'];

            return;
        }

        // Si no existe, fallback: usar reviewsUri o url genérico
        if (! empty($result['googleMapsLinks']['reviewsUri'])) {
            $this->reviewUrl = $result['googleMapsLinks']['reviewsUri'];

            return;
        }

        // Fallback público conocido
        $this->reviewUrl = "https://search.google.com/local/writereview?placeid={$placeId}";
    }

    public function render()
    {
        return view('livewire.search-place');
    }
}
