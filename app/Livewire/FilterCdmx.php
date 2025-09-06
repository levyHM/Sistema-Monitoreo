<?php

namespace App\Livewire;

use App\Models\Factura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class FilterCdmx extends Component
{
    use WithPagination; // Importante para habilitar paginación en Livewire

    public $estatusPendiente = false;
    public $estatusValidado = false;

    public $captura = ''; // <-- NUEVO: para el input del código de barras


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
            'ditipmv' => 'FE',
        ]);
        $this->dispatch('update-url', ['url' => url()->current() . '?' . $queryParams]);

        // Reiniciar paginación al cambiar filtros
        $this->resetPage();
    }

    public function validarCodigo()
    {
        $validatedData = $this->validate([
            'captura' => 'required|string',
        ]);

        try {
            $empacado = Factura::where('SERIE', $validatedData['captura'])->first();

            if ($empacado) {
                if ($empacado->CAPTURA === $validatedData['captura'] && $empacado->ESTATUS == 1) {
                    $this->dispatchBrowserEvent('codigo-validado', [
                        'mensaje' => 'El registro ya existe y está actualizado.',
                        'tipo' => 'warning',
                    ]);
                    $this->captura = '';
                    return;
                }

                $empacado->update([
                    'CAPTURA' => $validatedData['captura'],
                    'ESTATUS' => 1,
                ]);

                $this->dispatch('codigo-validado', [
                    'mensaje' => 'Registro actualizado exitosamente.',
                    'tipo' => 'success',
                ]);
            } else {
                $this->dispatch('codigo-validado', [
                    'mensaje' => 'No existe el registro con el número de captura proporcionado.',
                    'tipo' => 'error',
                ]);
            }
        } catch (\Exception $e) {
            $this->dispatch('codigo-validado', [
                'mensaje' => 'Ocurrió un error al procesar la solicitud.',
                'tipo' => 'error',
            ]);
        }

        $this->captura = '';
    }

    public function getFacturasProperty()
    {
        return Factura::when($this->estatusPendiente, function ($query) {
            // Si estatusPendiente es verdadero, aplica el filtro ESTATUS = 0 y DITIPMV = 'FE'
            $query->where('ESTATUS', 0)
                ->where('DITIPMV', 'FE');
        })
            ->when($this->estatusValidado, function ($query) {
                // Si estatusValidado es verdadero, aplica el filtro ESTATUS = 1
                $query->orWhere('ESTATUS', 1);
            })
            // El filtro DITIPMV solo se necesita una vez, fuera de las condiciones
            ->where('DITIPMV', 'FE')
            ->orderBy('id', 'desc')
            ->paginate(100);
    }

    public function render()
    {
        return view('livewire.filter-cdmx', [
            'facturas' => $this->facturas,
        ]);
    }
}
