<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdministradorController extends Controller
{
    /**
     * Mostrar administradores
     */
    public function index()
    {
        $administradores = User::orderBy('name')->paginate(10);

        return view('administradores.index', compact('administradores'));
    }

    /**
     * Mostrar formulario para crear administrador
     */
    public function create()
    {
        return view('administradores.create');
    }

    /**
     * Guardar administrador
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'administrador',
            'estado' => true,
        ]);

        return redirect()
            ->route('administradores.index')
            ->with('success', 'Administrador creado correctamente.');
    }

    /**
     * Mostrar administrador
     */
    public function show(User $administrador)
    {
        return view('administradores.show', compact('administrador'));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(User $administrador)
    {
        return view('administradores.edit', compact('administrador'));
    }

    /**
     * Actualizar administrador
     */
    public function update(Request $request, User $administrador)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $administrador->id,
        ]);

        $administrador->name = $request->name;
        $administrador->email = $request->email;

        if ($request->filled('password')) {

            $request->validate([
                'password' => 'string|min:6|confirmed',
            ]);

            $administrador->password = Hash::make($request->password);
        }

        $administrador->save();

        return redirect()
            ->route('administradores.index')
            ->with('success', 'Administrador actualizado correctamente.');
    }

    /**
     * Activar / desactivar administrador
     */
    public function destroy(User $administrador)
    {
        // Evitar que un administrador se desactive a sí mismo

        if ($administrador->id === auth()->id()) {

            return redirect()
                ->route('administradores.index')
                ->with('error', 'No puedes desactivar tu propio usuario.');
        }

        $administrador->estado = !$administrador->estado;

        $administrador->save();

        return redirect()
            ->route('administradores.index')
            ->with(
                'success',
                $administrador->estado
                    ? 'Administrador activado correctamente.'
                    : 'Administrador desactivado correctamente.'
            );
    }
}
