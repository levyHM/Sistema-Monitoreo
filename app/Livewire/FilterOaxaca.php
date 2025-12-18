<?php

namespace App\Livewire;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Factura;

class FilterOaxaca extends Component
{ 
    use WithPagination; // Importante para habilitar paginación en Livewire
    public $estatusPendiente = false;
    public $estatusValidado = false;

    protected $paginationTheme = 'bootstrap';

    public function mount(Request $request)
    {
        $this->estatusPendiente = $request->query('estatusPendiente', false);
        $this->estatusValidado = $request->query('estatusValidado', false);
    }

    public function updated()
    {
        // Actualizar la URL cuando cambien los filtros
        $queryParams = http_build_query([
            'estatusPendiente' => $this->estatusPendiente ? '1' : '0',
            'estatusValidado' => $this->estatusValidado ? '1' : '0',
        ]);
        $this->dispatch('update-url', ['url' => url()->current() . '?' . $queryParams]);

        // Reiniciar paginación al cambiar filtros
        $this->resetPage();
    }

    public function getFacturasProperty()
    {
        return Factura::when($this->estatusPendiente, function ($query) {
                // Si estatusPendiente es verdadero, aplica el filtro ESTATUS = 0 y DITIPMV = 'FE'
                $query->where('ESTATUS', 0)
                    ->where('DITIPMV', 'FO');
            })
            ->when($this->estatusValidado, function ($query) {
                // Si estatusValidado es verdadero, aplica el filtro ESTATUS = 1
                $query->orWhere('ESTATUS', 1);
            })
            // El filtro DITIPMV solo se necesita una vez, fuera de las condiciones
            ->where('DITIPMV', 'FO')  
            ->orderBy('id', 'desc')
            ->paginate(100);
    }
    
    public function render()
    {
        return view('livewire.filter-oaxaca', [
            'facturas' => $this->facturas,
        ]);
    }
}
