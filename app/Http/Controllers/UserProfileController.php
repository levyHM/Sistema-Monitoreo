<?php

namespace App\Http\Controllers;

use App\Models\User; // asegúrate de importar el modelo
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserProfileController extends Controller
{
    public function show()
    {
        return view('pages.user-profile');
    }

    public function create()
    {
        return view('usuarios.create');
    }

    public function store(Request $request)
    {
        $attributes = $request->validate([
            'username' => 'required|max:255|min:2',
            'firstname' => 'nullable|max:100',
            'lastname' => 'nullable|max:100',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|min:5|max:255',
            'address' => 'nullable|max:100',
            'city' => 'nullable|max:100',
            'country' => 'nullable|max:100',
            'postal' => 'nullable|max:100',
            'about' => 'nullable|max:255',
            'roles' => ['nullable', 'array'],
            'roles.*' => ['string', 'exists:roles,name'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
            'catalogo_sucursales_id' => ['required', 'exists:catalogo_sucursales,id'],

        ]);

        //ruta de la firma
        $attributes['signature'] = 'signature/default/firma.png';

        User::create($attributes);
        Log::info('Atributos validados para creación de usuario', $attributes);
        Log::info('Usuario creado', [
            'user_id' => auth()->id(),
            'created_user' => $attributes['username'],
            'request_data' => $request->except('password')
        ]);

        return redirect()->route('usuarios.create')
            ->with('success', 'Usuario creado correctamente.');
    }



    public function update(Request $request, User $user)
    {
        $attributes = $request->validate([
            'username' => ['required', 'max:255', 'min:2'],
            'firstname' => ['max:100'],
            'lastname' => ['max:100'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'address' => ['max:100'],
            'city' => ['max:100'],
            'country' => ['max:100'],
            'postal' => ['max:100'],
            'about' => ['max:255'],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['string', 'exists:roles,name'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
            'signature' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'catalogo_sucursales_id' => ['required', 'exists:catalogo_sucursales,id'],

        ]);
        // Si se subió archivo de firma
        if ($request->hasFile('signature')) {
            $file = $request->file('signature');

            // Guardar archivo en carpeta pública 'evidencias'
            $file = $file->store('signature/' . $user->id, 'public');

            // Guardar ruta relativa en $attributes para actualizar usuario
            // Nota: Guardamos solo 'signature/archivo.ext' para usar con disco 'public'
            $attributes['signature'] = $file;
            // Imprimir atributos para depuración
            Log::info('Atributos actualizados para usuario', $attributes);

            // Agregar logs

        }
        // Actualizar datos personales
        $user->update($attributes);

        // Actualizar roles
        $user->syncRoles($request->input('roles', []));

        // Actualizar permisos
        $user->syncPermissions($request->input('permissions', []));

        return redirect()->route('usuarios.edit', $user->id)
            ->with('success', 'Usuario actualizado correctamente.');
    }


    public function index(Request $request)
    {
        $users = User::orderBy('created_at', 'desc')->paginate(20);
        $roles = Role::all();
        $permissions = Permission::all();
        $selectedUser = $request->user_id ? User::find($request->user_id) : null;

        return view('pages.user-management', compact('users', 'roles', 'permissions', 'selectedUser'));
    }


    public function edit(User $user)
    {
        $users = User::orderBy('created_at', 'desc')->paginate(10);

        $roles = Role::all();
        $permissions = Permission::all();

        // Roles
        $rolesGenerales = $roles->filter(fn($r) => !in_array($r->name, ['dashboard', 'sucursales']));
        $rolesDashboard = $roles->filter(fn($r) => $r->name === 'dashboard');
        $rolesSucursales = $roles->filter(fn($r) => $r->name === 'sucursales');

        // Permisos
        $firmaPermisos = ['crear', 'editar', 'visualizar', 'eliminar', 'firmar'];
        $dashboardPermisos = ['factura', 'pedidos', 'embarques', 'embarques admin', 'recibos', 'usuarios'];
        $sucursalPermisos = ['cdmx', 'xalapa', 'oaxaca'];

        $permisosFirma = $permissions->filter(fn($p) => in_array($p->name, $firmaPermisos));
        $permisosDashboard = $permissions->filter(fn($p) => in_array($p->name, $dashboardPermisos));
        $permisosSucursales = $permissions->filter(fn($p) => in_array($p->name, $sucursalPermisos));



        $userRoles = $user->roles->pluck('name')->toArray();
        $userPermissions = $user->permissions->pluck('name')->toArray();

        return view('usuarios.edit', compact(
            'user',
            'users',
            'rolesGenerales',
            'rolesDashboard',
            'rolesSucursales',
            'permisosFirma',
            'permisosDashboard',
            'permisosSucursales',
            'userRoles',
            'userPermissions'
        ));
    }


    // Actualizar roles y permisos del usuario
    public function assign(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        $user->syncRoles($request->roles ?? []);
        $user->syncPermissions($request->permissions ?? []);

        return redirect()->route('usuarios.index', ['user_id' => $user->id])
            ->with('success', 'Permisos actualizados correctamente.');
    }

    public function destroy(User $user)
    {
        // Opcional: evita que un usuario se elimine a sí mismo
        if (auth()->id() === $user->id) {
            return redirect()->route('user-management')->with('error', 'No puedes eliminar tu propio usuario.');
        }

        $user->delete();

        return redirect()->route('user-management')->with('success', 'Usuario eliminado correctamente.');
    }
}
