<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Pedido; // Asegúrate de que el modelo Pedido esté creado
use Illuminate\Http\Request;
use Livewire\WithPagination;

class FilterPedidosOaxaca extends Component
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


    public function render()
    {
        $pedidos = Pedido::where('SUCURSAL', 'PO')
            ->orderBy('id', 'desc')
            ->when($this->estatusValidado, function ($query) {
                // Si estatusValidado es verdadero, aplica el filtro ESTATUS = 1
                $query->where('ESTATUS', 1);
            })
            ->when($this->estatusPendiente, function ($query) {
                // Si estatusPendiente es verdadero, aplica el filtro ESTATUS = 0
                $query->where('ESTATUS', 0);
            })
            ->paginate(100);

        return view('livewire.filter-pedidos-oaxaca', compact('pedidos'));
    }
}
