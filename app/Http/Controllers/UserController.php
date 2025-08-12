<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index()
    {
        // Verificar se é admin
        if (auth()->user()->tipo !== 'admin') {
            abort(403, 'Acesso negado. Apenas administradores podem gerenciar usuários.');
        }

        $users = User::select('id', 'name', 'email', 'tipo', 'created_at', 'last_login')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return Inertia::render('Admin/Users/Index', [
            'users' => $users
        ]);
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        // Verificar se é admin
        if (auth()->user()->tipo !== 'admin') {
            abort(403, 'Acesso negado. Apenas administradores podem gerenciar usuários.');
        }

        return Inertia::render('Admin/Users/Create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        // Verificar se é admin
        if (auth()->user()->tipo !== 'admin') {
            abort(403, 'Acesso negado. Apenas administradores podem gerenciar usuários.');
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'tipo' => 'required|in:admin,editor',
        ], [
            'name.required' => 'O nome é obrigatório.',
            'name.string' => 'O nome deve ser um texto válido.',
            'name.max' => 'O nome não pode ter mais de 255 caracteres.',
            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'Digite um e-mail válido.',
            'email.unique' => 'Este e-mail já está sendo usado.',
            'password.required' => 'A senha é obrigatória.',
            'password.min' => 'A senha deve ter pelo menos 8 caracteres.',
            'password.confirmed' => 'A confirmação da senha não confere.',
            'tipo.required' => 'O tipo de usuário é obrigatório.',
            'tipo.in' => 'Tipo de usuário inválido.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'tipo' => $request->tipo,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuário criado com sucesso!');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        return Inertia::render('Admin/Users/Show', [
            'user' => $user
        ]);
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        // Verificar se é admin
        if (auth()->user()->tipo !== 'admin') {
            abort(403, 'Acesso negado. Apenas administradores podem gerenciar usuários.');
        }

        return Inertia::render('Admin/Users/Edit', [
            'user' => $user
        ]);
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        // Verificar se é admin
        if (auth()->user()->tipo !== 'admin') {
            abort(403, 'Acesso negado. Apenas administradores podem gerenciar usuários.');
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'tipo' => 'required|in:admin,editor',
        ], [
            'name.required' => 'O nome é obrigatório.',
            'name.string' => 'O nome deve ser um texto válido.',
            'name.max' => 'O nome não pode ter mais de 255 caracteres.',
            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'Digite um e-mail válido.',
            'email.unique' => 'Este e-mail já está sendo usado.',
            'password.min' => 'A senha deve ter pelo menos 8 caracteres.',
            'password.confirmed' => 'A confirmação da senha não confere.',
            'tipo.required' => 'O tipo de usuário é obrigatório.',
            'tipo.in' => 'Tipo de usuário inválido.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'tipo' => $request->tipo,
        ];

        // Só atualiza a senha se foi fornecida
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuário atualizado com sucesso!');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Verificar se é admin
        if (auth()->user()->tipo !== 'admin') {
            abort(403, 'Acesso negado. Apenas administradores podem gerenciar usuários.');
        }

        // Não permitir que o usuário exclua a si mesmo
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'Você não pode excluir sua própria conta.']);
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuário excluído com sucesso!');
    }

    /**
     * Login as another user (admin only)
     */
    public function loginAs(User $user)
    {
        // Verificar se o usuário atual é admin
        if (auth()->user()->tipo !== 'admin') {
            return back()->withErrors(['error' => 'Acesso negado.']);
        }

        // Fazer login como o usuário selecionado
        auth()->login($user);

        return redirect()->route('admin.dashboard')
            ->with('success', "Logado como {$user->name}");
    }
}
