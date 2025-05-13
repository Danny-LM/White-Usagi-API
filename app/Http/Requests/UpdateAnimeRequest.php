<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateAnimeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (!Auth::check()) {
            return false;
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Obtén el modelo 'anime' desde la ruta
        $anime = $this->route('anime');

        return $user->can('update', $anime);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'nullable|string|max:255',
            'release_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:release_date',
            'season' => 'nullable|string|max:50',
            'synopsis' => 'nullable|string',
            'poster_url' => 'nullable|url|max:255',
        ];
    }
}
