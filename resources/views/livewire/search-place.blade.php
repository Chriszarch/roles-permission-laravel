<div x-data="searchPlace()" x-init="init()" class="space-y-6">
  <!-- Header -->
  <div class="mb-6">
    <p class="text-gray-600 dark:text-gray-400 mt-1">obtén tu enlace para solicitar reseñas de tu negocio</p>
  </div>

  <!-- Search Card -->
  <x-card class="p-6">
    <div class="space-y-4">
      <!-- Search Input -->
      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
          Buscar Negocio
        </label>
        <div class="relative">
          <input type="text"
               placeholder="Ej: Restaurante La Cocina, Lima"
               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
               x-model="query"
               @input.debounce.300ms="onInputChanged()"
               @focus="if(suggestions.length > 0) showSuggestions = true"
               @click.away="showSuggestions = false"
               autocomplete="off">
          
          <!-- Loading indicator -->
          <div x-show="loading" class="absolute right-3 top-3">
            <svg class="animate-spin h-6 w-6 text-primary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
          </div>

          <!-- Suggestions dropdown -->
          <div x-show="showSuggestions && suggestions.length > 0" 
               class="absolute z-10 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg shadow-lg max-h-60 overflow-y-auto"
               x-cloak>
            <template x-for="(suggestion, index) in suggestions" :key="suggestion.placeId">
              <div @click="selectPlace(suggestion)"
                   class="p-3 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 border-b border-gray-200 dark:border-gray-600 last:border-b-0 transition-colors">
                <div class="font-medium text-sm text-gray-900 dark:text-white" x-text="suggestion.mainText"></div>
                <div class="text-xs text-gray-600 dark:text-gray-400" x-text="suggestion.text"></div>
              </div>
            </template>
          </div>
        </div>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
          💡 Tip: Sé específico con el nombre y ubicación del negocio para mejores resultados
        </p>
      </div>

      <!-- Results Info (shown when a place is selected) -->
      <template x-if="placeName">
        <div x-transition
             class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
          <div class="flex items-start gap-3">
            <svg class="w-6 h-6 text-green-600 dark:text-green-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div class="flex-1">
              <h4 class="font-semibold text-green-900 dark:text-green-100">Lugar Seleccionado</h4>
              <p class="text-sm text-green-800 dark:text-green-200 mt-1" x-text="placeName"></p>
            </div>
          </div>
        </div>
      </template>

      <!-- Review URL Input (read-only) -->
      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
          Enlace de Reseñas
        </label>
        <div class="flex gap-2">
          <input readonly 
                 type="text" 
                 x-model="reviewUrl"
                 placeholder="Selecciona un negocio para generar el enlace..."
                 class="flex-1 px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white cursor-not-allowed" />

          <button 
              type="button"
              @click="copy()" 
              :disabled="!reviewUrl"
              class="px-4 py-3 rounded-lg text-white font-medium transition-colors flex items-center gap-2"
              :class="reviewUrl ? 'bg-blue-600 hover:bg-blue-700' : 'bg-gray-400 cursor-not-allowed'">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
            </svg>
            Copiar
          </button>
        </div>
      </div>

      <!-- Success Message -->
      <template x-if="copied">
        <div x-transition
             class="flex items-center gap-2 text-green-600 dark:text-green-400">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
          <span class="font-medium">¡Enlace copiado con éxito!</span>
        </div>
      </template>

      <!-- Clear Button -->
      <template x-if="reviewUrl">
        <div class="flex justify-end">
          <button 
              type="button"
              @click="clear()"
              class="px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition-colors flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
            Limpiar
          </button>
        </div>
      </template>
    </div>
  </x-card>

  <!-- Instructions Card -->
  <x-card class="p-6 bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800">
    <h3 class="font-semibold text-blue-900 dark:text-blue-100 mb-3">📋 Cómo usar esta herramienta</h3>
    <ol class="space-y-2 text-sm text-blue-800 dark:text-blue-200">
      <li class="flex gap-2">
        <span class="font-bold">1.</span>
        <span>Escribe el nombre del negocio en el campo de búsqueda</span>
      </li>
      <li class="flex gap-2">
        <span class="font-bold">2.</span>
        <span>Selecciona tu negocio de las sugerencias que aparecen</span>
      </li>
      <li class="flex gap-2">
        <span class="font-bold">3.</span>
        <span>Da click en el botón "Copiar" y Ualá ya tienes el enlace de reseñas listo</span>
      </li>
      <li class="flex gap-2">
        <span class="font-bold">4.</span>
        <span>Comparte tu enlace con tus clientes para que dejen reseñas en Google</span>
      </li>
    </ol>
  </x-card>
</div>

{{-- usamos la nueva API de Places Autocomplete con Alpine.js para comunicar de manera optima a Livewire --}}
<script>
function searchPlace() {
  return {
    query: '',
    placeId: null,
    reviewUrl: '',
    placeName: '',
    suggestions: [],
    showSuggestions: false,
    loading: false,
    copied: false,

    init() {
      // Inicialización del componente
      console.log('🚀 Search Place component initialized');
    },

    async onInputChanged() {
      if (this.query.length < 3) {
        this.suggestions = [];
        this.showSuggestions = false;
        this.placeId = null;
        this.reviewUrl = '';
        this.placeName = '';
        return;
      }

      this.loading = true;
      const apiKey = '{{ config('services.google_maps.api_key') }}';
      
      // Nueva API de Places - Autocomplete (nuevo)
      const url = 'https://places.googleapis.com/v1/places:autocomplete';
      
      try {
        const resp = await fetch(url, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-Goog-Api-Key': apiKey
          },
          body: JSON.stringify({
            input: this.query,
            languageCode: 'es'
          })
        });

        if (!resp.ok) {
          const errorData = await resp.json();
          console.error('Error de la API:', errorData);
          throw new Error('Error Autocomplete');
        }

        const data = await resp.json();
        
        // La nueva API devuelve suggestions con placePrediction
        if (data.suggestions && data.suggestions.length > 0) {
          this.suggestions = data.suggestions.filter(s => s.placePrediction).map(s => ({
            placeId: s.placePrediction.placeId,
            text: s.placePrediction.text.text,
            mainText: s.placePrediction.structuredFormat?.mainText?.text || s.placePrediction.text.text
          }));
          this.showSuggestions = true;
        } else {
          this.suggestions = [];
          this.showSuggestions = false;
        }
      } catch(err) {
        console.error('Error en búsqueda:', err);
      } finally {
        this.loading = false;
      }
    },

    selectPlace(suggestion) {
      this.placeId = suggestion.placeId;
      this.placeName = suggestion.text;
      this.query = suggestion.mainText;
      this.showSuggestions = false;
      
      // Generar URL de reseña
      this.reviewUrl = `https://search.google.com/local/writereview?placeid=${this.placeId}`;
      
      console.log('✅ Lugar seleccionado:', {
        placeId: this.placeId,
        placeName: this.placeName,
        reviewUrl: this.reviewUrl
      });
      
      // Enviar a Livewire (si es necesario)
      if (window.Livewire && this.$wire) {
        this.$wire.set('placeId', this.placeId);
      }
    },

    copy() {
      if (!this.reviewUrl) return;
      
      const textArea = document.createElement('textarea');
      textArea.value = this.reviewUrl;
      textArea.style.position = 'fixed';
      textArea.style.left = '-999999px';
      document.body.appendChild(textArea);
      textArea.select();
      
      try {
        document.execCommand('copy');
        document.body.removeChild(textArea);
        console.log('✅ URL copiada al portapapeles');
        this.copied = true;
        setTimeout(() => {
          this.copied = false;
        }, 3000);
      } catch (err) {
        document.body.removeChild(textArea);
        console.error('❌ Error al copiar:', err);
        
        if (navigator.clipboard && navigator.clipboard.writeText) {
          navigator.clipboard.writeText(this.reviewUrl).then(() => {
            console.log('✅ URL copiada al portapapeles (fallback)');
            this.copied = true;
            setTimeout(() => {
              this.copied = false;
            }, 3000);
          }).catch(() => {
            alert('Error al copiar el enlace');
          });
        } else {
          alert('Error al copiar el enlace');
        }
      }
    },

    clear() {
      this.query = '';
      this.placeId = null;
      this.reviewUrl = '';
      this.placeName = '';
      this.suggestions = [];
      this.showSuggestions = false;
      this.copied = false;
      console.log('🧹 Formulario limpiado');
    }
  }
}
</script>
