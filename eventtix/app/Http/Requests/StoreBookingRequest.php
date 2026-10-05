<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // $this->route('event') is the Event resolved by route model
        // binding on /events/{event:slug}/book — used to scope the ticket
        // type check so a ticket type from another event is rejected here,
        // as a normal validation error, instead of failing later with a
        // raw 404/exception.
        $event = $this->route('event');

        return [
            'ticket_type_id' => [
                'required',
                'integer',
                Rule::exists('ticket_types', 'id')->where(
                    fn ($query) => $query->where('event_id', $event?->id)
                ),
            ],
            'quantity' => ['required', 'integer', 'min:1', 'max:10'],
        ];
    }

    public function messages(): array
    {
        return [
            'ticket_type_id.required' => 'Merci de sélectionner un type de billet.',
            'ticket_type_id.integer' => 'Type de billet invalide.',
            'ticket_type_id.exists' => "Ce type de billet n'est pas disponible pour cet événement.",
            'quantity.required' => "Merci d'indiquer une quantité de billets.",
            'quantity.integer' => 'La quantité doit être un nombre entier.',
            'quantity.min' => 'La quantité minimale est de 1 billet.',
            'quantity.max' => 'Vous ne pouvez pas réserver plus de 10 billets à la fois.',
        ];
    }
}
