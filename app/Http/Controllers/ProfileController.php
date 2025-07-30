<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class ProfileController extends Controller
{
    /**
     * Exibir perfil do usuário
     */
    public function show()
    {
        $user = Auth::user();
        
        return Inertia::render('Admin/Profile/Show', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'tipo' => $user->tipo,
                'foto' => $user->foto ?? null,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
                'last_login' => $user->last_login ?? null,
            ]
        ]);
    }

    /**
     * Exibir página de configurações
     */
    public function edit()
    {
        $user = Auth::user();
        
        return Inertia::render('Admin/Profile/Edit', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'tipo' => $user->tipo,
                'foto' => $user->foto ?? null,
            ]
        ]);
    }

    /**
     * Atualizar informações do perfil
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'current_password' => ['nullable', 'required_with:password'],
            'password' => ['nullable', 'min:6', 'confirmed'],
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        // Verificar senha atual se uma nova senha foi fornecida
        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'A senha atual não confere.']);
            }
        }

        // Processar upload da foto
        if ($request->hasFile('foto')) {
            // Remover foto anterior se existir
            if ($user->foto) {
                Storage::delete('public/' . $user->foto);
            }

            // Salvar nova foto
            $foto = $request->file('foto');
            $nomeArquivo = time() . '_' . $foto->getClientOriginalName();
            $caminhoFoto = $foto->storeAs('usuarios', $nomeArquivo, 'public');
            $user->foto = $caminhoFoto;
        }

        // Atualizar dados
        $user->name = $request->name;
        $user->email = $request->email;
        
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'Perfil atualizado com sucesso!');
    }

    /**
     * Remover foto de perfil
     */
    public function removePhoto()
    {
        $user = Auth::user();

        if ($user->foto) {
            Storage::delete('public/' . $user->foto);
            $user->foto = null;
            $user->save();
        }

        return back()->with('success', 'Foto removida com sucesso!');
    }
}
