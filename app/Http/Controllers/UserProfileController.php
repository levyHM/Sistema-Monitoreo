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

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Validación básica
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email',
            // otros campos...
        ]);

        // Actualizar datos del usuario
        $user->update($request->only([
            'username',
            'email',
            'firstname',
            'lastname',
            'address',
            'city',
            'country',
            'postal',
            'about',
            'catalogo_sucursales_id'
        ]));

        // Firma (si se subió)
        if ($request->hasFile('signature')) {
            $path = $request->file('signature')->store('signature/' . $user->id, 'public');
            $user->signature = $path;
            $user->save();
        }

        // Roles
        $user->syncRoles($request->input('roles', []));

        // Permisos
        $permisosSolicitados = $request->input('permissions', []);

        // Filtrar y asignar permisos válidos
        $permisosFinales = collect($permisosSolicitados)->filter(function ($permiso) {
            return Permission::where('name', $permiso)->exists();
        });

        $user->syncPermissions($permisosFinales);

        return redirect()->back()->with('success', 'Usuario actualizado correctamente.');
    }



    public function index(Request $request)
    {
        $users = User::orderBy('created_at', 'desc')->paginate(20);
        $roles = Role::all();
        $permissions = Permission::all();
        $selectedUser = $request->user_id ? User::find($request->user_id) : null;

        return view('pages.user-management', compact('users', 'roles', 'permissions', 'selectedUser'));
    }



    public function edit($id)
    {
        $user = User::findOrFail($id);
        $rolesGrouped = Role::all()->groupBy('group'); // si usas agrupación
        $permissionsGrouped = Permission::all()->groupBy(function ($perm) {
            return explode('.', $perm->name)[0]; // agrupa por módulo
        });

        $userRoles = $user->roles->pluck('name')->toArray();
        $userPermissions = $user->getDirectPermissions()->pluck('name')->toArray();

        $rolePermissionsMap = Role::with('permissions')->get()->mapWithKeys(function ($role) {
            return [$role->name => $role->permissions->pluck('name')->toArray()];
        });

        return view('usuarios.edit', compact(
            'user',
            'rolesGrouped',
            'permissionsGrouped',
            'userRoles',
            'userPermissions',
            'rolePermissionsMap'
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
