<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Pedido; // Asegúrate de que el modelo Pedido esté creado
use Illuminate\Http\Request;
use Livewire\WithPagination;

class FilterPedidosXalapa extends Component
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
        $pedidos = Pedido::where('SUCURSAL', 'PV')
            ->orderBy('id', 'desc');

        // Solo aplicar los filtros si alguno de los dos está activo
        if ($this->estatusValidado && !$this->estatusPendiente) {
            // Si solo estatusValidado está activo
            $pedidos->where('ESTATUS', 1);
        } elseif ($this->estatusPendiente && !$this->estatusValidado) {
            // Si solo estatusPendiente está activo
            $pedidos->where('ESTATUS', 0);
        }
        // Si ambos son true, no se aplica filtro alguno
        
        $pedidos = $pedidos->paginate(100);

        return view('livewire.filter-pedidos-xalapa', compact('pedidos'));
    }
}
